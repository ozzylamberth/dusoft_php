<?php

/**
 * $Id: app_CentralImpresionHospitalizacion_user.php,v 1.4 2010/06/15 14:19:33 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo para el manejo de listas de trabajo
 */

class app_CentralImpresionHospitalizacion_user extends classModulo
{
  var $limit;
  var $conteo;//para saber cuantos registros encontr�

  function app_CentralImpresionHospitalizacion_user()
  {
    $this->limit=GetLimitBrowser();
    return true;
  }

    function main()
    {
				list($dbconn) = GetDBconn();
				unset($_SESSION['CENTRALHOSP']);
				GLOBAL $ADODB_FETCH_MODE;
				$query="SELECT d.id, d.tipo_id_tercero,
								x.empresa_id,  d.razon_social as descripcion1,
								x.descripcion as descripcion2, a.punto_impresion_hospitalaria_id
								FROM userpermisos_impresion_hospitalaria as a,
								puntos_impresion_hospitalaria as x,empresas as d
								WHERE a.usuario_id=".UserGetUID()."
								and a.punto_impresion_hospitalaria_id=x.punto_impresion_hospitalaria_id
								and x.empresa_id=d.empresa_id
								order by x.empresa_id";
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resulta = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error en la funcion ";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}

				while($data = $resulta->FetchRow())
				{
               $imp[$data['descripcion1']][$data['descripcion2']]=$data;
 				}

				$url[0]='app';
				$url[1]='CentralImpresionHospitalizacion';
				$url[2]='user';
				$url[3]='Principal';
				$url[4]='imp';

				$arreglo[0]='EMPRESA';
				$arreglo[1]='CENTRO IMPRESION';


				$this->salida.= gui_theme_menu_acceso('CENTRO IMPRESION HOSPITALARIA',$arreglo,$imp,$url,ModuloGetUrl('system','Menu','user','main'));
				return true;
    }



    function Principal()
    {
				$_SESSION['CENTRALHOSP']['EMPRESA']=$_REQUEST['imp']['empresa_id'];
				$_SESSION['CENTRALHOSP']['ID']=$_REQUEST['imp']['id'];
				$_SESSION['CENTRALHOSP']['TIPO']=$_REQUEST['imp']['tipo_id_tercero'];
				$_SESSION['CENTRALHOSP']['NOM_EMPRESA']=$_REQUEST['imp']['descripcion1'];

				$_SESSION['CENTRALHOSP']['PUNTO']=$_REQUEST['imp']['punto_impresion_hospitalaria_id'];

				list($dbconn) = GetDBconn();
				$query="SELECT a.estacion_id, b.descripcion
								FROM 	puntos_impresion_hospitalaria_estaciones as a,
								estaciones_enfermeria as b
								WHERE a.punto_impresion_hospitalaria_id=".$_SESSION['CENTRALHOSP']['PUNTO']."
								and a.estacion_ID=b.estacion_id";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}
				if(!$result->EOF)
				{
						$i=0;
						while (!$result->EOF)
						{
								if( $i == 0)
								{
										$_SESSION['CENTRALHOSP']['ESTACION'].="'".$result->fields[0]."'";
										//$_SESSION['CENTRALHOSP']['ESTACION'].=$result->fields[0];
										$_SESSION['CENTRALHOSP']['NOM_EST'].=$result->fields[1];
								}
								else
								{
										$_SESSION['CENTRALHOSP']['ESTACION'].=",'".$result->fields[0]."'";
										//$_SESSION['CENTRALHOSP']['ESTACION'].=','.$result->fields[0];
										$_SESSION['CENTRALHOSP']['NOM_EST'].=','.$result->fields[1];

								}
								$var[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
								$i++;
						}
				}
				$result->Close();
				$this->FormaBuscar();
				return true;
    }

		function TiposId()
		{
					list($dbconn) = GetDBconn();
					$query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					while(!$result->EOF){
							$vars[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					$result->Close();
					return $vars;
		}

		/**
		*
		*/
		function PacienteUrgencias()
		{
					list($dbconn) = GetDBconn();
					$query = "SELECT count(ingreso) FROM pacientes_urgencias WHERE sw_estado=1";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					$vars=$result->fields[0];
					$result->Close();
					return $vars;

		}


		/**
		*
		*/
		function Buscar()
		{

				$filtroTipoDocumento = '';
				$filtroDocumento='';
				$filtroNombres='';
				$filtroIngreso='';
				$filtroDepto='';


				if($_REQUEST[TipoDocumento]!='')
				{   $filtroTipoDocumento=" AND b.tipo_id_paciente = '".$_REQUEST[TipoDocumento]."'";   }

				if (!empty($_REQUEST[Documento]))
				{   $filtroDocumento =" AND b.paciente_id LIKE '".$_REQUEST[Documento]."%'";   }

				if ($_REQUEST[Nombres] != '')
				{
						$a=explode(' ',$_REQUEST[Nombres]);
						foreach($a as $k=>$v)
						{
								if(!empty($v))
										{
												$filtroNombres.=" and (upper(b.primer_nombre||' '||b.segundo_nombre||' '||
																														b.primer_apellido||' '||b.segundo_apellido) like '%".strtoupper($_REQUEST[Nombres])."%')";
										}
						}
				}

				if(!empty($_REQUEST[Ingreso]))
				{   $filtroIngreso=" AND j.ingreso =".$_REQUEST[Ingreso]."";   }

					list($dbconn) = GetDBconn();
          
					if(empty($_REQUEST['conteo']))
					{
						$query = "SELECT distinct count( d.ingreso)
											FROM (
												(
													select distinct j.tipo_id_paciente, 
                                  j.paciente_id,
                                  b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre
													from    hc_os_solicitudes as a, 
                                  hc_evoluciones as i, 
                                  ingresos as j,
                                  pacientes as b
													where   i.ingreso=j.ingreso 
                          and     j.estado='1'
													and     i.evolucion_id=a.evolucion_id and a.sw_estado='1'
													and     j.tipo_id_paciente=b.tipo_id_paciente 
                          and     j.paciente_id=b.paciente_id
													$filtroNombres 
                          $filtroDocumento 
                          $filtroIngreso
												)
												UNION
												(
													select distinct j.tipo_id_paciente, 
                                j.paciente_id,
													b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre
													from hc_os_solicitudes as a, hc_evoluciones as i, ingresos as j,
													os_maestro as c, pacientes as b
													where i.ingreso=j.ingreso and i.evolucion_id=a.evolucion_id
													 and j.estado='1'
													and a.hc_os_solicitud_id=c.hc_os_solicitud_id and c.sw_estado in('1','2','3')
													and j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id
													$filtroNombres 
                          $filtroDocumento 
                          $filtroIngreso
												)
												UNION
												(
													select distinct j.tipo_id_paciente, j.paciente_id,
													b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||
													b.segundo_apellido as nombre
													FROM  hc_medicamentos_recetados_amb a, 
                                hc_evoluciones f,
                                ingresos j, 
                                pacientes b
													WHERE a.evolucion_id = f.evolucion_id
													and   f.ingreso = j.ingreso
													and   j.tipo_id_paciente=b.tipo_id_paciente 
                          and   j.paciente_id=b.paciente_id
									        $filtroNombres 
                          $filtroDocumento 
                          $filtroIngreso
												)
												UNION
												(
                          select distinct j.tipo_id_paciente, j.paciente_id,
													b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||
													b.segundo_apellido as nombre
													FROM hc_incapacidades a, hc_evoluciones f,
													ingresos j, pacientes b
													WHERE a.evolucion_id = f.evolucion_id
													and f.ingreso = j.ingreso
													and j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id
									        $filtroNombres 
                          $filtroDocumento 
                          $filtroIngreso
												)
												
											) as a, 
                        --ingresos_departamento as b, 
                        estaciones_enfermeria b,
                        cuentas as c, 
                        ingresos as d,
                        departamentos DE
									WHERE b.estacion_id in(".$_SESSION['CENTRALHOSP']['ESTACION'].")
                  AND   b.departamento = DE.departamento
									AND   DE.departamento = d.departamento_actual
									AND   d.ingreso = c.ingreso
									AND   a.tipo_id_paciente=d.tipo_id_paciente
									AND   a.paciente_id=d.paciente_id ";
                  
							$resulta = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
									$this->error = "Error count";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}
              list($this->conteo)=$resulta->fetchRow();
					}
					else
					{  $this->conteo=$_REQUEST['conteo'];  }
					if(!$_REQUEST['Of'])
					{
							$Of='0';
					}
					else
					{
							$Of=$_REQUEST['Of'];
							if($Of > $this->conteo)
							{
									$Of=0;
									$_REQUEST['Of']=0;
									$_REQUEST['paso']=1;
							}
					}

					$query = "SELECT distinct a.*, d.ingreso
										FROM (
												(
													select distinct j.tipo_id_paciente, j.paciente_id,
													b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre
													from hc_os_solicitudes as a, hc_evoluciones as i, ingresos as j,
													pacientes as b
													where i.ingreso=j.ingreso and j.estado='1'
													and i.evolucion_id=a.evolucion_id and a.sw_estado='1'
													and j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id
													$filtroNombres $filtroDocumento $filtroIngreso
												)
												UNION
												(
													select distinct j.tipo_id_paciente, j.paciente_id,
													b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre
													from hc_os_solicitudes as a, hc_evoluciones as i, ingresos as j,
													os_maestro as c, pacientes as b
													where i.ingreso=j.ingreso and i.evolucion_id=a.evolucion_id
													 and j.estado='1'
													and a.hc_os_solicitud_id=c.hc_os_solicitud_id and c.sw_estado in('1','2','3')
													and j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id
													$filtroNombres $filtroDocumento $filtroIngreso
												)
												UNION
												(
													select distinct j.tipo_id_paciente, j.paciente_id,
													b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||
													b.segundo_apellido as nombre
													FROM  hc_medicamentos_recetados_amb a, 
                                hc_evoluciones f,
                                ingresos j, 
                                pacientes b
													WHERE a.evolucion_id = f.evolucion_id
													and   f.ingreso = j.ingreso
													and   j.tipo_id_paciente=b.tipo_id_paciente 
                          and   j.paciente_id=b.paciente_id
									        $filtroNombres $filtroDocumento $filtroIngreso
												)
												UNION
												(
                          select distinct j.tipo_id_paciente, j.paciente_id,
													b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||
													b.segundo_apellido as nombre
													FROM hc_incapacidades a, hc_evoluciones f,
													ingresos j, pacientes b
													WHERE a.evolucion_id = f.evolucion_id
													and f.ingreso = j.ingreso
													and j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id
									        $filtroNombres $filtroDocumento $filtroIngreso
												)
											) as a, 
                          --ingresos_departamento as b, 
                          estaciones_enfermeria b,
                          cuentas as c, 
                          ingresos as d,
                        departamentos DE
									WHERE b.estacion_id in(".$_SESSION['CENTRALHOSP']['ESTACION'].")
                  AND   b.departamento = DE.departamento
									AND   DE.departamento = d.departamento_actual
									AND   d.ingreso = c.ingreso
									AND   a.tipo_id_paciente=d.tipo_id_paciente
									AND   a.paciente_id=d.paciente_id
                  LIMIT ".$this->limit." OFFSET ".$Of.";";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					while(!$resulta->EOF)
					{
							$var[]=$resulta->GetRowAssoc($ToUpper = false);
							$resulta->MoveNext();
					}

					if($this->conteo==='0')
					{
									$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
					}

					$this->FormaBuscar($var);
					return true;
		}

