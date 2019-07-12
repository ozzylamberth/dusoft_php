<?php

/**
 * $Id: app_Contratacion_user.php,v 1.2 2009/10/05 19:04:05 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo para el manejo de la contratación (determinar las características de los planes)
 */

/**
* app_Contratación_user.php
*
* Clase que establece los métodos de acceso y búsqueda de información con las opciones
* de los detalles de los planes, ajustados a las características de los servicios y de
* los clientes con los cuales se va a contratar, relacionando los cargos y medicamentos
* con sus tarifarios, copagos, autorizaciones, semanas de carencia y paragrafados
**/

IncludeClass('app_Contratacion_Copia','','app','Contratacion');
class app_Contratacion_user extends classModulo
{
    var $uno;//para los errores
    var $dos;//para los errores por niveles
    var $limit;
    var $conteo;
    var $dias;//para los errores

    function app_Contratacion_user()
    {
        $this->limit=GetLimitBrowser();
        return true;
    }

    function main()
    {
        $this->PrincipalContra2();
        return true;
    }

    function UsuariosContra()//Función de permisos
    {
        list($dbconn) = GetDBconn();
        $usuario=UserGetUID();
        $query ="SELECT A.empresa_id,
                B.razon_social AS descripcion1
                FROM userpermisos_contratacion AS A,
                empresas AS B
                WHERE A.usuario_id=".$usuario."
                AND A.empresa_id=B.empresa_id
                ORDER BY descripcion1;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var1[$resulta->fields[1]]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        $mtz[0]='EMPRESAS';
        $url[0]='app';
        $url[1]='Contratacion';
        $url[2]='user';
        $url[3]='EmpresasContra';
        $url[4]='permisoscontra';
        $this->salida .=gui_theme_menu_acceso('CONTRATACIÓN', $mtz, $var1, $url, ModuloGetURL('system','Menu'));
        return true;
    }

    function SetStyle($campo)//Mensaje de error en caso de no encontrar los datos
    {
        if ($this->frmError[$campo] || $campo=="MensajeError")
        {
            if ($campo=="MensajeError")
            {
                return ("<tr><td class='label_error' colspan='2' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
            }
            else
            {
                return ("label_error");
            }
        }
        return ("normal_10AN");
    }

    function CalcularNumeroPasos($conteo)//Función de las barras
    {
        $numpaso=ceil($conteo/$this->limit);
        return $numpaso;
    }

    function CalcularBarra($paso)//Función de las barras
    {
        $barra=floor($paso/10)*10;
        if(($paso%10)==0)
        {
            $barra=$barra-10;
        }
        return $barra;
    }

    function CalcularOffset($paso)//Función de las barras
    {
        $offset=($paso*$this->limit)-$this->limit;
        return $offset;
    }

    /********************FUNCIONES PARA CREAR O MODIFCAR UN PLAN********************/
    function BuscarEmpresasPlanes($empresa,$estados,$traerestado,$estadobarra)//Busca los planes de la empresa seleccionada
    { 
				if (empty($traerestado) AND $_SESSION['contra']['estadotodos']==-2)
				$traerestado=$_SESSION['contra']['estadotodos'];
				else
				$_SESSION['contra']['estadotodos']=$traerestado;

        list($dbconn) = GetDBconn();
				//VerEstadoPlanContra - OCULTAR EL ESTADO ESPECIFICO DE LOS PALNES
 				//SOLO ESOS PARA PODER VER
				if ($estadobarra<>NULL AND $traerestado<>-2)
				{  
					$busqueda3="AND A.estado=$estadobarra";
				}
				else
				if ($traerestado<>NULL AND $traerestado<>-2)
				{
					$busqueda3="AND A.estado=$traerestado";
				}
				else
				if ($traerestado=="")
				{
					$busqueda3='';
					for($i=0;$i<sizeof($estados);$i++)
					{
						if ($estados[$i][sw_default]==1)
								$busqueda3="AND A.estado=1";
					}
				}
							
        if($_REQUEST['codigoctra'])
        {
            $codigo=$_REQUEST['codigoctra'];
            $busqueda="AND A.num_contrato LIKE '%$codigo%'";
        }
        else
        {
            $busqueda='';
        }
        if($_REQUEST['ctradescri'])
        {
            $codigo=STRTOUPPER($_REQUEST['ctradescri']);
            $busqueda2="AND UPPER(A.plan_descripcion) LIKE '%$codigo%'";
        }
        else
        {
            $busqueda2='';
        }
        if(empty($_REQUEST['conteo']))
        {
            $query ="SELECT count(*) FROM
                    (
                        SELECT A.plan_id,
                        A.plan_descripcion,
                        A.num_contrato,
                        A.estado,
                        A.sw_facturacion_agrupada,
                        A.sw_paragrafados_imd,
                        A.sw_paragrafados_cd,
                        A.tipo_para_imd,
												A.fecha_final,
												A.sw_contrata_hospitalizacion,
                        B.nombre_tercero,
                        B.tipo_id_tercero,
                        B.tercero_id,
                        C.descripcion
                        FROM planes AS A,
                        terceros AS B,
                        tipos_planes AS C
                        WHERE A.empresa_id='".$empresa."'
                        AND A.tipo_tercero_id=B.tipo_id_tercero
                        AND A.tercero_id=B.tercero_id
                        AND A.sw_tipo_plan=C.sw_tipo_plan
                        $busqueda
                        $busqueda2
                        $busqueda3
                    ) AS r;";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            list($this->conteo)=$resulta->fetchRow();
        }
        else
        {
            $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of'])
        {
            $Of='0';
        }
        else
        {
            $Of=$_REQUEST['Of'];
            if($_REQUEST['Of'] > $this->conteo)
            {
                $Of='0';
                $_REQUEST['Of']='0';
                $_REQUEST['paso']='1';
            }
        }
        $query ="
                (
                  SELECT  A.plan_id,
                          A.plan_descripcion,
                          A.num_contrato,
                          A.estado,
                          A.sw_facturacion_agrupada,
                          A.sw_paragrafados_imd,
                          A.sw_paragrafados_cd,
                          A.tipo_para_imd,
      										A.fecha_final,
      										A.sw_contrata_hospitalizacion,
                          B.nombre_tercero,
                          B.tipo_id_tercero,
                          B.tercero_id,
                          C.descripcion,
                          A.monto_contrato,
                          TO_CHAR(A.fecha_final,'DD/MM/YYYY') AS fecha_fin_contrato
                  FROM    planes AS A,
                          terceros AS B,
                          tipos_planes AS C
                  WHERE   A.empresa_id='".$empresa."'
                  AND     A.tipo_tercero_id=B.tipo_id_tercero
                  AND     A.tercero_id=B.tercero_id
                  AND     A.sw_tipo_plan=C.sw_tipo_plan
                  $busqueda
                  $busqueda2
                  $busqueda3
                  ORDER BY A.plan_descripcion
                )
                LIMIT ".$this->limit." OFFSET $Of;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
				$this->VerificarVencidos($var);
				return $var;
    }
		
		function VerificarVencidos($var)
		{
			$fecha_actual=date("Y-m-d");
			$i=0;
			while($i<sizeof($var))
			{
				if ($var[$i][fecha_final]<$fecha_actual)
					{
						$plan_id=$var[$i][plan_id];
						list($dbconn) = GetDBconn();
						$query ="	UPDATE planes SET estado=3
											WHERE	plan_id=$plan_id;";
						$resulta = $dbconn->Execute($query);
						$var[$i][estado]=3;//PLANES VENCIDOS
					}
				$i++;
			}
			return true;
		}

		function BuscarEstadoPlanContra($estado_id)
		{	
				if ($estado_id<>NULL)
				{
					$condicion="WHERE A.estado=$estado_id";
				}
				else
					$condicion="";
        list($dbconn) = GetDBconn();
        $query ="SELECT A.estado_id,
												A.descripcion,
												A.sw_default
                FROM planes_estado AS A
									$condicion";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
				$i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
						$i++;
        }
        return $var;		
		}

		function TraerListaPrecios()
		{
        list($dbconn) = GetDBconn();
				$query ="SELECT codigo_lista,
												descripcion
								FROM listas_precios;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
		}
		
		function TraerDescripcionLista($lista)
		{
        list($dbconn) = GetDBconn();
				$query ="SELECT codigo_lista,
												descripcion
								FROM listas_precios
								WHERE codigo_lista='$lista';";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var=$resulta->GetRowAssoc($ToUpper = false);
        return $var;
		}
		
    function MostrarEmpresasPlanes($plan)//Muestra y modifica la información del plan escogido
    {
      list($dbconn) = GetDBconn();
      $query ="SELECT A.empresa_id,
                A.tercero_id,
                A.tipo_tercero_id,
                A.plan_descripcion,
                A.tipo_cliente,
                A.num_contrato,
                A.fecha_inicio,
                A.fecha_final,
                A.monto_contrato,
                A.monto_contrato_mensual,
                A.saldo_contrato,
                A.tope_maximo_factura,
                A.dias_credito_cartera,
								A.tipo_liquidacion_id,
                A.sw_autoriza_sin_bd,
                A.sw_afiliacion,
                A.sw_tipo_plan,
                A.sw_facturacion_agrupada,
                A.observacion,
                A.estado,
                A.sw_paragrafados_cd,
                A.sw_paragrafados_imd,
                A.servicios_contratados,
                A.protocolos,
                A.contacto,
                A.lineas_atencion,
                A.nombre_copago,
                A.nombre_cuota_moderadora,
                A.sw_base_liquidacion_imd,
                A.sw_exceder_monto_mensual,
                A.actividad_incumplimientos,
                A.tipo_liquidacion_cargo,
                A.meses_consulta_base_datos,
                A.horas_cancelacion,
                A.telefono_cancelacion_cita,
                A.tipo_para_imd,
								A.porcentaje_utilidad,
                A.lista_precios,
                A.sw_contrata_hospitalizacion,
                A.sw_solicita_autorizacion_admision,
                A.sw_afiliados,
                B.descripcion,
                D.descripcion AS descripcion2,
                H.descripcion AS descripcion3,
                I.descripcion AS descripcion4,
                J.descripcion AS descripcion5,
                F.usuario_id,
                G.nombre
                FROM planes AS A
                LEFT JOIN tipos_cliente AS D ON
                (A.tipo_cliente=D.tipo_cliente),
                tipos_planes AS B,

                planes_encargados AS F,
                system_usuarios AS G,
                tipos_liquidacion_semanas_cotizadas AS H,
                tipo_liquidaciones_cargos AS I,
                tipos_paragrafados_imd AS J
                WHERE A.plan_id=".$plan."
                AND A.sw_tipo_plan=B.sw_tipo_plan
                AND A.plan_id=F.plan_id
                AND F.usuario_id=G.usuario_id
                AND A.tipo_liquidacion_id=H.tipo_liquidacion_id
                AND A.tipo_liquidacion_cargo=I.tipo_liquidacion_cargo
                AND A.tipo_para_imd=J.tipo_para_imd;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function MostrarIngresaDatosPlan2($plan)//Muestra y modifica la información del plan escogido
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT A.tipo_liq_habitacion,
                A.tipo_liquidacion_id,
                A.tipo_liquidacion_cargo,
                A.sw_autoriza_sin_bd,
                A.sw_afiliacion,
                A.sw_facturacion_agrupada,
                A.sw_paragrafados_cd,
                A.sw_paragrafados_imd,
                A.nombre_copago,
                A.nombre_cuota_moderadora,
                A.actividad_incumplimientos,
                A.meses_consulta_base_datos,
                A.horas_cancelacion,
                A.telefono_cancelacion_cita,
                A.tipo_para_imd,
                A.sw_contrata_hospitalizacion
                FROM planes AS A
                WHERE A.plan_id=".$plan.";";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function MostrarServiciosPlanes($plan)//Muestra y modifica la información del plan escogido
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT servicio
                FROM planes_servicios
                WHERE plan_id=".$plan."
                ORDER BY servicio;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var[$resulta->fields[0]]=1;
            $resulta->MoveNext();
        }
        return $var;
    }

