<?php
class app_AtencionUrgenciasEnfermeria_user extends classModulo
{

    function app_AtencionUrgenciasEnfermeria_user()
    {
        return true;
    }

    function main()
    {
        $this->PantallaInicial();
        return true;
    }
    
    function GetPacientesConfirmarAdmision()
    {
        list($dbconn) = GetDBconn();
        $sql="SELECT a.tipo_id_paciente, a.paciente_id, a.plan_id, a.triage_id, a.punto_triage_id, a.punto_admision_id, a.sw_no_atender, c.primer_nombre || ' ' || c.segundo_nombre || ' ' || c.primer_apellido || ' ' || c.segundo_apellido as nombre 
                FROM triages a,triages_pendientes_admitir b, pacientes c
                WHERE a.triage_id = b.triage_id
                AND a.tipo_id_paciente = c.tipo_id_paciente 
                AND a.paciente_id = c.paciente_id
                AND a.sw_estado=5
                AND b.estacion_id = '".$_SESSION['AtencionUrgenciasEnfermeria']['estacion_id']."';";
        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error en GetPacientesConfirmarAdmision()";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }    

        while(!$result->EOF)
        {
            $pacientestriage[$result->fields[0]][$result->fields[1]]=$result->GetRowAssoc(false);
            $result->MoveNext();
        }
        
        if(!empty($pacientestriage))
        {
            return $pacientestriage;
        }

        return false;
            
    }