		/**
		*
		*/
		function DetalleImpresion()
		{
				unset($_SESSION['CENTRALHOSP']['PACIENTE']);
				$_SESSION['CENTRALHOSP']['PACIENTE']['ingreso']=$_REQUEST['ingreso'];
				$_SESSION['CENTRALHOSP']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
				$_SESSION['CENTRALHOSP']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
				$_SESSION['CENTRALHOSP']['PACIENTE']['nombre_paciente']=$_REQUEST['nombre'];
				$_SESSION['CENTRALHOSP']['ingreso']=$_REQUEST['ingreso'];
				$_SESSION['CENTRALHOSP']['paciente_id']=$_REQUEST['paciente'];
				$_SESSION['CENTRALHOSP']['tipo_id_paciente']=$_REQUEST['tipoid'];
				$_SESSION['CENTRALHOSP']['nombre_paciente']=$_REQUEST['nombre'];


				$this->FormaDetalleImpresion();
				return true;

		}

//-----------------------------------------LO NUEVO---------------------------

	/**
	*
	*/
	function BuscarPorEstacion()
	{
				$_SESSION['CENTRALHOSP']['ingreso']=$_REQUEST['ingreso'];
				$_SESSION['CENTRALHOSP']['paciente_id']=$_REQUEST['paciente_id'];
				$_SESSION['CENTRALHOSP']['tipo_id_paciente']=$_REQUEST['tipo_id_paciente'];
				$_SESSION['CENTRALHOSP']['ESTACION']=$_REQUEST['nombre_estacion'];
				//cambio lorena pues saca error con los cargos de proovedores externos
				$_SESSION['CENTRALHOSP']['EMPRESA']=$_REQUEST['empresa_id'];
				//fin cambio
			if(empty($_SESSION['CENTRALHOSP']))
			{
						$this->error = "CENTRALHOSP NULA";
						$this->mensajeDeError = "Datos de la CENTRAL vacios.";
						return false;
			}

			if(empty($_SESSION['CENTRALHOSP']['RETORNO']))
			{
						$this->error = "CENTRA ";
						$this->mensajeDeError = "El retorno de la CENTRAL esta vacio.";
						return false;
			}
			
			



			//se necesita los datos paciente
			if(empty($_SESSION['CENTRALHOSP']['tipo_id_paciente']) || empty($_SESSION['CENTRALHOSP']['paciente_id']) || empty($_SESSION['CENTRALHOSP']['ingreso']))
			{
							$this->error = "CENTRAL ";
							$this->mensajeDeError = "Datos de la CENTRAL incompletos.";
							return false;
			}

			$this->BuscarSolicitudes($_SESSION['CENTRALHOSP']['ingreso']);
			return true;
	}

    /**
    *
    */
    function BuscarSwHc()
    {
        list($dbconn) = GetDBconn();
        $query = "select sw_hc from autorizaciones_niveles_autorizador
                  where nivel_autorizador_id='".$_SESSION['CentralImpresionHospitalizacion']['NIVEL']."'";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }
        if(!$result->EOF)
        {  $var=$result->fields[0];  }

        return $var;
    }

    /**
    *
    */
    function BuscarEvolucion($ingreso)
    {    $var='';
        list($dbconn) = GetDBconn();
        $query = "select b.evolucion_id from hc_evoluciones as b
                  where b.ingreso=$ingreso
                  and b.fecha_cierre=(select max(fecha_cierre)
                  from hc_evoluciones  where ingreso=$ingreso)";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }
        if(!$result->EOF)
        {  $var=$result->fields[0];  }

        return $var;
    }