    function MostrarServiciosPlanes2($plan)//Muestra y modifica la información del plan escogido
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT A.servicio,
                B.descripcion
                FROM planes_servicios AS A,
                servicios AS B
                WHERE A.plan_id=".$plan."
                AND A.servicio=B.servicio
                AND B.servicio<>'0'
                ORDER BY A.servicio;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function MostrarServiciosPlanes3($plan,$empresa)//Muestra y modifica la información del plan escogido
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT A.servicio,
                B.descripcion,
                C.departamento,
                C.descripcion AS descdept
                FROM planes_servicios AS A,
                servicios AS B,
                departamentos AS C
                WHERE A.plan_id=".$plan."
                AND A.servicio=B.servicio
                AND B.servicio=C.servicio
                AND B.servicio<>'0'
                AND C.empresa_id='".$empresa."'
                ORDER BY A.servicio, C.departamento;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function CambiarEstadoPlanContra()//Funcion que cambia el estado del plan
    {
        list($dbconn) = GetDBconn();
				if($_POST['justificacion']==NULL)
				{
					$this->frmError["justificacion"]=1;
					$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
					$this->uno=1;
					$this->JustificarCambiarEstadoPlanContra();
					return true;
				}
				else
				{
				$usuario=UserGetUID();
					$query ="INSERT INTO planes_estado_auditoria
										(plan_id,
										estado_id_anterior,
										estado_id_actual,
										usuario_id,
										descripcion,
										fecha_registro)
									VALUES(".$_REQUEST['planelegc'].",
												".$_REQUEST['estado'].",
												".$_REQUEST['restado'].",
													$usuario,
												'".$_POST['justificacion']."',
													now());";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
							$this->mensajeDeError = "Error DB : " .$dbconn->ErrorMsg();
							return false;
					}
					
				}
				$estado=$_REQUEST['restado'];
        if($_REQUEST['estado']==1)//ACTIVO ==1
        {
            $query ="UPDATE planes SET estado=$estado
                    WHERE plan_id=".$_REQUEST['planelegc'].";";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " .$dbconn->ErrorMsg();
                return false;
            }
        }
				else
        if($_REQUEST['estado']==2)//ACTIVO ==1
        {
            $query ="UPDATE planes SET estado=$estado
                    WHERE plan_id=".$_REQUEST['planelegc'].";";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " .$dbconn->ErrorMsg();
                return false;
            }
        }
        else//INACTIVO ==0
        {
						//VERIFICAR SI EL PLAN TIENE RANGOS PARA ALGUN TIPO DE AFILIADO
            $query ="SELECT count(plan_id) FROM planes_rangos
                    WHERE plan_id=".$_REQUEST['planelegc'].";";
            $resulta1 = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $resulta1->fields[0]=0;
            }
						//VERIFICAR SI EL PLAN TIENE TARIFARIOS ASOCIADOS
            $query ="SELECT count(plan_id) FROM plan_tarifario
                    WHERE plan_id=".$_REQUEST['planelegc'].";";
            $resulta2 = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $resulta2->fields[0]=1;
            }
            if((!($resulta1->fields[0]==0)) AND ($resulta2->fields[0]>1))//if(!empty($resulta->fields[0]))
            {
                $query ="UPDATE planes SET estado=$estado
                        WHERE plan_id=".$_REQUEST['planelegc'].";";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                    $this->mensajeDeError = "Error DB : " .$dbconn->ErrorMsg();
                    return false;
                }
            }
						else
						{
							$this->frmError["MensajeError"]="NO SE PUEDE ACTIVAR EL PLAN,\nNO TIENE RANGOS PARA ALGUN TIPO DE AFILIADO(planes_rangos)\nY/O NO TIENE TARIFARIOS ASOCIADOS(plan_tarifario).";
							$this->uno=1;
							$this->EmpresasContra();
							return true;
						}
        }
				$this->frmError["MensajeError"]="ESTADO MODIFICADO.";
				$this->uno=1;
        $this->EmpresasContra();
        return true;
    }

    function ValidarDatosPlanContra3()//Valida los datos a guardar
    {       
        $this->dias=0;
        $this->dias=0;
        if($_POST['tarifario2']<>NULL)
        {
            if($_POST['tipoTerceroId']==NULL)
            {
                $this->frmError["tipoTerceroId"]=1;
            }
            if($_POST['codigo']==NULL)
            {
                $this->frmError["codigo"]=1;
            }
            if($_POST['contactoctra']==NULL)
            {
                $this->frmError["contactoctra"]=1;
            }
            if($_POST['descr2ctra']==NULL)
            {
                $this->frmError["descr2ctra"]=1;
            }
            if($_POST['numeroctra']==NULL)
            {
                $this->frmError["numeroctra"]=1;
            }
            if(is_numeric($_POST['valorctra'])==0)
            {
                $this->frmError["valorctra"]=1;
                $_POST['valorctra']='';
            }
            else
            {
                $valorcontr=doubleval($_POST['valorctra']);
                if($valorcontr >= 100000000000000)
                {
                    $this->frmError["valorctra"]=1;
                    $_POST['valorctra']='';
                }
            }
            if(is_numeric($_POST['valmectra'])==0)
            {
                $_POST['valmectra']='0';
                $valmecontr=0;
            }
            else
            {
                $valmecontr=doubleval($_POST['valmectra']);
                if($valmecontr >= 100000000000000)
                {
                    $_POST['valmectra']='0';
                    $valmecontr=0;
                }
                else if($valorcontr<$valmecontr)
                {
                    $_POST['valmectra']='0';
                    $valmecontr=0;
                }
            }
            if(is_numeric($_POST['facturactra'])==0)
            {
                $_POST['facturactra']='0';
                $factucontr=0;
            }
            else
            {
                $factucontr=doubleval($_POST['facturactra']);
                if($factucontr >= 10000000000)
                {
                    $_POST['facturactra']='0';
                    $factucontr=0;
                }
                else
                {
                    if(!empty($_POST['valorctra']))
                    {
                        if($factucontr > $valorcontr)
                        {
                            $_POST['facturactra']='0';
                            $factucontr=0;
                        }
                    }
                }
            }

            if($_POST['diasCredito']==NULL)
            {
                $this->frmError["diasCredito"]=1;
            }
            if(!is_numeric($_POST['diasCredito']))
            {
                $this->dias=1;
            }

            if(empty($_POST['feinictra']))
            {
                $this->frmError["feinictra"]=1;
            }
            else
            {//La fecha no va validada con la fecha del sistema
                $fecdes=explode('/',$_POST['feinictra']);
                $day=$fecdes[0];
                $mon=$fecdes[1];
                $yea=$fecdes[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['feinictra']='';
                    $this->frmError["feinictra"]=1;
                }
                else
                {
                    $fecdes=$yea.'-'.$mon.'-'.$day;
                }
            }
            if(empty($_POST['fefinctra']))
            {
                $this->frmError["fefinctra"]=1;
            }
            else
            {
                $fechas=explode('/',$_POST['fefinctra']);
                $day=$fechas[0];
                $mon=$fechas[1];
                $yea=$fechas[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['fefinctra']='';
                    $this->frmError["fefinctra"]=1;
                }
                else
                {
                    $fech=date ("Y-m-d");
                    if($fech >= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['fefinctra']='';
                        $this->frmError["fefinctra"]=1;
                    }
                    else if(!empty($_POST['feinictra']))
                    {
                        if($fecdes >= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                        {
                            $_POST['fefinctra']='';
                            $this->frmError["fefinctra"]=1;
                        }
                        else
                        {
                            $fechas=$yea.'-'.$mon.'-'.$day;
                        }
                    }
                }
            }
            if($_POST['excmonctra']==NULL)
            {
                $_POST['excmonctra']='0';
            }
            if(empty($_POST['fefinctra'])||empty($_POST['feinictra'])||
            $_POST['valorctra']==NULL||$_POST['numeroctra']==NULL||
            $_POST['contactoctra']==NULL||$_POST['descr2ctra']==NULL||
            $_POST['tipoTerceroId']==NULL||$_POST['codigo']==NULL)
            {
                if($valorcontr<$valmecontr)
                {
                    $this->frmError["MensajeError"]="EL VALOR MENSUAL DEL CONTRATO ES MAYOR AL VALOR ANUAL";
                }
                else
                if($this->dias==1)
                {
                    $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS<br>EL CAMPO DIAS CREDITO DEBE SER NUMERICO";
                }
                else
                {
                    $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
                }
                $this->uno=1;
                $this->IngresaDatosPlan3();
            }
            else
            {
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
                $query ="SELECT NEXTVAL ('planes_plan_id_seq');";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                    return false;
                }
                $indice=$resulta->fields[0];//$indice=30;
                $usuario=UserGetUID();
								$arreglo=array('plan_id'=>$indice,
                        'empresa'=>$_SESSION['contra']['empresa'],
                        'tipoTerceroId'=>$_POST['tipoTerceroId'],
                        'codigo'=>$_POST['codigo'],
                        'descr2ctra'=>$_POST['descr2ctra'],
                        'numeroctra'=>$_POST['numeroctra'],
                        'fecdes'=>$fecdes,
                        'fechas'=>$fechas,
                        'valorcontr'=>$valorcontr,
                        'valmecontr'=>$valmecontr,
                        'valorcontr'=>$valorcontr,
                        'factucontr'=>$factucontr,
                        'diasCredito'=>$_POST['diasCredito'],
                        //'date'=>date("Y-m-d H:i:s"),
                        'usuario'=>$usuario,
                        'contactoctra'=>$_POST['contactoctra'],
                        'excmonctra'=>$_POST['excmonctra'],
                        'tarifario2'=> $_POST['tarifario2']);
								if($copia = new app_Contratacion_Copia(&$dbconn,$arreglo))
								{
									$dbconn->CommitTrans();
									$_SESSION['ctrpla']['planeleg']=$indice;
									$_SESSION['ctrpla']['desceleg']=$_POST['descr2ctra'];
									$_SESSION['ctrpla']['numeeleg']=$_POST['numeroctra'];
									$_SESSION['ctrpla']['nombeleg']=$_POST['nombre'];//nombre del cliente - tercero
									$_SESSION['ctrpla']['tidteleg']=$_POST['tipoTerceroId'];
									$_SESSION['ctrpla']['terceleg']=$_POST['codigo'];
									$_SESSION['ctrpla']['estaeleg']=0;
									$_SESSION['ctrpla']['pimdeleg']=$_POST['paragramed'];
									$_SESSION['ctrpla']['pcadeleg']=$_POST['paragracar'];
									$_SESSION['ctrpla']['tpmdeleg']=$_POST['tipoparimd'];
								}
								else
								{
									$dbconn->RollBackTrans();
								}
                $this->EmpresasContra();//ClientePlanContra
            }
        }
        else
        {
            $this->frmError["MensajeError"]="SELECCIONE UN PLAN";
            $this->uno=1;
            $this->IngresaDatosPlan3();
        }
        return true;
    }
    /**
    * Funcion para hacer la validacion de los datos del contrato
    *
    *
    */
    function ValidarDatosPlanContra()//Valida los datos a guardar
    {
      $this->uno=0;
      $this->dias=0;
      if($_POST['tipoplctra']==NULL) $this->frmError["tipoplctra"]=1;
      if(empty($_POST['descrictra'])) $this->frmError["descrictra"]=1;
      if(empty($_POST['nombre']))  $this->frmError["nombre"]=1;
      if(empty($_POST['codigo']))  $this->frmError["codigo"]=1;
      if(empty($_POST['tipoTerceroId']))  $this->frmError["tipoTerceroId"]=1;
      if($_POST['contactoctra']==NULL)  $this->frmError["contactoctra"]=1;
      if($_POST['usuariosctra']==NULL)  $this->frmError["usuariosctra"]=1;
      if($_POST['numeroctra']==NULL)  $this->frmError["numeroctra"]=1;
      if($_POST['clientectra']==NULL)  $this->frmError["clientectra"]=1;

      if(is_numeric($_POST['valorctra'])==0)
      {
        $this->frmError["valorctra"]=1;
        $_POST['valorctra']='';
      }
      else
      {
          $valorcontr=doubleval($_POST['valorctra']);
          if($valorcontr >= 100000000000000)//14+1
          {
              $this->frmError["valorctra"]=1;
              $_POST['valorctra']='';
          }
      }
      if(is_numeric($_POST['valmectra'])==0)
      {
          $_POST['valmectra']='0';
          $valmecontr=0;
      }
      else
      {
          $valmecontr=doubleval($_POST['valmectra']);
          if($valmecontr >= 100000000000000)
          {
              $_POST['valmectra']='0';
              $valmecontr=0;
          }
          else if($valorcontr<$valmecontr)
          {
              $_POST['valmectra']='0';
              $valmecontr=0;
          }
      }
      if(is_numeric($_POST['facturactra'])==0)
      {
          $_POST['facturactra']='0';
          $factucontr=0;
      }
      else
      {
          $factucontr=doubleval($_POST['facturactra']);
          if($factucontr >= 10000000000)
          {
              $_POST['facturactra']='0';
              $factucontr=0;
          }
          else
          {
              if(!empty($_POST['valorctra']))
              {
                  if($factucontr > $valorcontr)
                  {
                      $_POST['facturactra']='0';
                      $factucontr=0;
                  }
              }
          }
      }
      if(empty($_POST['feinictra']))
      {
          $this->frmError["feinictra"]=1;
      }
      else
      {//La fecha no va validada con la fecha del sistema
          $fecdes=explode('/',$_POST['feinictra']);
          $day=$fecdes[0];
          $mon=$fecdes[1];
          $yea=$fecdes[2];
          if(checkdate($mon, $day, $yea)==0)
          {
              $_POST['feinictra']='';
              $this->frmError["feinictra"]=1;
          }
          else
          {
              $fecdes=$yea.'-'.$mon.'-'.$day;
          }
      }
      if(empty($_POST['fefinctra']))
      {
          $this->frmError["fefinctra"]=1;
      }
      else
      {
          $fechas=explode('/',$_POST['fefinctra']);
          $day=$fechas[0];
          $mon=$fechas[1];
          $yea=$fechas[2];
          if(checkdate($mon, $day, $yea)==0)
          {
              $_POST['fefinctra']='';
              $this->frmError["fefinctra"]=1;
          }
          else
          {
              $fech=date ("Y-m-d");
              if($fech >= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
              {
                  $_POST['fefinctra']='';
                  $this->frmError["fefinctra"]=1;
              }
              else if(!empty($_POST['feinictra']))
              {
                  if($fecdes >= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                  {
                      $_POST['fefinctra']='';
                      $this->frmError["fefinctra"]=1;
                  }
                  else
                  {
                      $fechas=$yea.'-'.$mon.'-'.$day;
                  }
              }
          }
      }
      if($_POST['telefono1']==NULL)
      {
          $this->frmError["telefono1"]=1;
      }
      $this->frmError["servicios"]=0;
      for($i=0;$i<$_POST['servicios'];$i++)
      {
          if($_POST['servicios'.$i]<>NULL)
          {
              $this->frmError["servicios"]=0;
              break;
          }
          else
          {
              $this->frmError["servicios"]=1;
          }
      }
      if($_POST['bventactra']==NULL)
      {
          $this->frmError["bventactra"]=1;
      }

      if($_POST['diasCredito']==NULL)
      {
          $this->frmError["diasCredito"]=1;
      }
      if(!is_numeric($_POST['diasCredito']))
      {
          $this->dias=1;
      }

      if(empty($_POST['excmonctra']))
      {
          $this->frmError["excmonctra"]=1;
      }
      if($_POST['bventactra']!=3)
      {
          $_POST['listaprecios']=NULL;
      }
      if(empty($_POST['porcentaje'])) $_POST['porcentaje']=0;
      
      if(empty($_POST['descrictra'])||empty($_POST['nombre'])||
         empty($_POST['codigo'])||empty($_POST['tipoTerceroId'])||
         empty($_POST['fefinctra'])||empty($_POST['feinictra'])||
         empty($_POST['excmonctra'])||$this->frmError["servicios"]==1||
         $_POST['valorctra']==NULL||$_POST['clientectra']==NULL||
         $_POST['telefono1']==NULL||$_POST['bventactra']==NULL||
         $_POST['numeroctra']==NULL||$_POST['contactoctra']==NULL||
         $_POST['usuariosctra']==NULL||$_POST['tipoplctra']==NULL
      )
      {
        if($valorcontr<$valmecontr)
        {
          $this->frmError["MensajeError"]="EL VALOR MENSUAL DEL CONTRATO ES MAYOR AL VALOR ANUAL";
        }
        else if($this->dias==1)
        {
          $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS <br>EL CAMPO DIAS CREDITO DEBE SER NUMERICO";
        }
        else
        {
          $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
        }
        $this->uno=1;
        $this->IngresaDatosPlan();
      }
      else
      {
        if($_POST['excmonctra']==2)  $_POST['excmonctra']=0;
          
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query ="SELECT NEXTVAL ('planes_plan_id_seq');";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollBackTrans();
          return false;
        }
        $indice=$resulta->fields[0];//$indice=31;
        $usuario=UserGetUID();//HD-01
        
        $campo=" ";$valor=" ";
        if(!empty($_POST['listaprecios']))
        {
          $campo="lista_precios,";
          $valor="'".$_POST['listaprecios']."',";
        }
          
        $query ="INSERT INTO planes
                ( plan_id,
                  empresa_id,
                  tipo_tercero_id,
                  tercero_id,
                  plan_descripcion,
                  tipo_cliente,
                  sw_tipo_plan,
                  num_contrato,
                  fecha_inicio,
                  fecha_final,
                  monto_contrato,
                  monto_contrato_mensual,
                  saldo_contrato,
                  tope_maximo_factura,
                  dias_credito_cartera,
                  fecha_registro,
                  usuario_id,
                  estado,
                  servicios_contratados,
                  protocolos,
                  contacto,
                  lineas_atencion,
                  sw_base_liquidacion_imd,
                  sw_exceder_monto_mensual,
                  tipo_liquidacion_id,
                  $campo
                  porcentaje_utilidad
                )
                VALUES
                (
                   ".$indice.",
                  '".$_SESSION['contra']['empresa']."',
                  '".$_POST['tipoTerceroId']."',
                  '".$_POST['codigo']."',
                  '".$_POST['descrictra']."',
                  '".$_POST['clientectra']."',
                  '".$_POST['tipoplctra']."',
                  '".$_POST['numeroctra']."',
                  '".$fecdes."',
                  '".$fechas."',
                   ".$valorcontr.",
                   ".$valmecontr.",
                   ".$valorcontr.",
                   ".$factucontr.",
                   ".$_POST['diasCredito'].",
                  '".date("Y-m-d H:i:s")."',
                   ".$usuario.",
                   '0',
                  '".$_POST['servicioctra']."',
                  '".$_POST['protocoloctra']."',
                  '".$_POST['contactoctra']."',
                  '".$_POST['telefono1']."',
                  '".$_POST['bventactra']."',
                  '".$_POST['excmonctra']."',
                   ".(1).",
                   $valor
                   ".$_POST['porcentaje']."
                );";
          $resulta = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
              $dbconn->RollBackTrans();
              $_POST['numeroctra']='';
              $this->frmError["MensajeError"]="DATOS DUPLICADOS - VERIFICAR NÚMERO DEL CONTRATO";
              $this->uno=1;
              $this->IngresaDatosPlan();
              return true;
          }
          $query ="INSERT INTO planes_encargados
                  (plan_id,
                  usuario_id)
                  VALUES
                  (".$indice.",
                  ".$_POST['usuariosctra'].");";
          $resulta = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
              $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $dbconn->RollBackTrans();
              return false;
          }
          $query ="INSERT INTO plan_tarifario
                  (plan_id,
                  grupo_tarifario_id,
                  subgrupo_tarifario_id,
                  tarifario_id)
                  VALUES
                  (".$indice.",
                  '00',
                  '00',
                  'SYS');";
          $resulta = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
              $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $dbconn->RollBackTrans();
              return false;
          }
          for($i=0;$i<$_POST['servicios'];$i++)
          {
              if($_POST['servicios'.$i]<>NULL)
              {
                  $query ="INSERT INTO planes_servicios
                          (plan_id,
                          servicio)
                          VALUES
                          (".$indice.",
                          '".$_POST['servicios'.$i]."');";
                  $resulta = $dbconn->Execute($query);
                  if ($dbconn->ErrorNo() != 0)
                  {
                      $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      $dbconn->RollBackTrans();
                      return false;
                  }
              }
          }
          $dbconn->CommitTrans();
          $_SESSION['ctrpla']['plancrea']=$indice;
          $_SESSION['ctrpla']['desccrea']=$_POST['descrictra'];
          $_SESSION['ctrpla']['numecrea']=$_POST['numeroctra'];
          $_SESSION['ctrpla']['nombcrea']=$_POST['nombre'];
          $_SESSION['ctrpla']['tipocrea']=$_POST['tipoplctra'];
          $_SESSION['ctrpla']['busccrea']=1;
          $this->IngresaDatosPlan2();
      }
      return true;
    }

    function ValidarDatosPlanContra2()//Valida los datos a guardar
    {
        $this->uno=0;

        if($_POST['liquihactra']==NULL)
        {
            $this->frmError["liquihactra"]=1;
        }
        if($_POST['capitactra']==NULL)
        {
            $this->frmError["capitactra"]=1;
        }
        if($_POST['afiliactra']==NULL)
        {
            $this->frmError["afiliactra"]=1;
        }
        if($_POST['ponderarctra']==NULL)
        {
            $this->frmError["ponderarctra"]=1;
        }
        if($_POST['facagrctra']==NULL)
        {
            $this->frmError["facagrctra"]=1;
        }
        if(empty($_POST['rangctra']))
        {
            $_POST['rangctra']='';
            $this->frmError["rangctra"]=1;
        }
        if(is_numeric($_POST['rangctra'])==0)
        {
            $_POST['rangctra']='';
            $this->frmError["rangctra"]=1;
        }
        for($i=0;$i<sizeof($_SESSION['ctrpla']['afilcrea']);$i++)
        {
            if(!($_POST['afiliados'.$i]==NULL))
            {
                $_SESSION['ctrpla']['afiliado'][$i]=$_POST['afiliados'.$i];
            }
        }
        if(empty($_SESSION['ctrpla']['afiliado']))
        {
            $this->frmError["afilia"]=1;
        }
        if($_POST['paraimdctra']==NULL)
        {
            $this->frmError["paraimdctra"]=1;
        }
        if($_POST['tipaimdctra']==NULL AND $_POST['paraimdctra']==1)
        {
            $this->frmError["tipaimdctra"]=1;
        }
        if($_POST['paracadctra']==NULL)
        {
            $this->frmError["paracadctra"]=1;
        }
        if(is_numeric($_POST['incumpctra'])==0)
        {
            $_POST['incumpctra']='';
            $this->frmError["incumpctra"]=1;
        }
        else
        {
            $_POST['incumpctra']=intval($_POST['incumpctra']);
            if($_POST['incumpctra']>32000)
            {
                $_POST['incumpctra']='';
                $this->frmError["incumpctra"]=1;
            }
        }
        if($_POST['liquidacarctra']==NULL)
        {
            $this->frmError["liquidacarctra"]=1;
        }
        if(is_numeric($_POST['mesconbd'])==0)
        {
            $_POST['mesconbd']='';
            $this->frmError["mesconbd"]=1;
        }
        else
        {
            $_POST['mesconbd']=intval($_POST['mesconbd']);
            if($_POST['mesconbd']>32000)
            {
                $_POST['mesconbd']='';
                $this->frmError["mesconbd"]=1;
            }
        }
        
        if($_POST['sw_afiliaciones'] == '-1') $this->frmError['sw_afiliaciones']=1;
        if($_POST['auto_solicitud'] == "") $this->frmError['auto_solicitud']=1;
        
        if($_POST['capitactra']==NULL||$_POST['afiliactra']==NULL||
          //$_POST['facagrctra']==NULL||$_POST['clientectra']==NULL||
          $_POST['facagrctra']==NULL||
          $_POST['liquihactra']==NULL||$_POST['ponderarctra']==NULL||
          $_POST['paraimdctra']==NULL||$_POST['paracadctra']==NULL||
          $_POST['incumpctra']==NULL||$_POST['liquidacarctra']==NULL||
          $_POST['mesconbd']==NULL||$this->frmError["tipaimdctra"]==1||
          empty($_SESSION['ctrpla']['afiliado'])||empty($_POST['rangctra']) ||
          $_POST['sw_afiliaciones'] == '-1' || $_POST['auto_solicitud'] == ""
        )
        {
            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
            $this->uno=1;
            $this->IngresaDatosPlan2();
        }
        else
        {
            list($dbconn) = GetDBconn();
            if(empty($_POST['copagoctra']))
            {
                $_POST['copagoctra']="Copago";
            }
            if(empty($_POST['cuotactra']))
            {
                $_POST['cuotactra']="Cuota Moderadora";
            }
            if($_POST['paraimdctra']==0)
            {
                $_POST['tipaimdctra']=0;
            }
            //tipo_liq_habitacion='".$_POST['liquihactra']."',
           $query ="UPDATE planes SET
										sw_contrata_hospitalizacion='".$_POST['liquihactra']."',
                    sw_autoriza_sin_bd='".$_POST['capitactra']."',
                    sw_afiliacion='".$_POST['afiliactra']."',
                    tipo_liquidacion_id='".$_POST['ponderarctra']."',
                    sw_facturacion_agrupada='".$_POST['facagrctra']."',
                    sw_paragrafados_imd='".$_POST['paraimdctra']."',
                    sw_paragrafados_cd='".$_POST['paracadctra']."',
                    observacion='".$_POST['observacion']."',
                    nombre_copago='".$_POST['copagoctra']."',
                    nombre_cuota_moderadora='".$_POST['cuotactra']."',
                    actividad_incumplimientos='".$_POST['incumpctra']."',
                    tipo_liquidacion_cargo=".$_POST['liquidacarctra'].",
                    meses_consulta_base_datos=".$_POST['mesconbd'].",
                    horas_cancelacion='".$_POST['horaprecan']."',
                    telefono_cancelacion_cita='".$_POST['linecancit']."',
                    tipo_para_imd=".$_POST['tipaimdctra'].",
                    sw_solicita_autorizacion_admision = '".$_POST['auto_solicitud']."' ,
                    sw_afiliados = '".$_POST['sw_afiliaciones']."'
                    WHERE plan_id=".$_SESSION['ctrpla']['plancrea'].";";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $_SESSION['ctrpla']['rangospl']=$_POST['rangctra'];
            $this->ValoresRangosContra();
        }
        return true;
    }

    function ValidarDatosRangosContra()//Guarda los valores de los rangos según el plan y el afiliado
    {
        $nombre=$repite=1;
        for($i=0;$i<$_SESSION['ctrpla']['rangospl'];$i++)
        {
            if($_POST['nomranctra'.$i]==NULL)
            {
                $this->frmError["nomranctra".$i]=1;
                $nombre=0;
            }
        }
        for($i=0;$i<$_SESSION['ctrpla']['rangospl'];$i++)
        {
            for($j=$i+1;$j<$_SESSION['ctrpla']['rangospl'];$j++)
            {
                if($_POST['nomranctra'.$i]==$_POST['nomranctra'.$j])
                {
                    $this->frmError["nomranctra".$j]=1;
                    $repite=0;
                }
            }
        }
        for($i=0;$i<$_SESSION['ctrpla']['rangospl'];$i++)
        {
            for($k=0;$k<sizeof($_SESSION['ctrpla']['afilcrea']);$k++)
            {
                if(!($_SESSION['ctrpla']['afiliado'][$k]==NULL))//Llaves
                {
                    if(is_numeric($_POST['cuotamod'.$i.$k])==0
                    ||$_POST['cuotamod'.$i.$k]>=10000000)
                    {
                        $_POST['cuotamod'.$i.$k]='0.00';
                    }
                    if(is_numeric($_POST['copagopor'.$i.$k])==0
                    ||$_POST['copagopor'.$i.$k]>=1000)
                    {
                        $_POST['copagopor'.$i.$k]='0.00';
                    }
                    if(is_numeric($_POST['copagomax'.$i.$k])==0
                    ||$_POST['copagomax'.$i.$k]>=10000000)
                    {
                        $_POST['copagomax'.$i.$k]='0.00';
                    }
                    if(is_numeric($_POST['copagomin'.$i.$k])==0
                    ||$_POST['copagomin'.$i.$k]>=10000000)
                    {
                        $_POST['copagomin'.$i.$k]='0.00';
                    }
                    if(is_numeric($_POST['copagoano'.$i.$k])==0
                    ||$_POST['copagoano'.$i.$k]>=10000000)
                    {
                        $_POST['copagoano'.$i.$k]='0.00';
                    }
                }
            }
        }
        if($nombre==0 || $repite==0)
        {
            if($nombre==0)
            {
                $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
            }
            if($repite==0)
            {
                $this->frmError["MensajeError"]="EXISTEN RANGOS REPETIDOS";
            }
            $this->uno=1;
            $this->ValoresRangosContra();
        }
        else
        {
            list($dbconn) = GetDBconn();
            $dbconn->BeginTrans();
            for($i=0;$i<$_SESSION['ctrpla']['rangospl'];$i++)
            {
                for($k=0;$k<sizeof($_SESSION['ctrpla']['afilcrea']);$k++)
                {
                    if(!($_SESSION['ctrpla']['afiliado'][$k]==NULL))//Llaves
                    {
                        $query ="INSERT INTO planes_rangos
                                (plan_id,
                                tipo_afiliado_id,
                                rango,
                                cuota_moderadora,
                                copago,
                                copago_maximo,
                                copago_minimo,
                                copago_maximo_ano)
                                VALUES
                                (".$_SESSION['ctrpla']['plancrea'].",
                                '".$_SESSION['ctrpla']['afiliado'][$k]."',
                                '".$_POST['nomranctra'.$i]."',
                                ".$_POST['cuotamod'.$i.$k].",
                                ".$_POST['copagopor'.$i.$k].",
                                ".$_POST['copagomax'.$i.$k].",
                                ".$_POST['copagomin'.$i.$k].",
                                ".$_POST['copagoano'.$i.$k].");";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollBackTrans();
                            return false;
                        }
                    }
                }
            }
            $dbconn->CommitTrans();
            $this->EmpresasContra();
        }
        return true;
    }

    function BuscarRangosPlan($plan)//Busca los tipos de afiliación
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT rango,
                copago,
                cuota_moderadora,
                copago_maximo,
                copago_minimo,
                copago_maximo_ano,
                tipo_afiliado_id
                FROM planes_rangos
                WHERE plan_id=".$plan."
                ORDER BY rango, tipo_afiliado_id;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function CambiarRangosPlan($plan)//Establece si se oueden modificar los rangos del plan
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT count(plan_id)
                FROM cuentas
                WHERE plan_id=".$plan.";";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        return $resulta->fields[0];
    }

    function ModificarRangosContra()//Modifica los valores de los rangos de un plan
    {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        for($i=0;$i<sizeof($_SESSION['ctrpla']['rangosM']);$i++)
        {
            for($k=0;$k<sizeof($_SESSION['ctrpla']['afiliaM']);$k++)
            {
                if($_SESSION['ctrpla']['rangosM'][$i]['tipo_afiliado_id']==$_SESSION['ctrpla']['afiliaM'][$k]['tipo_afiliado_id'])//Llaves
                {
                    if(is_numeric($_POST['cuotamodM'.$i.$k])==0
                    ||$_POST['cuotamodM'.$i.$k]>=10000000)
                    {
                        $_POST['cuotamodM'.$i.$k]='0.00';
                    }
                    if(is_numeric($_POST['copagoporM'.$i.$k])==0
                    ||$_POST['copagoporM'.$i.$k]>=1000)
                    {
                        $_POST['copagoporM'.$i.$k]='0.00';
                    }
                    if(is_numeric($_POST['copagomaxM'.$i.$k])==0
                    ||$_POST['copagomaxM'.$i.$k]>=10000000)
                    {
                        $_POST['copagomaxM'.$i.$k]='0.00';
                    }
                    if(is_numeric($_POST['copagominM'.$i.$k])==0
                    ||$_POST['copagominM'.$i.$k]>=10000000)
                    {
                        $_POST['copagominM'.$i.$k]='0.00';
                    }
                    if(is_numeric($_POST['copagoanoM'.$i.$k])==0
                    ||$_POST['copagoanoM'.$i.$k]>=10000000)
                    {
                        $_POST['copagoanoM'.$i.$k]='0.00';
                    }
                    $query ="UPDATE planes_rangos SET
                            cuota_moderadora=".$_POST['cuotamodM'.$i.$k].",
                            copago=".$_POST['copagoporM'.$i.$k].",
                            copago_maximo=".$_POST['copagomaxM'.$i.$k].",
                            copago_minimo=".$_POST['copagominM'.$i.$k].",
                            copago_maximo_ano=".$_POST['copagoanoM'.$i.$k]."
                            WHERE
                            plan_id=".$_SESSION['ctrpla']['planeleg']."
                            AND tipo_afiliado_id='".$_SESSION['ctrpla']['rangosM'][$i]['tipo_afiliado_id']."'
                            AND rango='".$_SESSION['ctrpla']['rangosM'][$i]['rango']."';";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                        return false;
                    }
                }
            }
        }
        $dbconn->CommitTrans();
        $this->ClientePlanContra();
        return true;
    }

    function MatrizRangoContra()//Válida los parámetros para crear las matrices
    {
        if(empty($_POST['rangctra2']))
        {
            $_POST['rangctra2']='';
            $this->frmError["rangctra2"]=1;
        }
        if(is_numeric($_POST['rangctra2'])==0)
        {
            $_POST['rangctra2']='';
            $this->frmError["rangctra2"]=1;
        }
        for($i=0;$i<sizeof($_SESSION['ctrpla']['afiliaM']);$i++)
        {
            if(!($_POST['afiliados2'.$i]==NULL))
            {
                $_SESSION['ctrpla']['afiliado2'][$i]=$_POST['afiliados2'.$i];
            }
        }
        if(empty($_SESSION['ctrpla']['afiliado2']))
        {
            $this->frmError["afilia2"]=1;
        }
        if(empty($_SESSION['ctrpla']['afiliado2'])||empty($_POST['rangctra2']))
        {
            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
            $this->uno=1;
            $this->MatrizRangosPlanContra();
        }
        else
        {
            $_SESSION['ctrpla']['rangospl2']=$_POST['rangctra2'];
            $this->ValoresRangosContra2();
        }
        return true;
    }

    function ValidarDatosRangosContra2()//Guarda los valores de los rangos para un plan ya creado
    {
        $nombre=$repite=1;
        for($i=0;$i<$_SESSION['ctrpla']['rangospl2'];$i++)
        {
            if($_POST['nomranctra2'.$i]==NULL)
            {
                $this->frmError["nomranctra2".$i]=1;
                $nombre=0;
            }
        }
        for($i=0;$i<$_SESSION['ctrpla']['rangospl2'];$i++)
        {
            for($j=$i+1;$j<$_SESSION['ctrpla']['rangospl2'];$j++)
            {
                if($_POST['nomranctra2'.$i]==$_POST['nomranctra2'.$j])
                {
                    $this->frmError["nomranctra2".$j]=1;
                    $repite=0;
                }
            }
        }
        for($i=0;$i<$_SESSION['ctrpla']['rangospl2'];$i++)
        {
            for($k=0;$k<sizeof($_SESSION['ctrpla']['afiliaM']);$k++)
            {
                if(!($_SESSION['ctrpla']['afiliado2'][$k]==NULL))//Llaves
                {
                    if(is_numeric($_POST['cuotamod2'.$i.$k])==0
                    ||$_POST['cuotamod2'.$i.$k]>=10000000)
                    {
                        $_POST['cuotamod2'.$i.$k]='0.00';
                    }
                    if(is_numeric($_POST['copagopor2'.$i.$k])==0
                    ||$_POST['copagopor2'.$i.$k]>=1000)
                    {
                        $_POST['copagopor2'.$i.$k]='0.00';
                    }
                    if(is_numeric($_POST['copagomax2'.$i.$k])==0
                    ||$_POST['copagomax2'.$i.$k]>=10000000)
                    {
                        $_POST['copagomax2'.$i.$k]='0.00';
                    }
                    if(is_numeric($_POST['copagomin2'.$i.$k])==0
                    ||$_POST['copagomin2'.$i.$k]>=10000000)
                    {
                        $_POST['copagomin2'.$i.$k]='0.00';
                    }
                    if(is_numeric($_POST['copagoano2'.$i.$k])==0
                    ||$_POST['copagoano2'.$i.$k]>=10000000)
                    {
                        $_POST['copagoano2'.$i.$k]='0.00';
                    }
                }
            }
        }
        if($nombre==0 || $repite==0)
        {
            if($nombre==0)
            {
                $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
            }
            if($repite==0)
            {
                $this->frmError["MensajeError"]="EXISTEN RANGOS REPETIDOS";
            }
            $this->uno=1;
            $this->ValoresRangosContra2();
        }
        else
        {
            list($dbconn) = GetDBconn();
            $dbconn->BeginTrans();
            $query ="DELETE FROM planes_rangos
                    WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollBackTrans();
                return false;
            }
            for($i=0;$i<$_SESSION['ctrpla']['rangospl2'];$i++)
            {
                for($k=0;$k<sizeof($_SESSION['ctrpla']['afiliaM']);$k++)
                {
                    if(!($_SESSION['ctrpla']['afiliado2'][$k]==NULL))//Llaves
                    {
                        $query ="INSERT INTO planes_rangos
                                (plan_id,
                                tipo_afiliado_id,
                                rango,
                                cuota_moderadora,
                                copago,
                                copago_maximo,
                                copago_minimo,
                                copago_maximo_ano)
                                VALUES
                                (".$_SESSION['ctrpla']['planeleg'].",
                                '".$_SESSION['ctrpla']['afiliado2'][$k]."',
                                '".$_POST['nomranctra2'.$i]."',
                                ".$_POST['cuotamod2'.$i.$k].",
                                ".$_POST['copagopor2'.$i.$k].",
                                ".$_POST['copagomax2'.$i.$k].",
                                ".$_POST['copagomin2'.$i.$k].",
                                ".$_POST['copagoano2'.$i.$k].");";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollBackTrans();
                            return false;
                        }
                    }
                }
            }
            $dbconn->CommitTrans();
            $this->ClientePlanContra();
        }
        return true;
    }

    function ModificarDatosPlanContra()//Valida y modifica los datos del plan
    {
        $this->uno=0;
        $this->dias=0;
        if($_POST['clientectraM']==NULL)
        {
            $this->frmError["clientectraM"]=1;
        }
        if($_POST['tipoplctraM']==NULL)
        {
            $this->frmError["tipoplctraM"]=1;
        }
        if(empty($_POST['descrictraM']))
        {
            $this->frmError["descrictraM"]=1;
        }
        if(empty($_POST['nombre']))
        {
            $this->frmError["nombre"]=1;
        }
        if(empty($_POST['codigo']))
        {
            $this->frmError["codigo"]=1;
        }
        if(empty($_POST['tipoTerceroId']))
        {
            $this->frmError["tipoTerceroId"]=1;
        }
        if($_POST['contactoctraM']==NULL)
        {
            $this->frmError["contactoctraM"]=1;
            $_POST['contactoctraM']='';
        }
        if($_POST['usuariosctraM']==NULL)
        {
            $this->frmError["usuariosctraM"]=1;
        }
        if($_POST['numeroctraM']==NULL)
        {
            $this->frmError["numeroctraM"]=1;
        }
        if($_POST['diasCredito']==NULL)
        {
            $this->frmError["diasCredito"]=1;
        }
         if(!is_numeric($_POST['diasCredito']))
        {
            $this->dias=1;
        }
        //
          $_POST['valorctraM'] = str_replace('.','',$_POST['valorctraM']);
        //
       $valorcontr=doubleval($_POST['valorctraM']);
        if(is_numeric($_POST['valmectraM'])==0)
        {
            $_POST['valmectraM']='0';
        }
        else
        {
            $valmecontr=doubleval($_POST['valmectraM']);
            if($valmecontr >= 100000000000000)
            {
                $_POST['valmectraM']='0';
            }
            else if($valorcontr<$valmecontr)
            {
                $_POST['valmectraM']='0';
            }
        }
        if(is_numeric($_POST['facturactraM'])==0)
        {
            $_POST['facturactraM']='0';
        }
        else
        {
            $factucontr=doubleval($_POST['facturactraM']);
            if($factucontr >= 10000000000)
            {
                $_POST['facturactraM']='0';
            }
            else
            {
                if($factucontr > $valorcontr)
                {
                    $_POST['facturactraM']='0';
                }
            }
        }
        
        //MODIFICADO EHUDES, AGREGAR NUEVO CAMPO A LA TABLA PLANES
        if(!is_numeric($_POST['diasCredito']))
        {
            $this->frmError["diasCredito"]=1;
        }        
        
        if(empty($_POST['feinictraM']))
        {
            $this->frmError["feinictraM"]=1;
        }
        else
        {//La fecha no va validada con la fecha del sistema
            $fecdes=explode('/',$_POST['feinictraM']);
            $day=$fecdes[0];
            $mon=$fecdes[1];
            $yea=$fecdes[2];
            if(checkdate($mon, $day, $yea)==0)
            {
                $_POST['feinictraM']='';
                $this->frmError["feinictraM"]=1;
            }
            else
            {
                $fecdes=$yea.'-'.$mon.'-'.$day;
            }
        }
        if(empty($_POST['fefinctraM']))
        {
            $this->frmError["fefinctraM"]=1;
        }
        else
        {
            $fechas=explode('/',$_POST['fefinctraM']);
            $day=$fechas[0];
            $mon=$fechas[1];
            $yea=$fechas[2];
            if(checkdate($mon, $day, $yea)==0)
            {
                $_POST['fefinctraM']='';
                $this->frmError["fefinctraM"]=1;
            }
            else
            {
                $fech=date ("Y-m-d");
                if($fech >= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                {
                    $_POST['fefinctraM']='';
                    $this->frmError["fefinctraM"]=1;
                }
                else if(!empty($_POST['feinictraM']))
                {
                    if($fecdes >= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['fefinctraM']='';
                        $this->frmError["fefinctraM"]=1;
                    }
                    else
                    {
                        $fechas=$yea.'-'.$mon.'-'.$day;
                    }
                }
            }
        }
/*        if($_POST['liquihactraM']==NULL)
        {
            $this->frmError["liquihactraM"]=1;
        }*/
				//MODIFICACIÓN PARA LA LIQUIDACIÓN DE HABITACIONES - 16/09/05
        if($_POST['LiquidaHab']==NULL)
        {
            $this->frmError["LiquidaHab"]=1;
        }
				//FIN MODIFICACIÓN PARA LA LIQUIDACIÓN DE HABITACIONES - 16/09/05
        if($_POST['capitactraM']==NULL)
        {
            $this->frmError["capitactraM"]=1;
        }
        if($_POST['afiliactraM']==NULL)
        {
            $this->frmError["afiliactraM"]=1;
        }
        if($_POST['ponderarctraM']==NULL)
        {
            $this->frmError["ponderarctraM"]=1;
        }
        if($_POST['facagrctraM']==NULL)
        {
            $this->frmError["facagrctraM"]=1;
        }
        if($_POST['telefono1M']==NULL)
        {
            $this->frmError["telefono1M"]=1;
        }
        if($_POST['paraimdctraM']==NULL)
        {
            $this->frmError["paraimdctraM"]=1;
        }
        if($_POST['tipaimdctraM']==NULL AND $_POST['paraimdctraM']==1)
        {
            $this->frmError["tipaimdctraM"]=1;
        }
        if($_POST['paracadctraM']==NULL)
        {
            $this->frmError["paracadctraM"]=1;
        }
        $this->frmError["serviciosM"]=0;
        for($i=0;$i<$_POST['serviciosM'];$i++)
        {
            if($_POST['serviciosM'.$i]<>NULL)
            {
                $this->frmError["serviciosM"]=0;
                break;
            }
            else
            {
                $this->frmError["serviciosM"]=1;
            }
        }
        if($_POST['bventactraM']==NULL)
        {
            $this->frmError["bventactraM"]=1;
        }
        if($_POST['excmonctraM']==NULL)
        {
            $_POST['excmonctraM']='0';
        }
        if(is_numeric($_POST['incumpctraM'])==0)
        {
            $_POST['incumpctraM']='';
            $this->frmError["incumpctraM"]=1;
        }
        else
        {
            $_POST['incumpctraM']=intval($_POST['incumpctraM']);
            if($_POST['incumpctraM']>32000 OR $_POST['incumpctraM']<1)
            {
                $_POST['incumpctraM']='';
                $this->frmError["incumpctraM"]=1;
            }
        }
        if($_POST['liquidacarctraM']==NULL)
        {
            $this->frmError["liquidacarctraM"]=1;
        }
        if($_POST['mesconbdM']==NULL)
        {
            $this->frmError["mesconbdM"]=1;
        }
        else
        {
            $_POST['mesconbdM']=intval($_POST['mesconbdM']);
            if($_POST['mesconbdM']>32000 OR $_POST['mesconbdM']<1)
            {
                $_POST['mesconbdM']=1;
            }
        }
				//$_POST['liquihactraM']==NULL
        if(empty($_POST['descrictraM'])||empty($_POST['nombre'])||
        empty($_POST['codigo'])||empty($_POST['tipoTerceroId'])||
        empty($_POST['feinictraM'])||empty($_POST['fefinctraM'])||
        $this->frmError["serviciosM"]==1||$this->frmError["tipaimdctraM"]==1||
        $_POST['capitactraM']==NULL||$_POST['clientectraM']==NULL||
        $_POST['afiliactraM']==NULL||$_POST['telefono1M']==NULL||
        $_POST['facagrctraM']==NULL||$_POST['numeroctraM']==NULL||
        $_POST['contactoctraM']==NULL||$_POST['tipoplctraM']==NULL||
        $_POST['LiquidaHab']==NULL||$_POST['usuariosctraM']==NULL||
        $_POST['ponderarctraM']==NULL||$_POST['bventactraM']==NULL||
        $_POST['paraimdctraM']==NULL||$_POST['paracadctraM']==NULL||
        $_POST['incumpctraM']==NULL||$_POST['liquidacarctraM']==NULL)
        {
            if($valorcontr<$valmecontr)
            {
                $this->frmError["MensajeError"]="EL VALOR MENSUAL DEL CONTRATO ES MAYOR AL VALOR ANUAL";
            }
            else
            if($this->dias==1)
            {
                $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS <br>EL CAMPO DIAS CREDITO DEBE SER NUMERICO";
            }
            else
            {
                $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
            }
            $this->uno=1;
            $this->ModificaDatosPlan();
        }
        else
        {
            list($dbconn) = GetDBconn();
            $dbconn->BeginTrans();
						//CUANDO SE MODIFICA LA FECHA FINAL DEL CONTRATO
						$fech=date ("Y-m-d");
						$query ="	SELECT estado
											FROM planes
											WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
            $resulta = $dbconn->Execute($query);
						if ($fechas>$fech AND $resulta->fields[0]==3)
								$estado=", estado='0'";
						else
								$estado="";
						//FIN
            if(empty($_POST['copagoctraM']))
            {
                $_POST['copagoctraM']="Copago";
            }
            if(empty($_POST['cuotactraM']))
            {
                $_POST['cuotactraM']="Cuota Moderadora";
            }
            if($_POST['paraimdctraM']==0)
            {
                $_POST['tipaimdctraM']=0;
            }
						if($_POST['bventactraM']!=3)
						{
								$_POST['listaprecios']=NULL;
						}
						if(empty($_POST['porcentaje']))
						{
								$_POST['porcentaje']=0;
						}
						if(!empty($_POST['listaprecios']))
						{
							$sql="lista_precios='".$_POST['listaprecios']."',";
						}
						else
							$sql=" ";
						//tipo_liq_habitacion='".$_POST['liquihactraM']."',
            $query ="UPDATE planes SET
                    tipo_tercero_id='".$_POST['tipoTerceroId']."',
                    tercero_id='".$_POST['codigo']."',
                    plan_descripcion='".$_POST['descrictraM']."',
                    num_contrato='".$_POST['numeroctraM']."',
                    monto_contrato =".$_POST['valorctraM'].",
                    saldo_contrato = ".$_POST['valorctraM'].",
                    monto_contrato_mensual=".$_POST['valmectraM'].",
                    tope_maximo_factura=".$_POST['facturactraM'].",
                    dias_credito_cartera=".$_POST['diasCredito'].",
                    fecha_inicio='".$fecdes."',
                    fecha_final='".$fechas."',
                    sw_tipo_plan='".$_POST['tipoplctraM']."',
                    tipo_cliente='".$_POST['clientectraM']."',
                    sw_autoriza_sin_bd='".$_POST['capitactraM']."',
                    sw_afiliacion='".$_POST['afiliactraM']."',
                    tipo_liquidacion_id='".$_POST['ponderarctraM']."',
                    sw_facturacion_agrupada='".$_POST['facagrctraM']."',
                    sw_paragrafados_imd='".$_POST['paraimdctraM']."',
                    sw_paragrafados_cd='".$_POST['paracadctraM']."',
                    observacion='".$_POST['observacionM']."',
                    servicios_contratados='".$_POST['servicioctraM']."',
                    protocolos='".$_POST['protocoloctraM']."',
                    contacto='".$_POST['contactoctraM']."',
                    lineas_atencion='".$_POST['telefono1M']."',
                    nombre_copago='".$_POST['copagoctraM']."',
                    nombre_cuota_moderadora='".$_POST['cuotactraM']."',
                    sw_base_liquidacion_imd='".$_POST['bventactraM']."',
                    sw_exceder_monto_mensual='".$_POST['excmonctraM']."',
                    actividad_incumplimientos=".$_POST['incumpctraM'].",
                    tipo_liquidacion_cargo=".$_POST['liquidacarctraM'].",
                    meses_consulta_base_datos=".$_POST['mesconbdM'].",
                    horas_cancelacion='".$_POST['horaprecanM']."',
                    telefono_cancelacion_cita='".$_POST['linecancitM']."',
                    tipo_para_imd=".$_POST['tipaimdctraM'].",
                    sw_solicita_autorizacion_admision = '".$_POST['auto_solicitud']."',
                    sw_afiliados = '".$_POST['sw_afiliaciones']."',
                    usuario_id=".UserGetUID().",
                    sw_contrata_hospitalizacion='".$_POST['LiquidaHab']."',
			$sql
			porcentaje_utilidad='".$_POST['porcentaje']."'
			$estado
                    WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $dbconn->RollBackTrans();
               if(!is_numeric( $_POST['diasCredito']))
                $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS<br>EL CAMPO DIAS CREDITO DEBE SER NUMERICO";
               else
                $this->frmError["MensajeError"]="DATOS DUPLICADOS - VERIFICAR NÚMERO DEL CONTRATO.".$dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
                $this->uno=1;
                $this->ModificaDatosPlan();
                return true;
            }
            /*ESTO SE ACTIVA SI SE BORRA EL DE ARRIBA
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollBackTrans();
                return false;
            }*/
            $query ="UPDATE planes_encargados SET
                    usuario_id=".$_POST['usuariosctraM']."
                    WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollBackTrans();
                return false;
            }
            $query ="DELETE FROM planes_servicios
                    WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $dbconn->RollBackTrans();
            }
            for($i=0;$i<$_POST['serviciosM'];$i++)
            {
                if($_POST['serviciosM'.$i]<>NULL)
                {
                   $query ="INSERT INTO planes_servicios
                            (plan_id,
                            servicio)
                            VALUES
                            (".$_SESSION['ctrpla']['planeleg'].",
                            '".$_POST['serviciosM'.$i]."');"; 
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $dbconn->RollBackTrans();
                    }
                }
            }
						
						//ALMACEMAR CONDICION DE LOS USUARIOS A ATENDER
						$dat = SessionGetVar("CondicionUsuario");
						$query ="DELETE 
											FROM planes_condicion_usuario;";
						$resulta = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "ERROR AL CARGAR LOS DATOS planes_condicion_usuario";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
							$dbconn->RollBackTrans();
						}
						for($i=0;$i<sizeof($dat);$i++)
						{
								if($_POST["Condicion".$dat[$i][tipos_condicion_usuarios_planes_id]]=='1')
								{
										$query ="INSERT INTO planes_condicion_usuario
														(
															plan_id,
															tipos_condicion_usuarios_planes_id
														)
														VALUES
														(
															".$_SESSION['ctrpla']['planeleg'].",
															".$dat[$i][tipos_condicion_usuarios_planes_id]."
														);"; 
										$resulta = $dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0)
										{
												$this->error = "ERROR AL CARGAR LOS DATOS planes_condicion_usuario";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
												$dbconn->RollBackTrans();
										}
								}
						}
						//FIN ALMACEMAR CONDICION DE LOS USUARIOS A ATENDER
           
            $_SESSION['ctrpla']['desceleg']=$_POST['descrictraM'];
            $_SESSION['ctrpla']['numeeleg']=$_POST['numeroctraM'];
            $_SESSION['ctrpla']['nombeleg']=$_POST['nombre'];
            $_SESSION['ctrpla']['tidteleg']=$_POST['tipoTerceroId'];
            $_SESSION['ctrpla']['terceleg']=$_POST['codigo'];
            $_SESSION['ctrpla']['pimdeleg']=$_POST['paraimdctraM'];
            $_SESSION['ctrpla']['pcadeleg']=$_POST['paracadctraM'];
            $_SESSION['ctrpla']['tpmdeleg']=$_POST['tipaimdctraM'];
						$_SESSION['habitaciones']=$_POST['LiquidaHab'];
            $dbconn->CommitTrans();
            $this->ClientePlanContra();
        }
        return true;
    }

    /********************FUNCIONES GENERALES********************/
    function BuscarClientesContra()//Busca los clientes de la clinica
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT tipo_cliente, descripcion
                FROM tipos_cliente;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarLiqHabContra()//Busca los tipos de liquidación de una habitación
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT tipo_liq_habitacion, descripcion
                FROM tipos_liq_habitacion;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarLiqSemContra()//Busca los tipos de liquidación para las semanas de carencia
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT tipo_liquidacion_id, descripcion
                FROM tipos_liquidacion_semanas_cotizadas;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarLiqCarContra()//Busca los tipos de liquidación de los cargo
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT tipo_liquidacion_cargo, descripcion
                FROM tipo_liquidaciones_cargos;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarTipoParaImdContra()//Busca los tipos de liquidación de los cargo
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT tipo_para_imd, descripcion
                FROM tipos_paragrafados_imd;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarTipoPlanContra()//Busca los tipos de planes
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT sw_tipo_plan, descripcion
                FROM tipos_planes ORDER BY descripcion;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarServiciosContra()//Función que busca los servicios disponibles
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT servicio,
                descripcion
                FROM servicios
                WHERE sw_asistencial='1'
                AND servicio<>'0'
                ORDER BY servicio;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarNivelesAteContra()//Función que busca los niveles de atención disponibles
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT nivel, descripcion, descripcion_corta
                FROM niveles_atencion ORDER BY nivel;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarEncargadosContra($empresa)//Busca los usuarios del sistema
    {
        list($dbconn) = GetDBconn();//estado del usuario
        $query ="SELECT A.usuario_id,
                A.nombre
                FROM system_usuarios AS A,
                system_usuarios_empresas AS B,
                system_usuarios_funciones AS C
                WHERE B.empresa_id='".$empresa."'
                AND B.usuario_id=A.usuario_id
                AND B.usuario_id=C.usuario_id
                AND C.sw_tipo_funcion<>2
                AND A.sw_admin='0'
                AND A.activo='1'
                ORDER BY nombre;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarTipoAfiliadoContra()//Busca los tipos de afiliación
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT tipo_afiliado_id, tipo_afiliado_nombre
                FROM tipos_afiliado ORDER BY tipo_afiliado_id;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarEmpresasContra()//Busca las empresas existentes
    {
        list($dbconn) = GetDBconn();
        $usuario=UserGetUID();
        $query ="SELECT empresa_id, razon_social
                FROM empresas;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    /********************FUNCIONES DE LA OPCIÓN TARIFARIOS********************/
    function BuscarTarifariosContra()//Busca los tarifarios
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT tarifario_id,
                descripcion
                FROM tarifarios
                WHERE tarifario_id<>'SYS'
                ORDER BY descripcion;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function ModificarTariPlanContra($pl1,$gr1,$sg1)//Determina si se puede cambiar algún grupo o subgrupo
    {
        list($dbconn) = GetDBconn();
        $query ="select contratacion_cargosgrupo ('".$pl1."','".$gr1."','".$sg1."');";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        return $resulta->fields[0];
    }

    function BuscarCargosContratadosContra($plan)//
    {
        list($dbconn) = GetDBconn();
        if($_REQUEST['codigoctra'])
        {
            $codigo=$_REQUEST['codigoctra'];
            $busqueda="AND A.cargo LIKE '$codigo%'";
        }
        else
        {
            $busqueda='';
        }
        if($_REQUEST['descrictra'])
        {
            $codigo=STRTOUPPER($_REQUEST['descrictra']);
            $busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
        }
        else
        {
            $busqueda2='';
        }
        if($_REQUEST['tarifactra'])
        {
            $codigo=STRTOUPPER($_REQUEST['tarifactra']);
            $busqueda3="AND A.tarifario_id='$codigo'";
        }
        else
        {
            $busqueda3='';
        }
        if($_REQUEST['codigoctra'])
        {
            $query ="SELECT A.cargo,
                    A.descripcion,
                    B.tarifario_id,
                    C.descripcion AS destarifario
                    FROM tarifarios_detalle AS A
                    LEFT JOIN plan_tarifario AS B ON
                    (B.plan_id=$plan
                    AND A.grupo_tarifario_id=B.grupo_tarifario_id
                    AND A.subgrupo_tarifario_id=B.subgrupo_tarifario_id
                    AND A.tarifario_id=B.tarifario_id),
                    tarifarios AS C
                    WHERE A.tarifario_id=C.tarifario_id
                    $busqueda
                    $busqueda2
                    $busqueda3
                    ORDER BY A.tarifario_id, A.cargo;";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $i=0;
            while(!$resulta->EOF)
            {
                $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
                $i++;
            }
            return $var;
        }
        else
        {
            return false;
        }
    }

    function BuscarTarifarioPlanRapidaContra($plan,$grupo,$subgrupo)//Busca los grupos, las clases y subclases del inventario, asi como el tarifario
    {
        list($dbconn) = GetDBconn();
	$filtro ="";
	if($subgrupo)
	{$filtro =" AND B.subgrupo_tarifario_id = '$subgrupo' ";}
        $query ="SELECT DISTINCT C.tarifario_id,
                E.descripcion,
                A.grupo_tarifario_id,
                A.grupo_tarifario_descripcion,
                B.subgrupo_tarifario_id,
                B.subgrupo_tarifario_descripcion,
                D.porcentaje,
                D.por_cobertura,
                D.sw_descuento
                FROM tarifarios AS E,
                grupos_tarifarios AS A,
                subgrupos_tarifarios AS B,
                tarifarios_detalle AS C
                LEFT JOIN plan_tarifario AS D ON
                (
                    D.plan_id=".$plan."
                    AND D.grupo_tarifario_id='".$grupo."'
                    AND C.tarifario_id=D.tarifario_id
                    AND C.grupo_tarifario_id=D.grupo_tarifario_id
                    AND C.subgrupo_tarifario_id=D.subgrupo_tarifario_id
                )
                WHERE A.grupo_tarifario_id='".$grupo."'
                AND A.grupo_tarifario_id=B.grupo_tarifario_id
                AND C.grupo_tarifario_id=B.grupo_tarifario_id
                AND C.subgrupo_tarifario_id=B.subgrupo_tarifario_id
                AND C.tarifario_id<>'SYS'
                AND C.grupo_tarifario_id<>'00'
                AND C.tarifario_id=E.tarifario_id
		$filtro
                ORDER BY A.grupo_tarifario_id, B.subgrupo_tarifario_id;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarGruposPlanContra($plan)//Busca los grupos, las clases y subclases del inventario, asi como el tarifario
    {
        list($dbconn) = GetDBconn();
				//AND A.sw_internacion='0' se adicionó para que la parametrización
				//por la opción PARAMETRIZACIÓN DE HABITACIONES del menu dos
        $query ="SELECT A.grupo_tarifario_id,
                A.grupo_tarifario_descripcion,
                B.subgrupo_tarifario_id,
                B.subgrupo_tarifario_descripcion
                FROM grupos_tarifarios AS A,
                subgrupos_tarifarios AS B
                WHERE A.grupo_tarifario_id=B.grupo_tarifario_id
                AND A.grupo_tarifario_id<>'00'
								AND (A.sw_internacion = '0' OR A.sw_internacion = '1')
                ORDER BY A.grupo_tarifario_id, B.subgrupo_tarifario_id;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarPlanTarifarioPlanContra($plan)//Busca los grupos, las clases y subclases del inventario, asi como el tarifario
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT D.tarifario_id,
        D.grupo_tarifario_id,
        D.subgrupo_tarifario_id,
        D.porcentaje,
        D.por_cobertura,
        D.sw_descuento
        FROM plan_tarifario AS D
        WHERE D.plan_id=".$plan."
        AND D.tarifario_id<>'SYS';";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var[$resulta->fields[1]][$resulta->fields[2]]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    /*function BuscarTarifarioPlanContra($plan,$grupo,$subgr)//Busca los grupos, las clases y subclases del inventario, asi como el tarifario
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT DISTINCT C.tarifario_id,
                E.descripcion,
                D.porcentaje,
                D.por_cobertura,
                D.sw_descuento
                FROM tarifarios AS E,
                tarifarios_detalle AS C
                LEFT JOIN plan_tarifario AS D ON
                (
                    D.plan_id=".$plan."
                    AND C.tarifario_id=D.tarifario_id
                    AND C.grupo_tarifario_id=D.grupo_tarifario_id
                    AND C.subgrupo_tarifario_id=D.subgrupo_tarifario_id
                )
                WHERE C.grupo_tarifario_id='".$grupo."'
                AND C.subgrupo_tarifario_id='".$subgr."'
                AND C.tarifario_id<>'SYS'
                AND C.grupo_tarifario_id<>'00'
                AND C.tarifario_id=E.tarifario_id
                ORDER BY E.descripcion;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }*/

    function BuscarTarifarioPlanContra($grupo,$subgr)//Busca los grupos, las clases y subclases del inventario, asi como el tarifario
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT DISTINCT C.tarifario_id,
        E.descripcion
        FROM tarifarios AS E,
        tarifarios_detalle AS C
        WHERE C.grupo_tarifario_id='".$grupo."'
        AND C.subgrupo_tarifario_id='".$subgr."'
        AND C.tarifario_id<>'SYS'
        AND C.grupo_tarifario_id<>'00'
        AND C.tarifario_id=E.tarifario_id
        ORDER BY E.descripcion;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function ValidarTarifarioPlanContra()//Valida, y guarda o modifica los datos del plan tarifario
    {
        $this->frmError["MensajeError"]='';
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $ciclo=sizeof($_SESSION['ctrpl1']['grutaplanc']);
        for($k=0;$k<$ciclo;)
        {
            $g1=$g2=0;
            if(is_numeric($_POST['porceplanc'.$k])==1)
            {
                $por1=doubleval($_POST['porceplanc'.$k]);
                if($por1 <= 999.9999 AND $por1 >= -999.9999)
                {
                    $g1=1;
                }
            }
            if(is_numeric($_POST['coberplanc'.$k])==1)
            {
                $por2=doubleval($_POST['coberplanc'.$k]);
                if($por2 <= 100 AND $por2 >= 0)//999.9999
                {
                    $g2=1;
                }
            }
            if($g1==1||$g2==1)
            {
                if($g1==0)
                {
                    $por1=0.00;
                }
                if($g2==0)
                {
                    $por2=0.00;
                }
                if($_POST['descuplanc'.$k]==NULL)
                {
                    $_POST['descuplanc'.$k]=0;
                }
            }
            if($_POST['tarifplanc'.$k]<>NULL AND (($g1==1 OR $g2==1)))
            {
                if($_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']]['porcentaje']==NULL)
                {
                    $query ="INSERT INTO plan_tarifario
                            (plan_id,
                            grupo_tarifario_id,
                            subgrupo_tarifario_id,
                            tarifario_id,
                            porcentaje,
                            por_cobertura,
                            sw_descuento)
                            VALUES
                            (".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']."',
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']."',
                            '".$_POST['tarifplanc'.$k]."',
                            ".$por1.",
                            ".$por2.",
                            '".$_POST['descuplanc'.$k]."');";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                else if($_POST['tarifplanc'.$k]<>NULL
                AND $_POST['tarifplanc'.$k]<>$_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']]['tarifario_id'])
                {
                    $query ="UPDATE plan_tarifario SET
                            tarifario_id='".$_POST['tarifplanc'.$k]."',
                            porcentaje=".$por1.",
                            por_cobertura=".$por2.",
                            sw_descuento='".$_POST['descuplanc'.$k]."'
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                            AND grupo_tarifario_id='".$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']."'
                            AND subgrupo_tarifario_id='".$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']."';";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                    $query ="SELECT borrar_excepciones_mod(".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']]['tarifario_id']."',
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']."',
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']."');";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                    $query ="SELECT borrar_excepciones_copagos_mod(".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']]['tarifario_id']."',
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']."',
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']."');";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                    $query ="SELECT borrar_excepciones_semanas_mod(".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']]['tarifario_id']."',
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']."',
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']."');";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                else if($_POST['tarifplanc'.$k]<>NULL
                AND $_POST['tarifplanc'.$k]==$_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']]['tarifario_id']
                AND ($_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']]['porcentaje']<>$por1
                OR $_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']]['por_cobertura']<>$por2))
                {
                    $query ="UPDATE plan_tarifario SET
                            porcentaje=".$por1.",
                            por_cobertura=".$por2.",
                            sw_descuento='".$_POST['descuplanc'.$k]."'
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                            AND grupo_tarifario_id='".$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']."'
                            AND subgrupo_tarifario_id='".$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']."';";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                    $query ="SELECT borrar_excepciones_eli(".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']]['tarifario_id']."',
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']."',
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']."',
                            ".number_format(($por1), 4, '.', ',').",
                            ".number_format(($por2), 4, '.', ',').",
                            '".$_POST['descuplanc'.$k]."');";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
            }
            else if($_POST['tarifplanc'.$k]==NULL AND $_SESSION['ctrpl1']['plataplanc'][$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']][$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']]['porcentaje']<>NULL)
            {
                $query ="DELETE FROM plan_tarifario
                        WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                        AND grupo_tarifario_id='".$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']."'
                        AND subgrupo_tarifario_id='".$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']."';";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR ELIMINAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
            }
            $_POST['tarifplanc'.$k]='';
            $_POST['porceplanc'.$k]='';
            $_POST['coberplanc'.$k]='';
            $_POST['descuplanc'.$k]='';
            $k++;
        }
        $dbconn->CommitTrans();
        $query ="SELECT count(plan_id) FROM plan_tarifario
                WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
        $resulta2 = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $resulta2->fields[0]=1;
        }
        if($resulta2->fields[0]<2)
        {
            $query ="UPDATE planes SET estado=0
                    WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " .$dbconn->ErrorMsg();
                return false;
            }
        }
        if($this->frmError["MensajeError"]==NULL)
        {
            $this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
        }
        $this->uno=1;
        $this->TarifarioPlanContra();
        return true;
    }

    function ValidarTarifarioPlanContraRapida()
    {
        $this->frmError["MensajeError"]='';
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $ciclo=sizeof($_SESSION['ctrpl1']['grutaplanc']);
        for($k=0;$k<$ciclo;)
        {
            $g1=$g2=0;
            if(is_numeric($_POST['porceplanc'.$k])==1)
            {
                $por1=doubleval($_POST['porceplanc'.$k]);
                if($por1 <= 999.9999 AND $por1 >= -999.9999)
                {
                    $g1=1;
                }
            }
            if(is_numeric($_POST['coberplanc'.$k])==1)
            {
                $por2=doubleval($_POST['coberplanc'.$k]);
                if($por2 <= 100 AND $por2 >= 0)//999.9999
                {
                    $g2=1;
                }
            }
            if($g1==1||$g2==1)
            {
                if($g1==0)
                {
                    $por1=0.00;
                }
                if($g2==0)
                {
                    $por2=0.00;
                }
                if($_POST['descuplanc'.$k]==NULL)
                {
                    $_POST['descuplanc'.$k]=0;
                }
            }
            if($_POST['tarifplanc'.$k]<>NULL AND (($g1==1 OR $g2==1)))
            {
                if($_SESSION['ctrpl1']['grutaplanc'][$k]['porcentaje']==NULL)
                {
                    $query ="INSERT INTO plan_tarifario
                            (plan_id,
                            grupo_tarifario_id,
                            subgrupo_tarifario_id,
                            tarifario_id,
                            porcentaje,
                            por_cobertura,
                            sw_descuento)
                            VALUES
                            (".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']."',
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']."',
                            '".$_POST['tarifplanc'.$k]."',
                            ".$por1.",
                            ".$por2.",
                            '".$_POST['descuplanc'.$k]."');";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                else if($_POST['tarifplanc'.$k]<>NULL
                AND $_POST['tarifplanc'.$k]<>$_SESSION['ctrpl1']['grutaplanc'][$k]['tarifario_id'])
                {
                    $query ="UPDATE plan_tarifario SET
                            tarifario_id='".$_POST['tarifplanc'.$k]."',
                            porcentaje=".$por1.",
                            por_cobertura=".$por2.",
                            sw_descuento='".$_POST['descuplanc'.$k]."'
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                            AND grupo_tarifario_id='".$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']."'
                            AND subgrupo_tarifario_id='".$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']."';";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                    $query ="SELECT borrar_excepciones_mod(".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['tarifario_id']."',
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']."',
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']."');";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                    $query ="SELECT borrar_excepciones_copagos_mod(".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['tarifario_id']."',
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']."',
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']."');";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                    $query ="SELECT borrar_excepciones_semanas_mod(".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['tarifario_id']."',
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']."',
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']."');";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                else if($_POST['tarifplanc'.$k]<>NULL
                AND $_POST['tarifplanc'.$k]==$_SESSION['ctrpl1']['grutaplanc'][$k]['tarifario_id']
                AND ($_SESSION['ctrpl1']['grutaplanc'][$k]['porcentaje']<>$por1
                OR $_SESSION['ctrpl1']['grutaplanc'][$k]['por_cobertura']<>$por2))
                {
                    $query ="UPDATE plan_tarifario SET
                            porcentaje=".$por1.",
                            por_cobertura=".$por2.",
                            sw_descuento='".$_POST['descuplanc'.$k]."'
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                            AND grupo_tarifario_id='".$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']."'
                            AND subgrupo_tarifario_id='".$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']."';";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                    $query ="SELECT borrar_excepciones_eli(".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['tarifario_id']."',
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']."',
                            '".$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']."',
                            ".number_format(($por1), 4, '.', ',').",
                            ".number_format(($por2), 4, '.', ',').",
                            '".$_POST['descuplanc'.$k]."');";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
            }
            else if($_POST['tarifplanc'.$k]==NULL AND $_SESSION['ctrpl1']['grutaplanc'][$k]['porcentaje']<>NULL)
            {
                $query ="DELETE FROM plan_tarifario
                        WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                        AND grupo_tarifario_id='".$_SESSION['ctrpl1']['grutaplanc'][$k]['grupo_tarifario_id']."'
                        AND subgrupo_tarifario_id='".$_SESSION['ctrpl1']['grutaplanc'][$k]['subgrupo_tarifario_id']."';";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR ELIMINAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
            }
            $_POST['tarifplanc'.$k]='';
            $_POST['porceplanc'.$k]='';
            $_POST['coberplanc'.$k]='';
            $_POST['descuplanc'.$k]='';
            $k++;
        }
        $dbconn->CommitTrans();
        $query ="SELECT count(plan_id) FROM plan_tarifario
                WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
        $resulta2 = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $resulta2->fields[0]=1;
        }
        if($resulta2->fields[0]<2)
        {
            $query ="UPDATE planes SET estado=0
                    WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " .$dbconn->ErrorMsg();
                return false;
            }
        }
        if($this->frmError["MensajeError"]==NULL)
        {
            $this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
        }
        $this->uno=1;
        $this->TarifarioPlanContraRapida();
        return true;
    }

    function BuscarConsulCargosTarifarioContra($plan,$grd,$sud)//Busca los detalles del tarifario y las excepciones
    {
        list($dbconn) = GetDBconn();
        if($_REQUEST['codigoctra'])
        {
            $codigo=$_REQUEST['codigoctra'];
            $busqueda="AND A.cargo LIKE '$codigo%'";
        }
        else
        {
            $busqueda='';
        }
        if($_REQUEST['descrictra'])
        {
            $codigo=STRTOUPPER($_REQUEST['descrictra']);
            $busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
        }
        else
        {
            $busqueda2='';
        }
        if($_REQUEST['tarifactra'])
        {
            $codigo=STRTOUPPER($_REQUEST['tarifactra']);
            $busqueda3="AND A.tarifario_id='$codigo'";
        }
        else
        {
            $busqueda3='';
        }
        if(empty($_REQUEST['conteo']))
        {
            $query ="SELECT count(*) FROM (
                    (
                        SELECT A.cargo,
                        A.descripcion,
                        B.tarifario_id,
                        C.descripcion AS destarifario
                        FROM tarifarios_detalle AS A
                        LEFT JOIN plan_tarifario AS B ON
                        (B.plan_id=$plan
                        AND A.grupo_tarifario_id=B.grupo_tarifario_id
                        AND A.subgrupo_tarifario_id=B.subgrupo_tarifario_id
                        AND A.tarifario_id=B.tarifario_id),
                        tarifarios AS C
                        WHERE A.grupo_tarifario_id='$grd'
                        AND A.subgrupo_tarifario_id='$sud'
                        AND A.tarifario_id=C.tarifario_id
                        $busqueda
                        $busqueda2
                        $busqueda3
                    )
                    ) AS r;";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            list($this->conteo)=$resulta->fetchRow();
        }
        else
        {
            $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of'])
        {
            $Of='0';
        }
        else
        {
            $Of=$_REQUEST['Of'];
            if($_REQUEST['Of'] > $this->conteo)
            {
                $Of='0';
                $_REQUEST['Of']='0';
                $_REQUEST['paso']='1';
            }
        }
        $query ="
                (
                SELECT A.cargo,
                A.descripcion,
                B.tarifario_id,
                C.descripcion AS destarifario
                FROM tarifarios_detalle AS A
                LEFT JOIN plan_tarifario AS B ON
                (B.plan_id=$plan
                AND A.grupo_tarifario_id=B.grupo_tarifario_id
                AND A.subgrupo_tarifario_id=B.subgrupo_tarifario_id
                AND A.tarifario_id=B.tarifario_id),
                tarifarios AS C
                WHERE A.grupo_tarifario_id='$grd'
                AND A.subgrupo_tarifario_id='$sud'
                AND A.tarifario_id=C.tarifario_id
                $busqueda
                $busqueda2
                $busqueda3
                ORDER BY A.tarifario_id, A.cargo
                )
                LIMIT ".$this->limit." OFFSET $Of;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }
