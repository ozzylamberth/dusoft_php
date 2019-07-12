<?php

/**
 * $Id: app_AtencionPacientesUrgencias_user.php,v 1.2 2005/06/21 21:36:35 duvan Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_AtencionPacientesUrgencias_user extends classModulo
{

    function app_AtencionPacientesUrgencias_user()
    {
        return true;
    }

    function main()
    {
				UNSET($_SESSION['ESTACION_ENF']['CONTEO']);//esto es para q en estacion de enf no salgan valores erroneos
				//de los pacientes hospitalizados y los de consulta de urgencias.
        $this->PantallaInicial();
        return true;
    }
    
    function GetPacientesConfirmarAdmision()
    {
        list($dbconn) = GetDBconn();
        $sql="SELECT a.tipo_id_paciente, a.paciente_id, a.plan_id, a.triage_id, a.punto_triage_id, a.punto_admision_id, a.sw_no_atender,
							c.primer_nombre || ' ' || c.segundo_nombre || ' ' || c.primer_apellido || ' ' || c.segundo_apellido as nombre,
							a.observacion_enfermera
							FROM triages a,triages_pendientes_admitir b, pacientes c
							WHERE a.triage_id = b.triage_id
							AND a.tipo_id_paciente = c.tipo_id_paciente
							AND a.paciente_id = c.paciente_id
							AND a.sw_estado='5'
							AND b.estacion_id = '".$_SESSION['AtencionUrgencias']['estacion_id']."';";
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
        $sql="select c.paciente_id, c.tipo_id_paciente, c.primer_nombre || ' ' || c.segundo_nombre || ' ' || c.primer_apellido || ' ' || c.segundo_apellido as nombre, e.nivel_triage_id, d.hora_llegada, e.tiempo_atencion, b.ingreso, f.evolucion_id, to_char(f.fecha,'YYYY-MM-DD HH24:MI') as fecha, f.usuario_id, h.nombre, a.estacion_id, d.nivel_triage_id, d.plan_id, d.triage_id, d.punto_triage_id, d.punto_admision_id, d.sw_no_atender, i.numerodecuenta, z.egresos_no_atencion_id from pacientes_urgencias as a join ingresos as b  on (a.ingreso=b.ingreso and a.estacion_id='".$_SESSION['AtencionUrgencias']['estacion_id']."') join pacientes as c on (b.paciente_id=c.paciente_id and b.tipo_id_paciente=c.tipo_id_paciente and b.estado=1) left join triages as d on (a.triage_id=d.triage_id) left join niveles_triages as e on (d.nivel_triage_id=e.nivel_triage_id and e.nivel_triage_id!=0 and d.sw_estado!=9) left join hc_evoluciones as f on (b.ingreso=f.ingreso and f.estado=1) left join profesionales_usuarios as g on (f.usuario_id=g.usuario_id) left join profesionales as h on (g.tercero_id=h.tercero_id and g.tipo_tercero_id=h.tipo_id_tercero) left join cuentas as i on(a.ingreso=i.ingreso and i.estado='1') left join egresos_no_atencion as z on(z.ingreso=b.ingreso or z.triage_id=d.triage_id) where a.sw_estado='1' order by e.indice_de_orden, d.hora_llegada;";

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
        $_SESSION['TRIAGE']['ATENCION']['RETORNO']['modulo']='AtencionPacientesUrgencias';
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
        {//echo "entro 1a";
            unset($_SESSION['RETORNO']['TRIAGE']['ATENCION']);


						if($_SESSION['ATENCION']['URGENCIAS']['SWITCH']=='admision')
            {$this->ListadoPacientesConfirmarAdmision();}
						if($_SESSION['ATENCION']['URGENCIAS']['SWITCH']=='consulta')
            {$this->Pac_consultas_Urgencias();}
						if($_SESSION['ATENCION']['URGENCIAS']['SWITCH']=='remision')
            {$this->ListadoPacientesClasificar();}
        }
        else
        {//echo "entro aaca 2a";
            unset($_SESSION['RETORNO']['TRIAGE']['ATENCION']);
            $this->ContinuarHistoria();
        }
        return true;
    }


    function BuscarPacienteHosptalizados()
    {
        list($dbconn) = GetDBconn();
         $query = "SELECT MH.cama, B.pieza, C.ingreso, D.paciente_id, D.tipo_id_paciente, E.primer_nombre || ' ' || E.segundo_nombre || ' ' || E.primer_apellido || ' ' || E.segundo_apellido as nombretotal, G.evolucion_id, G.usuario_id, to_char(G.fecha,'YYYY-MM-DD HH24:MI') as fecha, I.nombre, A.numerodecuenta FROM movimientos_habitacion AS MH, ( SELECT ID.ingreso_dpto_id, ID.numerodecuenta, ID.departamento, ID.estacion_id, EE.descripcion, ID.orden_hospitalizacion_id FROM ingresos_departamento ID, estaciones_enfermeria EE WHERE ID.estado = '1' AND EE.estacion_id = ID.estacion_id AND EE.estacion_id = '".$_SESSION['AtencionUrgencias']['estacion_id']."' ) AS A, camas B, cuentas C, ingresos D left join hc_evoluciones as G on(D.ingreso=G.ingreso and G.estado=1) left join profesionales_usuarios as H on(G.usuario_id=H.usuario_id) left join profesionales as I on(H.tercero_id=I.tercero_id and H.tipo_tercero_id=I.tipo_id_tercero), pacientes E, departamentos F WHERE MH.ingreso_dpto_id = A.ingreso_dpto_id AND MH.fecha_egreso IS NULL AND MH.cama = B.cama AND C.numerodecuenta = A.numerodecuenta AND C.ingreso = D.ingreso AND C.estado = '1' AND D.paciente_id = E.paciente_id AND D.tipo_id_paciente = E.tipo_id_paciente AND F.departamento = A.departamento ORDER BY MH.cama, B.pieza,G.evolucion_id;";
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



		//aca sacamos las fechas de las evolcuiones pasadas y los nombres de los medicos
		//q atendieron esas evoluciones.[duvan]
		function Buscar_Evoluciones_Medicas($ingreso,$usuario_id)
		{
				if(!empty($usuario_id))
				{$filtro="AND G.usuario_id=$usuario_id";}else{$filtro='';}
				list($dbconn) = GetDBconn();
				$query="SELECT  H.nombre,to_char(G.fecha,'YYYY-MM-DD HH24:MI') as fecha,
								G.evolucion_id,C.numerodecuenta, G.usuario_id,D.ingreso
								FROM cuentas C,ingresos D
								left join hc_evoluciones as G on(D.ingreso=G.ingreso and G.estado=1 $filtro)
								left join system_usuarios as H on(G.usuario_id=H.usuario_id)
								WHERE D.ingreso=$ingreso
								AND C.ingreso = D.ingreso AND C.estado = '1'";
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
					$VectorControl[$i]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
					$i++;
				}
				return $VectorControl;
	 }



	 //buscar laevolucion pasadas quien o q profesional la hizo
	 function Buscar_Evoluciones_Pasadas($ingreso)
		{
			list($dbconn) = GetDBconn();
			unset($VectorControl);
			unset($datos);
			$query="SELECT  H.nombre,G.usuario_id,to_char(G.fecha,'YYYY-MM-DD HH24:MI') as fecha,evolucion_id
								FROM cuentas C,ingresos D
								left join hc_evoluciones as G on(D.ingreso=G.ingreso and G.estado='0')
								left join system_usuarios as H on(G.usuario_id=H.usuario_id)
								WHERE D.ingreso='$ingreso'
								AND C.ingreso = D.ingreso AND C.estado = '1' ORDER BY G.evolucion_id DESC";
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
					$VectorControl[$i]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
					$i++;
				}

			//	print_r($VectorControl);exit;
				for($n=0;$n<sizeof($VectorControl);$n++)
				{
					if($this->ReconocerProfesional($VectorControl[$n]['usuario_id'])==1
					OR $this->ReconocerProfesional($VectorControl[$n]['usuario_id'])==2)
					{
							$datos[0]=$VectorControl[$n];
							break;
					}
				}

				return $datos;
	 }




		//revisa si esta pendientepor ingresar a otra estacion,
		function Revisar_Si_esta_trasladado($ingreso)
		{
			 	list($dbconn) = GetDBconn();
	 			/*$sql = "SELECT COUNT(*) FROM ordenes_hospitalizacion
													WHERE hospitalizado = '0'
													AND ingreso=$ingreso";*/
					$sql="SELECT COUNT(*) FROM  pendientes_x_hospitalizar
								WHERE ingreso=$ingreso ";
			  $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
			  return $result->fields[0];
		}



    function PacientesClasificacionTriage()
    {
        list($dbconn) = GetDBconn();
        $sql="select b.tipo_id_paciente, b.paciente_id, b.plan_id, b.triage_id, b.punto_triage_id, b.punto_admision_id, b.sw_no_atender, c.primer_nombre || ' ' || c.segundo_nombre || ' ' || c.primer_apellido || ' ' || c.segundo_apellido as nombre from triage_no_atencion as a join triages as b on(a.triage_id=b.triage_id and a.estacion_id='".$_SESSION['AtencionUrgencias']['estacion_id']."' and b.nivel_triage_id='0' and b.sw_estado !=9) join pacientes as c on (b.tipo_id_paciente=c.tipo_id_paciente and b.paciente_id=c.paciente_id);";
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
            $this->mensajeDeError = "No se encontro la Estacion de Enfermeria No.".$_SESSION['AtencionUrgencias']['estacion_id'];
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
        
        $sql="select $tipo_hc_modulo,hc_modulo_medico,hc_modulo_enfermera from estaciones_enfermeria where estacion_id ='".$_SESSION['AtencionUrgencias']['estacion_id']."'";
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
            $this->mensajeDeError = "No se encontro la Estacion de Enfermeria No.".$_SESSION['AtencionUrgencias']['estacion_id'];
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

    function ReconocerProfesional($uid='')
    {
        list($dbconn) = GetDBconn();
       if($uid=='')
				{$a=UserGetUID();}else{$a=$uid;}
        
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
								unset($_SESSION['lista']);
								$_SESSION['lista']=$_REQUEST['lista'];
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
                                    $query = "UPDATE triages SET sw_estado=9
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
										//PACIENTES PARA CONSULTA EN URGENCIAS
                    if($_SESSION['lista']==1)
                    {
                                    $query = "UPDATE pacientes_urgencias SET sw_estado=9
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
                    elseif($_SESSION['lista']==2)
                    {
																//PACIENTES PARA CONFIRMAR ADMISION
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
                   // $this->Pac_consultas_Urgencias();
									 	if($_SESSION['ATENCION']['URGENCIAS']['SWITCH']=='admision')
										{$this->ListadoPacientesConfirmarAdmision();}
										if($_SESSION['ATENCION']['URGENCIAS']['SWITCH']=='consulta')
										{$this->Pac_consultas_Urgencias();}
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


function CallListRevisionPorSistemas()
{

	$estacion= $_REQUEST['estacion'];
	$this->ListRevisionPorSistemas($estacion);
	return true;

}


	function GetFechasHcApoyos($ingreso)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT fecha
								FROM hc_control_apoyosd_pendientes WHERE ingreso='$ingreso'
								AND usuario_confirma ISNULL
								AND fecha_registro_confirma ISNULL";

			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al traer las fechas de los apoyos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      	return false;
			}

			while (!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}

				return $var;
		}


		function GetConteo_Hc_control_apoyod($ingreso)
		{
			$query = "SELECT hc_control_apoyod($ingreso)";
			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al traer los controles de apoyos";
				$this->mensajeDeError = "Error al obtener el numero de cuenta del ingreso<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			return $result->fields[0];
		}

		/*
		*		CONTEO DE GetControles
		*
		*		@Author Arley velasquez
		*		@access Public
		*/
		function CountControles($ingreso)
		{
			list($dbconn) = GetDBconn();
			$controles=array();
			$query="SELECT COUNT(*)
							FROM  hc_controles_paciente
							WHERE ingreso=".$ingreso."";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado) {
				$this->error = "Error al buscar los controles del paciente en \"hc_controles_paciente\"<br>";
				$this->mensajeDeError = $query;
				return false;
			}

      if($resultado->fields[0]>0){return 1;}else{return 0;}

		}


/**
		*		funcion de darling
		*/
		function BuscarEvolucion($ingreso)
		{
				list($dbconn) = GetDBconn();
				$query = "select b.evolucion_id,b.usuario_id from hc_evoluciones as b
									where b.ingreso='$ingreso'
									and b.estado='1'
									and b.fecha_cierre=(select max(fecha_cierre) from hc_evoluciones	where ingreso='$ingreso')";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				if($result->EOF)
				{  return "nada";  }


				if(!$result->EOF)
				{  $var[0]=$result->fields[0];
				   $var[1]=$result->fields[1];
				}

				return $var;
		}



		/**
	*		GetPacientesPendientesXHospitalizar => Obtiene los pendientes por hospitalizar
	*
	*		llamado desde vista 1=> el subproceso1->"ingresar paciente" del proceso "ingreso de pacientes a la estación de enfermería"
	*		1.1.1.1.H => GetPacientesPendientesXHospitalizar()
	*		Obtiene los pacientes pendientes por ingresar al dpto almacenados en la tabla "pendientes_x_hospitalizar"
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool-array-string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function Get_Conteo_Pacientes_XHospitalizar($datos_estacion)
	{
		$query = "SELECT COUNT(*)
							FROM pacientes,
									(	SELECT  I.ingreso as ing_id,
														I.paciente_id as pac_id,
														I.tipo_id_paciente as tipo_id,
														P.estacion_destino as ee_destino,
														P.orden_hospitalizacion_id as orden_hosp,
														P.traslado as traslado,
														P.estacion_origen as estacion_origen
										FROM 	ingresos I,
													cuentas x,
													pendientes_x_hospitalizar P
										WHERE I.ingreso = P.ingreso AND
													P.estacion_destino = '".$datos_estacion[estacion_id]."'
													AND I.ingreso =x.ingreso
													AND x.estado='1'
									) as HOLA
							WHERE paciente_id = pac_id AND
										tipo_id_paciente = tipo_id AND
										ee_destino = '".$datos_estacion[estacion_id]."'
							";//pacientes_x_ingreso_x_pxh
		//echo $query; //exit;
		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtener los pacientes pendientes por hospitalizar<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}

		return $result->fields[0];
	}//fin Get


	/**
	*		GetDiasHospitalizacion
	*
	*		Calcula los días que lleva hospitalizada una persona, basandose en la fecha de ingreso.
	*		Esta funcion tamben es llamada desde el modulo censo
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return integer
	*		@param tiemstamp => fecha de ingreso del paciente
	*/
	function GetDiasHospitalizacion($fecha_ingreso)
	{

		if(empty($fecha_ingreso)){
			$fecha_ingreso = '';
			$fecha_ingreso = $_REQUEST['fecha_ingreso'];
		}


				$date1=date('Y-m-d H:i:s');

				$fecha_in=explode(".",$fecha_ingreso);
				$fecha_ingreso=$fecha_in[0];
				$date2=$fecha_ingreso;

				$s = strtotime($date1)-strtotime($date2);
				$d = intval($s/86400);
				$s -= $d*86400;
				$h = intval($s/3600);
				$s -= $h*3600;
				$m = intval($s/60);
				$s -= $m*60;

				$dif= (($d*24)+$h).hrs." ".$m."min";
				$dif2= $d.$space.dias." ".$h.hrs." ".$m."min";

			//	echo "Diferencia en horas: ".$dif;

			//	echo "Diferencia en dias: ".$dif2;
		return $dif2;
	}


/*funcion del mod estacione_medicamentos*/
	/**
	*		GetPacMedicamentosPorSolicitar
	*
	*		obtiene los pacientes que tengan medicamentos recetados vigentes
	*
	*		@Author Jairo Duvan Diaz M.
	*		@access Public
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	//buscar aqui
	function GetPacMedicamentosPorSolicitar($ingreso)
	{

			list($dbconn) = GetDBconn();
     $query="SELECT COUNT(*) FROM hc_medicamentos_recetados_hosp
											WHERE ingreso='".$ingreso."'
											AND sw_estado='1'
											AND (sw_ambulatorio ISNULL OR sw_ambulatorio='0')";
											$resulta=$dbconn->execute($query);
								if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
								}
								if($resulta->fields[0]>0)
								{
										return 1;
								}

		return "ShowMensaje";
	}


		function ConteoOrdenesPaciente($ingreso,$nom)
		{
				list($dbconn) = GetDBconn();
				$sql="select count(a.hc_os_solicitud_id)
				from hc_os_solicitudes as a, hc_evoluciones as i
				where i.ingreso=$ingreso and i.evolucion_id=a.evolucion_id
				and a.sw_estado='1'";
				$res=$dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				$contador=$res->fields[0];
				if($contador >0){return 1;}

			  $sql="select count(a.hc_os_solicitud_id)
				from hc_os_solicitudes as a, hc_evoluciones as i,
				os_maestro as c
				where i.ingreso=$ingreso and i.evolucion_id=a.evolucion_id
				and a.hc_os_solicitud_id=c.hc_os_solicitud_id and c.sw_estado in('1','2','3')";
				$result=$dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

			$contador2=$result->fields[0];
			if($contador2 >0){return 1;}else{return 0;}

		}







		/*funcion del mod estacione_controlpacientes*/
		/*
		*		GetControles
		*
		*		@Author Arley velasquez
		*		@access Public
		*/
		function GetControles($ingreso,$control_id)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			if($control_id)  //si existe un control, entonces filtraremos por el.
			{
				$query="SELECT COUNT(*)
							FROM  hc_controles_paciente cp,
										hc_tipos_controles_paciente tc
							WHERE cp.ingreso=".$ingreso." AND
										cp.control_id = tc.control_id
										AND cp.control_id='$control_id';";

				$resultado=$dbconn->Execute($query);
				if (!$resultado) {
							$this->error = "Error al buscar los controles del paciente en \"hc_controles_paciente\"<br>";
							$this->mensajeDeError = $query;
							return false;
						}
						$controles=$resultado->fields[0];

			}
			else
			{
				$controles=array();
				$query="SELECT cp.*,
										upper(tc.descripcion) as descripcion
							FROM  hc_controles_paciente cp,
										hc_tipos_controles_paciente tc
							WHERE cp.ingreso=".$ingreso." AND
										cp.control_id = tc.control_id;";


						$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
						$resultado=$dbconn->Execute($query);
						$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
						if (!$resultado) {
							$this->error = "Error al buscar los controles del paciente en \"hc_controles_paciente\"<br>";
							$this->mensajeDeError = $query;
							return false;
						}
						while ($data = $resultado->FetchRow()) {
							$controles[]=$data;
						}

			}

			return $controles;
		}