    /**
    *
    */
    function CantidadMeses($plan)
    {
        list($dbconn) = GetDBconn();
        $sql="select meses_consulta_base_datos from planes where plan_id=$plan;";
        $result=$dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }
        $result->Close();
        return $result->fields[0];
    }


    /**
    *
    */
    function DatosBD($TipoId,$PacienteId,$Plan)
    {
          if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
          {
              $this->error = "Error";
              $this->mensajeDeError = "No se pudo incluir : classes/notas_enfermeria/revision_sistemas.class.php";
              return false;
          }
          if(!class_exists('BDAfiliados'))
          {
              $this->error="Error";
              $this->mensajeDeError="no existe BDAfiliados";
              return false;
          }

          $class= New BDAfiliados($TipoId,$PacienteId,$Plan);
          if($class->GetDatosAfiliado()==false)
          {
              $this->error=$class->error;
              $this->mensajeDeError=$class->mensajeDeError;
              return false;
          }

          if(!empty($class->salida))
          {    return true;  }

          return false;
    }


	/**
	*
	*/
	function BuscarSolicitudes($ingreso)
	{
			if($_SESSION['CENTRALHOSP']['RETORNO']['modulo']!='Os_Atencion')
			{
				IncludeLib("funciones_central_impresion");
				$ingreso=$_SESSION['CENTRALHOSP']['ingreso'];
				unset($_SESSION['CENTRALHOSP']['ARREGLO']);
				list($dbconn) = GetDBconn();
				//solicitudes
				//$var=$this->BuscarDetalleSolcitudes($ingreso);
				$var=BuscarSolicitudesIngreso($ingreso,$_SESSION['CENTRALHOSP']['RETORNO']['argumentos']['estacion']['departamento']);
				
				//ordenes
				//$vars2=$this->BuscarOrdenes($ingreso);
				$vars2=BuscarOrdenesIngreso($ingreso);

				$_SESSION['CENTRALHOSP']['ARREGLO']['DETALLE']=$var;
				$_SESSION['CENTRALHOSP']['ARREGLO']['DETALLE2']=$vars2;
				
				$this->FormaDetalleSolicitud();
			}else
			{
				$pac_id=$_SESSION['CENTRALHOSP']['RETORNO']['argumentos']['id'];
				$pac_tipo_id=$_SESSION['CENTRALHOSP']['RETORNO']['argumentos']['tipo_id'];
				$depto=$_SESSION['CENTRALHOSP']['departamento'];
				$this->BuscarSolicitudesXPaciente($pac_id,$pac_tipo_id,$depto);
			}
				return true;
	}

	/**
	*
	*/
	/*function BuscarDetalleSolcitudes($ingreso)
	{
				list($dbconn) = GetDBconn();
				$query = "select distinct i.evolucion_id, i.ingreso, a.cantidad,a.hc_os_solicitud_id,    a.cargo as cargos,
									p.descripcion as descar, p.nivel_autorizador_id, p.sw_pos, j.tipo_id_paciente,
									j.paciente_id,
									k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
									a.plan_id,f.plan_descripcion, a.os_tipo_solicitud_id,
									a.sw_estado,
									l.servicio, p.descripcion,
									m.descripcion as desserv, g.descripcion as desos,
									i.fecha,
									NULL as profesional,NULL as prestador,NULL as observaciones,
									l.descripcion as despto, p.nivel_autorizador_id as nivel,
									q.departamento, r.tipo_id_tercero
									from hc_os_solicitudes as a, planes as f, os_tipos_solicitudes as g, hc_evoluciones as i,
									ingresos as j,
									pacientes as k, departamentos as l, servicios as m,
									cups as p left join departamentos_cargos as q on(p.cargo=q.cargo)
									left join terceros_proveedores_cargos as r on(p.cargo=r.cargo)
									where j.ingreso=$ingreso and p.cargo=a.cargo  and a.plan_id=f.plan_id
									and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id and a.evolucion_id is not null and a.evolucion_id=i.evolucion_id
									and i.ingreso=j.ingreso  and j.tipo_id_paciente=k.tipo_id_paciente and j.paciente_id=k.paciente_id
									and a.sw_estado=1
									and i.departamento=l.departamento and l.servicio=m.servicio";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al traer las solicitudes";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while (!$result->EOF)
				{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
				$result->Close();
				return $var;
	}


	function BuscarOrdenes($ingreso)
	{
				list($dbconn) = GetDBconn();
				$query = "select a.*, b.tipo_afiliado_nombre, c.descripcion as desserv, d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_nombre as nombre, e.numero_orden_id,
									case when e.sw_estado=1 then 'ACTIVO' when e.sw_estado=2 then 'PAGADO' when e.sw_estado=3 then 'PARA ATENCION' when e.sw_estado=7 then 'TRASCRIPCION' when e.sw_estado=8 then 'ANULADA POR VENCIMIENTO'  when e.sw_estado=0 then 'ATENDIDA' else 'ANULADA' end as estado,e.sw_estado,
									e.fecha_vencimiento, e.cantidad, e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups, f.descripcion,
									g.cargo, g.departamento, l.descripcion as desdpto, h.cargo as cargoext,i.plan_proveedor_id, i.plan_descripcion as planpro, k.nombre as autorizador,
									p.plan_descripcion,j.sw_estado
									from os_ordenes_servicios as a
									join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
									left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
									left join departamentos as l on(g.departamento=l.departamento)
									left join os_externas as h on (e.numero_orden_id=h.numero_orden_id)
									left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id),
									tipos_afiliado as b, servicios as c, pacientes as d, cups as f,
									autorizaciones as j, system_usuarios as k,
									planes as p, hc_os_solicitudes as z, hc_evoluciones as x
									where x.ingreso=$ingreso and x.evolucion_id=z.evolucion_id
									and a.tipo_afiliado_id=b.tipo_afiliado_id
									and a.servicio=c.servicio
									and a.plan_id=p.plan_id
									and z.os_tipo_solicitud_id<>'CIT'
									and z.hc_os_solicitud_id=e.hc_os_solicitud_id
									and a.tipo_id_paciente=d.tipo_id_paciente and a.paciente_id=d.paciente_id
									and e.cargo_cups=f.cargo
									and (a.autorizacion_int=j.autorizacion or a.autorizacion_ext=j.autorizacion)
									and j.usuario_id=k.usuario_id
									and e.sw_estado in(1,2,3)
									order by a.orden_servicio_id desc";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Tabal2 autorizaiones";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				while(!$result->EOF)
				{
								$vars2[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
				}
				$result->Close();
				return $vars2;
	}*/

	/**
	*
	*/
	function Planes($ingreso)
	{
				list($dbconn) = GetDBconn();
				$query = "select distinct a.plan_id, b.plan_descripcion
									from hc_os_solicitudes as a, hc_evoluciones as i, ingresos as j, planes as b
									where j.ingreso=$ingreso and i.ingreso=j.ingreso and i.evolucion_id=a.evolucion_id
									and a.sw_estado=1 and a.plan_id=b.plan_id";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Tabal2 autorizaiones";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}
				while(!$result->EOF)
				{
								$vars[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
				}
				$result->Close();
				return $vars;
	}

    /**
    *
    */
    function ClasificarPlan($plan)
    {
          list($dbconn) = GetDBconn();
          $query = "SELECT sw_tipo_plan, sw_afiliacion, protocolos, sw_autoriza_sin_bd
                    FROM planes
                    WHERE plan_id='$plan'";
          $results = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
          }
          $var=$results->GetRowAssoc($ToUpper = false);
          $results->Close();
          return $var;
    }

   /**
    *
    */
    function ValidarEquivalencias($cargo)
    {
          list($dbconn) = GetDBconn();
		  

          $query = "select count(a.cargo)
                  from tarifarios_equivalencias as a
                  left join tarifarios_detalle as h
                  on (a.cargo_base='$cargo' and h.cargo=a.cargo and h.tarifario_id=a.tarifario_id)
                  where a.cargo_base='$cargo'";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error al Guardar en la Tabal autorizaiones";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  return false;
          }
          if(!$result->EOF)
          {   $var=$result->fields[0];  }

          return $var;
    }
    /**
    * Funcion donde se busca la reserva de sagre depediendo del ingreso del paciente
    *
    * @param integer $ingreso el ingreso del paciente
    * @return boolean
    */
    function Get_Info_RerservaSangre($ingreso)
    {
          list($dbconn) = GetDBconn();
		  
		  //$dbconn->debug=true;
		  $query = "SELECT  A.solicitud_reserva_sangre_id, 
                             A.evolucion_id,C.tipo_id_tercero,C.tercero_id,C.nombre as nombre_tercero
                     FROM    banco_sangre_reserva_hc AS A,
                             banco_sangre_reserva AS B,
					         profesionales AS C
                     WHERE   A.ingreso = ".$ingreso."
                     AND     A.solicitud_reserva_sangre_id = B.solicitud_reserva_sangre_id
                     AND     B.usuario_id = C.usuario_id
                      ;";
         $resulta=$dbconn->execute($query);
      if ($dbconn->ErrorNo() != 0) 
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        $this->fileError = __FILE__;
        $this->lineError = __LINE__;
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
    * Funcion donde se busca la transfusion de sagre depediendo del ingreso del paciente
    *
    * @param integer $ingreso el ingreso del paciente
    * @return boolean
    */
    function Get_Info_TransfusionSangre($ingreso)
    {
      list($dbconn) = GetDBconn();
      //$dbconn->debug=true;
      $query = "SELECT    A.*,C.tipo_id_tercero,C.tercero_id,C.nombre as nombre_tercero
                FROM     hc_control_transfusiones as A, profesionales AS C 
                WHERE    A.ingreso = ".$ingreso."
				AND      A.usuario = C.usuario_id
                      ;";
           $resulta=$dbconn->execute($query);
      if ($dbconn->ErrorNo() != 0) 
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        $this->fileError = __FILE__;
        $this->lineError = __LINE__;
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
    *
    */
    function EdadPaciente($paciente_id,$tipo_id_paciente)
    {
          list($dbconn) = GetDBconn();
		 //$dbconn->debug=true;
          $query = "SELECT  edad_completa(fecha_nacimiento) as edad_paciente
                     FROM    pacientes
                     WHERE   paciente_id = '".$paciente_id."'
                     AND     tipo_id_paciente = '".$tipo_id_paciente."'  
					 ;";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error al Guardar en la Tabal autorizaiones";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  return false;
          }
          if(!$result->EOF)
          {   $var=$result->fields[0];  }

          return $var;
    }
    /**
    *
    */
    function ValidarContrato($cargo,$plan)
    {
          list($dbconn) = GetDBconn();
          $query = "( 	select r.plan_id
												from tarifarios_equivalencias as a, tarifarios_detalle as h,
												plan_tarifario as r
												where a.cargo_base='$cargo' and h.cargo=a.cargo
												and h.tarifario_id=a.tarifario_id
												and r.plan_id=$plan and h.grupo_tarifario_id=r.grupo_tarifario_id
												and h.subgrupo_tarifario_id=r.subgrupo_tarifario_id
												and h.tarifario_id=r.tarifario_id
												and excepciones(r.plan_id,r.tarifario_id,h.cargo)=0
										)
										UNION
										(
												SELECT b.plan_id
												FROM tarifarios_detalle a, excepciones b, subgrupos_tarifarios e,
												tarifarios_equivalencias as c
												WHERE c.cargo_base='$cargo'
												and a.cargo=c.cargo
												and a.tarifario_id=c.tarifario_id
												and b.plan_id = $plan AND
												b.tarifario_id = a.tarifario_id AND
												b.sw_no_contratado = 0 AND
												b.cargo = a.cargo AND
												e.grupo_tarifario_id = a.grupo_tarifario_id AND
												e.subgrupo_tarifario_id = a.subgrupo_tarifario_id
                    )";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error al Guardar en la Tabal autorizaiones";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  return false;
          }

          if(!$result->EOF)
          {  $var=$result->RecordCount();  }

          return $var;
    }


    /**
    *
    */
    function ValidarContratoEqui($tarifario,$cargo,$plan)
    {
          list($dbconn) = GetDBconn();
          $query = "( 	select r.plan_id
												from tarifarios_detalle as h, plan_tarifario as r
												where h.cargo='$cargo' and h.tarifario_id='$tarifario'
												and r.plan_id=$plan and h.grupo_tarifario_id=r.grupo_tarifario_id
												and h.subgrupo_tarifario_id=r.subgrupo_tarifario_id
												and h.tarifario_id=r.tarifario_id
												and excepciones(r.plan_id,h.tarifario_id,h.cargo)=0
										)
										UNION
										(
												SELECT b.plan_id
												FROM tarifarios_detalle a, excepciones b, subgrupos_tarifarios e
												WHERE a.cargo='$cargo' and a.tarifario_id='$tarifario'
												and b.plan_id = $plan AND
												b.tarifario_id = a.tarifario_id AND
												b.sw_no_contratado = 0 AND
												b.cargo = a.cargo AND
												e.grupo_tarifario_id = a.grupo_tarifario_id AND
												e.subgrupo_tarifario_id = a.subgrupo_tarifario_id
                    )";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error al Guardar en la Tabal autorizaiones";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  return false;
          }

          if(!$result->EOF)
          {   $var=$result->RecordCount();  }

          return $var;
    }