//
		function BuscarDesUnidad($tipounidad)
		{
			list($dbconn) = GetDBconn();
			$query = "SELECT
									descripcion_corta ,descripcion
								FROM tipos_unidades_cargos
								WHERE tipo_unidad_id='".$tipounidad."';";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$i=0;
			while(!$resulta->EOF)
			{
				$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
				$i++;
			}
			return $var;
		}
		//BuscarDatosUVR()
		function BuscarDatosUVRplanes()
		{
			list($dbconn) = GetDBconn();
			$query ="	SELECT 
									a.dc_valor,
									a.tarifario_id,
									a.da_valor,
									a.dg_valor,
									a.dy_valor,
									b.descripcion
								FROM planes_uvrs a, tarifarios b
								WHERE a.plan_id=".$_SESSION['ctrpla']['planeleg']."
											AND a.tarifario_id=b.tarifario_id;";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
					$this->uno=1;
					$this->frmError["MensajeError"]="Error al select en la tabla planes_uvrs : " . $dbconn->ErrorMsg();
					$this->FrmIngresarRangosUVR();
					return true;
			}
			$i=0;
			while(!$resulta->EOF)
			{
				$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
				$i++;
			}
			return $var;
		}
	
		//INGRESO DE DATOS UVR DEL PLAN
		function IngresarDatosPlanContraUVR()
		{
			list($dbconn) = GetDBconn();
			$usuario=UserGetUID();
			$dbconn->BeginTrans();
			$sw=false;
/*			if($_POST['feinictraM']==NULL)
			{
				$this->frmError["feinictraM"]=1;
				$sw=true;
			}
			if($_POST['fefinctraM']==NULL)
			{
				$this->frmError["fefinctraM"]=1;
				$sw=true;
			}*/
			if($_POST['valorespecialista']==NULL)
			{
				$this->frmError["especialista"]=1;
				$sw=true;
			}
			if($sw)
			{
				$this->uno=1;
				$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
				$this->FrmIngresarRangosUVR();
				return true;
			}
			$sw=false;
			if(!empty($_POST['valoranestesiologo']) AND !is_numeric($_POST['valoranestesiologo']))
			{
				$this->frmError["anestesiologo"]=1;
				$sw=true;
			}
			if(!empty($_POST['valorespecialista']) AND !is_numeric($_POST['valorespecialista']))
			{
				$this->frmError["especialista"]=1;
				$sw=true;
			}
			if(!empty($_POST['valorayudante']) AND !is_numeric($_POST['valorayudante']))
			{
				$this->frmError["ayudante"]=1;
				$sw=true;
			}
			if(!empty($_POST['valorgeneral']) AND !is_numeric($_POST['valorgeneral']))
			{
				$this->frmError["general"]=1;
				$sw=true;
			}
			if($sw)
			{
				$this->uno=1;
				$this->frmError["MensajeError"]="DATO DEBE SER NUMERICO";
				$this->FrmIngresarRangosUVR();
				return true;
			}
/*			if($_POST['feinictraM']>$_POST['fefinctraM'])
			{
				$this->uno=1;
				$this->frmError["MensajeError"]="LA FECHA INICIAL NO DEBE SER MAYOR QUE LA FECHA FINAL";
				$this->FrmIngresarRangosUVR();
				return true;
			}
			//VERIFICAR QUE LAS FECHAS DE LOS RANGOS NO SE CRUZEN
			$query ="	SELECT fecha_inicial, fecha_final
								FROM planes_uvrs
								WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
					$this->uno=1;
					$this->frmError["MensajeError"]="Error al select en la tabla planes_uvrs : " . $dbconn->ErrorMsg();
					$this->FrmIngresarRangosUVR();
					return true;
			}
			$i=0;
			while(!$resulta->EOF)
			{
				$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
				$i++;
			}
			$fi=explode('/',$_POST['feinictraM']);
			$ff=explode('/',$_POST['fefinctraM']);
			$fechainicial=$fi[2].'-'.$fi[1].'-'.$fi[0];
			$fechafinal=$ff[2].'-'.$ff[1].'-'.$ff[0];
			for($i=0;$i<sizeof($var);$i++)
			{ 
				if($var[$i][fecha_final]>=$fechainicial && $var[$i][fecha_inicial]<=$fechafinal)
				{ //echo $var[$i][fecha_final].$fechainicial;
					$this->uno=1;
					$this->frmError["MensajeError"]="LA FECHA INICIAL DEL NUEVO RANGO ES MENOR O IGUAL A LA FECHA FINAL DE UN RANGO YA CREADO";
					$this->FrmIngresarRangosUVR();
					return true;
				}
			}*/
			//FIN VERIFICAR QUE LAS FECHAS DE LOS RANGOS NO SE CRUZEN

/*			$fi=explode('/',$_POST['feinictraM']);
			$ff=explode('/',$_POST['fefinctraM']);
			$fechainicial=$fi[2].'-'.$fi[1].'-'.$fi[0];
			$fechafinal=$ff[2].'-'.$ff[1].'-'.$ff[0];*/
			if($_POST['tarifariouvrp']<> -1)
			{
				$query ="DELETE FROM tarifarios_uvrs_paquetes_excepciones
								WHERE plan_id = ".$_SESSION['ctrpla']['planeleg']."
								AND	tarifario_id = '".$_POST['tarifariouvrp']."'
								AND uvr_valor = ".$_POST['valoruvrp'].";";
								$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$dbconn->RollBackTrans();
						$this->uno=1;
						$this->frmError["MensajeError"]="Error al Eliminar en la tabla tarifarios_uvrs_paquetes_excepciones: ".$query.'--'.$dbconn->ErrorMsg();
						$this->FrmIngresarRangosUVR();
						return true;
				}
			}
		if(empty($_POST['valoranestesiologo']))
			$_POST['valoranestesiologo']=0.0;
		if(empty($_POST['valorgeneral']))
			$_POST['valorgeneral']=0.0;
		if(empty($_POST['valorayudante']))
			$_POST['valorayudante']=0.0;
					$query ="SELECT plan_id,
								dc_valor,
								usuario_id,
								tarifario_id,
								da_valor,
								dg_valor,
								dy_valor
								FROM planes_uvrs
								WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
								AND tarifario_id='".$_POST['tarifariouvr']."'";
			$resultado=$dbconn->Execute($query);
			if($resultado->RecordCount()>0)
			{
					$query ="UPDATE planes_uvrs SET	dc_valor=".$_POST['valorespecialista'].",
										usuario_id=".$usuario.",
										da_valor=".$_POST['valoranestesiologo'].",
										dg_valor=".$_POST['valorgeneral'].",
										dy_valor=".$_POST['valorayudante']."
										WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
										AND tarifario_id='".$_POST['tarifariouvr']."'";
					$dbconn->Execute($query);
/*					if ($dbconn->ErrorNo() != 0)
					{
							//$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
							//$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollBackTrans();
							$this->uno=1;
							$this->frmError["MensajeError"]="Error al actualizar en la tabla planes_uvrs : " . $dbconn->ErrorMsg();
							$this->FrmIngresarRangosUVR();
							return true;
					}*/
					
							$query ="SELECT count(*)
												FROM tarifarios_uvrs_paquetes_excepciones
												WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
							$resultado=$dbconn->Execute($query);
			
						if($resultado->fields[0]>0)
						{
							$query ="UPDATE tarifarios_uvrs_paquetes_excepciones
												SET tarifario_id='".$_POST['tarifariouvrp']."',
												uvr_valor=".$_POST['valoruvrp']."
												WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
									//$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
									//$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollBackTrans();
									$this->uno=1;
									$this->frmError["MensajeError"]="Error al actualizar la tabla tarifarios_uvrs_paquetes_excepciones: ".$query.'--'.$dbconn->ErrorMsg();
									$this->FrmIngresarRangosUVR();
									return true;
							}
						}
						elseif($_POST['tarifariouvrp']<> -1)
						{
										$query ="INSERT INTO tarifarios_uvrs_paquetes_excepciones
												(plan_id,
												tarifario_id,
												uvr_valor)
												VALUES(
												".$_SESSION['ctrpla']['planeleg'].",
												'".$_POST['tarifariouvrp']."',
												".$_POST['valoruvrp'].");";
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
									$dbconn->RollBackTrans();
									$this->uno=1;
									$this->frmError["MensajeError"]="Error al insertar en la tabla tarifarios_uvrs_paquetes_excepciones: ".$query.'--'.$dbconn->ErrorMsg();
									$this->FrmIngresarRangosUVR();
									return true;
							}
						}
						$this->uno=1;
						$this->frmError["MensajeError"]="DATOS ACTUALIZADOS";
						$dbconn->CommitTrans();
						$this->FrmIngresarRangosUVR();
						return true;
				}
				$query ="INSERT INTO planes_uvrs
									(plan_id,
									dc_valor,
									usuario_id,
									tarifario_id,
									da_valor,
									dg_valor,
									dy_valor)
									VALUES(
									".$_SESSION['ctrpla']['planeleg'].",
									".$_POST['valorespecialista'].",
									".$usuario.",
									'".$_POST['tarifariouvr']."',
									".$_POST['valoranestesiologo'].",
									".$_POST['valorgeneral'].",
									".$_POST['valorayudante'].");";
				$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
					//$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
					//$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
					$this->uno=1;
					$this->frmError["MensajeError"]="Error al guardar en la tabla planes_uvrs : " . $dbconn->ErrorMsg();
					$this->FrmIngresarRangosUVR();
					return true;
			}
			//INSERTAR UVR PAQUETES
/*		tarifario_id character varying(4) NOT NULL,
		uvr_valor numeric(12,2) DEFAULT 0 NOT NULL,
		plan_id integer NOT NULL*/
			if($_POST['tarifariouvrp']<> -1)
			{
							$query ="INSERT INTO tarifarios_uvrs_paquetes_excepciones
									(plan_id,
									tarifario_id,
									uvr_valor)
									VALUES(
									".$_SESSION['ctrpla']['planeleg'].",
									'".$_POST['tarifariouvrp']."',
									".$_POST['valoruvrp'].");";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						//$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						//$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollBackTrans();
						$this->uno=1;
						$this->frmError["MensajeError"]="Error al insertar en la tabla tarifarios_uvrs_paquetes_excepciones: ".$query.'--'.$dbconn->ErrorMsg();
						$this->FrmIngresarRangosUVR();
						return true;
				}
			}
			//FIN UVR PAQUETES
			$this->uno=1;
			$this->frmError["MensajeError"]="DATOS GUARDADOS";
			$dbconn->CommitTrans();
 			$this->FrmIngresarRangosUVR();
			return true;
		}