/*funcion del mod estacione_controlpacientes*/





/*
	**
	*		GetViaIngresoPaciente
	*
	*		Con el ingreso del paciente obtengo la via de ingreso
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool-array-string
	*		@param integer => numero de ingreso
	*/
	function GetViaIngresoPaciente($ingreso)
	{
		$query = "SELECT I.via_ingreso_id, VI.via_ingreso_nombre
							FROM ingresos I,
									 vias_ingreso VI
							WHERE I.ingreso = $ingreso AND
										VI.via_ingreso_id =  I.via_ingreso_id;";
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar consultar la vía de ingreso del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if(!$result->EOF)
			{
				$viaIngreso = $result->FetchRow();
				return $viaIngreso;
			}
			else{
				return "ShowMensaje";
			}
		}
	}//GetViaIngresoPaciente




/**
	*		GetPacientesPendientesXHospitalizar => Obtiene los pendientes por hospitalizar
	*
	*		llamado desde vista 1=> el subproceso1->"ingresar paciente" del proceso "ingreso de pacientes a la estación de enfermería"
	*		1.1.1.1.H => GetPacientesPendientesXHospitalizar()
	*		Obtiene los pacientes pendientes por ingresar al dpto almacenados en la tabla "pendientes_x_hospitalizar"
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool-array-string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetPacientesPendientesXHospitalizar($datos_estacion)
	{

	  if(!$datos_estacion)
		{
			$datos_estacion=$_REQUEST['datos'];
		}
		$query = "SELECT 	paciente_id,
											tipo_id_paciente,
											primer_apellido,
											segundo_apellido,
											primer_nombre,
											segundo_nombre,
											ing_id,
											ee_destino,
											orden_hosp,
											traslado,
											estacion_origen,
											plan_id,
											numerodecuenta
							FROM pacientes,
									(	SELECT  I.ingreso as ing_id,
														I.paciente_id as pac_id,
														I.tipo_id_paciente as tipo_id,
														P.estacion_destino as ee_destino,
														P.orden_hospitalizacion_id as orden_hosp,
														P.traslado as traslado,
														P.estacion_origen as estacion_origen,
														x.plan_id,
														x.numerodecuenta
										FROM 	ingresos I,
													cuentas x,
													pendientes_x_hospitalizar P
										WHERE I.ingreso = P.ingreso 
													AND I.ingreso=x.ingreso
													AND x.estado ='1' 
												AND
													P.estacion_destino = '".$datos_estacion[estacion_id]."'
									) as HOLA
							WHERE paciente_id = pac_id AND
										tipo_id_paciente = tipo_id AND
										ee_destino = '".$datos_estacion[estacion_id]."'
							ORDER BY  primer_nombre,
												segundo_nombre,
												primer_apellido,
            segundo_apellido";//pacientes_x_ingreso_x_pxh
		//echo $query; //exit;
		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtener los pacientes pendientes por hospitalizar<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}

		if($result->EOF)
		{
			return "ShowMensaje";
		}

		$i=0;
		while ($data = $result->FetchNextObject())
		{
  		$query = "SELECT descripcion
								FROM estaciones_enfermeria
								WHERE estacion_id = $data->ESTACION_ORIGEN";
			$desc = $dbconn->Execute($query);

			$x = $this->get_cuenta_x_ingreso($data->ING_ID);
			$Pacientes[$i][0]  = $data->PRIMER_NOMBRE." ".$data->SEGUNDO_NOMBRE;
			$Pacientes[$i][1]  = $data->PRIMER_APELLIDO." ".$data->SEGUNDO_APELLIDO;
			$Pacientes[$i][2]  = $data->PACIENTE_ID;
			$Pacientes[$i][3]  = $data->TIPO_ID_PACIENTE;
			$Pacientes[$i][4]  = $data->ING_ID;
			$Pacientes[$i][5]  = $data->ORDEN_HOSP;
			$Pacientes[$i][6]  = $x[0]; //CUENTA
			$Pacientes[$i][7]  = $x[1]; //PLAN
			$Pacientes[$i][8]  = $data->TRASLADO;
			$Pacientes[$i][9]  = $desc->fields[0];//descripcion ee origen
			$Pacientes[$i][10] = $data->ESTACION_ORIGEN;//id estacion origen
			$i++;
 	 	}
		return $Pacientes;
	}//fin GetPacientesPendientesXHospitalizar


	/**
	*		get_cuenta_x_ingreso
	*
	*		llamado desde el subproceso1->"Asignar cama" del proceso "ingreso de pacientes a la estación de enfermería"
	*		1.1.1.2.H => get_cuenta_x_ingreso()
	*		Obtiene la cuenta del paciente con el numero de ingreso
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return array
	*		@param integer => ingreso del paciente
	*/
	function get_cuenta_x_ingreso($ingreso)
	{
		$query = "SELECT C.numerodecuenta, C.plan_id
							FROM cuentas C
							JOIN planes P
							ON  C.ingreso = '".$ingreso."' AND
									P.plan_id = C.plan_id";
		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al obtener el numero de cuenta del ingreso<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}

		if($result->EOF)
		{
			$this->error = "Error al cargar el modulo";
			$this->mensajeDeError = "No se pudo obtener el plan de la cuenta del paciente";
			return false;
		}
		else
		{
			$x[0] = $result->fields[0]; //cuenta
			$x[1] = $result->fields[1]; //plan
			return $x;
		}
	}// fin get_cuenta_x_ingreso


		function GetPacientesControles($Estacion)
		{

      if(empty($Estacion))
			{
				return "ShowMensaje";
			}
//[cambiar query por proxima lentitud duvan].
				$query = "SELECT  MH.fecha_ingreso,
												MH.cama,
												B.pieza,
												C.ingreso,
												C.numerodecuenta,
												D.paciente_id,
												D.tipo_id_paciente,
												E.primer_nombre,
												E.segundo_nombre,
												E.primer_apellido,
												E.segundo_apellido,
												--hc_control_apoyod(C.ingreso),
												MH.ingreso_dpto_id

								FROM movimientos_habitacion AS MH,
										(
											SELECT  ID.ingreso_dpto_id,
															ID.numerodecuenta

											FROM  ingresos_departamento ID,
														estaciones_enfermeria EE
											WHERE ID.estado = '1' AND
														EE.estacion_id = ID.estacion_id AND
														EE.estacion_id = '$Estacion'
										) AS A,
											camas B,
											cuentas C,
											ingresos D,
											pacientes E

								WHERE MH.ingreso_dpto_id = A.ingreso_dpto_id AND
											MH.fecha_egreso IS NULL AND
											MH.cama = B.cama AND
											C.numerodecuenta = A.numerodecuenta AND
											C.ingreso = D.ingreso AND
											C.estado = '1' AND
											D.paciente_id = E.paciente_id AND
											D.tipo_id_paciente = E.tipo_id_paciente

								ORDER BY MH.cama, B.pieza";



			//echo "<br>".$query;
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar consultar los pacientes en las estacion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				while ($data = $result->FetchRow())
				{
						$datoscenso[hospitalizacion][] = $data;
				}
			}

			if(!$datoscenso){
				return "ShowMensaje";
			}
  		return $datoscenso;

		}//GetPacientesControles




		function BuscarPacientesConsulta_Urgencias($datos_estacion,$consultorio)
		{
				//GLOBAL $ADODB_FETCH_MODE;
        //$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				$filtro='';
				if(!empty($consultorio))
				{  $filtro=" and a.paciente_urgencia_consultorio_id=$consultorio";  }
        list($dbconn) = GetDBconn();
				//ojo dar=>lo quite porque no vi donde la utilizan
				//--, z.egresos_no_atencion_id
				//--left join egresos_no_atencion as z on(z.ingreso=b.ingreso or z.triage_id=d.triage_id)
				 $sql="SELECT c.paciente_id, c.tipo_id_paciente, c.primer_nombre, c.segundo_nombre ,c.primer_apellido , c.segundo_apellido,
							e.nivel_triage_id, d.hora_llegada, e.tiempo_atencion, b.ingreso, f.evolucion_id,
							b.fecha_ingreso, to_char(f.fecha,'YYYY-MM-DD HH24:MI') as fecha, f.usuario_id,
							h.nombre_tercero as nombre, a.estacion_id, d.nivel_triage_id, d.plan_id, d.triage_id, d.punto_triage_id,
							d.punto_admision_id, d.sw_no_atender, i.numerodecuenta
							FROM pacientes_urgencias as a
							join ingresos as b  on (a.ingreso=b.ingreso and a.estacion_id='".$datos_estacion['estacion_id']."')
							join pacientes as c on (b.paciente_id=c.paciente_id and b.tipo_id_paciente=c.tipo_id_paciente and b.estado='1')
							left join triages as d on (a.triage_id=d.triage_id)
							left join niveles_triages as e on (d.nivel_triage_id=e.nivel_triage_id and e.nivel_triage_id!=0 and d.sw_estado!='9')
							left join hc_evoluciones as f on (b.ingreso=f.ingreso and f.estado='1')
							left join profesionales_usuarios as g on (f.usuario_id=g.usuario_id)
								left join terceros as h on (g.tercero_id=h.tercero_id
							and g.tipo_tercero_id=h.tipo_id_tercero)
							left join cuentas as i on(a.ingreso=i.ingreso and i.estado='1')
							WHERE a.sw_estado='1' $filtro
							order by e.indice_de_orden, d.hora_llegada;";
        $result = $dbconn->Execute($sql);
        $i=0;
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al traer lospacientes de consulta de urgencias";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

				if($result->EOF)
				{
					return "ShowMensaje";
				}

				while ($data = $result->FetchNextObject())
				{


					$x = $this->get_cuenta_x_ingreso($data->INGRESO);
					$Pacientes[$i][0]  = $data->PRIMER_NOMBRE." ".$data->SEGUNDO_NOMBRE;
					$Pacientes[$i][1]  = $data->PRIMER_APELLIDO." ".$data->SEGUNDO_APELLIDO;
					$Pacientes[$i][2]  = $data->PACIENTE_ID;
					$Pacientes[$i][3]  = $data->TIPO_ID_PACIENTE;
					$Pacientes[$i][4]  = $data->INGRESO;
					$Pacientes[$i][5]  = $data->ORDEN_HOSP;
					$Pacientes[$i][6]  = $x[0]; //CUENTA
					$Pacientes[$i][7]  = $x[1]; //PLAN
					$Pacientes[$i][8]  = $data->TRASLADO;
					$Pacientes[$i][9]  = $desc->fields[0];//descripcion ee origen
					$Pacientes[$i][10] = $data->ESTACION_ORIGEN;//id estacion origen
					$Pacientes[$i][11] = $data->NIVEL_TRIAGE_ID;//id estacion origen
					$Pacientes[$i][12] = $data->HORA_LLEGADA;//id estacion origen
					$Pacientes[$i][13] = $data->TIEMPO_ATENCION;//id estacion origen
					$Pacientes[$i][14] = $data->EVOLUCION_ID;//id estacion origen
					$Pacientes[$i][15] = $data->NOMBRE;//id estacion origen
					$Pacientes[$i][16] = $data->FECHA;//id estacion origen
					$Pacientes[$i][17] = $data->USUARIO_ID;//id estacion origen
					$Pacientes[$i][18] = $data->PLAN_ID;//id estacion origen
					$Pacientes[$i][19] = $data->TRIAGE_ID;//id estacion origen
					$Pacientes[$i][20] = $data->PUNTO_TRIAGE_ID;//id estacion origen
					$Pacientes[$i][21] = $data->SW_NO_ATENDER;//id estacion origen
					$Pacientes[$i][22] = $data->PUNTO_ADMISION_ID;//id estacion origen
					$Pacientes[$i][23] = $data->FECHA_INGRESO;//id estacion origen
					$i++;
				}
				//print_R($Pacientes);
				return $Pacientes;

		}



		function Get_Conteo_Pacientes_remision()
		{
			$query="SELECT COUNT(*) FROM triage_no_atencion as a
							join triages as b on(a.triage_id=b.triage_id
							and a.estacion_id='".$_SESSION['AtencionUrgencias']['estacion_id']."'
							and b.nivel_triage_id='0' and b.sw_estado !=9);";
			/*$query="SELECT COUNT(*) FROM triage_no_atencion as a
							join triages as b on(a.triage_id=b.triage_id
							and a.estacion_id='".$_SESSION['AtencionUrgencias']['estacion_id']."'
							and b.nivel_triage_id='0' and b.sw_estado !=9)
							join pacientes as c on (b.tipo_id_paciente=c.tipo_id_paciente
							and b.paciente_id=c.paciente_id);";*/
							list($dbconn) = GetDBconn();
							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
									$this->error = "Error al ejecutar la conexion";
									$this->mensajeDeError = "Ocurrió un error al intentar consultar los pacientes en las estacion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
									return false;
							}

					return $result->fields[0];
		}


		function Get_Conteo_Pacientes_Admision()
		{
			$query="SELECT COUNT(*)
							FROM triages a,triages_pendientes_admitir b
							WHERE a.triage_id = b.triage_id
							AND a.sw_estado='5'
							AND b.estacion_id = '".$_SESSION['AtencionUrgencias']['estacion_id']."'";

			/*$query="SELECT COUNT(*)
							FROM triages a,triages_pendientes_admitir b, pacientes c
							WHERE a.triage_id = b.triage_id
							AND a.tipo_id_paciente = c.tipo_id_paciente
							AND a.paciente_id = c.paciente_id
							AND a.sw_estado='5'
							AND b.estacion_id = '".$_SESSION['AtencionUrgencias']['estacion_id']."'";*/
			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Ocurrió un error al intentar consultar los pacientes en las estacion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					return false;
			}

					return $result->fields[0];
		}

		//sacamos un conteo de los pacientes en consulta
		function Get_Conteo_Pacientes_Consulta($Estacion)
		{
			$query="SELECT a.ingreso
							from pacientes_urgencias as a
							join ingresos as b  on (a.ingreso=b.ingreso and a.estacion_id='$Estacion' and b.estado='1')
							left join triages as d on (a.triage_id=d.triage_id and d.sw_estado!='9')
							where a.sw_estado='1';";
			/*$query="SELECT a.ingreso
							from pacientes_urgencias as a
							join ingresos as b  on (a.ingreso=b.ingreso and a.estacion_id='$Estacion')
							join pacientes as c on (b.paciente_id=c.paciente_id
							and b.tipo_id_paciente=c.tipo_id_paciente and b.estado='1')
							left join triages as d on (a.triage_id=d.triage_id)
							left join niveles_triages as e on (d.nivel_triage_id=e.nivel_triage_id
							and e.nivel_triage_id!='0' and d.sw_estado!='9')
							left join hc_evoluciones as f on (b.ingreso=f.ingreso and f.estado=1)
							left join profesionales_usuarios as g on (f.usuario_id=g.usuario_id)
							left join profesionales as h on (g.tercero_id=h.tercero_id
							and g.tipo_tercero_id=h.tipo_id_tercero)
							left join cuentas as i on(a.ingreso=i.ingreso and i.estado='1')
							left join egresos_no_atencion as z on(z.ingreso=b.ingreso or z.triage_id=d.triage_id)
							where a.sw_estado='1';";*/
							list($dbconn) = GetDBconn();
							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al ejecutar la conexion";
								$this->mensajeDeError = "Ocurrió un error al intentar consultar los pacientes en las estacion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
								return false;
							}

							$i=0;
							while ($data = $result->FetchNextObject())
							{
								$Pacientes[$i][4]  = $data->INGRESO;
								$i++;
							}


							unset($vector_ingresos);
							for($k=0;$k<sizeof($Pacientes);$k++)
							{
									if(in_array($Pacientes[$k][4], $vector_ingresos)==FALSE)
									{
											$vector_ingresos[$k]=$Pacientes[$k][4];
									}
							}

							return sizeof($vector_ingresos);
		}

		//sacamos un conteo de los pacientes hospitalizados
		function Get_Conteo_Pacientes_Hospitalizados($Estacion)
		{
				$query = "SELECT  COUNT(MH.cama)
								FROM movimientos_habitacion AS MH,
										(
											SELECT  ID.ingreso_dpto_id,
															ID.numerodecuenta
											FROM  ingresos_departamento ID,
														estaciones_enfermeria EE
											WHERE ID.estado = '1' AND
														EE.estacion_id = ID.estacion_id AND
														EE.estacion_id = '$Estacion'
										) AS A, cuentas C
								WHERE MH.ingreso_dpto_id = A.ingreso_dpto_id AND
											MH.fecha_egreso IS NULL AND
											C.numerodecuenta = A.numerodecuenta AND
											C.estado = '1'";
			/*$query = "SELECT  COUNT(MH.cama)

								FROM movimientos_habitacion AS MH,
										(
											SELECT  ID.ingreso_dpto_id,
															ID.numerodecuenta

											FROM  ingresos_departamento ID,
														estaciones_enfermeria EE
											WHERE ID.estado = '1' AND
														EE.estacion_id = ID.estacion_id AND
														EE.estacion_id = '$Estacion'
										) AS A,
											camas B,
											cuentas C,
											ingresos D,
											pacientes E

								WHERE MH.ingreso_dpto_id = A.ingreso_dpto_id AND
											MH.fecha_egreso IS NULL AND
											MH.cama = B.cama AND
											C.numerodecuenta = A.numerodecuenta AND
											C.ingreso = D.ingreso AND
											C.estado = '1' AND
											D.paciente_id = E.paciente_id AND
											D.tipo_id_paciente = E.tipo_id_paciente";*/

			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar consultar los pacientes en las estacion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			return $result->fields[0];
}





		function RevisarSi_Es_Egresado($ingreso_dpto)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT estado FROM egresos_departamento
							WHERE
							--estado = '1'
						  ingreso_dpto_id='$ingreso_dpto'
							AND tipo_egreso != '4'
							AND	estado != '2'
							";
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar el sql ingresos_departamento";
				$this->mensajeDeError = "---";
				return false;
			}
   		$info[0]=$result->RecordCount();//sabemos el conteo de los registros
			$info[1]=$result->fields[0];//guardamos la información del estado del egreso
			return $info;

		}



		//funcion que revisa si el medico no ha leido un resultado firmado
		
	/*	function Revisar_Lectura_Examen_Para_Medico($tipo,$paciente)
		{
			unset($cadena);
    	list($dbconn) = GetDBconn();
			$query = "SELECT resultado_id
								FROM hc_resultados 
								WHERE tipo_id_paciente='$tipo'
								AND paciente_id='$paciente'";

			$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al traer las fechas de los apoyos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      	return false;
			}
			
			if($result->EOF)
			{
				return 1;
			}

			while (!$result->EOF)
			{
				  $cadena.=$result->fields[0].",";
					$result->MoveNext();
			}
			$cadena .="0";
			
			$query = "SELECT COUNT(resultado_id) FROM  
								hc_apoyod_lecturas_profesionales
								WHERE resultado_id IN($cadena)";

			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al traer las fechas de los apoyos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      	return false;
			}
	
				return $result->fields[0];
	}
	*/
	
	
	//funcion que revisa si el medico no ha leido un resultado firmado
	function Revisar_Lectura_Examen_Para_Medico($tipo,$paciente,$ingreso)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT count(d.evolucion_id)
							
							FROM hc_resultados as a, hc_resultados_sistema as b, os_maestro as c,
							hc_os_solicitudes as d left join hc_apoyod_lectura_grupal as f
							on(d.evolucion_id=f.evolucion_id_solicitud), hc_evoluciones as e
							
							WHERE a.tipo_id_paciente='$tipo' and a.paciente_id='$paciente'
							and b.resultado_id=a.resultado_id and b.numero_orden_id=c.numero_orden_id
							and c.sw_estado='4' and c.hc_os_solicitud_id=d.hc_os_solicitud_id
							and d.evolucion_id=e.evolucion_id and e.ingreso=$ingreso
							and ((f.sw_prof IS NULL OR f.sw_prof='2')
							AND (f.sw_prof_dpto IS NULL OR f.sw_prof_dpto='2')
							AND (f.sw_prof_todos IS NULL OR f.sw_prof_todos='2'))";
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al traer las fechas de los apoyos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
	
				if($result->fields[0]> 0)
				{return '0';}else{return '1';}				
	}
	//si es mayor q uno hay examenes pendientes
		
		


}
?>