//------------------AUTORIZACIONES-----------------------------------------
    /**
    *
    */
    function PedirAutorizacion()
    {
             //valida si eligieron algun cargo
            $f=0;
            foreach($_REQUEST as $k => $v)
            {
                if(substr_count($k,'Auto'))
                {
                        $f=1;
                }
            }
            if($f==0)
            {
                    $this->frmError["MensajeError"]="ERROR DATOS VACIOS: Debe elegir alguna Solicitud para Autorizar.";
                    $this->FormaDetalleSolicitud();
                    return true;
            }

            list($dbconn) = GetDBconn();
            unset($_SESSION['AUTORIZACIONES']);
            foreach($_REQUEST as $k => $v)
            {
                if(substr_count($k,'Auto'))
                {
                        $arr=explode(',',$v);
                        //2 ingreso 4 solicitud_id, 0 cargo, 1 tarifario, 3 servicio
                  /*      $query = "select * from oos_maestro_cargos
                                      where numero_orden_id";*/

                        if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso']))
                        {   $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso']=$arr[2];   }
                        $_SESSION['CENTRALHOSP']['SERVICIO']=$arr[3];
                        $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO'][$arr[4]][$arr[0]][$arr[1]][$arr[3]]=$arr[2];
                }
            }
						$sql="SELECT d.rango, d.tipo_afiliado_id, d.semanas_cotizadas
									FROM 	hc_os_solicitudes a, 
												hc_evoluciones b,
												ingresos c,
												cuentas d
												
									WHERE a.hc_os_solicitud_id = $arr[4]
												AND a.evolucion_id IS NOT NULL
												AND a.evolucion_id = b.evolucion_id
												AND c.ingreso = b.ingreso
												AND c.ingreso = d.ingreso
												AND d.estado IN (1,2)
												AND d.plan_id = " . $_REQUEST['plan'] . ";";

            list($dbconn) = GetDBconn();						 
            $result = $dbconn->Execute($sql);
						
            if($dbconn->ErrorNo() == 0)
						{
							if(!$result->EOF)
							{
								list($_SESSION['AUTORIZACIONES']['RANGO'],$_SESSION['AUTORIZACIONES']['AFILIADO'],$_SESSION['AUTORIZACIONES']['SEMANAS'])=$result->FetchRow();			
							}
							else
							{
									$_SESSION['AUTORIZACIONES']['SEMANAS']=NULL;
									$_SESSION['AUTORIZACIONES']['AFILIADO']=NULL;
									$_SESSION['AUTORIZACIONES']['RANGO']=NULL;							
							}
							$result->Close();
						}

            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$_SESSION['CENTRALHOSP']['paciente_id'];
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$_SESSION['CENTRALHOSP']['tipo_id_paciente'];
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']='CENTROAUTORIZACION';
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$_REQUEST['plan'];
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']=array();
            $_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
            $_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='CentralImpresionHospitalizacion';
            $_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';
            $_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='RetornoAutorizacion';

            $_SESSION['CENTRALHOSP']['PLAN']=$_REQUEST['plan'];
            $_SESSION['CENTRALHOSP']['SERVICIO']=$_REQUEST['servicio'];

						$this->ReturnMetodoExterno('app','CentroAutorizacion','user','ValidarCentroAutorizacion');
						return true;
    }

    /**
    * Llama el modulo de autorizaciones
    * @access public
    * @return boolean
    */
    function RetornoAutorizacion()
    {
          $_SESSION['CENTRALHOSP']['tipo_afiliado_id']=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id'];
          $_SESSION['CENTRALHOSP']['rango']=$_SESSION['AUTORIZACIONES']['RETORNO']['rango'];
          $_SESSION['CENTRALHOSP']['semanas']=$_SESSION['AUTORIZACIONES']['RETORNO']['semanas'];
          $Mensaje=$_SESSION['AUTORIZACIONES']['RETORNO']['Mensaje'];
          $_SESSION['CENTRALHOSP']['ext']=$_SESSION['AUTORIZACIONES']['RETORNO']['ext'];
          $_SESSION['CENTRALHOSP']['Autorizacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];
          $_SESSION['CENTRALHOSP']['NumAutorizacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion'];
          $_SESSION['CENTRALHOSP']['observacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['ObservacionesOS'];
          $_SESSION['CENTRALHOSP']['PLAN']=$_SESSION['AUTORIZACIONES']['RETORNO']['plan_id'];

          list($dbconn) = GetDBconn();
          $query = "DELETE FROM hc_os_autorizaciones_proceso WHERE
          tipo_id_paciente='".$_SESSION['CENTRALHOSP']['tipo_id_paciente']."'
          AND paciente_id='".$_SESSION['CENTRALHOSP']['paciente_id']."'
          AND plan_id=".$_SESSION['CENTRALHOSP']['PLAN']."
          AND usuario_id=".UserGetUID()."";
          $dbconn->Execute($query);

          /*if(!empty($_SESSION['CentralImpresionHospitalizacion']['TODO']['Autorizacion'])
            AND  empty($_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion']))
          {
              $this->FormaBuscarTodos();
              return true;
          }*/

          if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['TRAMITE']))
          {
                $Mensaje = 'La toma de requerimientos se realizo.';
                $accion=ModuloGetURL('app','CentralImpresionHospitalizacion','user','BuscarSolicitudes');
                if(!$this-> FormaMensaje($Mensaje,'CENTRO AUTORIZACIONES',$accion,'')){
                    return false;
                }
                return true;
          }
          //unset($_SESSION['AUTORIZACIONES']);
          if(empty($_SESSION['CENTRALHOSP']['Autorizacion'])
            AND empty($_SESSION['CENTRALHOSP']['NumAutorizacion']))
          {
                      //if(empty($_SESSION['CentralImpresionHospitalizacion']['TODO']['NumAutorizacion']))
                      $Mensaje = 'No se pudo realizar la Autorizaci�n para la Orden.';
                      $accion=ModuloGetURL('app','CentralImpresionHospitalizacion','user','BuscarSolicitudes');
                      if(!$this-> FormaMensaje($Mensaje,'CENTRO AUTORIZACIONES',$accion,'')){
                      return false;
                      }
                      return true;
          }

          $query = "(select a.hc_os_solicitud_id
                    from hc_os_autorizaciones as a
                    where (a.autorizacion_int=".$_SESSION['CENTRALHOSP']['Autorizacion']." OR
                    a.autorizacion_ext=".$_SESSION['CENTRALHOSP']['Autorizacion'].")
                    )
                    union
                    (select a.hc_os_solicitud_id
                    from hc_os_autorizaciones as a
                    where (a.autorizacion_int=".$_SESSION['CENTRALHOSP']['Autorizacion']." OR
                    a.autorizacion_ext=".$_SESSION['CENTRALHOSP']['Autorizacion'].")
                    )";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error select hc_os_solicitud_id";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  return false;
          }
          while(!$result->EOF)
          {
                  $query = "UPDATE hc_os_solicitudes SET   sw_estado=0
                                      WHERE hc_os_solicitud_id=".$result->fields[0]."";
                  $dbconn->Execute($query);
                  if ($dbconn->ErrorNo() != 0) {
                          $this->error = "Error UPDATE  hc_os_solicitudes ";
                          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                          $dbconn->RollbackTrans();
                          return false;
                  }
                  $result->MoveNext();
          }
          if(!empty($_SESSION['CENTRALHOSP']['Autorizacion'])
          AND empty($_SESSION['CENTRALHOSP']['NumAutorizacion']))
          {
                      $Mensaje = 'No se Autorizo la Orden.';
                      $accion=ModuloGetURL('app','CentralImpresionHospitalizacion','user','FormaDetalleSolicitud');
                      if(!$this-> FormaMensaje($Mensaje,'CENTRO AUTORIZACIONES',$accion,'')){
                      return false;
                      }
                      return true;
          }

          $query = "
										SELECT  x.*, h.descripcion, r.descripcion as descar 
										FROM
										((							
													SELECT a.fecha, a.hc_os_solicitud_id, a.cantidad, a.cargos,
															a.plan_id, a.os_tipo_solicitud_id, a.tarifario_id, a.cargo,
															q.servicio,a.evento_soat
													FROM
															(
																	SELECT b.cargo as cargos, e.fecha, b.hc_os_solicitud_id, b.cantidad, b.plan_id, b.os_tipo_solicitud_id, n.tarifario_id, n.cargo, NULL as servicio, e.departamento, b.cargo as cargo_base,soat.evento as evento_soat
																	FROM
																			hc_os_autorizaciones as a,
																			hc_os_solicitudes as b,
																			hc_evoluciones as e
																			left join ingresos_soat soat on(e.ingreso=soat.ingreso),
																			tarifarios_equivalencias as n
																	WHERE
																			a.autorizacion_int = ".$_SESSION['CENTRALHOSP']['Autorizacion']."
																			AND b.hc_os_solicitud_id = a.hc_os_solicitud_id
																			AND b.plan_id = ".$_SESSION['CENTRALHOSP']['PLAN']."
																			AND e.evolucion_id = b.evolucion_id
																			AND n.cargo_base = b.cargo
															) AS a,
															departamentos as q
													WHERE q.departamento = a.departamento
											)
											UNION
											(
													SELECT  a.fecha, a.hc_os_solicitud_id, a.cantidad, a.cargos,
																	a.plan_id, a.os_tipo_solicitud_id, a.tarifario_id, a.cargo,
																	a.servicio,a.evento_soat
													FROM
															(
																	SELECT b.cargo as cargos, e.fecha, b.hc_os_solicitud_id, b.cantidad, b.plan_id, b.os_tipo_solicitud_id, n.tarifario_id, n.cargo, e.servicio, e.departamento, b.cargo as cargo_base,e.evento_soat
																	FROM
																			hc_os_autorizaciones as a,
																			hc_os_solicitudes as b,
																			hc_os_solicitudes_manuales as e,
																			tarifarios_equivalencias as n
																	WHERE
																			a.autorizacion_int = ".$_SESSION['CENTRALHOSP']['Autorizacion']."
																			AND b.hc_os_solicitud_id = a.hc_os_solicitud_id
																			AND b.plan_id = ".$_SESSION['CENTRALHOSP']['PLAN']."
																			AND e.hc_os_solicitud_id = b.hc_os_solicitud_id
																			AND n.cargo_base = b.cargo
															) AS a LEFT JOIN departamentos as q ON (q.departamento=a.departamento)
											)) as x,
												cups as r,
												tarifarios_detalle as h,
												plan_tarifario as z	
										WHERE
												r.cargo = x.cargos
												AND (h.tarifario_id = x.tarifario_id AND h.cargo = x.cargo)
												AND z.plan_id = ".$_SESSION['CENTRALHOSP']['PLAN']."
												AND z.grupo_tarifario_id = h.grupo_tarifario_id
												AND z.subgrupo_tarifario_id = h.subgrupo_tarifario_id
												AND h.tarifario_id = z.tarifario_id
												ORDER BY x.hc_os_solicitud_id						
										";
									
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error select ";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  return false;
          }

          while(!$result->EOF)
          {
                  $var[]=$result->GetRowAssoc($ToUpper = false);
                  $result->MoveNext();
          }

          $this->FormaListadoCargos($var);
          return true;
    }

		/**
		*
		*/
		function GenerarOS()
		{
		
						$auto=$_SESSION['CENTRALHOSP']['Autorizacion'];

					 if(!empty($_REQUEST['cancelar']))
            {
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
								
                $query = "select a.hc_os_solicitud_id from hc_os_autorizaciones as a
                              where (a.autorizacion_int=".$auto." OR
                              a.autorizacion_ext=".$auto.")";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
								
                        $this->error = "Error select ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$dbconn->RollbackTrans();
                        return false;
                }
                while(!$result->EOF)
                {
                        $query = "UPDATE hc_os_solicitudes SET
                                                sw_estado=1
                                            WHERE hc_os_solicitud_id=".$result->fields[0]."";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
								
                                $this->error = "Error UPDATE  hc_os_solicitudes ";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                        }
                        $query = "DELETE FROM hc_os_autorizaciones
                                  WHERE hc_os_solicitud_id=".$result->fields[0]."";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
										
                                $this->error = "Error DELETE FROM hc_os_licitudes ";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                        }
                        if($result->RecordCount()>0)
                        { $result->MoveNext();  }
                }
                $result->Close();
                $dbconn->CommitTrans(); 
								$this->BuscarSolicitudes();
                return true;
            }

						IncludeLib("malla_validadora");

						$auto=$_SESSION['CENTRALHOSP']['Autorizacion'];
						$plan=$_SESSION['CENTRALHOSP']['PLAN'];
						$rango=$_SESSION['CENTRALHOSP']['rango'];
						$empresa=$_SESSION['CENTRALHOSP']['EMPRESA'];
						$afiliado=$_SESSION['CENTRALHOSP']['tipo_afiliado_id'];
						$semana=$_SESSION['CENTRALHOSP']['semanas'];
						$paciente=$_SESSION['CENTRALHOSP']['paciente_id'];
						$tipo=$_SESSION['CENTRALHOSP']['tipo_id_paciente'];
						$msg=$_SESSION['CENTRALHOSP']['observacion'];
						$servicio=$_SESSION['CENTRALHOSP']['SERVICIO'];
						if(empty($_SESSION['CENTRALHOSP']['ext']))
						{  $ext='NULL';  }
						else
						{  $ext=$auto;  }

            //va hacer la transcripcion
            if(!empty($_REQUEST['Transcripcion']))
            {
									$arreglo['x'][]=$_REQUEST['dat'];
									$inser=GenerarVariasOS($arreglo,$_REQUEST,$auto,$ext,$tipo,$paciente,$plan,$afiliado,$rango,$semana,$servicio,$msg,$empresa,true);
									if(empty($inser))
									{
                    $this->frmError["MensajeError"]="ERROR AL INSERTAR TRASCRIPCION.";
                    $this->FormaListadoCargos($_SESSION['CENTRAL_IMPRESION_HOSPITALIZACION']['ARREGLO_ORDENES']);
                    return true;
									}
									else
									{
           							list($dbconn) = GetDBconn();

												$query = "(select distinct  e.fecha,a.hc_os_solicitud_id, b.cantidad,b.cargo as cargos,b.plan_id,
																		b.os_tipo_solicitud_id, n.cargo,
																		n.tarifario_id,h.descripcion, r.descripcion as descar,soat.evento as evento_soat
																		from hc_os_autorizaciones as a,hc_os_solicitudes as b
																		join tarifarios_equivalencias as n on(n.cargo_base=b.cargo)
																		join tarifarios_detalle as h on (h.cargo=n.cargo and h.tarifario_id=n.tarifario_id),
																		hc_evoluciones as e
																		left join ingresos_soat soat on (soat.ingreso=e.ingreso)
																		, cups as r
																		where (a.autorizacion_int=".$auto."
																		OR a.autorizacion_ext=".$auto.")
																		and b.cargo=r.cargo
																		and a.hc_os_solicitud_id=b.hc_os_solicitud_id
																		and b.evolucion_id is not null
																		and e.evolucion_id=b.evolucion_id
																		and b.hc_os_solicitud_id not in(select hc_os_solicitud_id from os_maestro)
																	)";
												$result = $dbconn->Execute($query);
												if ($dbconn->ErrorNo() != 0) {
																$this->error = "Error select ";
																$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																return false;
												}
												if(!$result->EOF)
												{
															while(!$result->EOF)
															{
																			$vars[]=$result->GetRowAssoc($ToUpper = false);
																			$result->MoveNext();
															}
															$this->frmError["MensajeError"]="La Transcripcion Fue Realizada. Orden generada $numorden.";
															$this->FormaListadoCargos($vars);
															return true;
												}
												else
												{
															$Mensaje = 'La Transcripcion Fue Realizada.';
															$accion=ModuloGetURL('app','CentralImpresionHospitalizacion','user','BuscarSolicitudes');
															if(!$this-> FormaMensaje($Mensaje,'CENTRO AUTORIZACIONES',$accion,'')){
																	return false;
															}
															return true;
												}
									}
            }

            if(!empty($_REQUEST['Trans']))
            {
                    $this->frmError["MensajeError"]="ERROR: Debe Hacer Primero la Transcripci�n.";
                    $this->FormaListadoCargos($_SESSION['CENTRAL_IMPRESION_HOSPITALIZACION']['ARREGLO_ORDENES']);
                    return true;
            }

            $f=0;
            foreach($_REQUEST as $k => $v)
            {
                if(substr_count($k,'Combo'))
                {
                      if($v==-1)
                      {    $f=1; }
                 }
            }
            if($f==1)
            {
                    $this->frmError["MensajeError"]="ERROR DATOS VACIOS: Debe elegir algun Departamento o Proveedor del Cargo.";
                    $this->FormaListadoCargos($_SESSION['CENTRAL_IMPRESION_HOSPITALIZACION']['ARREGLO_ORDENES']);
                    return true;
           }

            foreach($_REQUEST as $k => $v)
            {
                if(substr_count($k,'Combo'))
                {
                      if($v!=-1)
                      {    //0 hc_os_solicitud_id
                          $arr=explode(',',$v);
                          $d=0;
                          foreach($_REQUEST as $ke => $va)
                          {
                              if(substr_count($ke,'Op'))
                              {    // 0 solicitud_id
                                  $var=explode(',',$va);
                                  if($var[0]==$arr[0])
                                  {  $d=1;  }
                              }
                          }
                          if($d==0)
                          {
                                  $this->frmError["MensajeError"]="ERROR DATOS VACIOS: Debe elegir algun Cargo.";
                                  $this->FormaListadoCargos($_SESSION['CENTRAL_IMPRESION_HOSPITALIZACION']['ARREGLO_ORDENES']);
                                  return true;
                          }
                      }
                }
            }

            foreach($_REQUEST as $k => $v)
            {
                if(substr_count($k,'Combo'))
                {      //0 hc_os_solicitud_id 2 tipo_id_tercero o dpto 1 tercero_id o departo si es departamento(interna)
                      //3 tarifario 4 cargo 5 cargocups 6 fecha 7 plan_proveedor 8 cantidad
                      $arr=explode(',',$v);
                      $arreglo[$arr[1].'-'.$arr[9]][]=$v;
											$eventos_soat[$arr[1].'-'.$arr[9]]=$arr[9];
                 }
            }

						$inser=GenerarVariasOS($arreglo,$_REQUEST,$auto,$ext,$tipo,$paciente,$plan,$afiliado,$rango,$semana,$servicio,$msg,$empresa,'',$eventos_soat);
						if(empty($inser))
						{
									list($dbconn) = GetDBconn();
									$dbconn->BeginTrans();
									$query = "select a.hc_os_solicitud_id from hc_os_autorizaciones as a
																where (a.autorizacion_int=".$auto." OR
																a.autorizacion_ext=".$auto.")";
									$result = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
													$this->error = "Error select ";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													return false;
									}
									while(!$result->EOF)
									{
													$query = "UPDATE hc_os_solicitudes SET
																									sw_estado=1
																							WHERE hc_os_solicitud_id=".$result->fields[0]."";
													$dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0) {
																	$this->error = "Error UPDATE  hc_os_solicitudes ";
																	$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																	$dbconn->RollbackTrans();
																	return false;
													}
													$query = "DELETE FROM hc_os_autorizaciones
																		WHERE hc_os_solicitud_id=".$result->fields[0]."";
													$dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0) {
																	$this->error = "Error DELETE FROM hc_os_licitudes ";
																	$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																	$dbconn->RollbackTrans();
																	return false;
													}
													if($result->RecordCount()>0)
													{ $result->MoveNext();  }
									}
									$result->Close();
                	$dbconn->CommitTrans();
									$this->frmError["MensajeError"]="ERROR EN LA CREACION DE LA ORDEN.";
									
									$this->FormaDetalleSolicitud();
									return true;
						}
						else
						{
									$this->frmError["MensajeError"]="SE CREARON LAS ORDENES.";
									$this->BuscarSolicitudes();
									return true;
						}
		}