//
    function BuscarCarTarPlanContra($plan,$grd,$sud)//Busca los detalles del tarifario y las excepciones
    {
        list($dbconn) = GetDBconn();
        if($_REQUEST['codigoctra'])
        {
            $codigo=$_REQUEST['codigoctra'];
            $busqueda="AND A.cargo LIKE '$codigo%'";
        }
        else
        {
            $busqueda='';
        }
        if($_REQUEST['descrictra'])
        {
            $codigo=STRTOUPPER($_REQUEST['descrictra']);
            $busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
        }
        else
        {
            $busqueda2='';
        }
        if(empty($_REQUEST['conteo']))
        {
						//A.sw_uvrsSE ELIMINA DE LA TABLA tarifarios_detalle
						//EN LOS NUEVOS CASOS DE LIQUIDACION
						//A.sw_uvrs,
            $query ="SELECT count(*) FROM (
                    (
                        SELECT A.cargo,
                        A.descripcion,
                        A.precio,
                        A.nivel,
												A.tipo_unidad_id,
                        B.porcentaje,
                        B.por_cobertura,
                        B.sw_descuento,
                        '0' AS sw_no_contratado,
                        0 AS excepcion
                        FROM tarifarios_detalle AS A,
                        plan_tarifario AS B
                        WHERE B.plan_id = $plan
                        AND B.grupo_tarifario_id = '$grd'
                        AND B.subgrupo_tarifario_id    = '$sud'
                        AND B.grupo_tarifario_id = A.grupo_tarifario_id
                        AND B.subgrupo_tarifario_id    = A.subgrupo_tarifario_id
                        AND B.tarifario_id = A.tarifario_id
                        AND excepciones(B.plan_id, B.tarifario_id, A.cargo) = 0
                        $busqueda
                        $busqueda2
                        )
                        UNION
                        (
                        SELECT A.cargo,
                        A.descripcion,
                        A.precio,
                        A.nivel,
												A.tipo_unidad_id,
                        B.porcentaje,
                        B.por_cobertura,
                        B.sw_descuento,
                        B.sw_no_contratado,
                        1 AS excepcion
                        FROM tarifarios_detalle AS A,
                        excepciones AS B
                        WHERE B.plan_id = $plan
                        AND A.grupo_tarifario_id = '$grd'
                        AND A.subgrupo_tarifario_id    = '$sud'
                        AND B.tarifario_id = A.tarifario_id
                        AND B.cargo = A.cargo
                        $busqueda
                        $busqueda2
                    )
                    ) AS r;";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            list($this->conteo)=$resulta->fetchRow();
        }
        else
        {
            $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of'])
        {
            $Of='0';
        }
        else
        {
            $Of=$_REQUEST['Of'];
            if($_REQUEST['Of'] > $this->conteo)
            {
                $Of='0';
                $_REQUEST['Of']='0';
                $_REQUEST['paso']='1';
            }
        }
				//A.sw_uvrs SE ELIMINA DE LA TABLA tarifarios_detalle
				//EN LOS NUVOS CASOS DE LIQUIDACION
				//A.sw_uvrs,
        $query ="
                (
                SELECT A.cargo,
                A.descripcion,
                A.precio,
                A.nivel,
								A.tipo_unidad_id,
                B.porcentaje,
                B.por_cobertura,
                B.sw_descuento,
                '0' AS sw_no_contratado,
                0 AS excepcion
                FROM tarifarios_detalle AS A,
                plan_tarifario AS B
                WHERE B.plan_id = $plan
                AND B.grupo_tarifario_id = '$grd'
                AND B.subgrupo_tarifario_id    = '$sud'
                AND B.grupo_tarifario_id = A.grupo_tarifario_id
                AND B.subgrupo_tarifario_id    = A.subgrupo_tarifario_id
                AND B.tarifario_id = A.tarifario_id
                AND excepciones(B.plan_id, B.tarifario_id, A.cargo) = 0
                $busqueda
                $busqueda2
                )
                UNION
                (
                SELECT A.cargo,
                A.descripcion,
                A.precio,
                A.nivel,
								A.tipo_unidad_id,
                B.porcentaje,
                B.por_cobertura,
                B.sw_descuento,
                B.sw_no_contratado,
                1 AS excepcion
                FROM tarifarios_detalle AS A,
                excepciones AS B
                WHERE B.plan_id = $plan
                AND A.grupo_tarifario_id = '$grd'
                AND A.subgrupo_tarifario_id    = '$sud'
                AND B.tarifario_id = A.tarifario_id
                AND B.cargo = A.cargo
                $busqueda
                $busqueda2
                ORDER BY A.cargo
                )
                LIMIT ".$this->limit." OFFSET $Of;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function ValidarExceTariPlanContra()//Valida, y guarda o modifica los datos de las excepciones
    {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $ciclo=sizeof($_SESSION['ctrpl1']['cargotaric']);
        for($i=0;($i<$ciclo);$i++)
        {
            $g1=0;
            $g2=0;
						//MODIFICACIÓN PARA CALCULAR LOS PORCENTAJES CUANDO SE DIGITA UN VOLOR
						//Y NO DIRECTAMENTE EL PORCENTAJE
						if(is_numeric($_POST['porexctra'.$i])==1 AND $_POST['radioporexctra'.$i]==0)
						{
							if ($_POST['tipounidad'.$i]=='03')//SMMLV
								$_POST['preciocargo'.$i]=($_SESSION['ctrpl1']['cargotaric'][$i]['precio']*GetSalarioMinimo(date("Y")));//*(1+$_SESSION['ctrpl1']['cargotaric'][$i]['porcentaje']/100);
							else
							if ($_POST['tipounidad'.$i]=='02')//UVR
									$_POST['preciocargo'.$i]=$_SESSION['ctrpl1']['cargotaric'][$i]['precio']*$_SESSION['ctrpl1']['cargotaric']['uvr'];//*(1+($_SESSION['ctrpl1']['cargotaric'][$i]['porcentaje'])/100);;

								$this->CalcularPorcentaje($_POST['preciocargo'.$i],$_POST['porexctra'.$i]);//SI NO ES NI 03(SMMLV), 02(UVR) en tonces entra directamewnto 01->pesos
								$por1=doubleval($this->valor);
							if($por1 <= 999.9999 AND $por1 >= -999.9999)
							{
									$g1=1;
							}
							else
							{
									$por1=0.00;
							}
						}
						else
						//FIN MODIFICACIÓN
            if(is_numeric($_POST['porexctra'.$i])==1 AND $_POST['radioporexctra'.$i]==1)//if(is_numeric($_POST['porexctra'.$i])==1)
						{ 
							$por1=doubleval($_POST['porexctra'.$i]);
							if($por1 <= 999.9999 AND $por1 >= -999.9999)
							{
									$g1=1;
							}
							else
							{
									$por1=0.00;
							}
						}
            else
						{	
							//SI PORCENTAJE ES VACIO
							//$por1=0.00;
							$por1=$_SESSION['ctrpl1']['dattarctra']['porcentaje'];
            }
            if(is_numeric($_POST['cobexctra'.$i])==1)
            {
                $por2=doubleval($_POST['cobexctra'.$i]);
                if($por2 <= 100 AND $por2 >= 0)//999.9999
                {
                    $g2=1;
                }
                else
                {
                    $por2=0.00;
                }
            }
            else
            {
							//$por2=0.00;
							//SI COBERTURA ES VACIA
							$por2=$_SESSION['ctrpl1']['dattarctra']['por_cobertura'];
            }
            if(empty($_POST['desexctra'.$i]))
            {
                $_POST['desexctra'.$i]=0;
            }
            if(!($_POST['porexctra'.$i]==NULL AND $_POST['cobexctra'.$i]==NULL) AND $_POST['contratado'.$i]==NULL)
            {
								//VARIABLE TEMPORAL PARA NO DEJAR PERDER EL VALOR DE LA 
								//VARIABLE DE SESSION, EL CUAL SE PERDIA
								$valtmp=$_SESSION['ctrpl1']['cargotaric'][$i]['por_cobertura'];
               if($_SESSION['ctrpl1']['dattarctra']['porcentaje']<>$por1
                OR $_SESSION['ctrpl1']['dattarctra']['por_cobertura']<>$por2
								//OR $valtmp<>$por2
                OR $_SESSION['ctrpl1']['dattarctra']['sw_descuento']<>$_POST['desexctra'.$i])
                {
                    if($_SESSION['ctrpl1']['cargotaric'][$i]['excepcion']==1 AND ($g1==1 OR $g2==1)
                    AND ($_SESSION['ctrpl1']['cargotaric'][$i]['porcentaje']<>$por1
                    //OR $_SESSION['ctrpl1']['cargotaric'][$i]['por_cobertura']<>$por2
										OR $valtmp<>$por2
                    OR $_SESSION['ctrpl1']['cargotaric'][$i]['sw_descuento']<>$_POST['desexctra'.$i]))
                    {
                        $query ="DELETE FROM excepciones
                                WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                                AND tarifario_id='".$_SESSION['ctrpl1']['dattarctra']['tarifario_id']."'
                                AND cargo='".$_SESSION['ctrpl1']['cargotaric'][$i]['cargo']."';";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "ERROR AL DELETE excepciones";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollBackTrans();
                            //return false;
                        }
                    }
                    if(($g1==1 OR $g2==1)
                    AND ($_SESSION['ctrpl1']['cargotaric'][$i]['porcentaje']<>$por1
										//OR $_SESSION['ctrpl1']['dattarctra']['por_cobertura']<>$por2
                    OR $valtmp<>$por2
                    OR $_SESSION['ctrpl1']['cargotaric'][$i]['sw_descuento']<>$_POST['desexctra'.$i]))
                    {
                        $query ="INSERT INTO excepciones
                                (plan_id,
                                tarifario_id,
                                cargo,
                                porcentaje,
                                por_cobertura,
                                sw_descuento)
                                VALUES
                                (".$_SESSION['ctrpla']['planeleg'].",
                                '".$_SESSION['ctrpl1']['dattarctra']['tarifario_id']."',
                                '".$_SESSION['ctrpl1']['cargotaric'][$i]['cargo']."',
                                ".$por1.",
                                ".$por2.",
                                '".$_POST['desexctra'.$i]."');";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "ERROR AL INSERTAR excepciones";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollBackTrans();
                           // return false;
                        }
                    }
                }
            }
            else if($_POST['contratado'.$i]<>NULL)
            {
                if($_SESSION['ctrpl1']['cargotaric'][$i]['excepcion']==1)
                {
                    $query ="DELETE FROM excepciones
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                            AND tarifario_id='".$_SESSION['ctrpl1']['dattarctra']['tarifario_id']."'
                            AND cargo='".$_SESSION['ctrpl1']['cargotaric'][$i]['cargo']."';";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "ERROR DELETE (excepciones)";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                        //return false;
                    }
                }
                $query = "INSERT INTO excepciones
                        (plan_id,
                        tarifario_id,
                        cargo,
                        porcentaje,
                        por_cobertura,
                        sw_descuento,
                        sw_no_contratado)
                        VALUES
                        (".$_SESSION['ctrpla']['planeleg'].",
                        '".$_SESSION['ctrpl1']['dattarctra']['tarifario_id']."',
                        '".$_SESSION['ctrpl1']['cargotaric'][$i]['cargo']."',
                        0, 0, 0, 1);";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al insertar en (excepciones)";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                    return false;
                }
								$dbconn->CommitTrans();
                $query = "SELECT borrar_excepciones_no_contratados('".$_SESSION['ctrpla']['planeleg']."',
                '".$_SESSION['ctrpl1']['dattarctra']['tarifario_id']."','".$_SESSION['ctrpl1']['cargotaric'][$i]['cargo']."');";
                $resulta = $dbconn->Execute($query);
								$db=1;
            }
            else if(($_SESSION['ctrpl1']['cargotaric'][$i]['sw_no_contratado']==1
            AND $_POST['contratado'.$i]==NULL) OR ($_SESSION['ctrpl1']['cargotaric'][$i]['excepcion']==1
            AND $_POST['porexctra'.$i]==NULL AND $_POST['cobexctra'.$i]==NULL))
            {
                $query ="DELETE FROM excepciones
                        WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                        AND tarifario_id='".$_SESSION['ctrpl1']['dattarctra']['tarifario_id']."'
                        AND cargo='".$_SESSION['ctrpl1']['cargotaric'][$i]['cargo']."';";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "ERROR AL CARGAR LOS DATOS DE excepciones";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                    //return false;
                }
            }
            $_POST['porexctra'.$i]='';
            $_POST['cobexctra'.$i]='';
            $_POST['desexctra'.$i]='';
            $_POST['contratado'.$i]='';
        }
				if($db!=1)
        	$dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
        $this->uno=1;
        $this->TariExcePlanContra();
        return true;
    }

		//CALCULA EL PORCENTAJE CUANDO SE ENTRA UN VALOR PREDETERMINADO
		function CalcularPorcentaje($precio, $desc)
		{
			$this->valor=(($desc*100)/$precio)-100;
			return true;
		}

    function ValidarCopiarTarifarioPlanContra()
    {
        if($_POST['tarifario2']<>NULL AND $_POST['tarifario2']<>$_SESSION['ctrpla']['planeleg'])
        {
            $this->frmError["MensajeError"]='';
            list($dbconn) = GetDBconn();
            $dbconn->BeginTrans();
            if($_POST['copiartari']==1)
            {
                $query ="DELETE FROM plan_tarifario
                        WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                        AND grupo_tarifario_id<>'00';"; 
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(plan_tarifario) ".$dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                }
                $query ="INSERT INTO plan_tarifario
                        (
                            plan_id,
                            grupo_tarifario_id,
                            subgrupo_tarifario_id,
                            tarifario_id,
                            porcentaje,
                            por_cobertura,
                            sw_descuento
                        )
                        SELECT
                        ".$_SESSION['ctrpla']['planeleg'].",
                        grupo_tarifario_id,
                        subgrupo_tarifario_id,
                        tarifario_id,
                        porcentaje,
                        por_cobertura,
                        sw_descuento
                        FROM plan_tarifario
                        WHERE plan_id=".$_POST['tarifario2']."
                        AND tarifario_id<>'SYS'
                        AND grupo_tarifario_id<>'00';";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(plan_tarifario) ".$dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                }
                else
                {
                    $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
                }
            }
            if($_POST['copiarsema']==1 AND $_POST['copiartari']==1)
            {
                $query ="DELETE FROM planes_semanas_cotizadas
                        WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(planes_semanas_cotizadas) ".$dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                }
                $query ="INSERT INTO planes_semanas_cotizadas
                        (
                            plan_id,
                            grupo_tarifario_id,
                            subgrupo_tarifario_id,
                            semanas_cotizadas
                        )
                        SELECT
                        ".$_SESSION['ctrpla']['planeleg'].",
                        grupo_tarifario_id,
                        subgrupo_tarifario_id,
                        semanas_cotizadas
                        FROM planes_semanas_cotizadas
                        WHERE plan_id=".$_POST['tarifario2'].";";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS ".$dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                }
                else
                {
                    $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
                }
            }
						//ELIMINAR EXCEPCIONES DEL TARIFARIO DESTINO
            $query ="DELETE FROM excepciones
                    WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."";
            $dbconn->Execute($query);
						//FIN MIDIFICACIÓN
            if($_POST['copiartariex']==1 AND $_POST['copiartari']==1)
            {
                $query ="INSERT INTO excepciones
                        (
                            plan_id,
                            tarifario_id,
                            cargo,
                            porcentaje,
                            por_cobertura,
                            sw_descuento
                        )
                        SELECT
                        ".$_SESSION['ctrpla']['planeleg'].",
                        tarifario_id,
                        cargo,
                        porcentaje,
                        por_cobertura,
                        sw_descuento
                        FROM excepciones
                        WHERE plan_id=".$_POST['tarifario2'].";";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(excepciones) ".$dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                }
            }
            if($_POST['copiarsemaex']==1 AND $_POST['copiarsema']==1 AND $_POST['copiartari']==1)
            {
								//MODIFICACION PARA ELIMINAR LAS EXCEPCIONES
								//PARA QUE SE PUEDAN COPIAR
								$query ="DELETE FROM excepciones_semanas_cotizadas
												WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0)
								{
										$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(excepciones_semanas_cotizadas) ".$dbconn->ErrorMsg();
										$dbconn->RollBackTrans();
								}
								//FIN MODIFICACION PARA ELIMINAR LAS EXCEPCIONES
								//PARA QUE SE PUEDAN COPIAR
                $query ="INSERT INTO excepciones_semanas_cotizadas
                        (
                            plan_id,
                            tarifario_id,
                            cargo,
                            semanas_cotizadas
                        )
                        SELECT
                        ".$_SESSION['ctrpla']['planeleg'].",
                        tarifario_id,
                        cargo,
                        semanas_cotizadas
                        FROM excepciones_semanas_cotizadas
                        WHERE plan_id=".$_POST['tarifario2'].";";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(excepciones_semanas_cotizadas) ".$dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                }
            }
            if(($_POST['copiartari']==1 AND $_POST['copiarcopa']==1)
            OR $_POST['copiarauin']==1 OR $_POST['copiarauex']==1
            OR $_POST['copiarpaim']==1 OR $_POST['copiarpacd']==1
            OR $_POST['copiarinme']==1 OR $_POST['copiarinme2']==1)
            {
                $query ="DELETE FROM planes_servicios
                        WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(planes_servicios) ".$dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                }
                $query ="INSERT INTO planes_servicios
                        (
                            plan_id,
                            servicio
                        )
                        SELECT
                        ".$_SESSION['ctrpla']['planeleg'].",
                        servicio
                        FROM planes_servicios
                        WHERE plan_id=".$_POST['tarifario2'].";";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(planes_servicios) ".$dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                }
                if($_POST['copiarcopa']==1 AND $_POST['copiartari']==1)
                {
                    $query ="DELETE FROM planes_copagos
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(planes_copagos) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
                    $query ="INSERT INTO planes_copagos
                            (
                                plan_id,
                                grupo_tarifario_id,
                                subgrupo_tarifario_id,
                                servicio,
                                sw_copago,
                                sw_cuota_moderadora
                            )
                            SELECT
                            ".$_SESSION['ctrpla']['planeleg'].",
                            grupo_tarifario_id,
                            subgrupo_tarifario_id,
                            servicio,
                            sw_copago,
                            sw_cuota_moderadora
                            FROM planes_copagos
                            WHERE plan_id=".$_POST['tarifario2'].";"; 
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(planes_copagos) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
                    else
                    {
                        $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
                    }
                }
                if($_POST['copiarcopaex']==1 AND $_POST['copiarcopa']==1 AND $_POST['copiartari']==1)
                {
										//MODIFICACION PARA ELIMINAR LAS EXCEPCIONES
										//PARA QUE SE PUEDAN COPIAR
                    $query ="DELETE FROM excepciones_copagos
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(excepciones_copagos) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
										//FIN MODIFICACION PARA ELIMINAR LAS EXCEPCIONES
										//PARA QUE SE PUEDAN COPIAR

                    $query ="INSERT INTO excepciones_copagos
                            (
                                plan_id,
                                tarifario_id,
                                cargo,
                                servicio,
                                sw_copago,
                                sw_cuota_moderadora
                            )
                            SELECT
                            ".$_SESSION['ctrpla']['planeleg'].",
                            tarifario_id,
                            cargo,
                            servicio,
                            sw_copago,
                            sw_cuota_moderadora
                            FROM excepciones_copagos
                            WHERE plan_id=".$_POST['tarifario2'].";";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(excepciones_copagos) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
                }
                if($_POST['copiarauin']==1)
                {
                    $query ="DELETE FROM planes_autorizaciones_int
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(planes_autorizaciones_int) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
                    $query ="INSERT INTO planes_autorizaciones_int
                            (
                                plan_id,
                                grupo_tipo_cargo,
                                tipo_cargo,
                                servicio,
                                nivel
                            )
                            SELECT
                            ".$_SESSION['ctrpla']['planeleg'].",
                            grupo_tipo_cargo,
                            tipo_cargo,
                            servicio,
                            nivel
                            FROM planes_autorizaciones_int
                            WHERE plan_id=".$_POST['tarifario2'].";";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(planes_autorizaciones_int) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
                    else
                    {
                        $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
                    }
                }
                if($_POST['copiarauin']==1 AND $_POST['copiarauinex']==1)
                {
										//MODIFICACION PARA ELIMINAR LAS EXCEPCIONES
										//PARA QUE SE PUEDAN COPIAR
                    $query ="DELETE FROM excepciones_aut_int
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(excepciones_aut_int) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
										//FIN MODIFICACION PARA ELIMINAR LAS EXCEPCIONES
										//PARA QUE SE PUEDAN COPIAR

                    $query ="INSERT INTO excepciones_aut_int
                            (
                                plan_id,
                                --tarifario_id,
                                cargo,
                                servicio,
                                sw_autorizado,
                                cantidad,
                                valor_maximo,
                                periocidad_dias
                            )
                            SELECT
                            ".$_SESSION['ctrpla']['planeleg'].",
                            --tarifario_id,
                            cargo,
                            servicio,
                            sw_autorizado,
                            cantidad,
                            valor_maximo,
                            periocidad_dias
                            FROM excepciones_aut_int
                            WHERE plan_id=".$_POST['tarifario2'].";";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(excepciones_aut_int) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
                }
                if($_POST['copiarauex']==1)
                {
                    $query ="DELETE FROM planes_autorizaciones_ext
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(planes_autorizaciones_ext) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
                    $query ="INSERT INTO planes_autorizaciones_ext
                            (
                                plan_id,
                                grupo_tipo_cargo,
                                tipo_cargo,
                                servicio,
                                nivel
                            )
                            SELECT
                            ".$_SESSION['ctrpla']['planeleg'].",
                            grupo_tipo_cargo,
                            tipo_cargo,
                            servicio,
                            nivel
                            FROM planes_autorizaciones_ext
                            WHERE plan_id=".$_POST['tarifario2'].";";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(planes_autorizaciones_ext) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
                    else
                    {
                        $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
                    }
                }
                if($_POST['copiarauex']==1 AND $_POST['copiarauexex']==1)
                {
										//MODIFICACION PARA ELIMINAR LAS EXCEPCIONES
										//PARA QUE SE PUEDAN COPIAR
                    $query ="DELETE FROM excepciones_aut_ext
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(excepciones_aut_ext) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
										//FIN MODIFICACION PARA ELIMINAR LAS EXCEPCIONES
										//PARA QUE SE PUEDAN COPIAR
                    $query ="INSERT INTO excepciones_aut_ext
                            (
                                plan_id,
                                --tarifario_id,
                                cargo,
                                servicio,
                                sw_autorizado,
                                cantidad,
                                valor_maximo,
                                periocidad_dias
                            )
                            SELECT
                            ".$_SESSION['ctrpla']['planeleg'].",
                            --tarifario_id,
                            cargo,
                            servicio,
                            sw_autorizado,
                            cantidad,
                            valor_maximo,
                            periocidad_dias
                            FROM excepciones_aut_ext
                            WHERE plan_id=".$_POST['tarifario2'].";";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(excepciones_aut_ext) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
                }
                if($_POST['copiarpaim']==1 AND $_SESSION['ctrpla']['pimdeleg']==1)
                {
                    $query ="DELETE FROM planes_paragrafados_medicamentos
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(planes_paragrafados_medicamentos) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
                    $query ="INSERT INTO planes_paragrafados_medicamentos
                            (
                                plan_id,
                                servicio,
																departamento,
                                codigo_producto
                            )
                            SELECT
                            ".$_SESSION['ctrpla']['planeleg'].",
                            servicio,
														departamento,
                            codigo_producto
                            FROM planes_paragrafados_medicamentos
                            WHERE plan_id=".$_POST['tarifario2'].";";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(planes_paragrafados_medicamentos) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
                    else
                    {
                        $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
                    }
                }
                $query ="SELECT count(plan_id)
                        FROM plan_tarifario
                        WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
                $resulta2 = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $resulta2->fields[0]=1;
                }
                if($_POST['copiarpacd']==1 AND $_SESSION['ctrpla']['pcadeleg']==1 AND ($resulta2->fields[0]>1))
                {
                    $query ="DELETE FROM planes_paragrafados_cargos
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(planes_paragrafados_cargos) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
                    $query ="INSERT INTO planes_paragrafados_cargos
                            (
                                plan_id,
                                servicio,
                                tarifario_id,
                                cargo
                            )
                            SELECT
                            ".$_SESSION['ctrpla']['planeleg'].",
                            servicio,
                            tarifario_id,
                            cargo
                            FROM planes_paragrafados_cargos
                            WHERE plan_id=".$_POST['tarifario2'].";";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(planes_paragrafados_cargos) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
                    else
                    {
                        $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
                    }
                }
                if($_POST['copiarinm2']==1)
                {
                    $query ="DELETE FROM plan_tarifario_inv_autorizaciones
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                            AND empresa_id='".$_SESSION['contra']['empresa']."';";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(plan_tarifario_inv_autorizaciones) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
                    $query ="INSERT INTO plan_tarifario_inv_autorizaciones
                            (
                                plan_id,
                                empresa_id,
                                grupo_contratacion_id,
                                servicio,
                                cantidad_max,
                                valor_max_unidad,
                                valor_max_cuenta,
                                requiere_autorizacion_int,
                                requiere_autorizacion_ext,
                                semanas_cotizadas
                            )
                            SELECT
                            ".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['contra']['empresa']."',
                            grupo_contratacion_id,
                            servicio,
                            cantidad_max,
                            valor_max_unidad,
                            valor_max_cuenta,
                            requiere_autorizacion_int,
                            requiere_autorizacion_ext,
                            semanas_cotizadas
                            FROM plan_tarifario_inv_autorizaciones
                            WHERE plan_id=".$_POST['tarifario2'].";";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(plan_tarifario_inv_autorizaciones) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
                    else
                    {
                        $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
                    }
                }
                if($_POST['copiarinm2']==1 AND $_POST['copiarinmee2']==1)
                {
										//MODIFICACION PARA LA ELIMINACON DE LAS EXCEPCIONES
										//A INSERTAR
										$query ="DELETE FROM excepciones_inv_autorizaciones
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                            AND empresa_id='".$_SESSION['contra']['empresa']."';";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(excepciones_inv_autorizaciones) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
										//FIN MODIFICACION PARA LA ELIMINACON DE LAS EXCEPCIONES
										//A INSERTAR
                    $query ="INSERT INTO excepciones_inv_autorizaciones
                            (
                                plan_id,
                                empresa_id,
                                codigo_producto,
                                servicio,
                                cantidad_max,
                                valor_max_unidad,
                                valor_max_cuenta,
                                requiere_autorizacion_int,
                                requiere_autorizacion_ext,
                                semanas_cotizadas
                            )
                            SELECT
                            ".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['contra']['empresa']."',
                            codigo_producto,
                            servicio,
                            cantidad_max,
                            valor_max_unidad,
                            valor_max_cuenta,
                            requiere_autorizacion_int,
                            requiere_autorizacion_ext,
                            semanas_cotizadas
                            FROM excepciones_inv_autorizaciones
                            WHERE plan_id=".$_POST['tarifario2'].";";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(planes_paragrafados_cargos) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
                }
                if($_POST['copiarinme']==1)
                {
                    $query ="DELETE FROM plan_tarifario_inv_copagos
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                            AND empresa_id='".$_SESSION['contra']['empresa']."';";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(plan_tarifario_inv_copagos) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
                    $query ="INSERT INTO plan_tarifario_inv_copagos
                            (
                                plan_id,
                                empresa_id,
                                grupo_contratacion_id,
                                servicio,
                                porcentaje,
                                por_cobertura,
                                sw_descuento,
                                sw_copago,
                                sw_cuota_moderadora
                            )
                            SELECT
                            ".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['contra']['empresa']."',
                            grupo_contratacion_id,
                            servicio,
                            porcentaje,
                            por_cobertura,
                            sw_descuento,
                            sw_copago,
                            sw_cuota_moderadora
                            FROM plan_tarifario_inv_copagos
                            WHERE plan_id=".$_POST['tarifario2'].";";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(planes_paragrafados_cargos) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
                    else
                    {
                        $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
                    }
                }
                if($_POST['copiarinme']==1 AND $_POST['copiarinmeex']==1)
                {
										//MODIFICACION PARA LA ELIMINACON DE LAS EXCEPCIONES
										//A INSERTAR
                   $query ="DELETE FROM excepciones_inv_copagos
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                            AND empresa_id='".$_SESSION['contra']['empresa']."';";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(excepciones_inv_copagos) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
										//FIN MODIFICACION PARA LA ELIMINACON DE LAS EXCEPCIONES
										//A INSERTAR
                    $query ="INSERT INTO excepciones_inv_copagos
                            (
                                plan_id,
                                empresa_id,
                                codigo_producto,
                                servicio,
                                porcentaje,
                                por_cobertura,
                                sw_descuento,
                                sw_copago,
                                sw_cuota_moderadora
                            )
                            SELECT
                            ".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['contra']['empresa']."',
                            codigo_producto,
                            servicio,
                            porcentaje,
                            por_cobertura,
                            sw_descuento,
                            sw_copago,
                            sw_cuota_moderadora
                            FROM excepciones_inv_copagos
                            WHERE plan_id=".$_POST['tarifario2'].";";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(excepciones_inv_copagos) ".$dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                    }
                }
            }
            if($_POST['copiarincu']==1)
            {
                $query ="DELETE FROM planes_incumplimientos_citas
                        WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(planes_incumplimientos_citas) ".$dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                }
                $query ="INSERT INTO planes_incumplimientos_citas
                        (
                            plan_id,
                            cargo_cita,
                            valor
                        )
                        SELECT
                        ".$_SESSION['ctrpla']['planeleg'].",
                        cargo_cita,
                        valor
                        FROM planes_incumplimientos_citas
                        WHERE plan_id=".$_POST['tarifario2'].";";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS(planes_incumplimientos_citas) ".$dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                }
                else
                {
                    $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
                }
            }
            $dbconn->CommitTrans();
        }
        $_POST['copiartari']='';
        $_POST['copiarcopa']='';
        $_POST['copiarsema']='';
        $_POST['copiartariex']='';
        $_POST['copiarcopaex']='';
        $_POST['copiarsemaex']='';
        $_POST['copiarauin']='';
        $_POST['copiarauinex']='';
        $_POST['copiarauex']='';
        $_POST['copiarauexex']='';
        $_POST['copiarinme']='';
        $_POST['copiarinmeex']='';
        $_POST['copiarinm2']='';
        $_POST['copiarinmee2']='';
        $_POST['copiarpaim']='';
        $_POST['copiarpacd']='';
        $_POST['copiarincu']='';
        if($this->frmError["MensajeError"]==NULL)
        {
            $this->frmError["MensajeError"]="LAS OPCIONES PARA COPIAR NO SON CORRECTAS
            <BR>EL SISTEMA NO EFECTUÓ NINGÚN CAMBIO, POR FAVOR VERIFIQUE LOS DATOS";
        }
        $this->uno=1;