    function BuscarPacientesEstacion()
    {
        //GLOBAL $ADODB_FETCH_MODE;
        //$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        list($dbconn) = GetDBconn();
        //$sql="select c.paciente_id, c.tipo_id_paciente, c.primer_nombre || ' ' || c.segundo_nombre || ' ' || c.primer_apellido || ' ' || c.segundo_apellido as nombre, e.color, d.hora_llegada, e.tiempo_atencion, b.ingreso, f.evolucion_id, to_char(f.fecha,'YYYY-MM-DD HH24:MI') as fecha, f.usuario_id, h.nombre, a.estacion_id, d.nivel_triage_id, d.plan_id, d.triage_id, d.punto_triage_id, d.punto_admision_id, d.sw_no_atender, i.numerodecuenta from pacientes_urgencias as a join ingresos as b  on (a.ingreso=b.ingreso and a.estacion_id='".$_SESSION['AtencionUrgencias']['estacion_id']."') join pacientes as c on (b.paciente_id=c.paciente_id and b.tipo_id_paciente=c.tipo_id_paciente and b.estado=1) left join triages as d on (a.triage_id=d.triage_id) left join niveles_triages as e on (d.nivel_triage_id=e.nivel_triage_id and e.nivel_triage_id!=0) left join hc_evoluciones as f on (b.ingreso=f.ingreso and f.estado=1) left join profesionales_usuarios as g on (f.usuario_id=g.usuario_id) left join profesionales as h on (g.tercero_id=h.tercero_id and g.tipo_tercero_id=h.tipo_id_tercero) left join cuentas as i on(a.ingreso=i.ingreso and i.estado=1) where a.sw_estado='1' order by e.indice_de_orden, d.hora_llegada;";
        $sql="select c.paciente_id, c.tipo_id_paciente, c.primer_nombre || ' ' || c.segundo_nombre || ' ' || c.primer_apellido || ' ' || c.segundo_apellido as nombre, e.nivel_triage_id, d.hora_llegada, e.tiempo_atencion, b.ingreso, f.evolucion_id, to_char(f.fecha,'YYYY-MM-DD HH24:MI') as fecha, f.usuario_id, h.nombre, a.estacion_id, d.nivel_triage_id, d.plan_id, d.triage_id, d.punto_triage_id, d.punto_admision_id, d.sw_no_atender, i.numerodecuenta, z.egresos_no_atencion_id from pacientes_urgencias as a join ingresos as b  on (a.ingreso=b.ingreso and a.estacion_id='".$_SESSION['AtencionUrgenciasEnfermeria']['estacion_id']."') join pacientes as c on (b.paciente_id=c.paciente_id and b.tipo_id_paciente=c.tipo_id_paciente and b.estado=1) left join triages as d on (a.triage_id=d.triage_id) left join niveles_triages as e on (d.nivel_triage_id=e.nivel_triage_id and e.nivel_triage_id!=0 and d.sw_estado!=9) left join hc_evoluciones as f on (b.ingreso=f.ingreso and f.estado=1) left join profesionales_usuarios as g on (f.usuario_id=g.usuario_id) left join profesionales as h on (g.tercero_id=h.tercero_id and g.tipo_tercero_id=h.tipo_id_tercero) left join cuentas as i on(a.ingreso=i.ingreso and i.estado=1) left join egresos_no_atencion as z on(z.ingreso=b.ingreso or z.triage_id=d.triage_id) where a.sw_estado='1' order by e.indice_de_orden, d.hora_llegada;";

        $result = $dbconn->Execute($sql);
        $i=0;
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            $spy=0;
            while (!$result->EOF)
            {
							if(empty($result->fields[19]))
							{
                if(!empty($result->fields[4]))
                {
                    $a=explode("-",$result->fields[4]);
                    $b=explode(" ",$a[2]);
                    $c=explode(":",$b[1]);
                    if(date("Y-m-d H:i:s",mktime($c[0],$c[1],$c[2],$a[1],$b[0],$a[0]))<date("Y-m-d H:i:s",mktime(date("H"), (date("i")-$result->fields[5]), 0, date("m"), date("d"), date("Y"))))
                    {
                        $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=1;
												if(!empty($result->fields[3]))
												{
															IncludeLib('funciones_admision');
															$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2] = ColorTriageClaro($result->fields[3]);
												}
                        /*if($result->fields[3]=='AZUL')
                        {
                            $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelazulclaro';
                        }
                        elseif($result->fields[3]=='ROJO')
                        {
                            $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelrojoclaro';
                        }
                        elseif($result->fields[3]=='VERDE')
                        {
                            $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelverdeclaro';
                        }
                        elseif($result->fields[3]=='AMARILLO')
                        {
                            $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelamarilloclaro';
                        }*/
                        else
                        {
                            if($spy==0)
                            {
                                $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='modulo_list_oscuro';
                                $spy=1;
                            }
                            else
                            {
                                $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='modulo_list_claro';
                                $spy=0;
                            }
                        }
                    }
                    else
                    {
                        $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=2;
												if(!empty($result->fields[3]))
												{
															IncludeLib('funciones_admision');
															$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2] = ColorTriageClaro($result->fields[3]);
												}
                        /*if($result->fields[3]=='AZUL')
                        {
                            $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelazulclaro';
                        }
                        elseif($result->fields[3]=='ROJO')
                        {
                            $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelrojoclaro';
                        }
                        elseif($result->fields[3]=='VERDE')
                        {
                            $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelverdeclaro';
                        }
                        elseif($result->fields[3]=='AMARILLO')
                        {
                            $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelamarilloclaro';
                        }*/
                        else
                        {
                            if($spy==0)
                            {
                                $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='modulo_list_oscuro';
                                $spy=1;
                            }
                            else
                            {
                                $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='modulo_list_claro';
                                $spy=0;
                            }
                        }
                    }
                    $total=(mktime(date("H"),date("i"),0,date("m"),date("d"),date("Y"))-mktime($c[0],$c[1],$c[2],$a[1],$b[0],$a[0]));
                    //echo 'Segundos:'.$segundos=$total%60;
                    $total=floor($total/60);
                    $minutos=($total%60);
                    $total=floor($total/60);
                    $horas=($total%24);
                    $total=floor($total/24);
                    $mostrar="";
                    if(!empty($total))
                    {
                        $mostrar=$total.' dias, ';
                    }
                    $mostrar.=$horas.':'.$minutos;
                    $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][1]=$mostrar;
                }
                $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][0]=1;
                if($spy==0 and empty($prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]))
                {
                    $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='modulo_list_oscuro';
                    $spy=1;
                }
                else
                {
                    if(empty($prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]))
                    {
                        $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='modulo_list_claro';
                        $spy=0;
                    }
                }
                $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][3]=$result->fields[6];
                $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[7];
                $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[8];
                $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[9];
                $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[10];
                $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[11];
                $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[12];
                $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[13];
                $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[14];
                $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[15];
                $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[16];
                $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[17];
                $prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[18];
							}
							$i++;
							$result->MoveNext();
            }
        }
        if($i<>0)
        {
            //print_r($prueba);
            return $prueba;
        }
        else
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "No existen paciente para esta estacion de enfermeria.";
            return false;
        }
    }

    function ClasificarTriage()
    {
        $_SESSION['Atencion']['estacion_id']=$_REQUEST['estacion_id'];
        $_SESSION['Atencion']['ingreso']=$_REQUEST['ingreso'];
        $_SESSION['Atencion']['modulo']=$_REQUEST['moduloh'];
        $_SESSION['TRIAGE']['ATENCION']['tipo_id_paciente']=$_REQUEST['tipo_id_paciente'];
        $_SESSION['TRIAGE']['ATENCION']['paciente_id']=$_REQUEST['paciente_id'];
        $_SESSION['TRIAGE']['ATENCION']['plan_id']=$_REQUEST['plan_id'];
        $_SESSION['TRIAGE']['ATENCION']['PENDIENTE']=$_REQUEST['pte'];                
        $_SESSION['TRIAGE']['ATENCION']['triage_id']=$_REQUEST['triage_id'];
        $_SESSION['TRIAGE']['ATENCION']['punto_triage_id']=$_REQUEST['punto_triage_id'];
        $_SESSION['TRIAGE']['ATENCION']['punto_admision_id']=$_REQUEST['punto_admision_id'];
        $_SESSION['TRIAGE']['ATENCION']['sw_no_atender']=$_REQUEST['sw_no_atender'];
        $_SESSION['TRIAGE']['ATENCION']['RETORNO']['contenedor']='app';
        $_SESSION['TRIAGE']['ATENCION']['RETORNO']['modulo']='AtencionUrgenciasEnfermeria';
        $_SESSION['TRIAGE']['ATENCION']['RETORNO']['tipo']='user';
        $_SESSION['TRIAGE']['ATENCION']['RETORNO']['metodo']='RetornoTriage';
        $_SESSION['TRIAGE']['ATENCION']['RETORNO']['argumentos']=array();
        $this->ReturnMetodoExterno('app','Triage','user','LlamarClasificacionMedico');
        return true;
    }

    function RetornoTriage()
    {
        unset($_SESSION['TRIAGE']['ATENCION']);
        if(empty($_SESSION['RETORNO']['TRIAGE']['ATENCION']) or empty($_SESSION['Atencion']['ingreso']))
        {
            unset($_SESSION['RETORNO']['TRIAGE']['ATENCION']);
            $this->ListadoPaciente();
        }
        else
        {
            unset($_SESSION['RETORNO']['TRIAGE']['ATENCION']);
            $this->ContinuarHistoria();
        }
        return true;
    }


    function BuscarPacienteHosptalizados()
    {
        list($dbconn) = GetDBconn();
         $query = "SELECT MH.cama, B.pieza, C.ingreso, D.paciente_id, D.tipo_id_paciente, E.primer_nombre || ' ' || E.segundo_nombre || ' ' || E.primer_apellido || ' ' || E.segundo_apellido as nombretotal, G.evolucion_id, G.usuario_id, to_char(G.fecha,'YYYY-MM-DD HH24:MI') as fecha, I.nombre, A.numerodecuenta FROM movimientos_habitacion AS MH, ( SELECT ID.ingreso_dpto_id, ID.numerodecuenta, ID.departamento, ID.estacion_id, EE.descripcion, ID.orden_hospitalizacion_id FROM ingresos_departamento ID, estaciones_enfermeria EE WHERE ID.estado = '1' AND EE.estacion_id = ID.estacion_id AND EE.estacion_id = '".$_SESSION['AtencionUrgenciasEnfermeria']['estacion_id']."' ) AS A, camas B, cuentas C, ingresos D left join hc_evoluciones as G on(D.ingreso=G.ingreso and G.estado=1) left join profesionales_usuarios as H on(G.usuario_id=H.usuario_id) left join profesionales as I on(H.tercero_id=I.tercero_id and H.tipo_tercero_id=I.tipo_id_tercero), pacientes E, departamentos F WHERE MH.ingreso_dpto_id = A.ingreso_dpto_id AND MH.fecha_egreso IS NULL AND MH.cama = B.cama AND C.numerodecuenta = A.numerodecuenta AND C.ingreso = D.ingreso AND C.estado = '1' AND D.paciente_id = E.paciente_id AND D.tipo_id_paciente = E.tipo_id_paciente AND F.departamento = A.departamento ORDER BY MH.cama, B.pieza,G.evolucion_id;";
        //echo $query;
        $result = $dbconn->Execute($query);
        $i=0;
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            $spy=0;
            while (!$result->EOF)
            {
                $prueba[$result->fields[3]][$result->fields[4]][$result->fields[5]][]=$result->GetRowAssoc(false);
                $hospitaesta[0][$i]=$result->fields[0];
                $hospitaesta[1][$i]=$result->fields[1];
                $hospitaesta[2][$i]=$result->fields[2];
                $hospitaesta[3][$i]=$result->fields[3];
                $hospitaesta[4][$i]=$result->fields[4];
                $hospitaesta[5][$i]=$result->fields[5];
                $i++;
                $result->MoveNext();
            }
        }
        $hospitaesta1[]=$hospitaesta;
        $hospitaesta1[]=$prueba;
        if($i<>0)
        {
            return $hospitaesta1;
        }
        else
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "No existen paciente para esta estacion de enfermeria.";
            return false;
        }
    }

    function PacientesClasificacionTriage()
    {
        list($dbconn) = GetDBconn();
        $sql="select b.tipo_id_paciente, b.paciente_id, b.plan_id, b.triage_id, b.punto_triage_id, b.punto_admision_id, b.sw_no_atender, c.primer_nombre || ' ' || c.segundo_nombre || ' ' || c.primer_apellido || ' ' || c.segundo_apellido as nombre from triage_no_atencion as a join triages as b on(a.triage_id=b.triage_id and a.estacion_id='".$_SESSION['AtencionUrgenciasEnfermeria']['estacion_id']."' and b.nivel_triage_id='0' and b.sw_estado !=9) join pacientes as c on (b.tipo_id_paciente=c.tipo_id_paciente and b.paciente_id=c.paciente_id);";
        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            $i=0;
            while(!$result->EOF)
            {
                $pacientestriage[$result->fields[0]][$result->fields[1]]=$result->GetRowAssoc(false);
                $result->MoveNext();
                $i=1;
            }
            if($i==1)
            {
                return $pacientestriage;
            }
            else
            {
                return false;
            }
        }
    }

    function TipoModulo($claseDeAtencion)
    {
        $tipo_profesional = $this->ReconocerProfesional();
        
        if(empty($tipo_profesional)){
            $this->error = "EL USUARIO NO ES UN TIPO DE PROFESIONAL VALIDO PARA ESTE MODULO";
            $this->mensajeDeError = "No se encontro la Estacion de Enfermeria No.".$_SESSION['AtencionUrgenciasEnfermeria']['estacion_id'];
            return false;            
        }
                
        list($dbconn) = GetDBconn();
        switch($claseDeAtencion)
        {
            case 'enfermeria':
                $tipo_hc_modulo='hc_modulo_enfermera';
            break;
            
            case 'consulta_urgencias';
                $tipo_hc_modulo='hc_modulo_consulta_urgencias';
            break;
            
            default:
                if($tipo_profesional==1 || $tipo_profesional==2){
                    $tipo_hc_modulo='hc_modulo_medico';
                }else{
                    $tipo_hc_modulo='hc_modulo_enfermera';
                }

        }
        
        $sql="select $tipo_hc_modulo,hc_modulo_medico,hc_modulo_enfermera from estaciones_enfermeria where estacion_id ='".$_SESSION['AtencionUrgenciasEnfermeria']['estacion_id']."'";
        $result = $dbconn->Execute($sql);
        
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        
        if ($result->EOF)
        {
            $this->error = "No se encontro la Estacion de Enfermeria";
            $this->mensajeDeError = "No se encontro la Estacion de Enfermeria No.".$_SESSION['AtencionUrgenciasEnfermeria']['estacion_id'];
            return false;
        }
        
        list($hc_modulo,$hc_modulo_medico,$hc_modulo_enfermera)= $result->FetchRow();

        
        if(empty($hc_modulo)){
            if($tipo_profesional==1 || $tipo_profesional==2){
                return $hc_modulo_medico;
            }else{
                return $hc_modulo_enfermera;
            }
        }else{
            return $hc_modulo;
        }        

    }

    function ReconocerProfesional()
    {
        list($dbconn) = GetDBconn();
        $a=UserGetUID();
        
        if(!empty($a))
        {
            $sql="select b.tipo_profesional from profesionales_usuarios as a, profesionales as b where a.usuario_id=".$a." and a.tipo_tercero_id=b.tipo_id_tercero and a.tercero_id=b.tercero_id;";
        }
        else
        {
            return false;
        }
        $result = $dbconn->Execute($sql);
        $i=0;
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            if(!$result->EOF)
            {
                return $result->fields[0];
            }
            else
            {
                return false;
            }
        }
    }