//--------------------------------REPORTE----------------------------------

    /**
    *
    */
    function EncabezadoReporte($ingreso,$tipo,$id)
    {
        unset($_SESSION['CENTRAL']['DATOS']);
        list($dbconn) = GetDBconn();
        $query = "select  b.tipo_id_paciente, b.paciente_id, b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
                  t.tipo_id_tercero, t.id, t.razon_social, t.direccion, t.telefonos, u.departamento,
                  v.municipio, p.plan_descripcion, p.nombre_cuota_moderadora, p.nombre_copago,
                  w.nombre_tercero, d.tipo_afiliado_nombre, c.rango,
                  f.nombre as usuario, f.usuario_id, c.plan_id,d.tipo_afiliado_id
									from pacientes as b, cuentas as c,
                  empresas as t,   tipo_dptos as u, tipo_mpios as v, planes as p, terceros as w,
                  tipos_afiliado as d, system_usuarios as f
									where c.ingreso=$ingreso
                  and c.empresa_id=t.empresa_id
                  and c.tipo_afiliado_id=d.tipo_afiliado_id
                  and f.usuario_id=".UserGetUID()."
                  and b.tipo_id_paciente='".$tipo."'
                  and c.plan_id=p.plan_id
                  and b.paciente_id='".$id."'
                  and t.tipo_pais_id=u.tipo_pais_id
                  and t.tipo_dpto_id=u.tipo_dpto_id
                  and t.tipo_pais_id=v.tipo_pais_id
                  and t.tipo_dpto_id=v.tipo_dpto_id
                  and t.tipo_mpio_id=v.tipo_mpio_id
                  and w.tipo_id_tercero=p.tipo_tercero_id
                  and w.tercero_id=p.tercero_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        else
        {
              $var=$result->GetRowAssoc($ToUpper = false);
        }
        $result->Close();
        return $var;
    }

    /**
    *
    */
    function ReporteOrdenServicio()
    {
        if (!IncludeFile("classes/reports/reports.class.php")) {
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
        }

				 $var[0]=$this->EncabezadoReporte($_SESSION['CENTRALHOSP']['ingreso'],$_SESSION['CENTRALHOSP']['tipo_id_paciente'],$_SESSION['CENTRALHOSP']['paciente_id']);
        //$var[0]=$this->EncabezadoReporte($_REQUEST['orden'],$_REQUEST['tipoid'],$_REQUEST['paciente'],$_REQUEST['afiliado'],$_REQUEST['plan']);

        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        $query = "select a.*,
                         e.numero_orden_id,
                         e.sw_estado, 
                         e.fecha_vencimiento, 
                         e.cantidad,
                         e.hc_os_solicitud_id, 
                         e.fecha_activacion, 
                         e.fecha_refrendar, 
                         e.cargo_cups,
                         f.descripcion, 
                         g.cargo, 
                         g.departamento, 
                         l.descripcion as desdpto,
                         h.cargo as cargoext,  
                         i.plan_proveedor_id, 
                         i.plan_descripcion as planpro,
                         z.tarifario_id, z.cargo, y.requisitos,
                         x.nombre_tercero as nompro, 
                         x.direccion  as dirpro, x.telefono as telpro,
                         s.descripcion as descar, 
                         NULL as profesional,
                         q.evolucion_id, 
                         n.observacion as obsapoyo,
			 o.observacion as obsinter, 
                         o.especialidad, 
                         a.observacion,
                         AB.descripcion as especialidad_nombre, 
                         BB.observacion as obsnoqx,
		         dsaq.horas_estimadas,
		         dsaq.minutos_estimados
                  from   os_ordenes_servicios as a
                         left join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id and e.sw_estado in('1','2','3'))
                         left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
                         left join departamentos as l on(g.departamento=l.departamento)
                         left join os_externas as h on (e.numero_orden_id=h.numero_orden_id)
                         left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id)
                         left join hc_os_solicitudes as q on(e.hc_os_solicitud_id=q.hc_os_solicitud_id)
						 left join hc_os_solicitudes_acto_qx as saq on( saq.hc_os_solicitud_id 	=q.hc_os_solicitud_id )
						 left join hc_os_solicitudes_datos_acto_qx as dsaq  on( saq.acto_qx_id 	=dsaq.acto_qx_id )
			 left join hc_os_solicitudes_apoyod as n on(n.hc_os_solicitud_id=q.hc_os_solicitud_id)
			 left join hc_os_solicitudes_interconsultas as o on(o.hc_os_solicitud_id=q.hc_os_solicitud_id)
			 left join hc_os_solicitudes_no_quirurgicos as BB on(BB.hc_os_solicitud_id=q.hc_os_solicitud_id)			left join especialidades as AB on(AB.especialidad=o.especialidad )
                         left join os_maestro_cargos as z on(z.numero_orden_id=e.numero_orden_id)
                         left join tarifarios_detalle as s on(s.cargo=z.cargo and s.tarifario_id=z.tarifario_id)
                         left join terceros as x on(x.tipo_id_tercero=i.tipo_id_tercero and x.tercero_id=i.tercero_id)
                         left join cups as f on(e.cargo_cups=f.cargo)
                         left join hc_apoyod_requisitos as y on(f.cargo=y.cargo) 
                  where a.orden_servicio_id=".$_REQUEST['orden']."
                  and a.tipo_afiliado_id='".$_REQUEST['afiliado']."'
                  and a.plan_id=".$_REQUEST['plan']."
                  
                  
                  and q.evolucion_id is not null
                  and a.tipo_id_paciente='".$_REQUEST['tipoid']."'
                  and a.paciente_id='".$_REQUEST['paciente']."'
                  order by q.evolucion_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        else
        {
            while (!$result->EOF)
            {
              $var[]=$result->GetRowAssoc($ToUpper = false);
              $result->MoveNext();
            }
        }
        $result->Close();

        $classReport = new reports;

				if($_REQUEST['pos']==1)
				{
						$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
						$reporte=$classReport->PrintReport('pos','app','CentroAutorizacion','ordenservicio',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
						if(!$reporte){
								$this->error = $classReport->GetError();
								$this->mensajeDeError = $classReport->MensajeDeError();
								unset($classReport);
								return false;
						}

						$resultado=$classReport->GetExecResultado();
						unset($classReport);


						if(!empty($resultado[codigo])){
								"El PrintReport retorno : " . $resultado[codigo] . "<br>";
						}

						if(!empty($_REQUEST['regreso']))//cuando es la impresion desde la autorizacion
						{  $this->$_REQUEST['regreso']();  }
						if(!empty($_REQUEST['regreso2']))//cuando es la impresion es desde listadoo
						{  $this->$_REQUEST['regreso2']($_REQUEST['tipoid'],$_REQUEST['paciente']);  }
						return true;
  			}
				else
				{
						/*$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pdf');
						$reporte=$classReport->PrintReport($tipo_reporte='pdf',$tipo_modulo='app',$modulo='CentralImpresionHospitalizacion',$reporte_name='ordenservicioPDF',$var,$impresora,$orientacion='P',$unidades='mm',$formato='letter',$html=1);*/
						if ($_REQUEST['parametro_retorno'] == '1')
						{
								IncludeLib("reportes/ordenservicio");
								GenerarOrden($var);
								if(is_array($var))
								{
										$RUTA = $_ROOT ."cache/ordenservicio.pdf";
										$mostrar ="\n<script language='javascript'>\n";
										$mostrar.="var rem=\"\";\n";
										$mostrar.="  function abreVentana(){\n";
										$mostrar.="    var nombre=\"\"\n";
										$mostrar.="    var url2=\"\"\n";
										$mostrar.="    var str=\"\"\n";
										$mostrar.="    var ALTO=screen.height\n";
										$mostrar.="    var ANCHO=screen.width\n";
										$mostrar.="    var nombre=\"REPORTE\";\n";
										$mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
										$mostrar.="    var url2 ='$RUTA';\n";
										$mostrar.="    rem = window.open(url2, nombre, str)};\n";
										$mostrar.="</script>\n";
										$this->salida.="$mostrar";
										$this->salida.="<BODY onload=abreVentana();>";
								}
								$this->ReturnMetodoExterno('app','ImpresionHC','user','FormaImpresionSolicitudes');
						}
						else
						{
								IncludeLib("reportes/ordenservicio");
								$vector['orden']=$_REQUEST['orden'];
								GenerarOrden($vector);
								$this->FormaDetalleImpresion($vector,3);
						}
				}
				return true;
    }

      /**
    *
    */
    function Reportesolicitudes()
    {
				if (!IncludeFile("classes/reports/reports.class.php")) {
						$this->error = "No se pudo inicializar la Clase de Reportes";
						$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
						return false;
				}

				$var[0]=$this->EncabezadoReporte($_SESSION['CENTRALHOSP']['ingreso'],$_SESSION['CENTRALHOSP']['tipo_id_paciente'],$_SESSION['CENTRALHOSP']['paciente_id']);

				for($i=0; $i<sizeof($_SESSION['CENTRALHOSP']['ARR_SOLICITUDES']);$i++)
				{
						$var[$i+1]=$_SESSION['CENTRALHOSP']['ARR_SOLICITUDES'][$i];
				}

				$classReport = new reports;
				if($_REQUEST['pos']==1)
				{
						$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
						$reporte=$classReport->PrintReport('pos','app','Central_de_Autorizaciones','solicitudes',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
						if(!$reporte)
						{
								$this->error = $classReport->GetError();
								$this->mensajeDeError = $classReport->MensajeDeError();
								unset($classReport);
								return false;
						}
						$resultado=$classReport->GetExecResultado();
						unset($classReport);
						if(!empty($resultado[codigo]))
						{
								"El PrintReport retorno : " . $resultado[codigo] . "<br>";
						}
						$this->FormaDetalleImpresion();
						return true;
				}
				else
				{
						/*
						$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pdf');
						$reporte=$classReport->PrintReport($tipo_reporte='pdf',$tipo_modulo='app',$modulo='CentralImpresionHospitalizacion',$reporte_name='solicitudesPDF',$var,$impresora,$orientacion='P',$unidades='mm',$formato='letter',$html=1);
						*/

						if ($_REQUEST['parametro_retorno'] == '1')
						{
								IncludeLib("reportes/solicitudes");
								GenerarSolicitud($var);
								if(is_array($var))
								{
										$RUTA = $_ROOT ."cache/solicitudes.pdf";
										$mostrar ="\n<script language='javascript'>\n";
										$mostrar.="var rem=\"\";\n";
										$mostrar.="  function abreVentana(){\n";
										$mostrar.="    var nombre=\"\"\n";
										$mostrar.="    var url2=\"\"\n";
										$mostrar.="    var str=\"\"\n";
										$mostrar.="    var ALTO=screen.height\n";
										$mostrar.="    var ANCHO=screen.width\n";
										$mostrar.="    var nombre=\"REPORTE\";\n";
										$mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
										$mostrar.="    var url2 ='$RUTA';\n";
										$mostrar.="    rem = window.open(url2, nombre, str)};\n";
										$mostrar.="</script>\n";
										$this->salida.="$mostrar";
										$this->salida.="<BODY onload=abreVentana();>";
									}
								$this->ReturnMetodoExterno('app','ImpresionHC','user','FormaImpresionSolicitudes');
						}
						else
						{
								IncludeLib("reportes/solicitudes");
								$vector['ingreso']=$_SESSION['CENTRALHOSP']['PACIENTE']['ingreso'];
								$vector['TipoDocumento']=$_SESSION['CENTRALHOSP']['PACIENTE']['tipo_id_paciente'];
								$vector['Documento']=$_SESSION['CENTRALHOSP']['PACIENTE']['paciente_id'];
								GenerarSolicitud($vector);
								$this->FormaDetalleImpresion($vector,2);
						}
				}
				return true;
		}

//-----------------------------------------------------------------------------------

    /**
    *
    */
    function ComboProveedor($Cargo)
    {
              
              list($dbconn) = GetDBconn();
			  
              /*$query = "select a.tipo_id_tercero, a.tercero_id, a.cargo,  c.plan_proveedor_id, c.empresa_id,
                                    c.plan_descripcion
                                    from terceros_proveedores_cargos as a, planes_proveedores as c,terceros_proveedores_servicios_salud as b
                                    where a.cargo='$Cargo'
                                    and a.empresa_id='".$_SESSION['CENTRALHOSP']['EMPRESA']."'
                                    and c.tipo_id_tercero=a.tipo_id_tercero 
																		and c.tercero_id=a.tercero_id
																		and b.empresa_id=a.empresa_id
																		and b.tipo_id_tercero=a.tipo_id_tercero
																		and b.tercero_id=a.tercero_id 
																		and b.estado='1'
																		and a.sw_estado='1'";*/								
                        
				$query = "select 	a.tipo_id_tercero, 
									a.tercero_id, 
									a.cargo,  
									c.plan_proveedor_id, 
									c.empresa_id,
									c.plan_descripcion,
									MIN (round((e.precio + (e.precio * f.porcentaje) / 100),0)) as valor_cargo
							from 	terceros_proveedores_cargos as a, 
									planes_proveedores as c,
									terceros_proveedores_servicios_salud as b,
									tarifarios_equivalencias d,
									tarifarios_detalle e,
									plan_tarifario_proveedores f
							where 	a.cargo='$Cargo'
							and 	a.empresa_id='".$_SESSION['CENTRALHOSP']['EMPRESA']."'
							and 	c.tipo_id_tercero=a.tipo_id_tercero 
							and 	c.tercero_id=a.tercero_id
							and 	b.empresa_id=a.empresa_id
							and 	b.tipo_id_tercero=a.tipo_id_tercero
							and 	b.tercero_id=a.tercero_id 
							and 	b.estado='1'
							and 	a.sw_estado='1'
							and     d.cargo_base = a.cargo
							and     e.cargo = d.cargo
							and     e.tarifario_id = d.tarifario_id
							and     f.tarifario_id = e.tarifario_id
							and     f.grupo_tarifario_id = e.grupo_tarifario_id
							and     f.subgrupo_tarifario_id = e.subgrupo_tarifario_id
							and     f.plan_proveedor_id = c.plan_proveedor_id
							GROUP BY 1,2,3,4,5,6
							ORDER BY valor_cargo";											
                $resulta=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                while(!$resulta->EOF)
                {
                        $vars[]=$resulta->GetRowAssoc($ToUpper = false);
                        $resulta->MoveNext();
                }
                $resulta->Close();
                return $vars;
    }


    /**
    *
    */
    function ComboDepartamento($Cargo)
    {
                list($dbconn) = GetDBconn();
				
				
                $query = "    select a.departamento, a.cargo, b.descripcion
                                        from departamentos_cargos as a, departamentos as b
                                        where a.cargo='$Cargo'
                                        and b.departamento=a.departamento
										and b.empresa_id = '".$_SESSION['CENTRALHOSP']['EMPRESA']."' ";
                $resulta=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                while(!$resulta->EOF)
                {
                        $vars[]=$resulta->GetRowAssoc($ToUpper = false);
                        $resulta->MoveNext();
                }
                $resulta->Close();
                return $vars;
    }
    /**
    *
    */
    function ReporteFormulaMedica()
    {
      if (!IncludeFile("classes/reports/reports.class.php"))
      {
				$this->error = "No se pudo inicializar la Clase de Reportes";
				$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
				return false;
      }

  		$criterio=='';
  		$uso_controlado = 0;
  		if(($_REQUEST['sw_paciente_no_pos']==='0') OR ($_REQUEST['sw_paciente_no_pos']==1))
  		{
  			$criterio= "AND k.sw_pos = '".$_REQUEST['sw_pos']."' AND a.sw_paciente_no_pos = '".$_REQUEST['sw_paciente_no_pos']."'";
  		}
  		elseif($_REQUEST['sw_pos']=='1')
  		{
  			$criterio= "AND k.sw_pos = '".$_REQUEST['sw_pos']."'";
  		}
  		if ($criterio == '' AND $_REQUEST['sw_uso_controlado']=='1')
  		{
  			$criterio = "AND k.sw_uso_controlado = '".$_REQUEST['sw_uso_controlado']."'";
  			$uso_controlado = 1;
  		}

  		//cargando criterios cuando sea invocado desde otro lado.
  		if ($_SESSION['CENTRALHOSP']['PACIENTE']['paciente_id']=='')
  		{
  			$criterio_paciente = $_REQUEST['paciente_id'];
  		}
  		else
  		{
  			$criterio_paciente = $_SESSION['CENTRALHOSP']['PACIENTE']['paciente_id'];
  		}
  		if ($_SESSION['CENTRALHOSP']['PACIENTE']['tipo_id_paciente'] =='')
  		{
  				$criterio_tipo_id = $_REQUEST['tipo_id_paciente'];
  		}
  		else
  		{
				$criterio_tipo_id = $_SESSION['CENTRALHOSP']['PACIENTE']['tipo_id_paciente'];
  		}
  		if ($_SESSION['CENTRALHOSP']['PACIENTE']['ingreso']=='')
  		{
				$criterio_ingreso = $_REQUEST['ingreso'];
  		}
  		else
  		{
				$criterio_ingreso = $_SESSION['CENTRALHOSP']['PACIENTE']['ingreso'];
      }
      //fin de criterios

  		list($dbconn) = GetDBconn();

  		$filtro_evolucion='';
  		if ($_REQUEST['modulo_invoca'] =='impresionhc')
  		{
				$filtro_evolucion="and a.evolucion_id = ".$_REQUEST['evolucion_id']."";
      }

  		$query="SELECT btrim( w.primer_nombre||' '||w.segundo_nombre||' '||
                            w.primer_apellido||' '||w.segundo_apellido,'') as paciente,
                		w.tipo_id_paciente, 
                    w.paciente_id, 
                    w.sexo_id, 
                    w.fecha_nacimiento,
                		x.historia_numero, 
                    x.historia_prefijo, 
                    n.fecha_cierre,
                		y.fecha_ingreso, 
                    z.cama, 
                    n.ingreso,
                    n.fecha, 
                    w.residencia_direccion, 
                    w.residencia_telefono,
                		v.tipo_afiliado_id, 
                    t.plan_id, 
                    sw_tipo_plan, 
                    s.rango,
                		v.tipo_afiliado_nombre, 
                    p.nombre_tercero,	
                    u.nombre_tercero as cliente,
                		r.descripcion as tipo_profesional, 
                    p.tipo_id_tercero as tipo_id_medico,
                		p.tercero_id as	medico_id, 
                    q.tarjeta_profesional, 
                    t.plan_descripcion,
                		a.evolucion_id, 
                    case when k.sw_pos = 1 then 'POS'	else 'NO POS' end as item,
                		a.sw_paciente_no_pos, 
                    a.codigo_producto,  
                    h.descripcion as producto,
                		c.descripcion as principio_activo, 
                    m.nombre as via, 
                    a.dosis,
                		a.unidad_dosificacion, 
                    a.tipo_opcion_posologia_id, 
                    a.cantidad,
                		l.descripcion, 
                    h.contenido_unidad_venta,	
                    a.observacion,
                    a.numero_formula,
                    da.descripcion AS centro,
                    da.ubicacion,
                    da.telefono,
                    HF.tiempo_total
              FROM  hc_medicamentos_recetados_amb as a 
                    left join hc_vias_administracion as m
                    on (a.via_administracion_id = m.via_administracion_id),
                    hc_evoluciones as n 
                    left join ingresos as y 
                    on (n.ingreso= y.ingreso) 
                    left join movimientos_habitacion z
                    on (y.ingreso = z.ingreso and 
                        z.fecha_egreso ISNULL)
                    left join	profesionales_usuarios as o 
                    on (n.usuario_id = o.usuario_id) 
                    left join terceros as p	
                    on (o.tipo_tercero_id = p.tipo_id_tercero AND
                        o.tercero_id = p.tercero_id) 
                    left join profesionales as q 
                    ON (o.tipo_tercero_id = q.tipo_id_tercero AND 
                        o.tercero_id = q.tercero_id)
                    left join tipos_profesionales as r 
                    on (q.tipo_profesional = r.tipo_profesional)
                    left join cuentas as s 
                    on (n.numerodecuenta = s.numerodecuenta) 
                    left join planes as t	
                    on (s.plan_id = t.plan_id) 
                    left join terceros as u 
                    on (t.tipo_tercero_id = u.tipo_id_tercero AND 
                        t.tercero_id	= u.tercero_id)
                    left join tipos_afiliado as v 
                    on (s.tipo_afiliado_id = v.tipo_afiliado_id)
                    left join pacientes as w 
                    on (w.paciente_id= '".$criterio_paciente."' and 
                        w.tipo_id_paciente = '".$criterio_tipo_id."')
                    left join	historias_clinicas x 
                    on (w.paciente_id= x.paciente_id AND
                        w.tipo_id_paciente = x.tipo_id_paciente),
                    inv_med_cod_principios_activos as c, 
                    inventarios_productos as h,
                    medicamentos as k, 
                    unidades as l,
                    centros_utilidad da, 
                    tipo_mpios st,
                    hc_formulacion_antecedentes HF
  		WHERE n.ingreso = ".$criterio_ingreso." 
      AND   a.evolucion_id = n.evolucion_id
  		and	  k.cod_principio_activo = c.cod_principio_activo
  		and   h.codigo_producto = k.codigo_medicamento 
      and		a.codigo_producto = h.codigo_producto 
      $filtroAmb
  		and h.codigo_producto = a.codigo_producto 
      and h.unidad_id = l.unidad_id
  		$criterio 
      $filtro_evolucion 
      and s.centro_utilidad=da.centro_utilidad 
      and s.empresa_id=da.empresa_id
      and da.tipo_pais_id=st.tipo_pais_id
      and da.tipo_dpto_id=st.tipo_dpto_id 
      and da.tipo_mpio_id=st.tipo_mpio_id
      AND HF.evolucion_id = a.evolucion_id
      AND HF.codigo_medicamento = a.codigo_producto
      order by k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto;";
      
  		$result = $dbconn->Execute($query);
  		if ($dbconn->ErrorNo() != 0)
  		{
  			$this->error = "Error al Cargar el Modulo";
  			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  			return false;
  		}
  		else
  		{
        while (!$result->EOF)
        {
          $var[]=$result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
        }
      }

  		$var[0][uso_controlado]=$uso_controlado;
  		$var[0][razon_social]=$_SESSION['CENTRALHOSP']['NOM_EMPRESA'];

  		//obteniendo la cuota moderadora solo para cuando el plan es = 3 y sw_pos = 1
  		if($_REQUEST['sw_pos']=='1' AND $var[0][sw_tipo_plan]==3)
  		{
        if((!empty($var[0][rango])) AND (!empty($var[0][plan_id])) AND
           (!empty($var[0][tipo_afiliado_id])))
        {
          $query = "SELECT  cuota_moderadora 
                    FROM    planes_rangos
                    WHERE   plan_id = ".$var[0][plan_id]."
                    AND     tipo_afiliado_id = '".$var[0][tipo_afiliado_id]."'
                    AND     rango = '".$var[0][rango]."';";

          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          else
          {
            $cuotam=$result->GetRowAssoc($ToUpper = false);
          }
          $var[0][cuota_moderadora]=$cuotam;
        }
      }

  		//obteniendo la posologia para cada medicamento desde la estacion para imprimir en la formula medica.
  		for($i=0;$i<sizeof($var);$i++)
  		{
				$query == '';
				unset ($vector);
				if ($var[$i][tipo_opcion_posologia_id] == 1)
				{
						$query= "select periocidad_id, tiempo from hc_posologia_horario_op1 where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
				}
				if ($var[$i][tipo_opcion_posologia_id] == 2)
				{
						$query= "select a.duracion_id, b.descripcion from hc_posologia_horario_op2 as a, hc_horario as b where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."' and a.duracion_id = b.duracion_id";
				}
				if ($var[$i][tipo_opcion_posologia_id] == 3)
				{
						$query= "select sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena from hc_posologia_horario_op3 where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
				}
				if ($var[$i][tipo_opcion_posologia_id] == 4)
				{
						$query= "select hora_especifica from hc_posologia_horario_op4_hosp where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
				}
				if ($var[$i][tipo_opcion_posologia_id] == 5)
				{
						$query= "select frecuencia_suministro from hc_posologia_horario_op5 where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
				}

				if ($query!='')
				{
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al buscar en la consulta de medicamentos recetados";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						else
						{
							if ($var[$i][tipo_opcion_posologia_id] != 4)
							{
								while (!$result->EOF)
								{
									$vector[]=$result->GetRowAssoc($ToUpper = false);
									$result->MoveNext();
								}
							}
							else
							{
								while (!$result->EOF)
								{
									$vector[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
									$result->MoveNext();
								}
							}
						}
				}
				$var[$i][posologia]=$vector;
				unset($vector);
      }

  		if($_REQUEST['impresion_pos']=='1')
  		{
               $classReport = new reports;
               $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
               $reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='CentralImpresionHospitalizacion',$reporte_name='formulamedica',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
               if(!$reporte)
               {
                         $this->error = $classReport->GetError();
                         $this->mensajeDeError = $classReport->MensajeDeError();
                         unset($classReport);
                         return false;
               }

               $resultado=$classReport->GetExecResultado();
               unset($classReport);

               if(!empty($resultado[codigo]))
               {
                    "El PrintReport retorno : " . $resultado[codigo] . "<br>";
               }
               if ($_REQUEST['parametro_retorno'] == '1')
               {
                    if ($_REQUEST['modulo_invoca'] == 'impresionhc')
                    {
                         $this->ReturnMetodoExterno('app','ImpresionHC','user','FormaImpresionSolicitudes');
                    }
                    elseif ($_REQUEST['modulo_invoca'] == 'salida_pacientes')
                    {
                         $this->ReturnMetodoExterno('app','Admisiones','user','FormaImpresionSolicitudes');
                    }
               }
               else
               {
                    $this->FormaDetalleImpresion();
               }
      }
  		else
  		{
        $opcion = ModuloGetVar("","","formato_formula");
        switch($opcion)
        {
          case '1': $reporte = "formula_ambulatoria_oms"; break;
          default: $reporte = "formula_ambulatoria"; break;
        }
        $var[0]['opcion_reporte'] = $opcion;
              
        if ($_REQUEST['parametro_retorno'] == '1')
        {
          IncludeLib("reportes/".$reporte);
          GenerarFormula($var);
          
          if(is_array($var))
          {
            $RUTA = $_ROOT ."cache/formula_medica_amb".UserGetUID().".pdf";
            $DIR="printer.php?ruta=$RUTA";
            $RUTA1= GetBaseURL() . $DIR;
            $mostrar ="\n<script language='javascript'>\n";
            $mostrar.="var rem=\"\";\n";
            $mostrar.="  function abreVentana(){\n";
            $mostrar.="    var url2=\"\"\n";
            $mostrar.="    var width=\"400\"\n";
            $mostrar.="    var height=\"300\"\n";
            $mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
            $mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
            $mostrar.="    var nombre=\"Printer_Mananger\";\n";
            $mostrar.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
            $mostrar.="    var url2 ='$RUTA1';\n";
            $mostrar.="    rem = window.open(url2, nombre, str)};\n";
            $mostrar.="</script>\n";
            $this->salida.="$mostrar";
            $this->salida.="<BODY onload=abreVentana();>";
          }
                    if ($_REQUEST['modulo_invoca'] == 'impresionhc')
                    {
                    	$this->ReturnMetodoExterno('app','ImpresionHC','user','FormaImpresionSolicitudes');
                    }
                    elseif ($_REQUEST['modulo_invoca'] == 'salida_pacientes')
                    {
                      $this->ReturnMetodoExterno('app','Admisiones','user','FormaImpresionSolicitudes');
                    }
        }
        else
        {
          IncludeLib("reportes/".$reporte);
          GenerarFormula($var);
          $this->FormaDetalleImpresion($var);
        }
		}
		return true;
}
//----------------FIN REPORTES CLAUDIA

//MauroB
	function BuscarPorPaciente()
	{
				$_SESSION['CENTRALHOSP']['paciente_id']=$_REQUEST['paciente_id'];
				$_SESSION['CENTRALHOSP']['tipo_id_paciente']=$_REQUEST['tipo_id_paciente'];
				$_SESSION['CENTRALHOSP']['departamento']=$_REQUEST['departamento'];
				$_SESSION['CENTRALHOSP']['EMPRESA']=$_REQUEST['empresa_id'];

			if(empty($_SESSION['CENTRALHOSP']))
			{
						$this->error = "CENTRALHOSP NULA";
						$this->mensajeDeError = "Datos de la CENTRAL vacios.";
						return false;
			}

			if(empty($_SESSION['CENTRALHOSP']['RETORNO']))
			{
						$this->error = "CENTRA ";
						$this->mensajeDeError = "El retorno de la CENTRAL esta vacio.";
						return false;
			}

			/*if(empty($_SESSION['CENTRALHOSP']['RETORNO_OS_ATENCION']))
			{
						$this->error = "NO RETORNO CENTRAL";
						$this->mensajeDeError = "El retorno de la CENTRAL esta vacio.";
						return false;
			}*/

			//se necesita los datos paciente
			if(empty($_SESSION['CENTRALHOSP']['tipo_id_paciente']) || empty($_SESSION['CENTRALHOSP']['paciente_id']) || empty($_SESSION['CENTRALHOSP']['departamento']))
			{
							$this->error = "CENTRAL ";
							$this->mensajeDeError = "Datos de la CENTRAL incompletos.";
							return false;
			}

			$this->BuscarSolicitudesXPaciente($_SESSION['CENTRALHOSP']['paciente_id'],$_SESSION['CENTRALHOSP']['tipo_id_paciente'],$_SESSION['CENTRALHOSP']['departamento']);
			return true;
	}

		/**
	*
	*/
	function BuscarSolicitudesXPaciente($pac_id,$pac_tipo_id,$depto)
	{
				IncludeLib("funciones_central_impresion");
				unset($_SESSION['CENTRALHOSP']['ARREGLO']);
				list($dbconn) = GetDBconn();
				//solicitudes
				//$var=$this->BuscarDetalleSolcitudes($ingreso);
				$var=BuscarSolicitudesPaciente($pac_id,$pac_tipo_id,$depto);
				//ECHO "PASO2";
				$_SESSION['CENTRALHOSP']['ARREGLO']['DETALLE']=$var;
				$this->FormaDetalleSolicitud();
				return true;
	}

//Fin MauroB
 }
?>