//				if ($_REQUEST['copagos'])
//					$this->CopagosPlanContra();
//				else
				$this->TarifarioPlanContra();
        return true;
    }

    function ContarDatosNivelContra()//Válida la contratación por niveles del tarifario, así como las excepciones
    {
        list($dbconn) = GetDBconn();
        for($i=1;$i<=$_POST['niveles'];$i++)
        {
            $consulta.=", (SELECT count(B.cargo)
                FROM tarifarios_detalle AS B
                WHERE A.tarifario_id=B.tarifario_id
                AND A.grupo_tarifario_id=B.grupo_tarifario_id
                AND A.subgrupo_tarifario_id=B.subgrupo_tarifario_id
                AND B.nivel='".$i."'
                ) AS nuncargos".$i."";
        }
        $query ="SELECT A.tarifario_id,
                A.grupo_tarifario_id,
                A.subgrupo_tarifario_id,
                A.porcentaje,
                A.por_cobertura
                $consulta
                FROM plan_tarifario AS A
                WHERE A.plan_id=".$_SESSION['ctrpla']['planeleg']."
                AND A.grupo_tarifario_id<>'00'
                ORDER BY A.grupo_tarifario_id;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        for($i=1;$i<=$_POST['niveles'];$i++)
        {
            $vector[$i]=0;
        }
        for($i=1;$i<$_POST['niveles'];$i++)
        {
            if($vector[$i]==0)
            {
                $vector[$i]=$i;
                if($vector[$i+1]==0)
                {
                    for($j=$i+1;$j<=$_POST['niveles'];$j++)
                    {
                        if(($_POST['porcnivect'.$i]==$_POST['porcnivect'.$j])
                        && ($_POST['cobenivect'.$i]==$_POST['cobenivect'.$j])
                        && ($_POST['descnivect'.$i]==$_POST['descnivect'.$j]))
                        {
                            $vector[$j]=$i;
                        }
                    }
                }
            }
        }
        if($vector[$i]==0)
        {
            $vector[$i]=$i;
        }
        $dbconn->BeginTrans();
        for($a=0;$a<sizeof($var);$a++)
        {
            $query ="DELETE FROM excepciones
                    WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                    AND tarifario_id='".$var[$a]['tarifario_id']."';";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollBackTrans();
                return false;
            }
        }
        for($a=0;$a<sizeof($var);$a++)
        {
            for($i=1;$i<=$_POST['niveles'];$i++)
            {
                for($j=1;$j<=$_POST['niveles'];$j++)
                {
                    if($vector[$i]==$j)
                    {
                        $sumas[$j]=$var[$a]['nuncargos'.$i]+$sumas[$j];
                    }
                }
            }
            $grupomayor=0;
            $indicmayor=0;
            for($i=1;$i<=$_POST['niveles'];$i++)
            {
                if($sumas[$i]>$grupomayor)
                {
                    $indicmayor=$i;
                    $grupomayor=$sumas[$i];
                }
            }
            $i=$indicmayor;
            $g1=0;
            $g2=0;
            if(is_numeric($_POST['porcnivect'.$i])==1)
            {
                $por1=doubleval($_POST['porcnivect'.$i]);
                if($por1 <= 999.9999)
                {
                    $g1=1;
                }
            }
            else
            {
                $por1=0.00;
            }
            if(is_numeric($_POST['cobenivect'.$i])==1)
            {
                $por2=doubleval($_POST['cobenivect'.$i]);
                if($por2 <= 100)//999.9999
                {
                    $g2=1;
                }
            }
            else
            {
                $por2=0.00;
            }
            if(empty($_POST['descnivect'.$i]))
            {
                $_POST['descnivect'.$i]=0;
            }
            if($g1==1 AND empty($_POST['cobenivect'.$i]))
            {
                $por2=0.00;
            }
            else if($g2==1 AND empty($_POST['porcnivect'.$i]))
            {
                $por1=0.00;
            }
            $query ="UPDATE plan_tarifario SET
                    porcentaje=".$por1.",
                    por_cobertura=".$por2.",
                    sw_descuento='".$_POST['descnivect'.$i]."'
                    WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                    AND tarifario_id='".$var[$a]['tarifario_id']."'
                    AND grupo_tarifario_id='".$var[$a]['grupo_tarifario_id']."'
                    AND subgrupo_tarifario_id='".$var[$a]['subgrupo_tarifario_id']."';";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollBackTrans();
                return false;
            }
            for($j=1;$j<=$_POST['niveles'];$j++)
            {
                if($vector[$j]<>$indicmayor)//!empty($sumas[])$vector[$j]<>$indicmayor$j<>$i
                {
                    $query ="SELECT cargo
                            FROM tarifarios_detalle
                            WHERE tarifario_id='".$var[$a]['tarifario_id']."'
                            AND grupo_tarifario_id='".$var[$a]['grupo_tarifario_id']."'
                            AND subgrupo_tarifario_id='".$var[$a]['subgrupo_tarifario_id']."'
                            AND nivel='".$j."';";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    $k=0;
                    while(!$resulta->EOF)
                    {
                        $cargos[$k]=$resulta->GetRowAssoc($ToUpper = false);
                        $resulta->MoveNext();
                        $k++;
                    }
                    $g1=0;
                    $g2=0;
                    if(is_numeric($_POST['porcnivect'.$j])==1)
                    {
                        $por1=doubleval($_POST['porcnivect'.$j]);
                        if($por1 <= 999.9999)
                        {
                            $g1=1;
                        }
                    }
                    else
                    {
                        $por1=0.00;
                    }
                    if(is_numeric($_POST['cobenivect'.$j])==1)
                    {
                        $por2=doubleval($_POST['cobenivect'.$j]);
                        if($por2 <= 100)//999.9999
                        {
                            $g2=1;
                        }
                    }
                    else
                    {
                        $por2=0.00;
                    }
                    if(empty($_POST['descnivect'.$j]))
                    {
                        $_POST['descnivect'.$j]=0;
                    }
                    if($g1==1 AND empty($_POST['cobenivect'.$j]))
                    {
                        $por2=0.00;
                    }
                    else if($g2==1 AND empty($_POST['porcnivect'.$j]))
                    {
                        $por1=0.00;
                    }
                    for($k=0;$k<sizeof($cargos);$k++)
                    {
                        $query ="INSERT INTO excepciones
                                (plan_id,
                                tarifario_id,
                                cargo,
                                porcentaje,
                                por_cobertura,
                                sw_descuento)
                                VALUES
                                (".$_SESSION['ctrpla']['planeleg'].",
                                '".$var[$a]['tarifario_id']."',
                                '".$cargos[$k]['cargo']."',
                                ".$por1.",
                                ".$por2.",
                                '".$_POST['descnivect'.$j]."');";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollBackTrans();
                            return false;
                        }
                    }
                }
                $cargos=NULL;
            }
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
        $this->dos=1;
        $this->TarifarioPlanContra();
        return true;
    }

    /********************FUNCIONES DE LA OPCIÓN COPAGOS********************/
    function BuscarCopagosPlanContra($plan)//Busca los grupos y subgrupos de un plan tarifario, si tiene copagos
    {
				//ORDER BY B.grupo_tarifario_id, C.subgrupo_tarifario_id;";        
				list($dbconn) = GetDBconn();
        $query ="SELECT DISTINCT C.subgrupo_tarifario_id,
                C.subgrupo_tarifario_descripcion,
                C.sw_copagos,
                B.grupo_tarifario_id,
                B.grupo_tarifario_descripcion,
                D.descripcion,
                E.sw_copago,
                E.sw_cuota_moderadora,
                E.servicio
                FROM plan_tarifario AS A
                LEFT JOIN planes_copagos AS E ON
                (
                    A.plan_id=E.plan_id
                    AND A.grupo_tarifario_id=E.grupo_tarifario_id
                    AND A.subgrupo_tarifario_id=E.subgrupo_tarifario_id
                ),
                grupos_tarifarios AS B,
                subgrupos_tarifarios AS C,
                tarifarios AS D
                WHERE A.plan_id=".$plan."
                AND A.grupo_tarifario_id=B.grupo_tarifario_id
                AND A.grupo_tarifario_id=C.grupo_tarifario_id
                AND A.subgrupo_tarifario_id=C.subgrupo_tarifario_id
                AND B.grupo_tarifario_id<>'00'
                AND A.tarifario_id=D.tarifario_id
								ORDER BY B.grupo_tarifario_descripcion,
								C.subgrupo_tarifario_descripcion,
								E.servicio;";
/*				$query ="SELECT DISTINCT C.subgrupo_tarifario_id, 
												C.subgrupo_tarifario_descripcion, 
												C.sw_copagos, 
												B.grupo_tarifario_id, 
												B.grupo_tarifario_descripcion, 
												D.descripcion, 
												E.sw_copago, 
												E.sw_cuota_moderadora, 
												E.servicio 
										FROM plan_tarifario AS A 
													LEFT JOIN planes_copagos AS E 
													ON ( A.plan_id=E.plan_id 
															AND A.grupo_tarifario_id=E.grupo_tarifario_id 
															AND A.subgrupo_tarifario_id=E.subgrupo_tarifario_id ), 
												grupos_tarifarios AS B, 
												subgrupos_tarifarios AS C, 
												tarifarios AS D, 
												planes_servicios AS F 
										WHERE A.plan_id=".$plan."
										AND A.plan_id=F.plan_id
										AND E.servicio=F.servicio 
										AND A.grupo_tarifario_id=B.grupo_tarifario_id 
										AND A.grupo_tarifario_id=C.grupo_tarifario_id 
										AND A.subgrupo_tarifario_id=C.subgrupo_tarifario_id 
										AND B.grupo_tarifario_id<>'00' 
										AND A.tarifario_id=D.tarifario_id 
										ORDER BY B.grupo_tarifario_descripcion, 
												C.subgrupo_tarifario_descripcion, 
												E.servicio;";*/
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function ValidarCopagosPlanContra()//Válida los datos a guardar
    {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        for($i=0;$i<sizeof($_SESSION['ctrpl1']['copagoctra']);$i=$k)
        {
            $k=$i;
            while($_SESSION['ctrpl1']['copagoctra'][$i]['grupo_tarifario_id']==$_SESSION['ctrpl1']['copagoctra'][$k]['grupo_tarifario_id'])
            {
                if($_SESSION['ctrpl1']['copagoctra'][$k]['sw_copagos']==0)
                {
                    if(($_SESSION['ctrpl1']['copagoctra'][$k]['servicio']<>NULL AND $_POST['cuotas'.$i.$k]==4)
                    OR ($_SESSION['ctrpl1']['copagoctra'][$k]['sw_copago']==1 AND $_POST['cuotas'.$i.$k]<>1)
                    OR ($_SESSION['ctrpl1']['copagoctra'][$k]['sw_cuota_moderadora']==1 AND $_POST['cuotas'.$i.$k]<>2)
                    OR ($_SESSION['ctrpl1']['copagoctra'][$k]['sw_cuota_moderadora']==0
                    AND $_SESSION['ctrpl1']['copagoctra'][$k]['sw_copago']==0 AND $_POST['cuotas'.$i.$k]<>3)
                    AND $_SESSION['ctrpl1']['copagoctra'][$k]['servicio']<>NULL AND $_POST['cuotas'.$i.$k]<>NULL)
                    {
                        $query ="DELETE FROM planes_copagos
                                WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                                AND grupo_tarifario_id='".$_SESSION['ctrpl1']['copagoctra'][$k]['grupo_tarifario_id']."'
                                AND subgrupo_tarifario_id='".$_SESSION['ctrpl1']['copagoctra'][$k]['subgrupo_tarifario_id']."'
                                AND servicio='".$_SESSION['ctrpl1']['copagoctra'][$k]['servicio']."';";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollBackTrans();
                            return false;
                        }
                    }
                    if($_POST['cuotas'.$i.$k]==1 AND $_SESSION['ctrpl1']['copagoctra'][$k]['sw_copago']<>1)
                    {
                        $query ="INSERT INTO planes_copagos
                                (plan_id,
                                grupo_tarifario_id,
                                subgrupo_tarifario_id,
                                servicio,
                                sw_copago,
                                sw_cuota_moderadora)
                                VALUES
                                (".$_SESSION['ctrpla']['planeleg'].",
                                '".$_SESSION['ctrpl1']['copagoctra'][$k]['grupo_tarifario_id']."',
                                '".$_SESSION['ctrpl1']['copagoctra'][$k]['subgrupo_tarifario_id']."',
                                '0', '1', '0');";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollBackTrans();
                            return false;
                        }
                    }
                    else if($_POST['cuotas'.$i.$k]==2 AND $_SESSION['ctrpl1']['copagoctra'][$k]['sw_cuota_moderadora']<>1)
                    {
                        $query ="INSERT INTO planes_copagos
                                (plan_id,
                                grupo_tarifario_id,
                                subgrupo_tarifario_id,
                                servicio,
                                sw_copago,
                                sw_cuota_moderadora)
                                VALUES
                                (".$_SESSION['ctrpla']['planeleg'].",
                                '".$_SESSION['ctrpl1']['copagoctra'][$k]['grupo_tarifario_id']."',
                                '".$_SESSION['ctrpl1']['copagoctra'][$k]['subgrupo_tarifario_id']."',
                                '0', '0', '1');";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollBackTrans();
                            return false;
                        }
                    }
                    else if($_POST['cuotas'.$i.$k]==3 AND
                    !($_SESSION['ctrpl1']['copagoctra'][$k]['sw_cuota_moderadora']<>NULL
                    AND $_SESSION['ctrpl1']['copagoctra'][$k]['sw_copago']<>NULL
                    AND $_SESSION['ctrpl1']['copagoctra'][$k]['sw_cuota_moderadora']<>1
                    AND $_SESSION['ctrpl1']['copagoctra'][$k]['sw_copago']<>1))
                    {
                        $query ="INSERT INTO planes_copagos
                                (plan_id,
                                grupo_tarifario_id,
                                subgrupo_tarifario_id,
                                servicio,
                                sw_copago,
                                sw_cuota_moderadora)
                                VALUES
                                (".$_SESSION['ctrpla']['planeleg'].",
                                '".$_SESSION['ctrpl1']['copagoctra'][$k]['grupo_tarifario_id']."',
                                '".$_SESSION['ctrpl1']['copagoctra'][$k]['subgrupo_tarifario_id']."',
                                '0', '0', '0');";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollBackTrans();
                            return false;
                        }
                    }
                    $k++;
                }
                else if($_SESSION['ctrpl1']['copagoctra'][$k]['sw_copagos']==1)
                {
                    $p=$k;
                    $t=0;
                    while($_SESSION['ctrpl1']['copagoctra'][$k]['subgrupo_tarifario_id']==
                    $_SESSION['ctrpl1']['copagoctra'][$p]['subgrupo_tarifario_id']
                    AND $_SESSION['ctrpl1']['copagoctra'][$k]['grupo_tarifario_id']==
                    $_SESSION['ctrpl1']['copagoctra'][$p]['grupo_tarifario_id'])
                    {
                        $t++;
                        $p++;
                    }
                    $p=$k;
                    for($s=0;$s<sizeof($_SESSION['ctrpl1']['copserctra']);$s++)
                    {
                        if($_SESSION['ctrpl1']['copagoctra'][$p]['servicio']==$_SESSION['ctrpl1']['copserctra'][$s]['servicio'])
                        {
                            if(($_SESSION['ctrpl1']['copagoctra'][$p]['sw_copago']==1 AND $_POST['cuotas'.$i.$k.$s]<>1) OR
                            ($_SESSION['ctrpl1']['copagoctra'][$p]['sw_cuota_moderadora']==1 AND $_POST['cuotas'.$i.$k.$s]<>2) OR
                            ($_SESSION['ctrpl1']['copagoctra'][$p]['sw_cuota_moderadora']==0
                            AND $_SESSION['ctrpl1']['copagoctra'][$p]['sw_copago']==0 AND $_POST['cuotas'.$i.$k.$s]<>3))
                            {
                                $query ="DELETE FROM planes_copagos
                                        WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                                        AND grupo_tarifario_id='".$_SESSION['ctrpl1']['copagoctra'][$p]['grupo_tarifario_id']."'
                                        AND subgrupo_tarifario_id='".$_SESSION['ctrpl1']['copagoctra'][$p]['subgrupo_tarifario_id']."'
                                        AND servicio='".$_SESSION['ctrpl1']['copagoctra'][$p]['servicio']."';";
                                $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0)
                                {
                                    $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollBackTrans();
                                    return false;
                                }
                                $_SESSION['ctrpl1']['copserctra'][$s]['grabar']=1;
                            }
                            else
                            {
                                $_SESSION['ctrpl1']['copserctra'][$s]['grabar']=0;
                            }
                            $p++;
                        }
                        else
                        {
                            $_SESSION['ctrpl1']['copserctra'][$s]['grabar']=1;
                        }
                    }
                    for($s=0;$s<sizeof($_SESSION['ctrpl1']['copserctra']);$s++)
                    {
                        if($_POST['cuotas'.$i.$k.$s]==1 AND $_SESSION['ctrpl1']['copserctra'][$s]['grabar']==1)
                        {

                            $query ="INSERT INTO planes_copagos
                                    (plan_id,
                                    grupo_tarifario_id,
                                    subgrupo_tarifario_id,
                                    servicio,
                                    sw_copago,
                                    sw_cuota_moderadora)
                                    VALUES
                                    (".$_SESSION['ctrpla']['planeleg'].",
                                    '".$_SESSION['ctrpl1']['copagoctra'][$k]['grupo_tarifario_id']."',
                                    '".$_SESSION['ctrpl1']['copagoctra'][$k]['subgrupo_tarifario_id']."',
                                    '".$_SESSION['ctrpl1']['copserctra'][$s]['servicio']."',
                                    '1', '0');";
                            $resulta = $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0)
                            {
                                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollBackTrans();
                                return false;
                            }
                        }
                        else if($_POST['cuotas'.$i.$k.$s]==2 AND $_SESSION['ctrpl1']['copserctra'][$s]['grabar']==1)
                        {
                            $query ="INSERT INTO planes_copagos
                                    (plan_id,
                                    grupo_tarifario_id,
                                    subgrupo_tarifario_id,
                                    servicio,
                                    sw_copago,
                                    sw_cuota_moderadora)
                                    VALUES
                                    (".$_SESSION['ctrpla']['planeleg'].",
                                    '".$_SESSION['ctrpl1']['copagoctra'][$k]['grupo_tarifario_id']."',
                                    '".$_SESSION['ctrpl1']['copagoctra'][$k]['subgrupo_tarifario_id']."',
                                    '".$_SESSION['ctrpl1']['copserctra'][$s]['servicio']."',
                                    '0', '1');";
                            $resulta = $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0)
                            {
                                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollBackTrans();
                                return false;
                            }
                        }
                        else if($_POST['cuotas'.$i.$k.$s]==3 AND $_SESSION['ctrpl1']['copserctra'][$s]['grabar']==1)
                        {
                            $query ="INSERT INTO planes_copagos
                                    (plan_id,
                                    grupo_tarifario_id,
                                    subgrupo_tarifario_id,
                                    servicio,
                                    sw_copago,
                                    sw_cuota_moderadora)
                                    VALUES
                                    (".$_SESSION['ctrpla']['planeleg'].",
                                    '".$_SESSION['ctrpl1']['copagoctra'][$k]['grupo_tarifario_id']."',
                                    '".$_SESSION['ctrpl1']['copagoctra'][$k]['subgrupo_tarifario_id']."',
                                    '".$_SESSION['ctrpl1']['copserctra'][$s]['servicio']."',
                                    '0', '0');";
                            $resulta = $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0)
                            {
                                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollBackTrans();
                                return false;
                            }
                        }
                    }
                    $k=$k+$t;
                }
            }
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
        $this->uno=1;
        $this->CopagosPlanContra();
        return true;
    }

    function BuscarCarCopPlanContra($plan,$grd,$sud,$servicio)//Busca los cargos de los copagos
    {
        list($dbconn) = GetDBconn();
        if($_REQUEST['codigoctra'])
        {
            $codigo=$_REQUEST['codigoctra'];
            $busqueda="AND A.cargo LIKE '$codigo%'";
        }
        else
        {
            $busqueda='';
        }
        if($_REQUEST['descrictra'])
        {
            $codigo=STRTOUPPER($_REQUEST['descrictra']);
            $busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
        }
        else
        {
            $busqueda2='';
        }
        if(empty($_REQUEST['conteo']))
        {
            $query ="SELECT count(*) FROM (
                    (
                        SELECT A.cargo,
                        A.descripcion,
                        A.tarifario_id,
                        C.sw_copago,
                        C.sw_cuota_moderadora,
                        0 AS excepcion
                        FROM tarifarios_detalle AS A,
                        plan_tarifario AS B
                        LEFT JOIN planes_copagos AS C ON
                        (B.plan_id=C.plan_id
                        AND B.grupo_tarifario_id=C.grupo_tarifario_id
                        AND B.subgrupo_tarifario_id=C.subgrupo_tarifario_id
                        AND C.servicio = '".$servicio."')
                        WHERE B.plan_id = ".$plan."
                        AND B.grupo_tarifario_id = '".$grd."'
                        AND B.subgrupo_tarifario_id = '".$sud."'
                        AND B.grupo_tarifario_id = A.grupo_tarifario_id
                        AND B.subgrupo_tarifario_id = A.subgrupo_tarifario_id
                        AND B.tarifario_id = A.tarifario_id
                        AND excepciones_copago(B.plan_id, B.tarifario_id, A.cargo, $servicio) = 0
                        AND excepciones_cargos_no_contratados(B.plan_id, B.tarifario_id, A.cargo) = 0
                        $busqueda
                        $busqueda2
                        )
                        UNION
                        (
                        SELECT A.cargo,
                        A.descripcion,
                        A.tarifario_id,
                        C.sw_copago,
                        C.sw_cuota_moderadora,
                        1 AS excepcion
                        FROM tarifarios_detalle AS A,
                        excepciones_copagos AS C
                        WHERE C.plan_id = ".$plan."
                        AND A.grupo_tarifario_id = '".$grd."'
                        AND A.subgrupo_tarifario_id = '".$sud."'
                        AND A.tarifario_id = C.tarifario_id
                        AND A.cargo=C.cargo
                        AND C.servicio = '".$servicio."'
                        $busqueda
                        $busqueda2
                    )
                    )
                    AS r;";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            list($this->conteo)=$resulta->fetchRow();
        }
        else
        {
            $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of'])
        {
            $Of='0';
        }
        else
        {
            $Of=$_REQUEST['Of'];
            if($_REQUEST['Of'] > $this->conteo)
            {
                $Of='0';
                $_REQUEST['Of']='0';
                $_REQUEST['paso']='1';
            }
        }
        $query ="
                (
                SELECT A.cargo,
                A.descripcion,
                A.tarifario_id,
                C.sw_copago,
                C.sw_cuota_moderadora,
                0 AS excepcion
                FROM tarifarios_detalle AS A,
                plan_tarifario AS B
                LEFT JOIN planes_copagos AS C ON
                (B.plan_id=C.plan_id
                AND B.grupo_tarifario_id=C.grupo_tarifario_id
                AND B.subgrupo_tarifario_id=C.subgrupo_tarifario_id
                AND C.servicio = '".$servicio."')
                WHERE B.plan_id = ".$plan."
                AND B.grupo_tarifario_id = '".$grd."'
                AND B.subgrupo_tarifario_id = '".$sud."'
                AND B.grupo_tarifario_id = A.grupo_tarifario_id
                AND B.subgrupo_tarifario_id = A.subgrupo_tarifario_id
                AND B.tarifario_id = A.tarifario_id
                AND excepciones_copago(B.plan_id, B.tarifario_id, A.cargo, $servicio) = 0
                AND excepciones_cargos_no_contratados(B.plan_id, B.tarifario_id, A.cargo) = 0
                $busqueda
                $busqueda2
                )
                UNION
                (
                SELECT A.cargo,
                A.descripcion,
                A.tarifario_id,
                C.sw_copago,
                C.sw_cuota_moderadora,
                1 AS excepcion
                FROM tarifarios_detalle AS A,
                excepciones_copagos AS C
                WHERE C.plan_id = ".$plan."
                AND A.grupo_tarifario_id = '".$grd."'
                AND A.subgrupo_tarifario_id = '".$sud."'
                AND A.tarifario_id = C.tarifario_id
                AND A.cargo=C.cargo
                AND C.servicio = '".$servicio."'
                $busqueda
                $busqueda2
                ORDER BY A.cargo
                )
                LIMIT ".$this->limit." OFFSET $Of;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function ValidarExceCopaPlanContra()
    {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $ciclo=sizeof($_SESSION['ctrpl1']['cargocopac']);
        for($i=0;($i<$ciclo);$i++)
        {
            if($_SESSION['ctrpl1']['cargocopac'][$i]['excepcion']==1)
            {
                $query ="DELETE FROM excepciones_copagos
                        WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                        AND tarifario_id='".$_SESSION['ctrpl1']['cargocopac'][$i]['tarifario_id']."'
                        AND cargo='".$_SESSION['ctrpl1']['cargocopac'][$i]['cargo']."'
                        AND servicio='".$_SESSION['ctrpl1']['datcopctra']['servicio']."';";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                    return false;
                }
            }
            if($_POST['cuotas'.$i]==1)
            {
                if(!$_SESSION['ctrpl1']['datcopctra']['sw_copago']==1)
                {
                    $query ="INSERT INTO excepciones_copagos
                            (plan_id,
                            tarifario_id,
                            cargo,
                            servicio,
                            sw_copago,
                            sw_cuota_moderadora)
                            VALUES
                            (".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['ctrpl1']['cargocopac'][$i]['tarifario_id']."',
                            '".$_SESSION['ctrpl1']['cargocopac'][$i]['cargo']."',
                            '".$_SESSION['ctrpl1']['datcopctra']['servicio']."',
                            '1', '0');";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                        return false;
                    }
                }
            }
            else if($_POST['cuotas'.$i]==2)
            {
                if(!$_SESSION['ctrpl1']['datcopctra']['sw_cuota_moderadora']==1)
                {
                    $query ="INSERT INTO excepciones_copagos
                            (plan_id,
                            tarifario_id,
                            cargo,
                            servicio,
                            sw_copago,
                            sw_cuota_moderadora)
                            VALUES
                            (".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['ctrpl1']['cargocopac'][$i]['tarifario_id']."',
                            '".$_SESSION['ctrpl1']['cargocopac'][$i]['cargo']."',
                            '".$_SESSION['ctrpl1']['datcopctra']['servicio']."',
                            '0', '1');";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                        return false;
                    }
                }
            }
            else if($_POST['cuotas'.$i]==3)
            {
                if(!($_SESSION['ctrpl1']['datcopctra']['sw_copago']==0
                AND $_SESSION['ctrpl1']['datcopctra']['sw_cuota_moderadora']==0))
                {
                    $query ="INSERT INTO excepciones_copagos
                            (plan_id,
                            tarifario_id,
                            cargo,
                            servicio,
                            sw_copago,
                            sw_cuota_moderadora)
                            VALUES
                            (".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['ctrpl1']['cargocopac'][$i]['tarifario_id']."',
                            '".$_SESSION['ctrpl1']['cargocopac'][$i]['cargo']."',
                            '".$_SESSION['ctrpl1']['datcopctra']['servicio']."',
                            '0', '0');";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                        return false;
                    }
                }
            }
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
        $this->uno=1;
        $this->CopagosExcePlanContra();
        return true;
    }

    /********************FUNCIONES DE LA OPCIÓN SEMANAS DE COTIZACIÓN********************/
    function BuscarSemanasPlanContra($plan)//Busca los grupos y subgrupos de un plan tarifario, si tiene copagos
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT DISTINCT C.subgrupo_tarifario_id,
                C.subgrupo_tarifario_descripcion,
                B.grupo_tarifario_id,
                B.grupo_tarifario_descripcion,
                D.descripcion,
                E.semanas_cotizadas
                FROM plan_tarifario AS A
                LEFT JOIN planes_semanas_cotizadas AS E ON
                (
                    A.plan_id=E.plan_id
                    AND A.grupo_tarifario_id=E.grupo_tarifario_id
                    AND A.subgrupo_tarifario_id=E.subgrupo_tarifario_id
                ),
                grupos_tarifarios AS B,
                subgrupos_tarifarios AS C,
                tarifarios AS D
                WHERE A.plan_id=".$plan."
                AND A.grupo_tarifario_id=B.grupo_tarifario_id
                AND A.grupo_tarifario_id=C.grupo_tarifario_id
                AND A.subgrupo_tarifario_id=C.subgrupo_tarifario_id
                AND B.grupo_tarifario_id<>'00'
                AND A.tarifario_id=D.tarifario_id
                ORDER BY B.grupo_tarifario_descripcion,
                C.subgrupo_tarifario_descripcion;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function ValidarSemanasPlanContra()//Válida los datos a guardar
    {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        for($i=0;$i<sizeof($_SESSION['ctrpl1']['semanactra']);$i++)
        {
            if(is_numeric($_POST['semana'.$i])==1)
            {
                if($_POST['semana'.$i]>32000)
                {
                    $_POST['semana'.$i]=0;
                }
                if($_SESSION['ctrpl1']['semanactra'][$i]['semanas_cotizadas']<>NULL
                AND $_POST['semana'.$i]<>$_SESSION['ctrpl1']['semanactra'][$i]['semanas_cotizadas'])
                {
                    $query ="DELETE FROM planes_semanas_cotizadas
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                            AND grupo_tarifario_id='".$_SESSION['ctrpl1']['semanactra'][$i]['grupo_tarifario_id']."'
                            AND subgrupo_tarifario_id='".$_SESSION['ctrpl1']['semanactra'][$i]['subgrupo_tarifario_id']."';";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                        return false;
                    }
                }
                if($_POST['semana'.$i]<>$_SESSION['ctrpl1']['semanactra'][$i]['semanas_cotizadas'])
                {
                    $query ="INSERT INTO planes_semanas_cotizadas
                            (plan_id,
                            grupo_tarifario_id,
                            subgrupo_tarifario_id,
                            semanas_cotizadas)
                            VALUES
                            (".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['ctrpl1']['semanactra'][$i]['grupo_tarifario_id']."',
                            '".$_SESSION['ctrpl1']['semanactra'][$i]['subgrupo_tarifario_id']."',
                            ".$_POST['semana'.$i].");";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                        return false;
                    }
                }
            }
            else
            {
                if($_POST['semana'.$i]==NULL AND $_SESSION['ctrpl1']['semanactra'][$i]['semanas_cotizadas']<>NULL)
                {
                    $query ="DELETE FROM planes_semanas_cotizadas
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                            AND grupo_tarifario_id='".$_SESSION['ctrpl1']['semanactra'][$i]['grupo_tarifario_id']."'
                            AND subgrupo_tarifario_id='".$_SESSION['ctrpl1']['semanactra'][$i]['subgrupo_tarifario_id']."';";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                        return false;
                    }
                }
            }
            $_POST['semana'.$i]='';
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
        $this->uno=1;
        $this->SemanasPlanContra();
        return true;
    }

    function BuscarCarSemPlanContra($plan,$grd,$sud)//Busca los cargos de los copagos
    {
        list($dbconn) = GetDBconn();
        if($_REQUEST['codigoctra'])
        {
            $codigo=$_REQUEST['codigoctra'];
            $busqueda="AND A.cargo LIKE '$codigo%'";
        }
        else
        {
            $busqueda='';
        }
        if($_REQUEST['descrictra'])
        {
            $codigo=STRTOUPPER($_REQUEST['descrictra']);
            $busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
        }
        else
        {
            $busqueda2='';
        }
        if(empty($_REQUEST['conteo']))
        {
            $query ="SELECT count(*) FROM (
                    (
                        SELECT A.cargo,
                        A.descripcion,
                        A.tarifario_id,
                        C.semanas_cotizadas,
                        0 AS excepcion
                        FROM tarifarios_detalle AS A,
                        plan_tarifario AS B
                        LEFT JOIN planes_semanas_cotizadas AS C ON
                        (B.plan_id=C.plan_id
                        AND B.grupo_tarifario_id=C.grupo_tarifario_id
                        AND B.subgrupo_tarifario_id=C.subgrupo_tarifario_id)
                        WHERE B.plan_id = ".$plan."
                        AND B.grupo_tarifario_id = '".$grd."'
                        AND B.subgrupo_tarifario_id = '".$sud."'
                        AND B.grupo_tarifario_id = A.grupo_tarifario_id
                        AND B.subgrupo_tarifario_id = A.subgrupo_tarifario_id
                        AND B.tarifario_id = A.tarifario_id
                        AND excepciones_semanas(B.plan_id, A.tarifario_id, A.cargo) = 0
                        AND excepciones_cargos_no_contratados(B.plan_id, A.tarifario_id, A.cargo) = 0
                        $busqueda
                        $busqueda2
                        )
                        UNION
                        (
                        SELECT A.cargo,
                        A.descripcion,
                        A.tarifario_id,
                        C.semanas_cotizadas,
                        1 AS excepcion
                        FROM tarifarios_detalle AS A,
                        excepciones_semanas_cotizadas AS C
                        WHERE C.plan_id = ".$plan."
                        AND A.grupo_tarifario_id = '".$grd."'
                        AND A.subgrupo_tarifario_id = '".$sud."'
                        AND A.tarifario_id = C.tarifario_id
                        AND A.cargo=C.cargo
                        $busqueda
                        $busqueda2
                    )
                    )
                    AS r;";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            list($this->conteo)=$resulta->fetchRow();
        }
        else
        {
            $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of'])
        {
            $Of='0';
        }
        else
        {
            $Of=$_REQUEST['Of'];
            if($_REQUEST['Of'] > $this->conteo)
            {
                $Of='0';
                $_REQUEST['Of']='0';
                $_REQUEST['paso']='1';
            }
        }
        $query ="
                (
                SELECT A.cargo,
                A.descripcion,
                A.tarifario_id,
                C.semanas_cotizadas,
                0 AS excepcion
                FROM tarifarios_detalle AS A,
                plan_tarifario AS B
                LEFT JOIN planes_semanas_cotizadas AS C ON
                (B.plan_id=C.plan_id
                AND B.grupo_tarifario_id=C.grupo_tarifario_id
                AND B.subgrupo_tarifario_id=C.subgrupo_tarifario_id)
                WHERE B.plan_id = ".$plan."
                AND B.grupo_tarifario_id = '".$grd."'
                AND B.subgrupo_tarifario_id = '".$sud."'
                AND B.grupo_tarifario_id = A.grupo_tarifario_id
                AND B.subgrupo_tarifario_id = A.subgrupo_tarifario_id
                AND B.tarifario_id = A.tarifario_id
                AND excepciones_semanas(B.plan_id, A.tarifario_id, A.cargo) = 0
                AND excepciones_cargos_no_contratados(B.plan_id, A.tarifario_id, A.cargo) = 0
                $busqueda
                $busqueda2
                )
                UNION
                (
                SELECT A.cargo,
                A.descripcion,
                A.tarifario_id,
                C.semanas_cotizadas,
                1 AS excepcion
                FROM tarifarios_detalle AS A,
                excepciones_semanas_cotizadas AS C
                WHERE C.plan_id = ".$plan."
                AND A.grupo_tarifario_id = '".$grd."'
                AND A.subgrupo_tarifario_id = '".$sud."'
                AND A.tarifario_id = C.tarifario_id
                AND A.cargo=C.cargo
                $busqueda
                $busqueda2
                ORDER BY A.cargo
                )
                LIMIT ".$this->limit." OFFSET $Of;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function ValidarExceSemaPlanContra()//Válida que las excepciones sean diferentes a la del grupo
    {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $ciclo=sizeof($_SESSION['ctrpl1']['cargosemac']);
        for($i=0;($i<$ciclo);$i++)
        {
            if($_SESSION['ctrpl1']['cargosemac'][$i]['excepcion']==1)
            {
                $query ="DELETE FROM excepciones_semanas_cotizadas
                        WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                        AND tarifario_id='".$_SESSION['ctrpl1']['cargosemac'][$i]['tarifario_id']."'
                        AND cargo='".$_SESSION['ctrpl1']['cargosemac'][$i]['cargo']."';";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                    return false;
                }
            }
            if(is_numeric($_POST['semanaex'.$i])==1)
            {
                if($_POST['semanaex'.$i]>32000)
                {
                    $_POST['semanaex'.$i]=0;
                }
                if($_SESSION['ctrpl1']['datsemctra']['semanas_cotizadas']<>$_POST['semanaex'.$i])
                {
                    $query ="INSERT INTO excepciones_semanas_cotizadas
                            (plan_id,
                            tarifario_id,
                            cargo,
                            semanas_cotizadas)
                            VALUES
                            (".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['ctrpl1']['cargosemac'][$i]['tarifario_id']."',
                            '".$_SESSION['ctrpl1']['cargosemac'][$i]['cargo']."',
                            ".$_POST['semanaex'.$i].");";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                        return false;
                    }
                }
            }
            $_POST['semanaex'.$i]='';
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
        $this->uno=1;
        $this->SemanasExcePlanContra();
        return true;
    }

    /********************FUNCIONES DE LA OPCIÓN AUTORIZACIÓN********************/
    function BuscarGruposAuInPlanContra($plan,$servicio)//
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT DISTINCT A.descripcion AS des1,
                B.descripcion AS des2,
                A.grupo_tipo_cargo,
                B.tipo_cargo,
                C.servicio,
                C.nivel
                FROM cups AS D,
                grupos_tipos_cargo AS A,
                tipos_cargos AS B
                LEFT JOIN planes_autorizaciones_int AS C ON
                (
                C.plan_id=".$plan."
                AND B.grupo_tipo_cargo=C.grupo_tipo_cargo
                AND B.tipo_cargo=C.tipo_cargo
                AND C.servicio='".$servicio."'
                )
                WHERE A.grupo_tipo_cargo=B.grupo_tipo_cargo
                AND B.grupo_tipo_cargo=D.grupo_tipo_cargo
                AND B.tipo_cargo=D.tipo_cargo
                AND A.grupo_tipo_cargo<>'SYS'
                ORDER BY des1, des2,
                C.servicio, C.nivel;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function ValidarAutoIntePlanContra()//
    {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        for($i=0;$i<sizeof($_SESSION['ctrpl1']['gruautoinc']);)
        {
            $k=$i;
            $l=0;
            while($_SESSION['ctrpl1']['gruautoinc'][$i]['grupo_tipo_cargo']==$_SESSION['ctrpl1']['gruautoinc'][$k]['grupo_tipo_cargo']
            AND $_SESSION['ctrpl1']['gruautoinc'][$i]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoinc'][$k]['tipo_cargo'])
            {
                $k++;
                $l++;
            }
            $k=$i;
            for($m=0;$m<sizeof($_SESSION['ctrpl1']['nivautoinc']);$m++)
            {
                $b=0;
                if($_SESSION['ctrpl1']['gruautoinc'][$k]['nivel']==$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel'] AND $_POST['nivauinc'.$i.$m]==NULL)
                {
                    $query ="DELETE FROM planes_autorizaciones_int
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                            AND grupo_tipo_cargo='".$_SESSION['ctrpl1']['gruautoinc'][$k]['grupo_tipo_cargo']."'
                            AND tipo_cargo='".$_SESSION['ctrpl1']['gruautoinc'][$k]['tipo_cargo']."'
                            AND servicio='".$_SESSION['ctrpl1']['serautintc']."'
                            AND nivel='".$_SESSION['ctrpl1']['gruautoinc'][$k]['nivel']."';";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $dbconn->RollBackTrans();
                    }
                    $b=1;
                    $k++;
                }
                else if($_SESSION['ctrpl1']['gruautoinc'][$k]['nivel']<>$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel'] AND $_POST['nivauinc'.$i.$m]<>NULL)
                {
                    $b=1;
                }
                if($_SESSION['ctrpl1']['gruautoinc'][$k]['nivel']==$_POST['nivauinc'.$i.$m] AND $_POST['nivauinc'.$i.$m]<>NULL)
                {
                    $b=0;
                    $k++;
                }
                if($b==1 AND $_POST['nivauinc'.$i.$m]<>NULL)
                {
                    $query ="INSERT INTO planes_autorizaciones_int
                            (plan_id,
                            grupo_tipo_cargo,
                            tipo_cargo,
                            servicio,
                            nivel)
                            VALUES
                            (".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['ctrpl1']['gruautoinc'][$i]['grupo_tipo_cargo']."',
                            '".$_SESSION['ctrpl1']['gruautoinc'][$i]['tipo_cargo']."',
                            '".$_SESSION['ctrpl1']['serautintc']."',
                            '".$_POST['nivauinc'.$i.$m]."');";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $dbconn->RollBackTrans();
                    }
                }
            }
            $i=$i+$l;
        }
        $dbconn->CommitTrans();
        $grupos=$this->BuscarGruposAuInPlanContra($_SESSION['ctrpla']['planeleg'],$_SESSION['ctrpl1']['serautintc']);
        for($i=0;$i<sizeof($grupos);$i++)
        {
            if($grupos[$i]['servicio']==NULL)
            {
                $query ="SELECT borrar_excepciones_auto_int2
                        (".$_SESSION['ctrpla']['planeleg'].",
                        '".$_SESSION['ctrpl1']['serautintc']."',
                        '".$grupos[$i]['grupo_tipo_cargo']."',
                        '".$grupos[$i]['tipo_cargo']."');";
                $dbconn->Execute($query);
            }
        }
        $this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
        $this->uno=1;
        $this->AutoIntePlanContra();
        return true;
    }

    function BuscarAuInPlanContra($plan,$grucar,$tipcar,$servicio)//Busca las autorizaciones del plan Auditoria Interna
    {
        list($dbconn) = GetDBconn();
        if($_REQUEST['codigoctra'])
        {
            $codigo=$_REQUEST['codigoctra'];
            $busqueda="AND A.cargo LIKE '$codigo%'";
        }
        else
        {
            $busqueda='';
        }
        if($_REQUEST['descrictra'])
        {
            $codigo=STRTOUPPER($_REQUEST['descrictra']);
            $busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
        }
        else
        {
            $busqueda2='';
        }
        if(empty($_REQUEST['conteo']))
        {
            $query ="SELECT count(*) FROM
                    (
                    SELECT A.cargo,
                    A.descripcion,
                    A.nivel,
                    excepciones_auto_int
                    (".$plan.", A.cargo, ".$servicio.")
                    AS excepciones
                    FROM cups AS A,
                    (
                        SELECT DISTINCT B.grupo_tipo_cargo,
                        B.tipo_cargo
                        FROM plan_tarifario AS A,
                        cups AS B
                        WHERE A.plan_id=".$plan."
                        AND A.grupo_tarifario_id=B.grupo_tarifario_id
                        AND A.subgrupo_tarifario_id=B.subgrupo_tarifario_id
                    ) AS B
                    WHERE A.grupo_tipo_cargo = '".$grucar."'
                    AND A.tipo_cargo = '".$tipcar."'
                    AND A.grupo_tipo_cargo=B.grupo_tipo_cargo
                    AND A.tipo_cargo=B.tipo_cargo
                    $busqueda
                    $busqueda2
                    )
                    AS r;";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            list($this->conteo)=$resulta->fetchRow();
        }
        else
        {
            $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of'])
        {
            $Of='0';
        }
        else
        {
            $Of=$_REQUEST['Of'];
            if($_REQUEST['Of'] > $this->conteo)
            {
                $Of='0';
                $_REQUEST['Of']='0';
                $_REQUEST['paso']='1';
            }
        }
        $query ="
                (
                SELECT A.cargo,
                A.descripcion,
                A.nivel,
                excepciones_auto_int
                (".$plan.", A.cargo, ".$servicio.")
                AS excepciones
                FROM cups AS A,
                (
                    SELECT DISTINCT B.grupo_tipo_cargo,
                    B.tipo_cargo
                    FROM plan_tarifario AS A,
                    cups AS B
                    WHERE A.plan_id=".$plan."
                    AND A.grupo_tarifario_id=B.grupo_tarifario_id
                    AND A.subgrupo_tarifario_id=B.subgrupo_tarifario_id
                ) AS B
                WHERE A.grupo_tipo_cargo = '".$grucar."'
                AND A.tipo_cargo = '".$tipcar."'
                AND A.grupo_tipo_cargo=B.grupo_tipo_cargo
                AND A.tipo_cargo=B.tipo_cargo
                $busqueda
                $busqueda2
                ORDER BY A.cargo
                )
                LIMIT ".$this->limit." OFFSET $Of;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarAuInExPlanContra($plan,$grucar,$tipcar,$servicio)//Busca las autorizaciones del plan
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT A.cargo,
                A.servicio,
                A.sw_autorizado,
                A.cantidad,
                A.valor_maximo,
                A.periocidad_dias,
                B.nivel
                FROM excepciones_aut_int AS A,
                cups AS B
                WHERE A.plan_id = ".$plan."
                AND A.servicio = '".$servicio."'
                AND B.grupo_tipo_cargo = '".$grucar."'
                AND B.tipo_cargo = '".$tipcar."'
                AND A.cargo=B.cargo
                ORDER BY A.cargo;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var[$resulta->fields[0]]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function ValidarAuInCargExPlanContra()//Valida, y guarda o modifica las excepciones del plan
    {
        list($dbconn) = GetDBconn();
        $i=$_REQUEST['ictra'];
        if($_REQUEST['cremod']==1)//INGRESA
        {
            $n=$_SESSION['ctrpl1']['tipocauinc'];
            for($m=0;$m<sizeof($_SESSION['ctrpl1']['nivautoinc']);$m++)
            {
                if($_SESSION['ctrpl1']['gruautoinc'][$n]['nivel']==$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel'] AND
                $_SESSION['ctrpl1']['gruautoinc'][$n]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoinc'][$_SESSION['ctrpl1']['tipocauinc']]['tipo_cargo'] AND
                $_SESSION['ctrpl1']['cargoauinc'][$_SESSION['ctrpl1']['incaexainc']]['nivel']==$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel'])
                {
                    $check=1;
                    break;
                }
                else if($_SESSION['ctrpl1']['gruautoinc'][$n]['nivel']==$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel'] AND
                $_SESSION['ctrpl1']['gruautoinc'][$n]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoinc'][$_SESSION['ctrpl1']['tipocauinc']]['tipo_cargo'] AND
                $_SESSION['ctrpl1']['cargoauinc'][$_SESSION['ctrpl1']['incaexainc']]['nivel']<>$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel'])
                {
                    $check=0;
                    $n++;
                }
            }
            if(is_numeric($_POST['valmaxainc'.$i])==1)
            {
                $vmc=doubleval($_POST['valmaxainc'.$i]);
                if($vmc >= 1000000000)
                {
                    $vmc=0;
                }
            }
            else
            {
                $vmc=0;
            }
            if(is_numeric($_POST['periocainc'.$i])==1)
            {
                $pdc=intval($_POST['periocainc'.$i]);
                if($pdc > 32000)
                {
                    $pdc=0;
                }
            }
            else
            {
                $pdc=0;
            }
            if(is_numeric($_POST['cantidainc'.$i])==1)
            {
                $cac=doubleval($_POST['cantidainc'.$i]);
                if($cac >= 10000000)
                {
                    $cac=0;
                }
            }
            else
            {
                $cac=0;
            }
            if(empty($vmc) AND empty($pdc) AND empty($cac))
            {
                $vacio=1;
            }
            else
            {
                $vacio=0;
            }
            if($vacio==1 AND (($check==1 AND $_POST['swautinexc'.$i]==NULL) OR ($check==0 AND $_POST['swautinexc'.$i]<>NULL)))
            {
                if($_POST['swautinexc'.$i]<>NULL)
                {
                    $autorizado="'1'";
                }
                else if($_POST['swautinexc'.$i]==NULL)
                {
                    $autorizado="'0'";
                }
                $query ="INSERT INTO excepciones_aut_int
                        (plan_id,
                        cargo,
                        servicio,
                        sw_autorizado,
                        cantidad,
                        valor_maximo,
                        periocidad_dias)
                        VALUES
                        (".$_SESSION['ctrpla']['planeleg'].",
                        '".$_SESSION['ctrpl1']['cargoauinc'][$_SESSION['ctrpl1']['incaexainc']]['cargo']."',
                        '".$_SESSION['ctrpl1']['serautintc']."',
                        $autorizado,
                        ".$cac.",
                        ".$vmc.",
                        ".$pdc.");";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
            else if($vacio==0)
            {
                if($_POST['swautinexc'.$i]<>NULL)
                {
                    $autorizado="'1'";
                }
                else if($_POST['swautinexc'.$i]==NULL)
                {
                    $autorizado="'0'";
                }
                $query ="INSERT INTO excepciones_aut_int
                        (plan_id,
                        cargo,
                        servicio,
                        sw_autorizado,
                        cantidad,
                        valor_maximo,
                        periocidad_dias)
                        VALUES
                        (".$_SESSION['ctrpla']['planeleg'].",
                        '".$_SESSION['ctrpl1']['cargoauinc'][$_SESSION['ctrpl1']['incaexainc']]['cargo']."',
                        '".$_SESSION['ctrpl1']['serautintc']."',
                        $autorizado,
                        ".$cac.",
                        ".$vmc.",
                        ".$pdc.");";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
        }
        else if($_REQUEST['cremod']==2)//MODIFICA
        {
            $n=$_SESSION['ctrpl1']['tipocauinc'];
            for($m=0;$m<sizeof($_SESSION['ctrpl1']['nivautoinc']);$m++)
            {
                if($_SESSION['ctrpl1']['gruautoinc'][$n]['nivel']==$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel'] AND
                $_SESSION['ctrpl1']['gruautoinc'][$n]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoinc'][$_SESSION['ctrpl1']['tipocauinc']]['tipo_cargo'] AND
                $_SESSION['ctrpl1']['cargoauinc'][$i]['nivel']==$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel'])
                {
                    $check=1;
                    break;
                }
                else if($_SESSION['ctrpl1']['gruautoinc'][$n]['nivel']==$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel'] AND
                $_SESSION['ctrpl1']['gruautoinc'][$n]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoinc'][$_SESSION['ctrpl1']['tipocauinc']]['tipo_cargo'] AND
                $_SESSION['ctrpl1']['cargoauinc'][$i]['nivel']<>$_SESSION['ctrpl1']['nivautoinc'][$m]['nivel'])
                {
                    $check=0;
                    $n++;
                }
            }
            if(is_numeric($_POST['valmaxainc'.$i])==1)
            {
                $vmc=doubleval($_POST['valmaxainc'.$i]);
                if($vmc >= 1000000000)
                {
                    $vmc=0;
                }
            }
            else
            {
                $vmc=0;
            }
            if(is_numeric($_POST['periocainc'.$i])==1)
            {
                $pdc=intval($_POST['periocainc'.$i]);
                if($pdc > 32000)
                {
                    $pdc=0;
                }
            }
            else
            {
                $pdc=0;
            }
            if(is_numeric($_POST['cantidainc'.$i])==1)
            {
                $cac=doubleval($_POST['cantidainc'.$i]);
                if($cac >= 10000000)
                {
                    $cac=0;
                }
            }
            else
            {
                $cac=0;
            }
            if(empty($vmc) AND empty($pdc) AND empty($cac))
            {
                $vacio=1;
            }
            else
            {
                $vacio=0;
            }
            if($vacio==1 AND (($check==1 AND $_POST['swautinexc'.$i]==NULL) OR ($check==0 AND $_POST['swautinexc'.$i]<>NULL)))
            {
                if($_POST['swautinexc'.$i]<>NULL)
                {
                    $autorizado="sw_autorizado='1'";
                }
                else if($_POST['swautinexc'.$i]==NULL)
                {
                    $autorizado="sw_autorizado='0'";
                }
                $query ="UPDATE excepciones_aut_int SET
                        $autorizado,
                        cantidad=".$cac.",
                        valor_maximo=".$vmc.",
                        periocidad_dias=".$pdc."
                        WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                        AND cargo='".$_SESSION['ctrpl1']['cargoauinc'][$i]['cargo']."'
                        AND servicio='".$_SESSION['ctrpl1']['serautintc']."';";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
            else if($vacio==0)
            {
                if($_POST['swautinexc'.$i]<>NULL)
                {
                    $autorizado="sw_autorizado='1'";
                }
                else if($_POST['swautinexc'.$i]==NULL)
                {
                    $autorizado="sw_autorizado='0'";
                }
                $query ="UPDATE excepciones_aut_int SET
                        $autorizado,
                        cantidad=".$cac.",
                        valor_maximo=".$vmc.",
                        periocidad_dias=".$pdc."
                        WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                        AND cargo='".$_SESSION['ctrpl1']['cargoauinc'][$i]['cargo']."'
                        AND servicio='".$_SESSION['ctrpl1']['serautintc']."';";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
        }
        $this->AutoInteExPlanContra();
        return true;
    }

    function EliminarAuInCargExPlanContra()//Elimina las excepciones del cargo
    {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query ="DELETE FROM excepciones_aut_int
                WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                AND cargo='".$_SESSION['ctrpl1']['cargoauinc'][$_REQUEST['idcarainexc']]['cargo']."'
                AND servicio='".$_SESSION['ctrpl1']['serautintc']."';";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollBackTrans();
            return false;
        }
        $dbconn->CommitTrans();
        $this->AutoInteExPlanContra();
        return true;
    }

    function BuscarGruposAuExPlanContra($plan,$servicio)//
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT DISTINCT A.descripcion AS des1,
                B.descripcion AS des2,
                A.grupo_tipo_cargo,
                B.tipo_cargo,
                C.servicio,
                C.nivel
                FROM cups AS D,
                grupos_tipos_cargo AS A,
                tipos_cargos AS B
                LEFT JOIN planes_autorizaciones_ext AS C ON
                (
                C.plan_id=".$plan."
                AND B.grupo_tipo_cargo=C.grupo_tipo_cargo
                AND B.tipo_cargo=C.tipo_cargo
                AND C.servicio='".$servicio."'
                )
                WHERE A.grupo_tipo_cargo=B.grupo_tipo_cargo
                AND B.grupo_tipo_cargo=D.grupo_tipo_cargo
                AND B.tipo_cargo=D.tipo_cargo
                AND A.grupo_tipo_cargo<>'SYS'
                ORDER BY des1, des2,
                C.servicio, C.nivel;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function ValidarAutoExtePlanContra()//
    {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        for($i=0;$i<sizeof($_SESSION['ctrpl1']['gruautoexc']);)
        {
            $k=$i;
            $l=0;
            while($_SESSION['ctrpl1']['gruautoexc'][$i]['grupo_tipo_cargo']==$_SESSION['ctrpl1']['gruautoexc'][$k]['grupo_tipo_cargo']
            AND $_SESSION['ctrpl1']['gruautoexc'][$i]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoexc'][$k]['tipo_cargo'])
            {
                $k++;
                $l++;
            }
            $k=$i;
            for($m=0;$m<sizeof($_SESSION['ctrpl1']['nivautoexc']);$m++)
            {
                $b=0;
                if($_SESSION['ctrpl1']['gruautoexc'][$k]['nivel']==$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel'] AND $_POST['nivauexc'.$i.$m]==NULL)
                {
                    $query ="DELETE FROM planes_autorizaciones_ext
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                            AND grupo_tipo_cargo='".$_SESSION['ctrpl1']['gruautoexc'][$k]['grupo_tipo_cargo']."'
                            AND tipo_cargo='".$_SESSION['ctrpl1']['gruautoexc'][$k]['tipo_cargo']."'
                            AND servicio='".$_SESSION['ctrpl1']['serautextc']."'
                            AND nivel='".$_SESSION['ctrpl1']['gruautoexc'][$k]['nivel']."';";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $dbconn->RollBackTrans();
                    }
                    $b=1;
                    $k++;
                }
                else if($_SESSION['ctrpl1']['gruautoexc'][$k]['nivel']<>$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel'] AND $_POST['nivauexc'.$i.$m]<>NULL)
                {
                    $b=1;
                }
                if($_SESSION['ctrpl1']['gruautoexc'][$k]['nivel']==$_POST['nivauexc'.$i.$m] AND $_POST['nivauexc'.$i.$m]<>NULL)
                {
                    $b=0;
                    $k++;
                }
                if($b==1 AND $_POST['nivauexc'.$i.$m]<>NULL)
                {
                    $query ="INSERT INTO planes_autorizaciones_ext
                            (plan_id,
                            grupo_tipo_cargo,
                            tipo_cargo,
                            servicio,
                            nivel)
                            VALUES
                            (".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['ctrpl1']['gruautoexc'][$i]['grupo_tipo_cargo']."',
                            '".$_SESSION['ctrpl1']['gruautoexc'][$i]['tipo_cargo']."',
                            '".$_SESSION['ctrpl1']['serautextc']."',
                            '".$_POST['nivauexc'.$i.$m]."');";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $dbconn->RollBackTrans();
                    }
                }
            }
            $i=$i+$l;
        }
        $dbconn->CommitTrans();
        $grupos=$this->BuscarGruposAuExPlanContra($_SESSION['ctrpla']['planeleg'],$_SESSION['ctrpl1']['serautextc']);
        for($i=0;$i<sizeof($grupos);$i++)
        {
            if($grupos[$i]['servicio']==NULL)
            {
                $query ="SELECT borrar_excepciones_auto_ext2
                        (".$_SESSION['ctrpla']['planeleg'].",
                        '".$_SESSION['ctrpl1']['serautextc']."',
                        '".$grupos[$i]['grupo_tipo_cargo']."',
                        '".$grupos[$i]['tipo_cargo']."');";
                $dbconn->Execute($query);
            }
        }
        $this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
        $this->uno=1;
        $this->AutoExtePlanContra();
        return true;
    }

    function BuscarAuExPlanContra($plan,$grucar,$tipcar,$servicio)//Busca las autorizaciones del plan Auditoria Externa
    {
        list($dbconn) = GetDBconn();
        if($_REQUEST['codigoctra'])
        {
            $codigo=$_REQUEST['codigoctra'];
            $busqueda="AND A.cargo LIKE '$codigo%'";
        }
        else
        {
            $busqueda='';
        }
        if($_REQUEST['descrictra'])
        {
            $codigo=STRTOUPPER($_REQUEST['descrictra']);
            $busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
        }
        else
        {
            $busqueda2='';
        }
        if(empty($_REQUEST['conteo']))
        {
            $query ="SELECT count(*) FROM
                    (
                    SELECT A.cargo,
                    A.descripcion,
                    A.nivel,
                    excepciones_auto_ext
                    (".$plan.", A.cargo, ".$servicio.")
                    AS excepciones
                    FROM cups AS A
                    WHERE A.grupo_tipo_cargo = '".$grucar."'
                    AND A.tipo_cargo = '".$tipcar."'
                    $busqueda
                    $busqueda2
                    )
                    AS r;";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            list($this->conteo)=$resulta->fetchRow();
        }
        else
        {
            $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of'])
        {
            $Of='0';
        }
        else
        {
            $Of=$_REQUEST['Of'];
            if($_REQUEST['Of'] > $this->conteo)
            {
                $Of='0';
                $_REQUEST['Of']='0';
                $_REQUEST['paso']='1';
            }
        }
        $query ="
                (
                SELECT A.cargo,
                A.descripcion,
                A.nivel,
                excepciones_auto_ext
                (".$plan.", A.cargo, ".$servicio.")
                AS excepciones
                FROM cups AS A
                WHERE A.grupo_tipo_cargo = '".$grucar."'
                AND A.tipo_cargo = '".$tipcar."'
                $busqueda
                $busqueda2
                ORDER BY A.cargo
                )
                LIMIT ".$this->limit." OFFSET $Of;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarAuExExPlanContra($plan,$grucar,$tipcar,$servicio)//Busca las autorizaciones del plan
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT A.cargo,
                A.servicio,
                A.sw_autorizado,
                A.cantidad,
                A.valor_maximo,
                A.periocidad_dias,
                B.nivel
                FROM excepciones_aut_ext AS A,
                tarifarios_detalle AS B
                WHERE A.plan_id = ".$plan."
                AND A.servicio = '".$servicio."'
                AND B.grupo_tipo_cargo = '".$grucar."'
                AND B.tipo_cargo = '".$tipcar."'
                AND A.cargo=B.cargo
                ORDER BY A.cargo;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var[$resulta->fields[0]]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function ValidarAuExCargExPlanContra()//Valida, y guarda o modifica las excepciones del plan
    {
        list($dbconn) = GetDBconn();
        $i=$_REQUEST['ictra'];
        if($_REQUEST['cremod']==1)//INGRESA
        {
            $n=$_SESSION['ctrpl1']['tipocauexc'];
            for($m=0;$m<sizeof($_SESSION['ctrpl1']['nivautoexc']);$m++)
            {
                if($_SESSION['ctrpl1']['gruautoexc'][$n]['nivel']==$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel'] AND
                $_SESSION['ctrpl1']['gruautoexc'][$n]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoexc'][$_SESSION['ctrpl1']['tipocauexc']]['tipo_cargo'] AND
                $_SESSION['ctrpl1']['cargoauexc'][$_SESSION['ctrpl1']['incaexaexc']]['nivel']==$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel'])
                {
                    $check=1;
                    break;
                }
                else if($_SESSION['ctrpl1']['gruautoexc'][$n]['nivel']==$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel'] AND
                $_SESSION['ctrpl1']['gruautoexc'][$n]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoexc'][$_SESSION['ctrpl1']['tipocauexc']]['tipo_cargo'] AND
                $_SESSION['ctrpl1']['cargoauexc'][$_SESSION['ctrpl1']['incaexaexc']]['nivel']<>$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel'])
                {
                    $check=0;
                    $n++;
                }
            }
            if(is_numeric($_POST['valmaxaexc'.$i])==1)
            {
                $vmc=doubleval($_POST['valmaxaexc'.$i]);
                if($vmc >= 1000000000)
                {
                    $vmc=0;
                }
            }
            else
            {
                $vmc=0;
            }
            if(is_numeric($_POST['periocaexc'.$i])==1)
            {
                $pdc=intval($_POST['periocaexc'.$i]);
                if($pdc > 32000)
                {
                    $pdc=0;
                }
            }
            else
            {
                $pdc=0;
            }
            if(is_numeric($_POST['cantidaexc'.$i])==1)
            {
                $cac=doubleval($_POST['cantidaexc'.$i]);
                if($cac >= 10000000)
                {
                    $cac=0;
                }
            }
            else
            {
                $cac=0;
            }
            if(empty($vmc) AND empty($pdc) AND empty($cac))
            {
                $vacio=1;
            }
            else
            {
                $vacio=0;
            }
            if($vacio==1 AND (($check==1 AND $_POST['swautexexc'.$i]==NULL) OR ($check==0 AND $_POST['swautexexc'.$i]<>NULL)))
            {
                if($_POST['swautexexc'.$i]<>NULL)
                {
                    $autorizado="'1'";
                }
                else if($_POST['swautexexc'.$i]==NULL)
                {
                    $autorizado="'0'";
                }
                $query ="INSERT INTO excepciones_aut_ext
                        (plan_id,
                        cargo,
                        servicio,
                        sw_autorizado,
                        cantidad,
                        valor_maximo,
                        periocidad_dias)
                        VALUES
                        (".$_SESSION['ctrpla']['planeleg'].",
                        '".$_SESSION['ctrpl1']['cargoauexc'][$_SESSION['ctrpl1']['incaexaexc']]['cargo']."',
                        '".$_SESSION['ctrpl1']['serautextc']."',
                        $autorizado,
                        ".$cac.",
                        ".$vmc.",
                        ".$pdc.");";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
            else if($vacio==0)
            {
                if($_POST['swautexexc'.$i]<>NULL)
                {
                    $autorizado="'1'";
                }
                else if($_POST['swautexexc'.$i]==NULL)
                {
                    $autorizado="'0'";
                }
                $query ="INSERT INTO excepciones_aut_ext
                        (plan_id,
                        cargo,
                        servicio,
                        sw_autorizado,
                        cantidad,
                        valor_maximo,
                        periocidad_dias)
                        VALUES
                        (".$_SESSION['ctrpla']['planeleg'].",
                        '".$_SESSION['ctrpl1']['cargoauexc'][$_SESSION['ctrpl1']['incaexaexc']]['cargo']."',
                        '".$_SESSION['ctrpl1']['serautextc']."',
                        $autorizado,
                        ".$cac.",
                        ".$vmc.",
                        ".$pdc.");";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
        }
        else if($_REQUEST['cremod']==2)//MODIFICA
        {
            $n=$_SESSION['ctrpl1']['tipocauexc'];
            for($m=0;$m<sizeof($_SESSION['ctrpl1']['nivautoexc']);$m++)
            {
                if($_SESSION['ctrpl1']['gruautoexc'][$n]['nivel']==$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel'] AND
                $_SESSION['ctrpl1']['gruautoexc'][$n]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoexc'][$_SESSION['ctrpl1']['tipocauexc']]['tipo_cargo'] AND
                $_SESSION['ctrpl1']['cargoauexc'][$i]['nivel']==$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel'])
                {
                    $check=1;
                    break;
                }
                else if($_SESSION['ctrpl1']['gruautoexc'][$n]['nivel']==$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel'] AND
                $_SESSION['ctrpl1']['gruautoexc'][$n]['tipo_cargo']==$_SESSION['ctrpl1']['gruautoexc'][$_SESSION['ctrpl1']['tipocauexc']]['tipo_cargo'] AND
                $_SESSION['ctrpl1']['cargoauexc'][$i]['nivel']<>$_SESSION['ctrpl1']['nivautoexc'][$m]['nivel'])
                {
                    $check=0;
                    $n++;
                }
            }
            if(is_numeric($_POST['valmaxaexc'.$i])==1)
            {
                $vmc=doubleval($_POST['valmaxaexc'.$i]);
                if($vmc >= 1000000000)
                {
                    $vmc=0;
                }
            }
            else
            {
                $vmc=0;
            }
            if(is_numeric($_POST['periocaexc'.$i])==1)
            {
                $pdc=intval($_POST['periocaexc'.$i]);
                if($pdc > 32000)
                {
                    $pdc=0;
                }
            }
            else
            {
                $pdc=0;
            }
            if(is_numeric($_POST['cantidaexc'.$i])==1)
            {
                $cac=doubleval($_POST['cantidaexc'.$i]);
                if($cac >= 10000000)
                {
                    $cac=0;
                }
            }
            else
            {
                $cac=0;
            }
            if(empty($vmc) AND empty($pdc) AND empty($cac))
            {
                $vacio=1;
            }
            else
            {
                $vacio=0;
            }
            if($vacio==1 AND (($check==1 AND $_POST['swautexexc'.$i]==NULL) OR ($check==0 AND $_POST['swautexexc'.$i]<>NULL)))
            {
                if($_POST['swautexexc'.$i]<>NULL)
                {
                    $autorizado="sw_autorizado='1'";
                }
                else if($_POST['swautexexc'.$i]==NULL)
                {
                    $autorizado="sw_autorizado='0'";
                }
                $query ="UPDATE excepciones_aut_ext SET
                        $autorizado,
                        cantidad=".$cac.",
                        valor_maximo=".$vmc.",
                        periocidad_dias=".$pdc."
                        WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                        AND cargo='".$_SESSION['ctrpl1']['cargoauexc'][$i]['cargo']."'
                        AND servicio='".$_SESSION['ctrpl1']['serautextc']."';";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
            else if($vacio==0)
            {
                if($_POST['swautexexc'.$i]<>NULL)
                {
                    $autorizado="sw_autorizado='1'";
                }
                else if($_POST['swautexexc'.$i]==NULL)
                {
                    $autorizado="sw_autorizado='0'";
                }
                $query ="UPDATE excepciones_aut_ext SET
                        $autorizado,
                        cantidad=".$cac.",
                        valor_maximo=".$vmc.",
                        periocidad_dias=".$pdc."
                        WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                        AND cargo='".$_SESSION['ctrpl1']['cargoauexc'][$i]['cargo']."'
                        AND servicio='".$_SESSION['ctrpl1']['serautextc']."';";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
        }
        $this->AutoExteExPlanContra();
        return true;
    }

    function EliminarAuExCargExPlanContra()//Elimina las excepciones del cargo
    {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query ="DELETE FROM excepciones_aut_ext
                WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                AND cargo='".$_SESSION['ctrpl1']['cargoauexc'][$_REQUEST['idcaraexexc']]['cargo']."'
                AND servicio='".$_SESSION['ctrpl1']['serautextc']."';";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollBackTrans();
            return false;
        }
        $dbconn->CommitTrans();
        $this->AutoExteExPlanContra();
        return true;
    }

    /********************FUNCIONES DE LA OPCIÓN AUDITORES********************/
    function BuscarAuditoresInternos($empresa)//Busca todos los auditores internos
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT A.usuario_id,
                A.extension,
                A.celular,
                A.estado,
								D.descripcion,
								D.tipo_auditoria_id,
                B.nombre
                FROM auditores_internos AS A,
                system_usuarios AS B,
                system_usuarios_empresas AS C, tipos_auditoria AS D
                WHERE C.empresa_id='".$empresa."'
                AND C.usuario_id=B.usuario_id
                AND A.usuario_id=B.usuario_id
                AND A.tipo_auditoria_id=D.tipo_auditoria_id
                ORDER BY B.nombre, A.estado;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarAuditoresInPlan($plan)//Busca los auditores internos del plan
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT A.usuario_id,
                A.extension,
                A.celular,
                A.estado,
								D.descripcion,
                C.nombre
                FROM auditores_internos AS A,
                planes_auditores_int AS B,
                system_usuarios AS C, tipos_auditoria AS D
                WHERE B.plan_id=".$plan."
                AND B.usuario_id=A.usuario_id
                AND C.usuario_id=A.usuario_id
                AND B.sw_tipo_auditoria=A.tipo_auditoria_id
                AND A.tipo_auditoria_id=D.tipo_auditoria_id
                ORDER BY C.nombre, A.estado;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function ValidarAsignarInContra()//Asigna auditores internos a un plan
    {//*****************OJO planes_auditores_int-sw_tipo_auditoria
        list($dbconn) = GetDBconn();
        for($i=0;$i<$_REQUEST['total'];$i++)
        {
            if(!empty($_POST['asignar'.$i]))
            {
                $query ="INSERT INTO planes_auditores_int
                        (usuario_id,
                        plan_id,
												sw_tipo_auditoria)
                        VALUES
                        (".$_POST['asignar'.$i].",
                        ".$_SESSION['ctrpla']['planeleg'].",
												'".$_POST['tipo_auditoria'.$i]."');";
                $resulta = $dbconn->Execute($query);
            }
        }
        $this->AuditoresInPlanContra();
        return true;
    }

    function EliminarAsignarInContra()//Elimina auditores internos a un plan
    {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query ="DELETE FROM planes_auditores_int
                WHERE usuario_id=".$_REQUEST['usuario']."
                AND plan_id=".$_SESSION['ctrpla']['planeleg'].";";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollBackTrans();
        }
        $dbconn->CommitTrans();
        $this->AuditoresInPlanContra();
        return true;
    }

    function BuscarAuditoresExternos($empresa)//Busca todos los auditores externos
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT A.usuario_id,
                A.telefonos,
                A.celular,
                A.estado,
                A.tipo_tercero_id,
                A.tercero_id,
                B.nombre,
                D.nombre_tercero
                FROM auditores_externos AS A,
                system_usuarios AS B,
                system_usuarios_empresas AS C,
                terceros AS D
                WHERE C.empresa_id='".$empresa."'
                AND C.usuario_id=B.usuario_id
                AND A.usuario_id=B.usuario_id
                AND A.tipo_tercero_id=D.tipo_id_tercero
                AND A.tercero_id=D.tercero_id
                ORDER BY B.nombre, A.estado;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarAuditoresExPlan($plan,$tipoidt,$tercero)//Busca los auditores internos del plan
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT A.usuario_id,
                A.telefonos,
                A.celular,
                A.estado,
                A.tipo_id_tercero,
                A.tercero_id,
                C.nombre,
                D.nombre_tercero
                FROM auditores_externos AS A,
                planes_auditores_ext AS B,
                system_usuarios AS C,
                terceros AS D
                WHERE B.plan_id=".$plan."
                AND B.usuario_id=A.usuario_id
                AND C.usuario_id=A.usuario_id
                AND A.tipo_id_tercero='".$tipoidt."'
                AND A.tercero_id='".$tercero."'
                AND A.tipo_id_tercero=D.tipo_id_tercero
                AND A.tercero_id=D.tercero_id
                ORDER BY C.nombre, A.estado;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function ValidarAsignarExContra()//Asigna auditores externos a un plan
    {
        list($dbconn) = GetDBconn();
        for($i=0;$i<$_REQUEST['total'];$i++)
        {
            if(!empty($_POST['asignar'.$i]))
            {
                $query ="INSERT INTO planes_auditores_ext
                        (usuario_id,
                        plan_id,
                        tipo_id_tercero,
                        tercero_id)
                        VALUES
                        (".$_POST['asignar'.$i].",
                        ".$_SESSION['ctrpla']['planeleg'].",
                        '".$_SESSION['ctrpla']['tidteleg']."',
                        '".$_SESSION['ctrpla']['terceleg']."');";
                $resulta = $dbconn->Execute($query);
            }
        }
        $this->AuditoresExPlanContra();
        return true;
    }

    function EliminarAsignarExContra()//Elimina auditores externos a un plan
    {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query ="DELETE FROM planes_auditores_ext
                WHERE usuario_id=".$_REQUEST['usuario']."
                AND plan_id=".$_SESSION['ctrpla']['planeleg']."
                AND tipo_id_tercero='".$_SESSION['ctrpla']['tidteleg']."'
                AND tercero_id='".$_SESSION['ctrpla']['terceleg']."';";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollBackTrans();
        }
        $dbconn->CommitTrans();
        $this->AuditoresExPlanContra();
        return true;
    }

    function BuscarAuditoresExternos2($empresa,$plan)//Busca los auditores externos del plan
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT A.usuario_id,
                A.telefonos,
                A.celular,
                A.estado,
                A.tipo_id_tercero,
                A.tercero_id,
                B.nombre,
                D.nombre_tercero
                FROM auditores_externos AS A,
                system_usuarios AS B,
                system_usuarios_empresas AS C,
                terceros AS D,
                planes AS E
                WHERE C.empresa_id='".$empresa."'
                AND C.usuario_id=B.usuario_id
                AND A.usuario_id=B.usuario_id
                AND E.plan_id=".$plan."
                AND E.tipo_tercero_id=D.tipo_id_tercero
                AND E.tercero_id=D.tercero_id
                AND D.tipo_id_tercero=A.tipo_id_tercero
                AND D.tercero_id=A.tercero_id
                ORDER BY B.nombre, A.estado;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    /********************FUNCIONES DE LA OPCIÓN INVENTARIOS AUTORIZACIONES********************/
    function BuscarGruposAutoInveContra($empresa,$plan,$servicio)//Busca los grupos de contratación del inventario y el plan tarifario de autorizaciones
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT A.grupo_contratacion_id,
                A.descripcion AS des1,
                D.semanas_cotizadas,
                D.cantidad_max,
                D.valor_max_unidad,
                D.valor_max_cuenta,
                D.requiere_autorizacion_int,
                D.requiere_autorizacion_ext
                FROM inv_grupos_contrataciones AS A
                LEFT JOIN plan_tarifario_inv_autorizaciones AS D ON
                (
                    D.empresa_id='".$empresa."'
                    AND D.plan_id=".$plan."
                    AND D.servicio='".$servicio."'
                    AND D.grupo_contratacion_id=A.grupo_contratacion_id
                )
                WHERE A.grupo_contratacion_id<>'0'
                ORDER BY des1;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function ValidarTarifarioAutoInveContra()//Válida y guarda del inventario lo que es incluido en el plan
    {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        for($i=0;$i<sizeof($_SESSION['ctrpl1']['grupoinauc']);$i++)
        {
            $g1=0;
            $g2=0;
            $g3=0;
            $g4=0;
            if(is_numeric($_POST['seminvctra'.$i])==1)
            {
                $sem1=intval($_POST['seminvctra'.$i]);
                if($sem1 <= 32000)
                {
                    $g1=1;
                }
            }
            if(is_numeric($_POST['caninvctra'.$i])==1)
            {
                $can1=intval($_POST['caninvctra'.$i]);
                if($can1 <= 32000)
                {
                    $g2=1;
                }
            }
            if(is_numeric($_POST['vmuinvctra'.$i])==1)
            {
                $vmu1=doubleval($_POST['vmuinvctra'.$i]);
                if($vmu1 < 10000000000)
                {
                    $g3=1;
                }
            }
            if(is_numeric($_POST['vmcinvctra'.$i])==1)
            {
                $vmc1=doubleval($_POST['vmcinvctra'.$i]);
                if($vmc1 < 10000000000)
                {
                    $g4=1;
                }
            }
            if($g1==1||$g2==1||$g3==1||$g4==1)
            {
                if($g1==0)
                {
                    $sem1=0;
                }
                if($g2==0)
                {
                    $can1=0;
                }
                if($g3==0)
                {
                    $vmu1=0.00;
                }
                if($g4==0)
                {
                    $vmc1=0.00;
                }
                if($_POST['autoint'.$i]==NULL)
                {
                    $_POST['autoint'.$i]=0;
                }
                if($_POST['autoext'.$i]==NULL)
                {
                    $_POST['autoext'.$i]=0;
                }
                if(($_SESSION['ctrpl1']['grupoinauc'][$i]['semanas_cotizadas']<>NULL)
                AND ($sem1<>$_SESSION['ctrpl1']['grupoinauc'][$i]['semanas_cotizadas']
                OR $can1<>$_SESSION['ctrpl1']['grupoinauc'][$i]['cantidad_max']
                OR $vmu1<>$_SESSION['ctrpl1']['grupoinauc'][$i]['valor_max_unidad']
                OR $vmc1<>$_SESSION['ctrpl1']['grupoinauc'][$i]['valor_max_cuenta']
                OR $_POST['autoint'.$i]<>$_SESSION['ctrpl1']['grupoinauc'][$i]['requiere_autorizacion_int']
                OR $_POST['autoext'.$i]<>$_SESSION['ctrpl1']['grupoinauc'][$i]['requiere_autorizacion_ext']))
                {
                    $query ="DELETE FROM plan_tarifario_inv_autorizaciones
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                            AND empresa_id='".$_SESSION['contra']['empresa']."'
                            AND servicio='".$_SESSION['ctrpl1']['serinvautc']."'
                            AND grupo_contratacion_id='".$_SESSION['ctrpl1']['grupoinauc'][$i]['grupo_contratacion_id']."';";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                        return false;
                    }
                }
                if($sem1<>$_SESSION['ctrpl1']['grupoinauc'][$i]['semanas_cotizadas']
                OR $can1<>$_SESSION['ctrpl1']['grupoinauc'][$i]['cantidad_max']
                OR $vmu1<>$_SESSION['ctrpl1']['grupoinauc'][$i]['valor_max_unidad']
                OR $vmc1<>$_SESSION['ctrpl1']['grupoinauc'][$i]['valor_max_cuenta']
                OR $_POST['autoint'.$i]<>$_SESSION['ctrpl1']['grupoinauc'][$i]['requiere_autorizacion_int']
                OR $_POST['autoext'.$i]<>$_SESSION['ctrpl1']['grupoinauc'][$i]['requiere_autorizacion_ext'])
                {
                    $query ="INSERT INTO plan_tarifario_inv_autorizaciones
                            (plan_id,
                            empresa_id,
                            grupo_contratacion_id,
                            servicio,
                            semanas_cotizadas,
                            cantidad_max,
                            valor_max_unidad,
                            valor_max_cuenta,
                            requiere_autorizacion_int,
                            requiere_autorizacion_ext)
                            VALUES
                            (".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['contra']['empresa']."',
                            '".$_SESSION['ctrpl1']['grupoinauc'][$i]['grupo_contratacion_id']."',
                            '".$_SESSION['ctrpl1']['serinvautc']."',
                            ".$sem1.",
                            ".$can1.",
                            ".$vmu1.",
                            ".$vmc1.",
                            '".$_POST['autoint'.$i]."',
                            '".$_POST['autoext'.$i]."');";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                        return false;
                    }
                }
            }
            else
            {
                if($_POST['seminvctra'.$i]==NULL AND $_POST['caninvctra'.$i]==NULL
                AND $_POST['vmuinvctra'.$i]==NULL AND $_POST['vmcinvctra'.$i]==NULL
                AND $_SESSION['ctrpl1']['grupoinauc'][$i]['semanas_cotizadas']<>NULL)
                {
                    $query ="DELETE FROM plan_tarifario_inv_autorizaciones
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                            AND empresa_id='".$_SESSION['contra']['empresa']."'
                            AND servicio='".$_SESSION['ctrpl1']['serinvautc']."'
                            AND grupo_contratacion_id='".$_SESSION['ctrpl1']['grupoinauc'][$i]['grupo_contratacion_id']."';";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                        return false;
                    }
                }
            }
            $_POST['seminvctra'.$i]='';
            $_POST['caninvctra'.$i]='';
            $_POST['vmuinvctra'.$i]='';
            $_POST['vmcinvctra'.$i]='';
            $_POST['autoint'.$i]='';
            $_POST['autoext'.$i]='';
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
        $this->uno=1;
        $this->TarifarioAutoInveContra();
        return true;
    }

    function BuscarTariAutoInveContra($plan,$empresa,$servicio,$grupo)//Busca los productos al nivel del detalle para la excepción
    {
        list($dbconn) = GetDBconn();
        if($_REQUEST['codigoctra'])
        {
            $codigo=$_REQUEST['codigoctra'];
            $busqueda="AND A.codigo_producto LIKE '$codigo%'";
        }
        else
        {
            $busqueda='';
        }
        if($_REQUEST['descrictra'])
        {
            $codigo=STRTOUPPER($_REQUEST['descrictra']);
            $busqueda2="AND UPPER(B.descripcion) LIKE '%$codigo%'";
        }
        else
        {
            $busqueda2='';
        }
        if(empty($_REQUEST['conteo']))
        {
            $query ="SELECT count(*) FROM (
                    (
                    SELECT A.codigo_producto,
                    B.descripcion,
                    A.costo,
                    A.costo_ultima_compra,
                    A.precio_venta,
                    0 AS excepcion,
                    C.semanas_cotizadas,
                    C.cantidad_max,
                    C.valor_max_unidad,
                    C.valor_max_cuenta,
                    C.requiere_autorizacion_int,
                    C.requiere_autorizacion_ext
                    FROM inventarios AS A,
                    inventarios_productos AS B,
                    plan_tarifario_inv_autorizaciones AS C
                    WHERE C.plan_id = ".$plan."
                    AND C.empresa_id = '".$empresa."'
                    AND C.servicio='".$servicio."'
                    AND C.grupo_contratacion_id = '".$grupo."'
                    AND C.empresa_id = A.empresa_id
                    AND C.grupo_contratacion_id = A.grupo_contratacion_id
                    AND B.codigo_producto=A.codigo_producto
                    AND excepciones_inventarios_autorizaciones
                    (C.plan_id, A.codigo_producto, C.servicio)=0
                    $busqueda
                    $busqueda2
                    )
                    UNION
                    (
                    SELECT A.codigo_producto,
                    B.descripcion,
                    A.costo,
                    A.costo_ultima_compra,
                    A.precio_venta,
                    1 AS excepcion,
                    C.semanas_cotizadas,
                    C.cantidad_max,
                    C.valor_max_unidad,
                    C.valor_max_cuenta,
                    C.requiere_autorizacion_int,
                    C.requiere_autorizacion_ext
                    FROM inventarios AS A,
                    inventarios_productos AS B,
                    excepciones_inv_autorizaciones AS C
                    WHERE C.plan_id = ".$plan."
                    AND C.empresa_id = '".$empresa."'
                    AND C.servicio='".$servicio."'
                    AND C.empresa_id = A.empresa_id
                    AND C.codigo_producto=B.codigo_producto
                    AND B.codigo_producto=A.codigo_producto
                    $busqueda
                    $busqueda2
                    )
                    ) AS r;";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            list($this->conteo)=$resulta->fetchRow();
        }
        else
        {
            $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of'])
        {
            $Of='0';
        }
        else
        {
            $Of=$_REQUEST['Of'];
            if($_REQUEST['Of'] > $this->conteo)
            {
                $Of='0';
                $_REQUEST['Of']='0';
                $_REQUEST['paso']='1';
            }
        }
        $query ="
                (
                SELECT A.codigo_producto,
                B.descripcion,
                A.costo,
                A.costo_ultima_compra,
                A.precio_venta,
                0 AS excepcion,
                C.semanas_cotizadas,
                C.cantidad_max,
                C.valor_max_unidad,
                C.valor_max_cuenta,
                C.requiere_autorizacion_int,
                C.requiere_autorizacion_ext
                FROM inventarios AS A,
                inventarios_productos AS B,
                plan_tarifario_inv_autorizaciones AS C
                WHERE C.plan_id=".$plan."
                AND C.empresa_id='".$empresa."'
                AND C.servicio='".$servicio."'
                AND C.grupo_contratacion_id='".$grupo."'
                AND C.empresa_id=A.empresa_id
                AND C.grupo_contratacion_id=A.grupo_contratacion_id
                AND B.codigo_producto=A.codigo_producto
                AND excepciones_inventarios_autorizaciones
                (C.plan_id, A.codigo_producto, C.servicio)=0
                $busqueda
                $busqueda2
                )
                UNION
                (
                SELECT A.codigo_producto,
                B.descripcion,
                A.costo,
                A.costo_ultima_compra,
                A.precio_venta,
                1 AS excepcion,
                C.semanas_cotizadas,
                C.cantidad_max,
                C.valor_max_unidad,
                C.valor_max_cuenta,
                C.requiere_autorizacion_int,
                C.requiere_autorizacion_ext
                FROM inventarios AS A,
                inventarios_productos AS B,
                excepciones_inv_autorizaciones AS C
                WHERE C.plan_id=".$plan."
                AND C.empresa_id='".$empresa."'
                AND C.servicio='".$servicio."'
                AND C.empresa_id=A.empresa_id
                AND C.codigo_producto=B.codigo_producto
                AND B.codigo_producto=A.codigo_producto
                $busqueda
                $busqueda2
                ORDER BY A.codigo_producto
                )
                LIMIT ".$this->limit." OFFSET $Of;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function ValidarTariExceAutoInveContra()//
    {
         list($dbconn) = GetDBconn();
         $dbconn->BeginTrans();
        for($i=0;$i<sizeof($_SESSION['ctrpl1']['codigoiauc']);$i++)
        {
            if($_SESSION['ctrpl1']['codigoiauc'][$i]['excepcion']==1)
            {
                $query ="DELETE FROM excepciones_inv_autorizaciones
                        WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                        AND empresa_id='".$_SESSION['contra']['empresa']."'
                        AND codigo_producto='".$_SESSION['ctrpl1']['codigoiauc'][$i]['codigo_producto']."'
                        AND servicio='".$_SESSION['ctrpl1']['serinvautc']."';";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                    return false;
                }
            }
            $g1=0;
            $g2=0;
            $g3=0;
            $g4=0;
            if(is_numeric($_POST['seminvexc'.$i])==1)
            {
                $sem1=intval($_POST['seminvexc'.$i]);
                if($sem1 <= 32000)
                {
                    $g1=1;
                }
            }
            if(is_numeric($_POST['caninvexc'.$i])==1)
            {
                $can1=intval($_POST['caninvexc'.$i]);
                if($can1 <= 32000)
                {
                    $g2=1;
                }
            }
            if(is_numeric($_POST['vmuinvexc'.$i])==1)
            {
                $vmu1=doubleval($_POST['vmuinvexc'.$i]);
                if($vmu1 < 10000000000)
                {
                    $g3=1;
                }
            }
            if(is_numeric($_POST['vmcinvexc'.$i])==1)
            {
                $vmc1=doubleval($_POST['vmcinvexc'.$i]);
                if($vmc1 < 10000000000)
                {
                    $g4=1;
                }
            }
            if($g1==1||$g2==1||$g3==1||$g4==1)
            {
                if($g1==0)
                {
                    $sem1=0;
                }
                if($g2==0)
                {
                    $can1=0;
                }
                if($g3==0)
                {
                    $vmu1=0.00;
                }
                if($g4==0)
                {
                    $vmc1=0.00;
                }
                if($_POST['autoexintc'.$i]==NULL)
                {
                    $_POST['autoexintc'.$i]=0;
                }
                if($_POST['autoexextc'.$i]==NULL)
                {
                    $_POST['autoexextc'.$i]=0;
                }
                if(!($_SESSION['ctrpl1']['datautinvc']['semanas_cotizadas']==$sem1 AND
                $_SESSION['ctrpl1']['datautinvc']['cantidad_max']==$can1 AND
                $_SESSION['ctrpl1']['datautinvc']['valor_max_unidad']==$vmu1 AND
                $_SESSION['ctrpl1']['datautinvc']['valor_max_cuenta']==$vmc1 AND
                $_SESSION['ctrpl1']['datautinvc']['requiere_autorizacion_int']==$_POST['autoexintc'.$i] AND
                $_SESSION['ctrpl1']['datautinvc']['requiere_autorizacion_ext']==$_POST['autoexextc'.$i]))
                {
                    $query ="INSERT INTO excepciones_inv_autorizaciones
                            (plan_id,
                            empresa_id,
                            codigo_producto,
                            servicio,
                            semanas_cotizadas,
                            cantidad_max,
                            valor_max_unidad,
                            valor_max_cuenta,
                            requiere_autorizacion_int,
                            requiere_autorizacion_ext)
                            VALUES
                            (".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['contra']['empresa']."',
                            '".$_SESSION['ctrpl1']['codigoiauc'][$i]['codigo_producto']."',
                            '".$_SESSION['ctrpl1']['serinvautc']."',
                            ".$sem1.",
                            ".$can1.",
                            ".$vmu1.",
                            ".$vmc1.",
                            '".$_POST['autoexintc'.$i]."',
                            '".$_POST['autoexextc'.$i]."');";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                        return false;
                    }
                }
            }
            $_POST['seminvexc'.$i]='';
            $_POST['caninvexc'.$i]='';
            $_POST['vmuinvexc'.$i]='';
            $_POST['vmcinvexc'.$i]='';
            $_POST['autoexintc'.$i]='';
            $_POST['autoexextc'.$i]='';
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
        $this->uno=1;
        $this->TariExceAutoInveContra();
        return true;
    }

    /**********************************************************************************/
    /*********FUNCIÓN QUE TRAE LOS DATOS PARA PARAMETRIZACIÓN DE HABITACIONES**********/
    /**********************************************************************************/
		function ConsultarTarifarios_UvrsTarifarios()
    {
//		tarifario_id 	dc_valor 	da_valor 	dy_valor 	dg_valor
       list($dbconn) = GetDBconn();
        $query ="SELECT a.tarifario_id,a.dc_valor,a.da_valor,a.dy_valor,a.dg_valor,
												b.descripcion
								FROM tarifarios_uvrs a, tarifarios b
								WHERE a.tarifario_id=b.tarifario_id;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

		function TraerTarifariosUVR()
    {
									//			A.precio_lista
       list($dbconn) = GetDBconn();
       $query ="SELECT a.tarifario_id, a.descripcion
								FROM tarifarios a, tipos_tarifarios b
								WHERE a.tipo_tarifario_id=b.tipo_tarifario_id
								AND b.tipo_tarifario_id='02'
								ORDER BY descripcion;"; 
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarTiposCamas($empresa)//Busca las clases de h1abitaciones existentes para parametrizar
    {
									//			A.precio_lista
       list($dbconn) = GetDBconn();
	   
				$query ="SELECT A.tipo_cama_id,
												A.descripcion AS desclase,
												D.descripcion AS destipo, 
												A.cargo
								FROM tipos_camas AS A,
												tipos_clases_camas AS D
								WHERE A.tipo_clase_cama_id=D.tipo_clase_cama_id
								AND	  A.empresa_id = '".$empresa."'
								ORDER BY destipo,desclase;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarParametrosHab($empresa,$plan)//Busca las clases de h1abitaciones existentes para parametrizar
    {
				//								A.valor_lista,
				list($dbconn) = GetDBconn();
				$query ="SELECT A.tipo_cama_id,
												A.cargo_cups,
												A.plan_id,
												A.tarifario_id,
												A.cargo,
												A.porcentaje,
												A.valor_excedente,
												A.tarifario_excedente,
												A.cargo_excedente,
												A.porcentaje_excedente,
												B.descripcion,
												C.precio,
												A.valor_lista,
												D.descripcion_corta,
												D.descripcion AS desunidad
									FROM planes_tipos_camas A, tarifarios B, tarifarios_detalle C,
												tipos_unidades_cargos D
									WHERE A.empresa_id='".$empresa."'
											AND A.plan_id=".$plan."
											AND A.tarifario_id=B.tarifario_id
											AND C.tarifario_id=A.tarifario_id
											AND C.cargo=A.cargo
											AND C.tipo_unidad_id=D.tipo_unidad_id";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$i=0;
				while(!$resulta->EOF)
				{
						$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
						$i++;
				}
				return $var;
		}
    function BuscarDesCargo($tar,$cargo)//Busca las clases de h1abitaciones existentes para parametrizar
    {
				list($dbconn) = GetDBconn();
				if (!empty($tar) AND !empty($cargo))
					{
						$query ="SELECT descripcion
										FROM tarifarios_detalle
										WHERE tarifario_id='".$tar."'
										AND cargo='".$cargo."'";
					}
					else
					{
				$query ="SELECT descripcion
									FROM cups
									WHERE cargo='".$cargo."'";
					}
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
					list($var)=$resulta->FetchRow();
				return $var;
		}

    function BuscarDesTari($tar)//Busca las clases de h1abitaciones existentes para parametrizar
    {
				list($dbconn) = GetDBconn();
				$query ="SELECT descripcion
									FROM tarifarios
									WHERE tarifario_id='".$tar."'";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
					list($var)=$resulta->FetchRow();
					return $var;
		}


		function TipoCleseCama()
		{
				list($dbconn) = GetDBconn();
				$query ="SELECT tipo_clase_cama_id,descripcion
								FROM tipos_clases_camas
								ORDER BY tipo_clase_cama_id";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$i=0;
				while(!$resulta->EOF)
				{
						$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
						$i++;
				}
				return $var;
		}


		//TRAER CARGOS CUPS INTERNACIÓN
		function ConsultarCargosCups()
		{
				list($dbconn) = GetDBconn();
				$query ="SELECT cargo,descripcion
								FROM cups
								WHERE sw_internacion=1
								ORDER BY cargo,descripcion";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$i=0;
				while(!$resulta->EOF)
				{
						$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
						$i++;
				}
				return $var;
		}

//     tipo_cama_id integer NOT NULL,
//     empresa_id character(2) NOT NULL,
//     tarifario_id character varying(4) NOT NULL,
//     cargo character varying(10) NOT NULL,
//     cargo_cups character varying(10)
		//TRAER CARGOS TARIFAS 
		function TraerTarifas($tarifario,$cargo)
		{
				list($dbconn) = GetDBconn();
				if(empty($tarifario))
				{
					$query ="SELECT DISTINCT A.tarifario_id,B.descripcion AS destari, 
																		A.tipo_cama_id, A.cargo, A.cargo_cups
									FROM tarifarios_cargos_planes_habitaciones A,
												tarifarios B
									WHERE A.tarifario_id=B.tarifario_id";
				}
				else
				{
						$query ="SELECT B.descripcion AS destari,A.tarifario_id,
													A.cargo_cups, A.cargo, A.tipo_cama_id,
													C.descripcion AS descar, D.descripcion AS descarcups
										FROM tarifarios_cargos_planes_habitaciones A,
													tarifarios B, cups C, tarifarios_detalle D
										WHERE A.tarifario_id=B.tarifario_id
													AND A.tarifario_id='".$tarifario."'
													AND A.cargo='".$cargo."'
													AND C.cargo=A.cargo_cups
													AND D.tarifario_id=A.tarifario_id
													AND D.cargo=A.cargo";
				}
						
            $resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$i=0;
				while(!$resulta->EOF)
				{
						$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
						$i++;
				}
				return $var;
		}
/*	tipos_liq_habitacion_clases_camas>>>
				tipo_liq_habitacion 
				tipo_clase_cama_id*/

// tipos_liq_habitacion
//     tipo_liq_habitacion character varying(4) NOT NULL,
//     descripcion character varying(255) NOT NULL,
//     detalle text DEFAULT ''::text NOT NULL,
//     sw_estado character(1) DEFAULT '1'::bpchar NOT NULL
//tipos_liq_habitacion_clases_camas
//FOREIGN KEY (tipo_liq_habitacion, tipo_clase_cama_id) REFERENCES tipos_liq_habitacion_clases_camas(tipo_liq_habitacion, tipo_clase_cama_id)
		function TiposLiqHab()
		{
				list($dbconn) = GetDBconn();
				$query ="SELECT A.tipo_clase_cama_id, B.tipo_liq_habitacion, C.descripcion,
														C.detalle,C.sw_interface_configuracion
								FROM tipos_clases_camas A,tipos_liq_habitacion_clases_camas B, 
										tipos_liq_habitacion C 
								WHERE C.tipo_liq_habitacion=B.tipo_liq_habitacion
									AND B.tipo_clase_cama_id=A.tipo_clase_cama_id
									AND C.sw_estado='1'
								ORDER BY C.descripcion";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$i=0;
				while(!$resulta->EOF)
				{
						$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
						$i++;
				}
				return $var;
		}

		function TraerLiqGuardadas()
		{
				list($dbconn) = GetDBconn();
				$query ="SELECT A.tipo_liq_habitacion,
										A.tipo_clase_cama_id,
										A.plan_id,
										C.descripcion,
										C.detalle
								FROM planes_tipos_liq_habitacion A,
										 tipos_liq_habitacion_clases_camas B,
										 tipos_liq_habitacion C
								WHERE A.tipo_liq_habitacion=B.tipo_liq_habitacion
											AND A.tipo_clase_cama_id=B.tipo_clase_cama_id
											AND B.tipo_liq_habitacion=C.tipo_liq_habitacion
											AND A.plan_id=".$_SESSION['ctrpla']['planeleg']."";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$i=0;
				while(!$resulta->EOF)
				{
						$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
						$i++;
				}
				return $var;
		}

		function ConsultarPlanesCargosExcedente()
		{
				GLOBAL $ADODB_FETCH_MODE;
				list($dbconn) = GetDBconn();
				$query ="SELECT a.cargo_cups,
								a.tarifario_id,
								a.cargo,
								b.descripcion as descarcups,
								c.descripcion as descar,
								d.descripcion as destari
								FROM planes_cargos_excedente_habitaciones a,
										cups b, tarifarios_detalle c, tarifarios d
								WHERE a.plan_id=".$_SESSION['ctrpla']['planeleg']."
											AND a.cargo_cups=b.cargo AND a.cargo=c.cargo
											AND a.tarifario_id=c.tarifario_id
											AND a.tarifario_id=d.tarifario_id;";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				list($var[cargo_cups],$var[tarifario_id],$var[cargo],$var[descarcups],$var[descar],$var[destari])=$resulta->fetchRow();
				return $var;
		}

		function GuardarLiquidacionHabitaciones()
		{
				$datosliq=$_SESSION['DATOSLIQUIDACION'];
				UNSET($_SESSION['DATOSLIQUIDACION']);
				$insert=false;
				//print_r($_SESSION['contra']['tarifa']); 
				list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();
		//GUARDAR PARAMETROS planes_cargos_excedente_habitaciones
				if ($_REQUEST['cargocupsplan']<>-1 
						AND !empty($_REQUEST['tarifariosplan_id'])
						AND !empty($_REQUEST['cargosplan_id'])
						AND $datosliq[cargo_cups]==NULL
						AND $datosliq[tarifario_id]==NULL
						AND $datosliq[cargo]==NULL)
					{
						$query ="INSERT INTO planes_cargos_excedente_habitaciones
							(
								plan_id,
								cargo_cups,
								tarifario_id,
								cargo
							)
							VALUES
							(
							".$_SESSION['ctrpla']['planeleg'].",
							'".$_REQUEST['cargocupsplan']."',
							'".$_REQUEST['tarifariosplan_id']."',
							'".$_REQUEST['cargosplan_id']."'
							);"; 
						$resulta = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->uno=1;
							$this->frmError["MensajeError"]="ERROR AL GUARDAR planes_cargos_excedente_habitaciones ".$dbconn->ErrorMsg();
							$dbconn->RollBackTrans();
							$this->ParametrizacionHabitaciones();
							return true;
						}
					}
					else
				if ($_REQUEST['cargocupsplan']<>-1 
						AND !empty($_REQUEST['tarifariosplan_id'])
						AND !empty($_REQUEST['cargosplan_id'])
						AND $datosliq[cargo_cups]<>NULL
						AND $datosliq[tarifario_id]<>NULL
						AND $datosliq[cargo]<>NULL
						AND ($datosliq[cargo_cups]<>$_REQUEST['cargocupsplan']
								OR $datosliq[tarifario_id]<>$_REQUEST['tarifariosplan_id']
								OR $datosliq[cargo]<>$_REQUEST['cargosplan_id']))
					{
						$query ="UPDATE planes_cargos_excedente_habitaciones
										SET cargo_cups='".$_REQUEST['cargocupsplan']."',
												tarifario_id='".$_REQUEST['tarifariosplan_id']."',
												cargo='".$_REQUEST['cargosplan_id']."'
										WHERE plan_id=".$_SESSION['ctrpla']['planeleg'].";"; 
						$resulta = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->uno=1;
							$this->frmError["MensajeError"]="ERROR AL GUARDAR planes_cargos_excedente_habitaciones ".$dbconn->ErrorMsg();
							$dbconn->RollBackTrans();
							$this->ParametrizacionHabitaciones();
							return true;
						}
					}
		//FIN GUARDAR PARAMETROS planes_cargos_excedente_habitaciones
				$ciclo=sizeof($_SESSION['contra']['tarifa']);
				$ciclo2=sizeof($_SESSION['contra']['liquidacion']);
				//echo $ciclo2; exit;
				for($i=0;($i<$ciclo);$i++)
				{
						$var=explode(',',$_POST['tipocama'.$i]);
						if($ciclo2>0)
						{
							for($k=0;$k<$ciclo2;$k++)
							{
									if ($_POST['tipocama'.$i]<>-1)
									{
										$query ="	SELECT tipo_liq_habitacion
															FROM planes_tipos_liq_habitacion
															WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
																AND tipo_clase_cama_id=".$var[0].";";
										$result = $dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0)
										{
											$this->uno=1;
											$this->frmError["MensajeError"]="ERROR AL SELECCIONAR LIQUIDACIÓN ".$dbconn->ErrorMsg();
											$dbconn->RollBackTrans();
											$this->ParametrizacionHabitaciones();
											return true;
										}
										if (empty($result->fields[0]))
											$insert=true; else $insert=false;
										if($var[1]<>$_SESSION['contra']['liquidacion'][$k]['tipo_liq_habitacion']
											//AND $_SESSION['contra']['tarifa'][$i][tipo_clase_cama_id]==$_SESSION['contra']['liquidacion'][$k]['tipo_clase_cama_id']
											//AND $_POST['tipocama'.$i]<>-1
											AND $_SESSION['contra']['liquidacion'][$k]['tipo_liq_habitacion']<>NULL
											AND !$insert
											)
											{
												$query ="UPDATE planes_tipos_liq_habitacion SET
																				tipo_liq_habitacion='".$var[1]."'
																WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
																AND tipo_clase_cama_id=".$_SESSION['contra']['tarifa'][$i]['tipo_clase_cama_id'].";";
												$resulta = $dbconn->Execute($query);
												if ($dbconn->ErrorNo() != 0)
												{
													$this->uno=1;
													$this->frmError["MensajeError"]="ERROR AL ACTUALIZAR LIQUIDACIÓN ".$dbconn->ErrorMsg();
													$dbconn->RollBackTrans();
													$this->ParametrizacionHabitaciones();
													return true;
												}
											}
											else
											{
												if ($insert)
													{
														$query ="INSERT INTO planes_tipos_liq_habitacion
															(
																tipo_clase_cama_id,
																tipo_liq_habitacion,
																plan_id
															)
															VALUES
															(
															".$var[0].",
															'".$var[1]."',
															".$_SESSION['ctrpla']['planeleg']."
															);"; 
														$resulta = $dbconn->Execute($query);
														if ($dbconn->ErrorNo() != 0)
														{
															$this->uno=1;
															$this->frmError["MensajeError"]="ERROR AL GUARDAR LIQUIDACIÓN ".$dbconn->ErrorMsg();
															$dbconn->RollBackTrans();
															$this->ParametrizacionHabitaciones();
															return true;
														}
													}
											}
								}
								else
								if ($_POST['tipocama'.$i]==-1
										AND $_SESSION['contra']['liquidacion'][$k]['tipo_clase_cama_id']==$_SESSION['contra']['tarifa'][$i][tipo_clase_cama_id])
								{
											$query ="	DELETE
																				FROM planes_tipos_liq_habitacion
																				WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
																					AND tipo_clase_cama_id=".$_SESSION['contra']['liquidacion'][$k]['tipo_clase_cama_id'].";"; 
											$result = $dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
												$this->uno=1;
												$this->frmError["MensajeError"]="ERROR AL ELIMINAR LIQUIDACIÓN ".$dbconn->ErrorMsg();
												$dbconn->RollBackTrans();
												$this->ParametrizacionHabitaciones();
												return true;
											}
								}

						}
			}
			else
			if ($_POST['tipocama'.$i]<>-1)
			{
			$query ="INSERT INTO planes_tipos_liq_habitacion
						(
							tipo_clase_cama_id,
							tipo_liq_habitacion,
							plan_id
						)
						VALUES
						(
						".$var[0].",
						'".$var[1]."',
						".$_SESSION['ctrpla']['planeleg']."
						);"; 
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->uno=1;
						$this->frmError["MensajeError"]="ERROR AL GUARDAR LIQUIDACIÓN ".$dbconn->ErrorMsg();
						$dbconn->RollBackTrans();
						$this->ParametrizacionHabitaciones();
						return true;
					}
			}
		}//FIN FOR
			$dbconn->CommitTrans();
			$this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
			$this->uno=1;
			$this->ParametrizacionHabitaciones();
			return true;

		}

		function ValidarParametrosHabitaciones()
		{
				$tmp=false;
				list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();
				$ciclo=sizeof($_SESSION['ctrpl1']['grupoincoc']);
				for($i=0;($i<$ciclo);$i++)
				{ 
						$g1=0;
						//MODIFICACIÓN PARA CALCULAR LOS PORCENTAJES CUANDO SE DIGITA UN VOLOR
						//Y NO DIRECTAMENTE EL PORCENTAJE
						if(is_numeric($_POST['porexctra'.$i])==1 AND $_POST['radioporexctra'.$i]==1)
						{
								$por1=$_POST['porexctra'.$i];
							if($por1 <= 999.9999 AND $por1 >= -999.9999)
							{
								$g1=1;
								//NUEVO
								$_POST['porexctra'.$i]=0.00;
							}
							else
							{
								$por1=0.00;
								//$_POST['porexctra'.$i]=0.00;
							}
						}
						else
						//FIN MODIFICACIÓN
						if(is_numeric($_POST['porexctra'.$i])==1 AND $_POST['radioporexctra'.$i]==0)
						{ 
							$por1=doubleval($_POST['porexctra'.$i]);
							if($por1 <= 999.9999 AND $por1 >= -999.9999)
							{
								$g1=1;
								$por1=0.00;
							}
							else
							{
								$por1=0.00;
							}
						}
						else
						{	
							//SI PORCENTAJE ES VACIO
							$por1=0.00;
						}
					//$_POST['porexctra'.$i]=0.0;
					if ($_POST['excedente'.$i]==NULL)
						$_POST['excedente'.$i]=0.0;
					$ciclo2=sizeof($_SESSION['ctrpl1']['paramhab']);
				if($ciclo2<>0)
				{
						for($k=0;$k<$ciclo2;$k++)
						{
							if($_SESSION['ctrpl1']['grupoincoc'][$i]['tipo_cama_id']==$_SESSION['ctrpl1']['paramhab'][$k]['tipo_cama_id'])
								{
/*										if(empty($_POST['porexctra'.$i]))
										{
											$this->uno=1;
											$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS - PORCE. // VALOR";
											$this->ParametrizacionHabitaciones();
											return true;
										}*/
												if ($_POST['porexctra'.$i]==0 OR $_POST['porexctra'.$i]=='')
												{
													$query ="SELECT precio
																		FROM tarifarios_detalle
														WHERE tarifario_id='".$_POST['tarifario'.$i.'_id']."' AND
																cargo='".$_POST['cargo'.$i.'_id']."';";
													$resulta = $dbconn->Execute($query);
													$_POST['porexctra'.$i]=$resulta->fields[0];
												}
													//$_POST['porexctra'.$i]=$_SESSION['ctrpl1']['paramhab'][$k]['precio'];
											if($_POST["rcobroexce".$i.""]==0)
											{
												if(!empty($_POST['tarifarioexcedente'.$i.'_id']) 
														AND !empty($_POST['cargoexcedente'.$i.'_id']))
												{
													$campo="tarifario_excedente='".$_POST['tarifarioexcedente'.$i.'_id']."' ,
													cargo_excedente='".$_POST['cargoexcedente'.$i.'_id']."' ,";
													$_POST['excedente'.$i]=0.0;
												}
												else
												{
													$campo=" ";
												}
											}
											if($_POST["rcobroexce".$i.""]==1)
											{
													$campo="tarifario_excedente=NULL ,
													cargo_excedente=NULL ,";
											}
										if(empty($_POST['porcentajexcedente'.$i]))
												$_POST['porcentajexcedente'.$i]=0;
												$query ="UPDATE planes_tipos_camas
													SET cargo_cups='".$_POST['cargocups'.$i]."',
															tarifario_id='".$_POST['tarifario'.$i.'_id']."',
															cargo='".$_POST['cargo'.$i.'_id']."',
															porcentaje=$por1,
															valor_lista=".$_POST['porexctra'.$i].",
															valor_excedente=".$_POST['excedente'.$i].",
															$campo
															porcentaje_excedente=".$_POST['porcentajexcedente'.$i]."
													WHERE tipo_cama_id=".$_SESSION['ctrpl1']['grupoincoc'][$i]['tipo_cama_id']."
															AND empresa_id='".$_SESSION['contra']['empresa']."'
															AND plan_id= ".$_SESSION['ctrpla']['planeleg'].";"; 
									$resulta = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0)
									{
		/*								$this->error = "ERROR AL GUARDAR LOS DATOS DEL MODULO";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();*/
										$this->uno=1;
										$this->frmError["MensajeError"]="ERROR AL ACTUALIZAR ".$dbconn->ErrorMsg();
										$dbconn->RollBackTrans();
										$this->ParametrizacionHabitaciones();
										return true;
									}

								}
								else
								{
										$query ="SELECT tipo_cama_id
														FROM planes_tipos_camas
														WHERE tipo_cama_id=".$_SESSION['ctrpl1']['grupoincoc'][$i]['tipo_cama_id']."
																	AND empresa_id='".$_SESSION['contra']['empresa']."'
																	AND plan_id=".$_SESSION['ctrpla']['planeleg']."";
										$resulta = $dbconn->Execute($query); 

										$tipocama=$resulta->fields[0];
								if ($_POST['cargocups'.$i]<>NULL AND $_POST['tarifario'.$i.'_id']<>NULL AND $_POST['cargo'.$i.'_id']<>NULL
										AND !($_SESSION['ctrpl1']['grupoincoc'][$i]['tipo_cama_id']==$tipocama))
										{
											if ($_POST['porexctra'.$i]==0 OR $_POST['porexctra'.$i]=='')
											{
												 $query ="SELECT precio
																	FROM tarifarios_detalle
													WHERE tarifario_id='".$_POST['tarifario'.$i.'_id']."' AND
															cargo='".$_POST['cargo'.$i.'_id']."';";
												$resulta = $dbconn->Execute($query);
												$_POST['porexctra'.$i]=$resulta->fields[0];
											}
											if($_POST["rcobroexce".$i.""]==0)
											{
												if(!empty($_POST['tarifarioexcedente'.$i.'_id']) 
														AND !empty($_POST['cargoexcedente'.$i.'_id']))
												{
													$campos="tarifario_excedente,
															cargo_excedente,";
													$valores="'".$_POST['tarifarioexcedente'.$i.'_id']."',
																		'".$_POST['cargoexcedente'.$i.'_id']."',";
												}
												else
												{
													$campos=" ";
													$valores=" ";
												}
											}
											if($_POST["rcobroexce".$i.""]==1)
											{
												$campos=" ";
												$valores=" ";
											}
										if(empty($_POST['porcentajexcedente'.$i]))
											$_POST['porcentajexcedente'.$i]=0;
										$query ="INSERT INTO planes_tipos_camas
											(
											tipo_cama_id,
											empresa_id,
											plan_id,
											cargo_cups,
											tarifario_id,
											cargo,
											porcentaje,
											valor_lista,
											valor_excedente,
											$campos
											porcentaje_excedente
											)
											VALUES
											(
											".$_SESSION['ctrpl1']['grupoincoc'][$i]['tipo_cama_id'].",
											'".$_SESSION['contra']['empresa']."',
											".$_SESSION['ctrpla']['planeleg'].",
											'".$_POST['cargocups'.$i]."',
											'".$_POST['tarifario'.$i.'_id']."',
											'".$_POST['cargo'.$i.'_id']."',
											$por1,
											".$_POST['porexctra'.$i].",
											".$_POST['excedente'.$i].",
											$valores
											".$_POST['porcentajexcedente'.$i]."
											);";
										$resulta = $dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0)
										{
			/*								$this->error = "ERROR AL GUARDAR LOS DATOS DEL MODULO";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();*/
											$this->uno=1;
											$this->frmError["MensajeError"]="ERROR AL GUARDAR ".$dbconn->ErrorMsg();
											$dbconn->RollBackTrans();
											$this->ParametrizacionHabitaciones();
											return true;
										}
								}
							}
								$tmp=true;
						}
				}
				else
				if ($_POST['cargocups'.$i]<>NULL AND $_POST['tarifario'.$i.'_id']<>NULL
						 AND $_POST['cargo'.$i.'_id']<>NULL)
				{
					//if ($_POST['porexctra'.$i]==0)
						//$_POST['porexctra'.$i]=$_POST['valorlista'.$i];
							if ($_POST['porexctra'.$i]==0 OR $_POST['porexctra'.$i]=='')
							{
								 $query ="SELECT precio
													FROM tarifarios_detalle
									WHERE tarifario_id='".$_POST['tarifario'.$i.'_id']."' AND
											cargo='".$_POST['cargo'.$i.'_id']."';";
								$resulta = $dbconn->Execute($query);
								$_POST['porexctra'.$i]=$resulta->fields[0];
							}
							if($_POST["rcobroexce".$i.""]==0)
							{
								if(!empty($_POST['tarifarioexcedente'.$i.'_id']) 
										AND !empty($_POST['cargoexcedente'.$i.'_id']))
								{
									$campos="tarifario_excedente,
											cargo_excedente,";
									$valores="'".$_POST['tarifarioexcedente'.$i.'_id']."',
														'".$_POST['cargoexcedente'.$i.'_id']."',";
								}
								else
								{
									$campos=" ";
									$valores=" ";
								}
							}
							if($_POST["rcobroexce".$i.""]==1)
							{
								$campos=" ";
								$valores=" ";
							}

							if(empty($_POST['porcentajexcedente'.$i]))
								$_POST['porcentajexcedente'.$i]=0;
							$query ="INSERT INTO planes_tipos_camas
							(
							tipo_cama_id,
							empresa_id,
							plan_id,
							cargo_cups,
							tarifario_id,
							cargo,
							porcentaje,
							valor_lista,
							valor_excedente,
							$campos
							porcentaje_excedente
							)
							VALUES
							(
							".$_SESSION['ctrpl1']['grupoincoc'][$i]['tipo_cama_id'].",
							'".$_SESSION['contra']['empresa']."',
							".$_SESSION['ctrpla']['planeleg'].",
							'".$_POST['cargocups'.$i]."',
							'".$_POST['tarifario'.$i.'_id']."',
							'".$_POST['cargo'.$i.'_id']."',
							$por1,
							".$_POST['porexctra'.$i].",
							".$_POST['excedente'.$i].",
							$valores
							".$_POST['porcentajexcedente'.$i]."
							);";
						$resulta = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->uno=1;
							$this->frmError["MensajeError"]="ERROR AL GUARDAR ".$dbconn->ErrorMsg();
							$dbconn->RollBackTrans();
							$this->ParametrizacionHabitaciones();
							return true;
						}
					$tmp=true;
				}
			}
				if ($tmp)
				{
					$dbconn->CommitTrans();
					$this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
				}
				else
				{
					$this->frmError["MensajeError"]="DATOS SIN GUARDAR" . $dbconn->ErrorMsg();
				}
					$this->uno=1;
					$this->ParametrizacionHabitaciones();
					return true;
		}

// 		function ValidarParametrosHabitaciones()
// 		{
// 				$tmp=false;
// 				list($dbconn) = GetDBconn();
// 				$dbconn->BeginTrans();
// 				$ciclo=sizeof($_SESSION['ctrpl1']['grupoincoc']);
// 				for($i=0;($i<$ciclo);$i++)
// 				{
// 						$g1=0;
// 						//MODIFICACIÓN PARA CALCULAR LOS PORCENTAJES CUANDO SE DIGITA UN VOLOR
// 						//Y NO DIRECTAMENTE EL PORCENTAJE
// 						if(is_numeric($_POST['porexctra'.$i])==1 AND $_POST['radioporexctra'.$i]==1)
// 						{
// 								//echo $_POST['porexctra'.$i].'--'.$_SESSION['ctrpl1']['grupoincoc'][$i]['precio_lista']; exit;
// 									echo $_SESSION['ctrpl1']['grupoincoc'][$i]['precio_lista'].'--'.$_POST['porexctra'.$i]; 
// 								//$this->CalcularPorcentaje($_SESSION['ctrpl1']['grupoincoc'][$i]['precio_lista'],$_POST['porexctra'.$i]);
// 								//$por1=doubleval($this->valor);
// 								$por1=$_POST['porexctra'.$i];
// 							if($por1 <= 999.9999 AND $por1 >= -999.9999)
// 							{
// 								$g1=1;
// 								//NUEVO
// 								$_POST['porexctra'.$i]=0.00;
// 							}
// 							else
// 							{
// 								$por1=0.00;
// 								//$_POST['porexctra'.$i]=0.00;
// 							}
// 						}
// 						else
// 						//FIN MODIFICACIÓN
// 						if(is_numeric($_POST['porexctra'.$i])==1 AND $_POST['radioporexctra'.$i]==0)
// 						{ 
// 							$por1=doubleval($_POST['porexctra'.$i]);
// 							if($por1 <= 999.9999 AND $por1 >= -999.9999)
// 							{
// 								$g1=1;
// 								$por1=0.00;
// 							}
// 							else
// 							{
// 								$por1=0.00;
// 							}
// 						}
// 						else
// 						{	
// 							//SI PORCENTAJE ES VACIO
// 							$por1=0.00;
// 						}
// 					//$_POST['porexctra'.$i]=0.0;
// 					if ($_POST['excedente'.$i]==NULL)
// 						$_POST['excedente'.$i]=0.0;
// 					$ciclo2=sizeof($_SESSION['ctrpl1']['paramhab']);
// 				if($ciclo2<>0)
// 				{
// 						for($k=0;$k<$ciclo2;$k++)
// 						{
// 							if(
// 									$_SESSION['ctrpl1']['grupoincoc'][$i]['tipo_cama_id']<>$_SESSION['ctrpl1']['paramhab'][$k]['tipo_cama_id']
// 									AND $_POST['tarifario'.$i.'_id']<>NULL AND $_POST['cargo'.$i.'_id']<>NULL
// 								)
// 								{echo $_SESSION['ctrpl1']['paramhab'][$k]['tipo_cama_id'];
// 								if ($_SESSION['ctrpl1']['grupoincoc'][$i]['tipo_cama_id']<>$_SESSION['ctrpl1']['paramhab'][$k]['tipo_cama_id']
// 									AND $_SESSION['ctrpl1']['paramhab'][$k]['tipo_cama_id']==NULL)
// 								{
// 								//$_POST['preciolista'.$i]
// 							echo	$query ="INSERT INTO planes_tipos_camas
// 												(
// 												tipo_cama_id,
// 												empresa_id,
// 												plan_id,
// 												cargo_cups,
// 												tarifario_id,
// 												cargo,
// 												porcentaje,
// 												valor_lista,
// 												valor_excedente
// 												)
// 												VALUES
// 												(
// 												".$_SESSION['ctrpl1']['grupoincoc'][$i]['tipo_cama_id'].",
// 												'".$_SESSION['contra']['empresa']."',
// 												".$_SESSION['ctrpla']['planeleg'].",
// 												'".$_POST['cargocups'.$i]."',
// 												'".$_POST['tarifario'.$i.'_id']."',
// 												'".$_POST['cargo'.$i.'_id']."',
// 												$por1,
// 												".$_POST['porexctra'.$i].",
// 												".$_POST['excedente'.$i]."
// 												);"; exit;
// 										$resulta = $dbconn->Execute($query);
// 										if ($dbconn->ErrorNo() != 0)
// 										{
// 											$this->uno=1;
// 											$this->frmError["MensajeError"]="ERROR AL GUARDAR ".$dbconn->ErrorMsg();
// 											$dbconn->RollBackTrans();
// 											$this->ParametrizacionHabitaciones();
// 											return true;
// 										}
// 								}
// 								else
// 							//if ($_SESSION['ctrpl1']['paramhab'][$k]['cargo']<>$_POST['cargo'.$i.'_id'])
// 								{ 
// 						echo		$query ="UPDATE planes_tipos_camas
// 												SET cargo_cups='".$_POST['cargocups'.$i]."',
// 														tarifario_id='".$_POST['tarifario'.$i.'_id']."',
// 														cargo='".$_POST['cargo'.$i.'_id']."',
// 														porcentaje=$por1,
// 														valor_lista=".$_POST['porexctra'.$i].",
// 														valor_excedente=".$_POST['excedente'.$i]."
// 												WHERE tipo_cama_id=".$_SESSION['ctrpl1']['grupoincoc'][$i]['tipo_cama_id']."
// 														AND empresa_id='".$_SESSION['contra']['empresa']."'
// 														AND plan_id= ".$_SESSION['ctrpla']['planeleg'].";";
// 								$resulta = $dbconn->Execute($query);
// 								if ($dbconn->ErrorNo() != 0)
// 								{
// 	/*								$this->error = "ERROR AL GUARDAR LOS DATOS DEL MODULO";
// 									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();*/
// 									$this->uno=1;
// 									$this->frmError["MensajeError"]="ERROR AL ACTUALIZAR ".$dbconn->ErrorMsg();
// 									$dbconn->RollBackTrans();
// 									$this->ParametrizacionHabitaciones();
// 									return true;
// 								}
// 							}
// 						$tmp=true;
// 							}
// 						}//FIN FOR
// 			}
// 			else
// 			{ //echo 'tarifario->'.$_POST['tarifario'.$i.'_id'].'cargo->'.$_POST['cargo'.$i.'_id'].'>>';
// /*				if ($_POST['excedente'.$i]==NULL)
// 					$_POST['excedente'.$i]=0.0;*/
// 				if ($_POST['tarifario'.$i.'_id']<>NULL AND $_POST['cargo'.$i.'_id']<>NULL)
// 				{
// 					//$_POST['preciolista'.$i]
// 					$query ="INSERT INTO planes_tipos_camas
// 								(
// 								tipo_cama_id,
// 								empresa_id,
// 								plan_id,
// 								cargo_cups,
// 								tarifario_id,
// 								cargo,
// 								porcentaje,
// 								valor_lista,
// 								valor_excedente
// 								)
// 								VALUES
// 								(
// 								".$_SESSION['ctrpl1']['grupoincoc'][$i]['tipo_cama_id'].",
// 								'".$_SESSION['contra']['empresa']."',
// 								".$_SESSION['ctrpla']['planeleg'].",
// 								'".$_POST['cargocups'.$i]."',
// 								'".$_POST['tarifario'.$i.'_id']."',
// 								'".$_POST['cargo'.$i.'_id']."',
// 								$por1,
// 								".$_SESSION['ctrpl1']['grupoincoc'][$i]['precio_lista'].",
// 								".$_POST['excedente'.$i]."
// 								);";
// 							$resulta = $dbconn->Execute($query);
// 							if ($dbconn->ErrorNo() != 0)
// 							{
// /*								$this->error = "ERROR AL GUARDAR LOS DATOS DEL MODULO";
// 								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();*/
// 								$this->uno=1;
// 								$this->frmError["MensajeError"]="ERROR AL GUARDAR ".$dbconn->ErrorMsg();
// 								$dbconn->RollBackTrans();
// 								$this->ParametrizacionHabitaciones();
// 								return true;
// 							}
// 
// 				}
// 						$tmp=true;
// 			}
// 				}//FIN FOR 1
// 				if ($tmp)
// 				{
// 					$dbconn->CommitTrans();
// 					$this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
// 				}
// 				else
// 				{
// 					$this->frmError["MensajeError"]="DATOS SIN GUARDAR" . $dbconn->ErrorMsg();
// 				}
// 					$this->uno=1;
// 					$this->ParametrizacionHabitaciones();
// 					return true;
// 		}

    /********************FUNCIONES DE LA OPCIÓN INVENTARIOS COPAGOS********************/
		function BuscarGruposCopaInveContra($empresa,$plan,$servicio)//Busca los grupos de contratación del inventario y el plan tarifario de copagos
		{
				list($dbconn) = GetDBconn();
				$query ="SELECT A.grupo_contratacion_id,
								A.descripcion AS des1,
								D.porcentaje,
								D.por_cobertura,
								D.porcentaje_nopos_autorizado,
								D.sw_descuento,
								D.sw_copago,
								D.sw_cuota_moderadora
								FROM inv_grupos_contrataciones AS A
								LEFT JOIN plan_tarifario_inv_copagos AS D ON
								(
										D.empresa_id='".$empresa."'
										AND D.plan_id=".$plan."
										AND D.servicio='".$servicio."'
										AND D.grupo_contratacion_id=A.grupo_contratacion_id
								)
								WHERE A.grupo_contratacion_id<>'0'
								ORDER BY des1;";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$i=0;
				while(!$resulta->EOF)
				{
						$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
						$i++;
				}
				return $var;
		}

		function ValidarTarifarioCopaInveContra()//Válida y guarda del inventario lo que es incluido en el plan
		{
				list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();
				for($i=0;$i<sizeof($_SESSION['ctrpl1']['grupoincoc']);$i++)
				{
						$g1=$g2=$g3=$g4=0;
						if(is_numeric($_POST['porinvctra'.$i])==1)
						{
								$por1=doubleval($_POST['porinvctra'.$i]);
								if($por1 <= 999.9999)
								{
										$g1=1;
								}
						}
						if(is_numeric($_POST['cobinvctra'.$i])==1)
						{
								$cob1=doubleval($_POST['cobinvctra'.$i]);
								if($cob1 <= 100)//999.9999
								{
										$g2=1;
								}
						}
						if(is_numeric($_POST['posinvctra'.$i])==1)
						{
								$pos1=doubleval($_POST['posinvctra'.$i]);
								if($pos1 <= 100)//999.9999
								{
										$g4=1;
								}
						}
						if($_POST['cuotas'.$i]<>NULL)
						{
								if($_POST['cuotas'.$i]==1)
								{
										$copago=1;
										$cuotam=0;
								}
								else if($_POST['cuotas'.$i]==2)
								{
										$copago=0;
										$cuotam=1;
								}
								else if($_POST['cuotas'.$i]==3)
								{
										$copago=0;
										$cuotam=0;
								}
								$g3=1;
						}
						if($g1==1||$g2==1)
						{
								if($g1==0)
								{
										$por1=0.00;
								}
								if($g2==0)
								{
										$cob1=0.00;
								}
								if($g4==0)
								{
										$pos1=0.00;
								}
								if($g3==0)
								{
										$copago=0;
										$cuotam=0;
								}
								if($_POST['desinvctra'.$i]==NULL)
								{
										$_POST['desinvctra'.$i]=0;
								}
								if(($_SESSION['ctrpl1']['grupoincoc'][$i]['porcentaje']<>NULL)
								AND ($por1<>$_SESSION['ctrpl1']['grupoincoc'][$i]['porcentaje']
								OR $cob1<>$_SESSION['ctrpl1']['grupoincoc'][$i]['por_cobertura']
								OR $pos1<>$_SESSION['ctrpl1']['grupoincoc'][$i]['porcentaje_nopos_autorizado']
								OR $copago<>$_SESSION['ctrpl1']['grupoincoc'][$i]['sw_copago']
								OR $cuotam<>$_SESSION['ctrpl1']['grupoincoc'][$i]['sw_cuota_moderadora']
								OR $_POST['desinvctra'.$i]<>$_SESSION['ctrpl1']['grupoincoc'][$i]['sw_descuento']))
								{
										$query ="DELETE FROM plan_tarifario_inv_copagos
														WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
														AND empresa_id='".$_SESSION['contra']['empresa']."'
														AND servicio='".$_SESSION['ctrpl1']['serinvcopc']."'
														AND grupo_contratacion_id='".$_SESSION['ctrpl1']['grupoincoc'][$i]['grupo_contratacion_id']."';";
										$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0)
										{
												$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$dbconn->RollBackTrans();
												return false;
										}
								}
								if($por1<>$_SESSION['ctrpl1']['grupoincoc'][$i]['porcentaje']
								OR $cob1<>$_SESSION['ctrpl1']['grupoincoc'][$i]['por_cobertura']
								OR $pos1<>$_SESSION['ctrpl1']['grupoincoc'][$i]['porcentaje_nopos_autorizado']
								OR $copago<>$_SESSION['ctrpl1']['grupoincoc'][$i]['sw_copago']
								OR $cuotam<>$_SESSION['ctrpl1']['grupoincoc'][$i]['sw_cuota_moderadora']
								OR $_POST['desinvctra'.$i]<>$_SESSION['ctrpl1']['grupoincoc'][$i]['sw_descuento'])
								{
										$query ="INSERT INTO plan_tarifario_inv_copagos
														(plan_id,
														empresa_id,
														grupo_contratacion_id,
														servicio,
														porcentaje,
														por_cobertura,
														porcentaje_nopos_autorizado,
														sw_descuento,
														sw_copago,
														sw_cuota_moderadora)
														VALUES
														(".$_SESSION['ctrpla']['planeleg'].",
														'".$_SESSION['contra']['empresa']."',
														'".$_SESSION['ctrpl1']['grupoincoc'][$i]['grupo_contratacion_id']."',
														'".$_SESSION['ctrpl1']['serinvcopc']."',
														".$por1.",
														".$cob1.",
														".$pos1.",
														'".$_POST['desinvctra'.$i]."',
														'".$copago."',
														'".$cuotam."');";
										$resulta = $dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0)
										{
												$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$dbconn->RollBackTrans();
												return false;
										}
								}
						}
						else
						{
								if($_POST['porinvctra'.$i]==NULL
								AND $_POST['cobinvctra'.$i]==NULL
								AND $_POST['posinvctra'.$i]==NULL
								AND $_SESSION['ctrpl1']['grupoincoc'][$i]['porcentaje']<>NULL)
								{
										$query ="DELETE FROM plan_tarifario_inv_copagos
														WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
														AND empresa_id='".$_SESSION['contra']['empresa']."'
														AND servicio='".$_SESSION['ctrpl1']['serinvcopc']."'
														AND grupo_contratacion_id='".$_SESSION['ctrpl1']['grupoincoc'][$i]['grupo_contratacion_id']."';";
										$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0)
										{
												$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$dbconn->RollBackTrans();
												return false;
										}
								}
						}
						$_POST['porinvctra'.$i]='';
						$_POST['cobinvctra'.$i]='';
						$_POST['posinvctra'.$i]='';
						$_POST['desinvctra'.$i]='';
						$_POST['cuotas'.$i]='';
				}
				$dbconn->CommitTrans();
				$this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
				$this->uno=1;
				$this->TarifarioCopaInveContra();
				return true;
		}

    function BuscarTariCopaInveContra($plan,$empresa,$servicio,$grupo)//Busca los productos al nivel del detalle para la excepción
    {
        list($dbconn) = GetDBconn();
        if($_REQUEST['codigoctra'])
        {
            $codigo=$_REQUEST['codigoctra'];
            $busqueda="AND A.codigo_producto LIKE '$codigo%'";
        }
        else
        {
            $busqueda='';
        }
        if($_REQUEST['descrictra'])
        {
            $codigo=STRTOUPPER($_REQUEST['descrictra']);
            $busqueda2="AND UPPER(B.descripcion) LIKE '%$codigo%'";
        }
        else
        {
            $busqueda2='';
        }
        if(empty($_REQUEST['conteo']))
        {
            $query ="SELECT count(*) FROM (
                    (
                    SELECT A.codigo_producto,
                    B.descripcion,
                    A.costo,
                    A.costo_ultima_compra,
                    A.precio_venta,
                    0 AS excepcion,
                    C.porcentaje,
                    C.por_cobertura,
                    C.porcentaje_nopos_autorizado,
                    C.sw_descuento,
                    C.sw_copago,
                    C.sw_cuota_moderadora
                    FROM inventarios AS A,
                    inventarios_productos AS B,
                    plan_tarifario_inv_copagos AS C
                    WHERE C.plan_id = ".$plan."
                    AND C.empresa_id = '".$empresa."'
                    AND C.servicio='".$servicio."'
                    AND C.grupo_contratacion_id = '".$grupo."'
                    AND C.empresa_id = A.empresa_id
                    AND C.grupo_contratacion_id = A.grupo_contratacion_id
                    AND B.codigo_producto=A.codigo_producto
                    AND excepciones_inventarios_copagos
                    (C.plan_id, A.codigo_producto, C.servicio)=0
                    $busqueda
                    $busqueda2
                    )
                    UNION
                    (
                    SELECT A.codigo_producto,
                    B.descripcion,
                    A.costo,
                    A.costo_ultima_compra,
                    A.precio_venta,
                    1 AS excepcion,
                    C.porcentaje,
                    C.por_cobertura,
                    C.porcentaje_nopos_autorizado,
                    C.sw_descuento,
                    C.sw_copago,
                    C.sw_cuota_moderadora
                    FROM inventarios AS A,
                    inventarios_productos AS B,
                    excepciones_inv_copagos AS C
                    WHERE C.plan_id = ".$plan."
                    AND C.empresa_id = '".$empresa."'
                    AND C.servicio='".$servicio."'
                    AND C.empresa_id = A.empresa_id
                    AND C.codigo_producto=B.codigo_producto
                    AND B.codigo_producto=A.codigo_producto
                    $busqueda
                    $busqueda2
                    )
                    ) AS r;";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            list($this->conteo)=$resulta->fetchRow();
        }
        else
        {
            $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of'])
        {
            $Of='0';
        }
        else
        {
            $Of=$_REQUEST['Of'];
            if($_REQUEST['Of'] > $this->conteo)
            {
                $Of='0';
                $_REQUEST['Of']='0';
                $_REQUEST['paso']='1';
            }
        }
        $query ="
                (
                SELECT A.codigo_producto,
                B.descripcion,
                A.costo,
                A.costo_ultima_compra,
                A.precio_venta,
                0 AS excepcion,
                C.porcentaje,
                C.por_cobertura,
                C.porcentaje_nopos_autorizado,
                C.sw_descuento,
                C.sw_copago,
                C.sw_cuota_moderadora
                FROM inventarios AS A,
                inventarios_productos AS B,
                plan_tarifario_inv_copagos AS C
                WHERE C.plan_id = ".$plan."
                AND C.empresa_id = '".$empresa."'
                AND C.servicio='".$servicio."'
                AND C.grupo_contratacion_id = '".$grupo."'
                AND C.empresa_id = A.empresa_id
                AND C.grupo_contratacion_id = A.grupo_contratacion_id
                AND B.codigo_producto=A.codigo_producto
                AND excepciones_inventarios_copagos
                (C.plan_id, A.codigo_producto, C.servicio)=0
                $busqueda
                $busqueda2
                )
                UNION
                (
                SELECT A.codigo_producto,
                B.descripcion,
                A.costo,
                A.costo_ultima_compra,
                A.precio_venta,
                1 AS excepcion,
                C.porcentaje,
                C.por_cobertura,
                C.porcentaje_nopos_autorizado,
                C.sw_descuento,
                C.sw_copago,
                C.sw_cuota_moderadora
                FROM inventarios AS A,
                inventarios_productos AS B,
                excepciones_inv_copagos AS C
                WHERE C.plan_id = ".$plan."
                AND C.empresa_id = '".$empresa."'
                AND C.servicio='".$servicio."'
                AND C.empresa_id = A.empresa_id
                AND C.codigo_producto=B.codigo_producto
                AND B.codigo_producto=A.codigo_producto
                $busqueda
                $busqueda2
                ORDER BY A.codigo_producto
                )
                LIMIT ".$this->limit." OFFSET $Of;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function ValidarTariExceCopaInveContra()//Válida y guarda los productos que sean excepción en los copagos, según su grupo de contratación
    {
         list($dbconn) = GetDBconn();
         $dbconn->BeginTrans();
        for($i=0;$i<sizeof($_SESSION['ctrpl1']['codigoicoc']);$i++)
        {
            if($_SESSION['ctrpl1']['codigoicoc'][$i]['excepcion']==1)
            {
                $query ="DELETE FROM excepciones_inv_copagos
                        WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                        AND empresa_id='".$_SESSION['contra']['empresa']."'
                        AND codigo_producto='".$_SESSION['ctrpl1']['codigoicoc'][$i]['codigo_producto']."'
                        AND servicio='".$_SESSION['ctrpl1']['serinvcopc']."';";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                    return false;
                }
            }
            $g1=$g2=$g3=$g4=0;
            if(is_numeric($_POST['porinvexc'.$i])==1)
            {
                $por1=doubleval($_POST['porinvexc'.$i]);
                if($por1 <= 999.9999)
                {
                    $g1=1;
                }
            }
            if(is_numeric($_POST['cobinvexc'.$i])==1)
            {
                $cob1=doubleval($_POST['cobinvexc'.$i]);
                if($cob1 <= 100)//999.9999
                {
                    $g2=1;
                }
            }
            if(is_numeric($_POST['posinvexc'.$i])==1)
            {
                $pos1=doubleval($_POST['posinvexc'.$i]);
                if($pos1 <= 100)//999.9999
                {
                    $g4=1;
                }
            }
            if($_POST['cuoinvexc'.$i]<>NULL)
            {
                if($_POST['cuoinvexc'.$i]==1)
                {
                    $copago=1;
                    $cuotam=0;
                }
                else if($_POST['cuoinvexc'.$i]==2)
                {
                    $copago=0;
                    $cuotam=1;
                }
                else if($_POST['cuoinvexc'.$i]==3)
                {
                    $copago=0;
                    $cuotam=0;
                }
                $g3=1;
            }
            if($g1==1||$g2==1)
            {
                if($g1==0)
                {
                    $por1=0.00;
                }
                if($g2==0)
                {
                    $cob1=0.00;
                }
                if($g4==0)
                {
                    $pos1=0.00;
                }
                if($g3==0)
                {
                    $copago=0;
                    $cuotam=0;
                }
                if($_POST['desinvexc'.$i]==NULL)
                {
                    $_POST['desinvexc'.$i]=0;
                }
                if(!($_SESSION['ctrpl1']['datcopinvc']['porcentaje']==$por1 AND
                $_SESSION['ctrpl1']['datcopinvc']['por_cobertura']==$cob1 AND
                $_SESSION['ctrpl1']['datcopinvc']['porcentaje_nopos_autorizado']==$pos1 AND
                $_SESSION['ctrpl1']['datcopinvc']['sw_descuento']==$_POST['desinvexc'.$i] AND
                $_SESSION['ctrpl1']['datcopinvc']['sw_copago']==$copago AND
                $_SESSION['ctrpl1']['datcopinvc']['sw_cuota_moderadora']==$cuotam))
                {
                    $query ="INSERT INTO excepciones_inv_copagos
                            (plan_id,
                            empresa_id,
                            codigo_producto,
                            servicio,
                            porcentaje,
                            por_cobertura,
                            porcentaje_nopos_autorizado,
                            sw_descuento,
                            sw_copago,
                            sw_cuota_moderadora)
                            VALUES
                            (".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['contra']['empresa']."',
                            '".$_SESSION['ctrpl1']['codigoicoc'][$i]['codigo_producto']."',
                            '".$_SESSION['ctrpl1']['serinvcopc']."',
                            ".$por1.",
                            ".$cob1.",
                            ".$pos1.",
                            '".$_POST['desinvexc'.$i]."',
                            '".$copago."',
                            '".$cuotam."');";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollBackTrans();
                        return false;
                    }
                }
            }
            $_POST['porinvexc'.$i]='';
            $_POST['cobinvexc'.$i]='';
            $_POST['posinvexc'.$i]='';
            $_POST['desinvexc'.$i]='';
            $_POST['cuoinvexc'.$i]='';
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
        $this->uno=1;
        $this->TariExceCopaInveContra();
        return true;
    }

    /********FUNCIONES DE LA OPCIÓN PARAGRAFADOS INSUMOS Y MEDICAMENTOS********/
    function BuscarParaTipoImdInveContra($empresa,$plan,$servicio,$departamento)//
    {
        list($dbconn) = GetDBconn();
        if($_REQUEST['codigoctra'])
        {
            $codigo=$_REQUEST['codigoctra'];
            $busqueda="AND A.codigo_producto LIKE '$codigo%'";
        }
        else
        {
            $busqueda='';
        }
        if($_REQUEST['descrictra'])
        {
            $codigo=STRTOUPPER($_REQUEST['descrictra']);
            $busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
        }
        else
        {
            $busqueda2='';
        }
        if(empty($_REQUEST['conteo']))
        {
            $query ="SELECT count(*) FROM (
                    (
                    SELECT A.codigo_producto,
                    A.descripcion,
                    paragrafados_medicamentos(".$plan.", '".$servicio."', '".$departamento."', A.codigo_producto) AS paragrafado
                    FROM inventarios_productos AS A,
                    inventarios AS B,
                    inv_grupos_contrataciones AS C
                    WHERE B.empresa_id='".$empresa."'
                    AND B.codigo_producto=A.codigo_producto
                    AND C.grupo_contratacion_id<>'0'
                    AND C.grupo_contratacion_id=B.grupo_contratacion_id
                    $busqueda
                    $busqueda2
                    )
                    ) AS r;";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            list($this->conteo)=$resulta->fetchRow();
        }
        else
        {
            $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of'])
        {
            $Of='0';
        }
        else
        {
            $Of=$_REQUEST['Of'];
            if($_REQUEST['Of'] > $this->conteo)
            {
                $Of='0';
                $_REQUEST['Of']='0';
                $_REQUEST['paso']='1';
            }
        }
       $query ="
                (
                SELECT A.codigo_producto,
                A.descripcion,
                paragrafados_medicamentos(".$plan.", '".$servicio."', '".$departamento."', A.codigo_producto) AS paragrafado
                FROM inventarios_productos AS A,
                inventarios AS B,
                inv_grupos_contrataciones AS C
                WHERE B.empresa_id='".$empresa."'
                AND B.codigo_producto=A.codigo_producto
                AND C.grupo_contratacion_id<>'0'
                AND C.grupo_contratacion_id=B.grupo_contratacion_id
                $busqueda
                $busqueda2
                ORDER BY A.codigo_producto
                )
                LIMIT ".$this->limit." OFFSET $Of;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function ValidarImdInveContra()//
    {
         list($dbconn) = GetDBconn();
         $dbconn->BeginTrans();
        $contador1=$contador2=0;
        for($i=0;$i<sizeof($_SESSION['ctrpl1']['codigosimd']);$i++)
        {
            if($_SESSION['ctrpl1']['codigosimd'][$i]['paragrafado']==1 AND $_POST['grabarimd'.$i]==NULL)
            {
                $contador1++;
                $query ="DELETE FROM planes_paragrafados_medicamentos
                        WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                        AND servicio='".$_SESSION['ctrpl1']['servicimdc']."'
                        AND departamento='".$_SESSION['ctrpl1']['departimdc']."'
                        AND codigo_producto='".$_SESSION['ctrpl1']['codigosimd'][$i]['codigo_producto']."';";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]=$dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                }
            }
            else if($_SESSION['ctrpl1']['codigosimd'][$i]['paragrafado']==0 AND $_POST['grabarimd'.$i]<>NULL)
            {
                $contador2++;
                $query ="INSERT INTO planes_paragrafados_medicamentos
                        (plan_id,
                        servicio,
                        departamento,
                        codigo_producto)
                        VALUES
                        (".$_SESSION['ctrpla']['planeleg'].",
                        '".$_SESSION['ctrpl1']['servicimdc']."',
                        '".$_SESSION['ctrpl1']['departimdc']."',
                        '".$_SESSION['ctrpl1']['codigosimd'][$i]['codigo_producto']."');";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]=$dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                }
            }
        }
        $dbconn->CommitTrans();
        if($this->frmError["MensajeError"]==NULL)
        {
            $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador2."
            <br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador1."";
        }
        $this->uno=1;
        $this->ModificarImdInveContra();
        return true;
    }

    function BuscarParaTipoImdInveContra2($plan,$servicio,$departamento)//
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT E.codigo_producto,
                E.descripcion
                FROM planes_paragrafados_medicamentos AS D,
                inventarios_productos AS E
                WHERE D.plan_id=".$plan."
                AND D.servicio='".$servicio."'
                AND D.departamento='".$departamento."'
                AND D.codigo_producto=E.codigo_producto
                ORDER BY E.codigo_producto;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarClasificacionTipoContra($tipopara,$servicio,$departamento)//
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT A.codigo_producto,
                A.descripcion
                FROM inventarios_productos AS A,
                tipos_paragrafados_imd_detalle AS D
                WHERE A.codigo_producto=D.codigo_producto
                AND D.tipo_para_imd=".$tipopara."
                AND D.servicio='".$servicio."'
                AND D.departamento='".$departamento."'
                ORDER BY A.codigo_producto;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var[]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    /********FUNCIONES DE LA OPCIÓN PARAGRAFADOS CARGOS DIRECTOS********/
    function BuscarParagraCadInveContra($plan,$servicio)//$empresa
    {
        list($dbconn) = GetDBconn();
        if($_REQUEST['codigoctra'])
        {
            $codigo=$_REQUEST['codigoctra'];
            $busqueda="AND A.cargo LIKE '$codigo%'";
        }
        else
        {
            $busqueda='';
        }
        if($_REQUEST['descrictra'])
        {
            $codigo=STRTOUPPER($_REQUEST['descrictra']);
            $busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
        }
        else
        {
            $busqueda2='';
        }
        if(empty($_REQUEST['conteo']))
        {
            $query ="SELECT count(*) FROM (
                    (
                    SELECT C.grupo_tarifario_descripcion AS des1,
                    D.subgrupo_tarifario_descripcion AS des2,
                    E.descripcion AS des3,
                    A.tarifario_id,
                    A.cargo,
                    A.descripcion,
                    paragrafados_cargos(B.plan_id, A.tarifario_id, A.cargo, '".$servicio."') AS paragrafado
                    FROM tarifarios_detalle AS A,
                    plan_tarifario AS B,
                    grupos_tarifarios AS C,
                    subgrupos_tarifarios AS D,
                    tarifarios AS E
                    WHERE B.plan_id=".$plan."
                    AND B.tarifario_id=A.tarifario_id
                    AND B.grupo_tarifario_id=A.grupo_tarifario_id
                    AND B.subgrupo_tarifario_id=A.subgrupo_tarifario_id
                    AND A.tarifario_id<>'SYS'
                    AND A.grupo_tarifario_id<>'00'
                    AND A.grupo_tipo_cargo<>'SYS'
                    AND A.grupo_tarifario_id=C.grupo_tarifario_id
                    AND A.subgrupo_tarifario_id=D.subgrupo_tarifario_id
                    AND C.grupo_tarifario_id=D.grupo_tarifario_id
                    AND A.tarifario_id=E.tarifario_id
                    $busqueda
                    $busqueda2
                    )
                    ) AS r;";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            list($this->conteo)=$resulta->fetchRow();
        }
        else
        {
            $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of'])
        {
            $Of='0';
        }
        else
        {
            $Of=$_REQUEST['Of'];
            if($_REQUEST['Of'] > $this->conteo)
            {
                $Of='0';
                $_REQUEST['Of']='0';
                $_REQUEST['paso']='1';
            }
        }
        $query ="
                (
                SELECT C.grupo_tarifario_descripcion AS des1,
                D.subgrupo_tarifario_descripcion AS des2,
                E.descripcion AS des3,
                A.tarifario_id,
                A.cargo,
                A.descripcion,
                paragrafados_cargos(B.plan_id, A.tarifario_id, A.cargo, '".$servicio."') AS paragrafado
                FROM tarifarios_detalle AS A,
                plan_tarifario AS B,
                grupos_tarifarios AS C,
                subgrupos_tarifarios AS D,
                tarifarios AS E
                WHERE B.plan_id=".$plan."
                AND B.tarifario_id=A.tarifario_id
                AND B.grupo_tarifario_id=A.grupo_tarifario_id
                AND B.subgrupo_tarifario_id=A.subgrupo_tarifario_id
                AND A.tarifario_id<>'SYS'
                AND A.grupo_tarifario_id<>'00'
                AND A.grupo_tipo_cargo<>'SYS'
                AND A.grupo_tarifario_id=C.grupo_tarifario_id
                AND A.subgrupo_tarifario_id=D.subgrupo_tarifario_id
                AND C.grupo_tarifario_id=D.grupo_tarifario_id
                AND A.tarifario_id=E.tarifario_id
                $busqueda
                $busqueda2
                ORDER BY des1, des2,
                A.tarifario_id, A.cargo
                )
                LIMIT ".$this->limit." OFFSET $Of;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function ValidarCadInveContra()//
    {
         list($dbconn) = GetDBconn();
         $dbconn->BeginTrans();
        for($i=0;$i<sizeof($_SESSION['ctrpl1']['codigoscad']);$i++)
        {
            if($_SESSION['ctrpl1']['codigoscad'][$i]['paragrafado']==1 AND $_POST['grabarcad'.$i]==NULL)
            {
                $query ="DELETE FROM planes_paragrafados_cargos
                        WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                        AND servicio='".$_SESSION['ctrpl1']['serparcadc']."'
                        AND tarifario_id='".$_SESSION['ctrpl1']['codigoscad'][$i]['tarifario_id']."'
                        AND cargo='".$_SESSION['ctrpl1']['codigoscad'][$i]['cargo']."';";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollBackTrans();
                }
            }
            else if($_SESSION['ctrpl1']['codigoscad'][$i]['paragrafado']==0 AND $_POST['grabarcad'.$i]<>NULL)
            {
                $query ="INSERT INTO planes_paragrafados_cargos
                        (plan_id,
                        servicio,
                        tarifario_id,
                        cargo)
                        VALUES
                        (".$_SESSION['ctrpla']['planeleg'].",
                        '".$_SESSION['ctrpl1']['serparcadc']."',
                        '".$_SESSION['ctrpl1']['codigoscad'][$i]['tarifario_id']."',
                        '".$_SESSION['ctrpl1']['codigoscad'][$i]['cargo']."');";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollBackTrans();
                }
            }
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
        $this->uno=1;
        $this->ModificarCadInveContra();
        return true;
    }

    function BuscarParagraCadInveContra2($plan,$servicio)//
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT F.grupo_tarifario_descripcion AS des1,
                G.subgrupo_tarifario_descripcion AS des2,
                H.descripcion AS des3,
                E.tarifario_id,
                E.cargo,
                E.descripcion,
                0 AS paragrafado
                FROM planes_paragrafados_cargos AS D,
                tarifarios_detalle AS E,
                grupos_tarifarios AS F,
                subgrupos_tarifarios AS G,
                tarifarios AS H
                WHERE D.plan_id=".$plan."
                AND D.servicio='".$servicio."'
                AND D.cargo=E.cargo
                AND D.tarifario_id=E.tarifario_id
                AND E.grupo_tarifario_id=F.grupo_tarifario_id
                AND E.subgrupo_tarifario_id=G.subgrupo_tarifario_id
                AND F.grupo_tarifario_id=G.grupo_tarifario_id
                AND D.tarifario_id=H.tarifario_id
                ORDER BY des1, des2,
                E.tarifario_id, E.cargo;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarIncumplimientoContra($plan)//
    {
        list($dbconn) = GetDBconn();
        if($_REQUEST['codigoctra'])
        {
            $codigo=$_REQUEST['codigoctra'];
            $busqueda="WHERE A.cargo_cita LIKE '$codigo%'";
        }
        else
        {
            $busqueda='';
        }
        if($_REQUEST['descrictra'])
        {
            $codigo=STRTOUPPER($_REQUEST['descrictra']);
            if($busqueda==NULL)
            {
                $busqueda2="WHERE UPPER(A.descripcion) LIKE '%$codigo%'";
            }
            else
            {
                $busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
            }
        }
        else
        {
            $busqueda2='';
        }
        if(empty($_REQUEST['conteo']))
        {
            $query ="SELECT count(*) FROM (
                        (
                        SELECT A.cargo_cita,
                        A.descripcion,
                        B.valor
                        FROM cargos_citas AS A
                        LEFT JOIN planes_incumplimientos_citas AS B ON
                        (A.cargo_cita=B.cargo_cita
                        AND B.plan_id=".$plan.")
                        $busqueda
                        $busqueda2
                        )
                        ) AS r;";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            list($this->conteo)=$resulta->fetchRow();
        }
        else
        {
            $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of'])
        {
            $Of='0';
        }
        else
        {
            $Of=$_REQUEST['Of'];
            if($_REQUEST['Of'] > $this->conteo)
            {
                $Of='0';
                $_REQUEST['Of']='0';
                $_REQUEST['paso']='1';
            }
        }
        $query ="
                    (
                    SELECT A.cargo_cita,
                    A.descripcion,
                    B.valor
                    FROM cargos_citas AS A
                    LEFT JOIN planes_incumplimientos_citas AS B ON
                    (A.cargo_cita=B.cargo_cita
                    AND B.plan_id=".$plan.")
                    $busqueda
                    $busqueda2
                    ORDER BY A.cargo_cita
                    )
                    LIMIT ".$this->limit." OFFSET $Of;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function ValidarIncumplimientoContra()//
    {
        list($dbconn) = GetDBconn();
        $contador1=$contador2=$contador3=0;
        $dbconn->BeginTrans();
        for($i=0;$i<sizeof($_SESSION['ctrpl1']['incumpctra']);$i++)
        {
            $g1=0;
            if(is_numeric($_POST['valorictra'.$i])==1)
            {
                $_POST['valorictra'.$i]=doubleval($_POST['valorictra'.$i]);
                if($_POST['valorictra'.$i] < 10000000000)
                {
                    $g1=1;
                }
            }
            if($_SESSION['ctrpl1']['incumpctra'][$i]['valor']<>NULL AND $g1==1 AND
            $_SESSION['ctrpl1']['incumpctra'][$i]['valor']<>$_POST['valorictra'.$i])
            {
                $contador1++;
                $query ="UPDATE planes_incumplimientos_citas SET
                            valor=".$_POST['valorictra'.$i]."
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                            AND cargo_cita='".$_SESSION['ctrpl1']['incumpctra'][$i]['cargo_cita']."';";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollBackTrans();
                }
            }
            else if($_SESSION['ctrpl1']['incumpctra'][$i]['valor']==NULL AND $g1==1)
            {
                $contador2++;
                $query ="INSERT INTO planes_incumplimientos_citas
                            (plan_id,
                            cargo_cita,
                            valor)
                            VALUES
                            (".$_SESSION['ctrpla']['planeleg'].",
                            '".$_SESSION['ctrpl1']['incumpctra'][$i]['cargo_cita']."',
                            ".$_POST['valorictra'.$i].");";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollBackTrans();
                }
            }
            if($g1==0 AND $_SESSION['ctrpl1']['incumpctra'][$i]['valor']<>NULL)
            {
                $contador3++;
                $query ="DELETE FROM planes_incumplimientos_citas
                            WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."
                            AND cargo_cita='".$_SESSION['ctrpl1']['incumpctra'][$i]['cargo_cita']."';";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollBackTrans();
                }
            }
            $_POST['valorictra'.$i]='';
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador2."
        <br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador1."
        <br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
        $this->uno=1;
        $this->IncumplimientoContra();
        return true;
    }
// CREATE TABLE tarifarios_uvrs_paquetes_excepciones (
//     tarifario_id character varying(4) NOT NULL,
//     uvr_valor numeric(12,2) DEFAULT 0 NOT NULL,
//     plan_id integer NOT NULL
// );


		
		function TraerTarifariosUVRPaquete()
		{
		
        list($dbconn) = GetDBconn();
/*       echo $query ="	SELECT A.tarifario_id,A.uvr_valor,A.plan_id, B.descripcion
									FROM tarifarios_uvrs_paquetes_excepciones A, tarifarios B,
										planes C, tarifarios_detalle D
									WHERE A.plan_id=".$_SESSION['ctrpla']['planeleg']."
									AND A.plan_id=C.plan_id
									AND A.tarifario_id=B.tarifario_id
									AND B.tarifario_id=D.tarifario_id
									AND D.tipo_unidad_id='05';"; exit;*/
					 $query ="SELECT DISTINCT B.tarifario_id,B.descripcion 
												FROM tarifarios B,tarifarios_detalle D 
												WHERE B.tarifario_id=D.tarifario_id 
												AND D.tipo_unidad_id='05';";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO-> ".$query;
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
		}
		
		function BuscarDatosUVRplanesp()
		{
		
        list($dbconn) = GetDBconn();
        $query ="	SELECT DISTINCT A.tarifario_id,A.uvr_valor,A.plan_id, B.descripcion
									FROM tarifarios_uvrs_paquetes_excepciones A, tarifarios B,
										planes C, tarifarios_detalle D
									WHERE A.plan_id=".$_SESSION['ctrpla']['planeleg']."
									AND A.plan_id=C.plan_id
									AND A.tarifario_id=B.tarifario_id
									AND B.tarifario_id=D.tarifario_id
									AND D.tipo_unidad_id='05';";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO-> ".$query;
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
		}
		
		/**
		 * Guarda el protocolo de internacion
		 */
		function GuardarProtocoloInternacion()
		{
			$sql="
			UPDATE 
				planes 
			SET 
				protocolo_internacion='{$_REQUEST['protocoloInternacion']}' 
			WHERE
				plan_id={$_SESSION['ctrpla']['planeleg']}";
			list($dbconn) = GetDBconn();
			$resulta = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "ERROR AL GUARDAR PROTOCOLO INTERNACION ".$sql;
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$this->ClienteCargosContra();
			return true;
		}
		
		/**
		 * Retorna el protocolo de internacion de un plan con el plan id
		 *
		 * @param integer planId
		 */
		function GetProtocoloInternacion($planId=null)
		{
			
			if(empty($planId))
			{
				$planId=$_SESSION['ctrpla']['planeleg'];
			}
			$sql="
				SELECT 
					protocolo_internacion
				FROM
					planes
				WHERE 
					plan_id=$planId";
			list($dbconn) = GetDBconn();
			$protocolo = $dbconn->GetOne($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "ERROR AL BUSCAR EL PROTOCOLO INTERNACION ".$sql;
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $protocolo;
		}
		
		function GetDatosCondicionUsuario()
		{
			$sql="SELECT *
						FROM  tipos_condicion_usuarios_planes;";
			list($dbconn) = GetDBconn();
			$resulta = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "ERROR AL BUSCAR EN tipos_condicion_usuarios_planes "."[".get_class($this)."][".__LINE__."]";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			while(!$resulta->EOF)
			{
					$var[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
			}
			return $var;
		}
		
		/**
		 * Retorna el protocolo de internacion de un plan con el plan id
		 *
		 * @param integer planId
		 */
		function GetPlanCondicionUsuario()
		{
			$sql="SELECT *
						FROM planes_condicion_usuario
						WHERE plan_id=".$_SESSION['ctrpla']['planeleg']."";
			list($dbconn) = GetDBconn();
			$resulta = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "ERROR AL CARGAR EL MODULO ";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
				return false;
			}
			while(!$resulta->EOF)
			{
					$var[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
			}
			return $var;
		}
	function BuscarCargoGrupoSubgrupo()
	{	
		$filtro1 = $filtro2 = "";
		if($_REQUEST['codigo'])	
		{
		 $filtro1 = " AND B.cargo LIKE '".$_REQUEST['codigo']."%' "; 
		}
		if($_REQUEST['descripcion'])
		{
		 //$filtro2 = " AND UPPER(B.descripcion) LIKE '%".$_REQUEST['descripcion']."%' ";
		 $filtro2 = " AND B.descripcion LIKE UPPER('%".$_REQUEST['descripcion']."%') ";
		}
		
		$plan_id = $_REQUEST['plan_id'];
		
	      $sql = "SELECT A.grupo_tarifario_id,A.subgrupo_tarifario_id,D.grupo_tarifario_descripcion,
							E.subgrupo_tarifario_descripcion,
							B.cargo,
							B.precio,
							B.descripcion,
							B.tarifario_id,
							C.descripcion AS destarifario,
							F.porcentaje,
							F.sw_descuento,
							F.sw_no_contratado,
							B.tipo_unidad_id
					FROM plan_tarifario AS A
					LEFT JOIN tarifarios_detalle AS B ON
						(A.plan_id=$plan_id
						AND A.grupo_tarifario_id=B.grupo_tarifario_id
						AND A.subgrupo_tarifario_id=B.subgrupo_tarifario_id
						AND A.tarifario_id=B.tarifario_id),
					tarifarios AS C,
					grupos_tarifarios AS D,
					subgrupos_tarifarios AS E,
					excepciones F
					WHERE A.tarifario_id=C.tarifario_id
					AND A.grupo_tarifario_id = D.grupo_tarifario_id
					AND A.grupo_tarifario_id=E.grupo_tarifario_id
					AND A.subgrupo_tarifario_id=E.subgrupo_tarifario_id
					AND F.plan_id = A.plan_id
					AND F.tarifario_id = B.tarifario_id
					AND F.cargo = B.cargo
					$filtro1 $filtro2
					";
			list($dbconn) = GetDBconn();
			$resulta = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "ERROR AL CARGAR EL MODULO ";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
				return false;
			}
			if($resulta->EOF)
			{
			 $sql="SELECT A.grupo_tarifario_id,A.subgrupo_tarifario_id,D.grupo_tarifario_descripcion,
							E.subgrupo_tarifario_descripcion,
							B.cargo,
							B.precio,
							B.descripcion,
							B.tarifario_id,
							C.descripcion AS destarifario,
							A.porcentaje,
							--A.por_cobertura,
							A.sw_descuento,
							'' AS sw_no_contratado,
							B.tipo_unidad_id
					FROM plan_tarifario AS A
					LEFT JOIN tarifarios_detalle AS B ON
						(A.plan_id=$plan_id
						AND A.grupo_tarifario_id=B.grupo_tarifario_id
						AND A.subgrupo_tarifario_id=B.subgrupo_tarifario_id
						AND A.tarifario_id=B.tarifario_id),
					tarifarios AS C,
					grupos_tarifarios AS D,
					subgrupos_tarifarios AS E
					WHERE A.tarifario_id=C.tarifario_id
					AND A.grupo_tarifario_id = D.grupo_tarifario_id
					AND A.grupo_tarifario_id=E.grupo_tarifario_id
					AND A.subgrupo_tarifario_id=E.subgrupo_tarifario_id
					$filtro1 $filtro2

					";
			}	
			list($dbconn) = GetDBconn();
			$resulta = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "ERROR AL CARGAR EL MODULO ";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
				return false;
			}
			while(!$resulta->EOF)
			{
				$var[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
			$this->TarifarioGrupoContraRapida($var);
			return true;
	}

}//fin de la clase
?>