//-------------------------DARLING----------------------------

		/**
		*
		*/
		function SacarPacienteLista()
		{
				$this->FormaSacarLista($_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['triage_id'],$_REQUEST['ingreso']);
				return true;
		}

		/**
		*
		*/
		function SacarPaciente()
		{
					if(empty($_REQUEST['observacion']))
					{
							$this->frmError["observacion"]=1;
							$this->frmError["MensajeError"]='DEBE ESCRIBIR EL MOTIVO.';
							if(!$this->FormaSacarLista($_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['triage_id'],$_REQUEST['ingreso'])){
								return false;
							}
							return true;
					}

					if(empty($_REQUEST['ingreso']))
					{  $_REQUEST['ingreso']='NULL';  }
					if(empty($_REQUEST['triage_id']))
					{  $_REQUEST['triage_id']='NULL';  }

					list($dbconn) = GetDBconn();
					$dbconn->BeginTrans();
			 	  $query = "INSERT INTO egresos_no_atencion (
																					tipo_id_paciente,
																					paciente_id,
																					ingreso,
																					triage_id,
																					observacion,
																					fecha_registro,
																					usuario_id)
											VALUES('".$_REQUEST['tipo_id_paciente']."','".$_REQUEST['paciente_id']."',
											".$_REQUEST['ingreso'].",".$_REQUEST['triage_id'].",
											'".$_REQUEST['observacion']."','now()',".UserGetUID().")";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error INSERT INTO egresos_no_atencion";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
					}

					if(!empty($_REQUEST['triage_id']))
					{
								echo	$query = "UPDATE triages SET sw_estado=9
													  WHERE triage_id=".$_REQUEST['triage_id']."";
									$results = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error update PACIENTES_URGENCIAS";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
									}
					}

					//sacar listado medico
					if($_SESSION['ADMISIONES']['PACIENTE']['lista']==1)
					{
								echo	$query = "UPDATE pacientes_urgencias SET sw_estado=9
													  WHERE ingreso=".$_REQUEST['ingreso']."";
									$results = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error update PACIENTES_URGENCIAS";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
									}

									$query = "UPDATE ingresos SET estado=0
													  WHERE ingreso=".$_REQUEST['ingreso']."";
									$results = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error update ingresos";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
									}

									$query = "UPDATE cuentas SET estado=0
													  WHERE ingreso=".$_REQUEST['ingreso']."";
									$results = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error update ingresos";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
									}
					}
					elseif($_SESSION['ADMISIONES']['PACIENTE']['lista']==2)
					{
								$query = "delete from triages_pendientes_admitir where triage_id=".$_REQUEST['triage_id']."";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
												$this->error = "delete autorizaciones_solicitudes_cargos";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$dbconn->RollbackTrans();
												return false;
								}
					}

					$dbconn->CommitTrans();
					$this->frmError["MensajeError"]='EL PACIENTE FUE SACADO DE LA LISTA';
					$this->ListadoPaciente();
					return true;
		}

		/**
		*
		*/
		function PacientesAtendidosTriage()
		{
					list($dbconn) = GetDBconn();
					$query = "SELECT DISTINCT c.tipo_id_paciente, c.paciente_id, d.triage_id,
										c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre
										FROM chequeo_triages as a, triages as d, pacientes as c
										WHERE a.usuario_id=".UserGetUID()."
										and a.triage_id=d.triage_id
										and d.tipo_id_paciente=c.tipo_id_paciente
										and d.paciente_id=c.paciente_id
										and a.fecha_registro > timestamp'".date("Y-m-d H:i")."' - interval '12 hour'";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "ERROR";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					while(!$result->EOF)
					{
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					return $var;
		}


		/*function PacientesAtendidos()
		{
					list($dbconn) = GetDBconn();
					$query = "SELECT distinct b.ingreso, c.tipo_id_paciente, c.paciente_id, d.triage_id,
										c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre
										FROM hc_evoluciones as a left join triages as d on(a.ingreso=d.ingreso),
										ingresos as b, pacientes as c
										WHERE a.usuario_id=".UserGetUID()." and a.ingreso=b.ingreso
										and b.tipo_id_paciente=c.tipo_id_paciente
										and b.paciente_id=c.paciente_id
										and a.fecha_cierre is not null
										and date(a.fecha_cierre)=".date("Y-m-d")."";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "ERROR";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					while(!$result->EOF)
					{
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					return $var;
		}*/

//------------------------------------------------------------

}
?>
