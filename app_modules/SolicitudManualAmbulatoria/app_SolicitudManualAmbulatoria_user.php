 <?php

 /**
 * $Id: app_SolicitudManualAmbulatoria_user.php,v 1.3 2006/03/01 13:42:46 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo logico de las autorizaciones.
 */

/**
*Contiene los metodos para realizar las autorizaciones.
*/

class app_SolicitudManualAmbulatoria_user extends classModulo
{

    var $limit;
    var $conteo;

    function app_SolicitudManualAmbulatoria_user()
    {
            $this->limit=GetLimitBrowser();
            return true;
    }

		/**
		*La funcion main es la principal y donde se llama FormaBuscar de la clase
		* que muestra la forma para buscar al paciente
		*/
		function main()
		{
					unset($_SESSION['SOLICITUD']);
					if(!empty($_SESSION['SEGURIDAD']['SOLICITUDMANUAL']))
					{
											$this->salida.= gui_theme_menu_acceso('SOLICITUD MANUAL',$_SESSION['SEGURIDAD']['SOLICITUDMANUAL']['arreglo'],$_SESSION['SEGURIDAD']['SOLICITUDMANUAL']['admon'],$_SESSION['SEGURIDAD']['SOLICITUDMANUAL']['url'],ModuloGetURL('system','Menu'));
											return true;
					}
					list($dbconn) = GetDBconn();
					GLOBAL $ADODB_FETCH_MODE;
				  $query = "select c.servicio, c.empresa_id, d.razon_social as descripcion1,b.departamento,
										c.descripcion as descripcion2, b.descripcion as descripcion3,a.punto_solicitud_manual_id
										from userpermisos_solicitud_manual as a, puntos_solicitud_manual as b,
										departamentos as c, empresas as d
										where a.usuario_id=".UserGetUID()."
										and a.punto_solicitud_manual_id=b.punto_solicitud_manual_id
										and b.departamento=c.departamento and d.empresa_id=c.empresa_id
										order by d.razon_social,c.descripcion";
					$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
					$resulta=$dbconn->Execute($query);
					$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Base de Datos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}

					while ($data = $resulta->FetchRow()) {
							$admon[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']]=$data;
							$seguridad[$data['empresa_id']][$data['departamento']][$data['punto_solicitud_manual_id']]=1;
					}

					$url[0]='app';
					$url[1]='SolicitudManualAmbulatoria';
					$url[2]='user';
					$url[3]='Principal';
					$url[4]='Sol';

					$arreglo[0]='EMPRESA';
					$arreglo[1]='DEPARTAMENTO';
					$arreglo[2]='SOLICITUD';

					$_SESSION['SEGURIDAD']['SOLICITUDMANUAL']['arreglo']=$arreglo;
					$_SESSION['SEGURIDAD']['SOLICITUDMANUAL']['admon']=$admon;
					$_SESSION['SEGURIDAD']['SOLICITUDMANUAL']['url']=$url;
					$_SESSION['SEGURIDAD']['SOLICITUDMANUAL']['puntos']=$seguridad;
					$this->salida.= gui_theme_menu_acceso('SOLICITUD MANUAL',$_SESSION['SEGURIDAD']['SOLICITUDMANUAL']['arreglo'],$_SESSION['SEGURIDAD']['SOLICITUDMANUAL']['admon'],$_SESSION['SEGURIDAD']['SOLICITUDMANUAL']['url'],ModuloGetURL('system','Menu'));
					return true;
		}

		/**
		*
		*/
		function Principal()
		{
				if(empty($_SESSION['SOLICITUD']['EMPRESA']))
				{
							if(empty($_SESSION['SEGURIDAD']['SOLICITUDMANUAL']['puntos'][$_REQUEST['Sol']['empresa_id']][$_REQUEST['Sol']['departamento']][$_REQUEST['Sol']['punto_solicitud_manual_id']]))
							{
											$this->error = "Error de Seguridad.";
											$this->mensajeDeError = "Violación a la Seguridad.";
											return false;
							}

							$_SESSION['SOLICITUD']['EMPRESA']=$_REQUEST['Sol']['empresa_id'];
							$_SESSION['SOLICITUD']['PTOSOL']=$_REQUEST['Sol']['punto_solicitud_manual_id'];
							$_SESSION['SOLICITUD']['DPTO']=$_REQUEST['Sol']['departamento'];
							$_SESSION['SOLICITUD']['SERVICIO']=$_REQUEST['Sol']['servicio'];
							$_SESSION['SOLICITUD']['DPTONOMBRE']=$_REQUEST['Sol']['descripcion2'];
							$_SESSION['SOLICITUD']['NOM_EMPRESA']=$_REQUEST['Sol']['descripcion1'];

				}
				if(!$this->LlamarFormaBuscar()){
						return false;
				}
				return true;
		}

		/**
		*
		*/
		function LlamarFormaBuscar()
		{
				unset($_SESSION['SOLICITUD']['PACIENTE']);
				unset($_SESSION['SOLICITUD']['DATOS']);

				list($dbconn) = GetDBconn();
				$sql="select nextval('asignacuentavirtual_seq')";
				$result = $dbconn->Execute($sql);
				$dato=$result->fields[0];
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				$_SESSION['SOLICITUD']['SERIAL']=$dato;

				if(!$this->FormaBuscar()){
						return false;
				}
				return true;
		}


		/**
		*
		*/
		function responsables()
		{
						list($dbconn) = GetDBconn();
						$query = "select sw_todos_planes from userpermisos_solicitud_manual
											where usuario_id=".UserGetUID()." and sw_todos_planes=1
											and punto_solicitud_manual_id=".$_SESSION['SOLICITUD']['PTOSOL']." ";
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
						}
						if(!$result->EOF)
						{			//tiene todos los planes
									$query="SELECT a.plan_id,a.plan_descripcion,a.tercero_id,a.tipo_tercero_id
													FROM planes as a
													WHERE a.fecha_final >= now() and a.estado=1
													and a.fecha_inicio <= now()
													and empresa_id='".$_SESSION['SOLICITUD']['EMPRESA']."'
													order by a.plan_descripcion";
						}
						else
						{			//planes especificos
									$query="SELECT a.plan_id,a.plan_descripcion,a.tercero_id,a.tipo_tercero_id
													FROM planes as a, userpermisos_solicitud_manual as b
													WHERE a.fecha_final >= now() and a.estado=1 and a.fecha_inicio <= now()
													and b.usuario_id=".UserGetUID()."
													and b.plan_id=a.plan_id
													and b.punto_solicitud_manual_id=".$_SESSION['SOLICITUD']['PTOSOL']."
													and a.empresa_id='".$_SESSION['SOLICITUD']['EMPRESA']."'
													order by a.plan_descripcion";
						}
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
						}
						if(!$result->EOF)
						{
								while (!$result->EOF)
								{
										$var[]=$result->GetRowAssoc($ToUpper = false);
										$result->MoveNext();
								}
						}
						$result->Close();
						return $var;
		}

		/**
		*
		*/
		function BuscarPaciente()
		{
						list($dbconn) = GetDBconn();
						$query = "SELECT sw_tipo_plan FROM planes
											WHERE estado='1' and plan_id=".$_REQUEST['plan']."
											and fecha_final >= now() and fecha_inicio <= now()";
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
						}
						//1 soat
						if($result->fields[0]==1)
						{
									$this->frmError["MensajeError"]="Los Planes Soat deben Realizar el proceso en la Central de Autorizaciones.";
									$this->FormaBuscar();
									return true;
						}

						if($_REQUEST['plan']==-1)
						{
									if($Plan==-1){ $this->frmError["plan"]=1; }
									$this->frmError["MensajeError"]="Faltan datos obligatorios.";
									$this->FormaBuscar();
									return true;
						}

            if($_REQUEST['Tipo']=='AS' OR $_REQUEST['Tipo']=='MS')
            {  $_REQUEST['Documento']=$this->CallMetodoExterno('app','Pacientes','user','IdentifiacionNN');  }

						if($_REQUEST['Tipo']==-1 AND !$_REQUEST['Documento'] AND !$_REQUEST['prefijo'] AND !$_REQUEST['historia'])
						{
									$this->frmError["MensajeError"]="DEBE ELEGIR CRITERIOS PARA LA BUSQUEDA.";
									$this->FormaBuscar();
									return true;
						}

						if($_REQUEST['prefijo'] OR $_REQUEST['historia'])
						{
									$query = "SELECT tipo_id_paciente, paciente_id FROM historias_clinicas
														WHERE historia_numero='".$_REQUEST['historia']."'
														AND historia_prefijo='".strtoupper($_REQUEST['prefijo'])."'";
									$result=$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
													$this->error = "Error al Guardar SELECT en historias_clinicas";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													return false;
									}
									if($result->EOF)
									{
											$this->frmError["MensajeError"]="LA HISTORIA NO EXISTE.";
											$this->FormaBuscar();
											return true;
									}
									else
									{
												$_REQUEST['Documento']=$result->fields[1];
												$_REQUEST['Tipo']=$result->fields[0];
									}
									$result->Close();
						}
						elseif($_REQUEST['Tipo']==-1 OR !$_REQUEST['Documento'])
						{
									if($_REQUEST['Tipo']==-1){ $this->frmError["Tipo"]=1; }
									if(!$_REQUEST['Documento']){ $this->frmError["Documento"]=1; }
									$this->frmError["MensajeError"]="PARA BUSCA POR DOCUMENTOS DEBE DIGITAR LAS DOS OPCIONES.";
									$this->FormaBuscar();
									return true;
						}

						$_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$_REQUEST['Documento'];
						$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['Tipo'];
						$_SESSION['PACIENTES']['PACIENTE']['plan_id']=$_REQUEST['plan'];
						$_SESSION['PACIENTES']['RETORNO']['argumentos']=array();
						$_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
						$_SESSION['PACIENTES']['RETORNO']['modulo']='SolicitudManualAmbulatoria';
						$_SESSION['PACIENTES']['RETORNO']['tipo']='user';
						$_SESSION['PACIENTES']['RETORNO']['metodo']='RetornoPaciente';

						$this->ReturnMetodoExterno('app','Pacientes','user','PedirDatos');
						return true;
		}

		/**
		*
		*/
		function RetornoPaciente()
		{
				unset($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
				$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO'];
				//si se cancelo en proceso de tomar datos del paciente
				if(empty($_SESSION['PACIENTES']['RETORNO']['PASO']))
				{
							unset($_SESSION['PACIENTES']);
							$this->FormaBuscar();
							return true;
				}
				else
				{
							$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']=$_SESSION['PACIENTES']['PACIENTE']['paciente_id'];
							$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']=$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'];
							$_SESSION['SOLICITUD']['PACIENTE']['plan_id']=$_SESSION['PACIENTES']['PACIENTE']['plan_id'];

							list($dbconn) = GetDBconn();
							$query = "SELECT plan_descripcion,sw_tipo_plan     FROM planes
							WHERE plan_id=".$_SESSION['SOLICITUD']['PACIENTE']['plan_id']."";
							$result=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
															$this->error = "Error al Guardar en la Base de Datos";
															$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
															return false;
							}
							$_SESSION['SOLICITUD']['PACIENTE']['plan_descripcion']=$result->fields[0];

							unset($_SESSION['PACIENTES']);
							$this->PedirAutorizacion();
							return true;
				}
		}

    /**
    *
    */
    function PedirAutorizacion()
    {
					unset($_SESSION['AUTORIZACIONES']);
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$_SESSION['SOLICITUD']['PACIENTE']['paciente_id'];
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'];
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$_SESSION['SOLICITUD']['PACIENTE']['plan_id'];
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']=array();
					$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
					$_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='SolicitudManualAmbulatoria';
					$_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';
					$_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='RetornoAutorizacion';

					$this->ReturnMetodoExterno('app','Autorizacion','user','SolicitudAutorizacionAmbulatoria');
					return true;
    }

    /**
    * Llama el modulo de autorizaciones
    * @access public
    * @return boolean
    */
   	function RetornoAutorizacion()
    {
					$_SESSION['SOLICITUD']['PACIENTE']['tipo_afiliado_id']=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id'];
					$_SESSION['SOLICITUD']['PACIENTE']['rango']=$_SESSION['AUTORIZACIONES']['RETORNO']['rango'];
					$_SESSION['SOLICITUD']['PACIENTE']['semanas']=$_SESSION['AUTORIZACIONES']['RETORNO']['semanas'];
					$_SESSION['SOLICITUD']['PACIENTE']['observacion_ingreso']=$_SESSION['AUTORIZACIONES']['RETORNO']['observacion_ingreso'];

					if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['ext'])){  $_SESSION['AUTORIZACIONES']['RETORNO']['ext']='NULL'; }
					$_SESSION['SOLICITUD']['PACIENTE']['AUTORIZACIONEXT']=$_SESSION['AUTORIZACIONES']['RETORNO']['ext'];
					$_SESSION['SOLICITUD']['PACIENTE']['AUTORIZACION']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];

					$_SESSION['SOLICITUD']['Autorizacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion'];
					$_SESSION['SOLICITUD']['NumAutorizacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];

					if(empty($_SESSION['SOLICITUD']['NumAutorizacion']))
					{  $_SESSION['SOLICITUD']['NumAutorizacion']='NULL';  }

					unset($_SESSION['AUTORIZACIONES']);

					if(empty($_SESSION['SOLICITUD']['Autorizacion'])
							AND empty($_SESSION['SOLICITUD']['NumAutorizacion']))
					{
								if(empty($_SESSION['SOLICITUD']['NumAutorizacion']))
								{   $Mensaje = 'No se pudo realizar la Autorización.';   }
								$accion=ModuloGetURL('app','CentroAutorizacion','user','DetalleSolicitud');
								if(!$this-> FormaMensaje($Mensaje,'AUTORIZACION SOLICITUD MANUAL',$accion,'')){
								return false;
								}
								return true;
					}

					if(empty($_SESSION['CENTROAUTORIZACION']['Autorizacion'])
					AND !empty($_SESSION['CENTROAUTORIZACION']['NumAutorizacion']))
					{
											$Mensaje = 'No se Autorizo al Paciente.';
											$accion=ModuloGetURL('app','CentroAutorizacion','user','FormaMetodoBuscar');
											if(!$this-> FormaMensaje($Mensaje,'CENTRO AUTORIZACIONES',$accion,'')){
											return false;
											}
											return true;
					}
					//$this->FormaDatosSolicitud();

					//parte de duvan ...
					$_SESSION['LABORATORIO']['SW_ESTADO']=1;
					$nom=$this->GetNombrePaciente($_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'],$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']);
					$conteo=$this->TraerOrdenesServicio($_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'],$_SESSION['SOLICITUD']['PACIENTE']['paciente_id'],true);
					if($conteo >0)
					{
						$this->FrmOrdenar($nom,$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'],$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']);
					}
					else
					{
						$this->FormaDatosSolicitud();
					}
					return true;
    }



		/*
		* funcion que retorna el cumplimiento
		*/
		function RetornoCumplimiento()
		{
				//parte de duvan ...
					$_SESSION['LABORATORIO']['SW_ESTADO']=1;
					$nom=$this->GetNombrePaciente($_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'],$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']);
					$conteo=$this->TraerOrdenesServicio($_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'],$_SESSION['SOLICITUD']['PACIENTE']['paciente_id'],true);
					if($conteo >0)
					{
						$this->FrmOrdenar($nom,$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'],$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']);
					}
					else
					{
						$this->FormaDatosSolicitud();
					}
					return true;
		}


	/*
	* Esta funcion permite buscar una cuenta activa en un paciente.
	* si tiene una cuenta activa o no para tener la opcion de cargarla a la cuenta.
	* @return array
	*/
	function BuscarCuentaActiva($id,$tipo,$nom,$op,$plan)
	{

				if(empty($op))
				{
					$id=$_REQUEST['id'];
					$tipo=$_REQUEST['id_tipo'];
					$nom=urldecode($_REQUEST['nom']);
					$plan=$_REQUEST['plan_id'];
					$op=$_REQUEST['op'];

				}
				if(empty($op))
				{
								$this->frmError["MensajeError"]="SELECCIONE MINIMO 1 CARGO";
								$this->FrmOrdenar($nom,$tipo,$id);
								return true;
				}
				 foreach($_REQUEST['op'] as $index=>$codigo)
				 {
						$valores=explode(",",$codigo);// "--->>".print_r($valores);exiT;
						break;
				 }

				//"$valores[7]" este es el campo->servicio para determinar si es ambulatorio
				//o si es hospitalario,si es ambulatorio no mostrara cargar a la cuenta
				list($dbconn) = GetDBconn();
				 $query="SELECT servicio,descripcion FROM servicios WHERE servicio='".$valores[7]."' AND sw_cargo_multidpto='1'";
				$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al traer las 0rdenes de servicios";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}

		/*	//si viene en 1 o mas es por q si se deberia buscar si existe una cuenta..
			if($result->RecordCount() >=1)
			{


					  $query="SELECT
									a.numerodecuenta,a.plan_id,a.total_cuenta,c.servicio,c.descripcion,
									d.ingreso,f.plan_descripcion,e.nombre_tercero as tercero,
									(a.total_cuenta - a.valor_cubierto - (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_letras)) as saldo

									FROM cuentas a,servicios c,ingresos d,terceros e,planes f

									WHERE
         					 c.servicio='".$result->fields[0]."'
									AND d.tipo_id_paciente='". $tipo."'
									AND d.paciente_id=". $id."
									AND a.ingreso=d.ingreso
									AND d.estado=1
									AND a.estado=1
									AND c.sw_cargo_multidpto=1
									AND e.tipo_id_tercero=f.tipo_tercero_id
									AND e.tercero_id=f.tercero_id
									AND a.plan_id='". $plan."'
									AND a.plan_id=f.plan_id";
									//AND DATE(a.fecha_vencimiento) > NOW()

					//exit;
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al traer las 0rdenes de servicios";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}
							while (!$result->EOF) {
											$var[]=$result->GetRowAssoc($ToUpper = false);
											$result->MoveNext();
									}
				}*/

						//$result->Close();
						$this->LiquidacionOrden($var,$nom,$tipo,$id,$op,$plan);
						return true;
	}



	/*
	*  Trae el nombre del tarifario.
	*/
	function TraerNombreTarifario($tarifario_id,$cargo)
	{
				list($dbconn) = GetDBconn();
				$query="SELECT descripcion FROM tarifarios_detalle
								WHERE tarifario_id='$tarifario_id' AND cargo='$cargo'";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al listar las empresas";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}

				return $resulta->fields[0];
	}


	/*
	*  trae el nombre de copago y el de cuota moderadora.
	*/
	function BuscarNombreCop($plan)
	{

		list($dbconn) = GetDBconn();
		$query="SELECT nombre_copago,nombre_cuota_moderadora
		          FROM planes
							WHERE plan_id=$plan
							";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer los planes";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$result->GetRowAssoc($ToUpper = false);
			return $var;
	}


	/*
	* Esta funcion trae los datos de la tabla
	* os_maestro,os_ordenes_servicios segun el numero de la orden.
	* @return array
	*/
	function DatosOs($orden)
	{
  		list($dbconn) = GetDBconn();
			$query = "SELECT *, b.cargo_cups,d.descripcion,c.os_maestro_cargos_id FROM os_ordenes_servicios a,os_maestro b,os_maestro_cargos c,
			tarifarios_detalle as d
			WHERE a.orden_servicio_id=b.orden_servicio_id AND b.numero_orden_id=$orden
			AND b.numero_orden_id=c.numero_orden_id and c.cargo=d.cargo
			and c.tarifario_id=d.tarifario_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer las 0rdenes de servicios";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
			}

			$result->Close();
			return $var;
	}



//Funcion de duvan

function Revision_Cita($numero_orden_id, $cargo)
{
    list($dbconnect) = GetDBconn();
	 $query= " SELECT a.cargo, a.departamento, b.sw_cita, a.tipo_equipo_imagen_id
		FROM departamentos_cargos_citas a, os_imagenes_tipo_equipos b
		WHERE a.tipo_equipo_imagen_id = b.tipo_equipo_imagen_id
		AND a.cargo = '".$cargo."' and a.departamento = '".$_SESSION['SOLICITUD']['DPTO']."' ";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al crear subexamen generico";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		$a=$result->GetRowAssoc($ToUpper = false);
    if($a[sw_cita]=='1')
		{
			$query= " SELECT numero_orden_id FROM os_imagenes_citas WHERE numero_orden_id = ".$numero_orden_id."";
			$result = $dbconnect->Execute($query);
			if ($dbconnect->ErrorNo() != 0)
			{
				$this->error = "Error al crear subexamen generico";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
			}
			$b=$result->GetRowAssoc($ToUpper = false);
			$a[existe_cita]=$b;
		}
	  return $a;
}


	/**
  * Busca el nombre del paciente
	* @access public
	* @return array
	*/
		function GetNombrePaciente($tipo,$paciente_id)
		{
					list($dbconn) = GetDBconn();
					$query="SELECT  btrim(primer_nombre||' '||segundo_nombre||' ' ||
                	primer_apellido||' '||segundo_apellido,'') as nombre
									FROM pacientes
									WHERE tipo_id_paciente='$tipo'
									AND paciente_id='$paciente_id' ";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}

					return $result->fields[0];
		}





// $spia es una variable q si esta activa  va a realizar un record count del query
//si no va vacia y se realiza el query comun y corriente.
function TraerOrdenesServicio($TipoId,$PacienteId,$spia='')
{
	list($dbconn) = GetDBconn();

//	if($_SESSION['LABORATORIO']['SW_ESTADO']==1)
//	{
			//$filtro_cuenta=", os_cuenta_activa('$TipoId','$PacienteId',c.plan_id) as sw_cuenta";

	//}
	//else
	//{
		//$filtro_cuenta=', 0 as sw_cuenta';
	//}

      $query="SELECT
					c.plan_id,c.plan_descripcion,a.servicio,h.descripcion as serv_des,
					sw_cargo_multidpto as switche,
					CASE c.sw_tipo_plan
					WHEN '0' THEN d.nombre_tercero
					WHEN '1' THEN 'SOAT'
					WHEN '2' THEN 'PARTICULAR'
					WHEN '3' THEN 'CAPITACION - '||d.nombre_tercero
					ELSE e.descripcion END,

					a.tipo_afiliado_id,a.rango,a.orden_servicio_id,f.numero_orden_id,a.fecha_registro,
					i.fecha_vencimiento, f.cargo as cargoi,g.descripcion as des1,i.cantidad,
					a.autorizacion_int,a.autorizacion_ext,a.observacion,
					k.tipo_afiliado_nombre,h.sw_cargo_multidpto$filtro_cuenta

					FROM os_ordenes_servicios as a, pacientes as b, planes c,
					terceros d, tipos_planes as e, os_internas as f, cups g,
					servicios h,os_maestro i, tipos_afiliado k

					WHERE
					a.orden_servicio_id=i.orden_servicio_id
					AND i.numero_orden_id=f.numero_orden_id
					AND a.tipo_id_paciente=b.tipo_id_paciente
					AND a.paciente_id=b.paciente_id
					AND a.tipo_id_paciente='$TipoId'
					AND a.paciente_id='$PacienteId'
					AND a.servicio=h.servicio
					AND g.cargo=f.cargo
					AND c.plan_id=a.plan_id
					AND e.sw_tipo_plan=c.sw_tipo_plan
					AND c.tercero_id=d.tercero_id
					AND c.tipo_tercero_id=d.tipo_id_tercero
 					AND f.departamento='".$_SESSION['SOLICITUD']['DPTO']."'
					AND i.sw_estado=1
					AND a.tipo_afiliado_id=k.tipo_afiliado_id
					AND DATE(i.fecha_activacion) <= NOW()
					ORDER BY c.plan_id,i.fecha_vencimiento,a.orden_servicio_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al traer las 0rdenes de servicios";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if($spia==true)
			{
				return $result->RecordCount();
			}
			while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					$result->Close();
					return $var;
}




//si no va vacia y se realiza el query comun y corriente.
//trae las ordenes de servicio de estado 3 osea atender.
function TraerOrdenesServicio_estado3($TipoId,$PacienteId,$spia='')
{
	list($dbconn) = GetDBconn();

   $query="SELECT
					c.plan_id,c.plan_descripcion,a.servicio,h.descripcion as serv_des,
					sw_cargo_multidpto as switche,
					CASE c.sw_tipo_plan
					WHEN '0' THEN d.nombre_tercero
					WHEN '1' THEN 'SOAT'
					WHEN '2' THEN 'PARTICULAR'
					WHEN '3' THEN 'CAPITACION - '||d.nombre_tercero
					ELSE e.descripcion END,

					a.tipo_afiliado_id,a.rango,a.orden_servicio_id,f.numero_orden_id,a.fecha_registro,
					i.fecha_vencimiento, f.cargo as cargoi,g.descripcion as des1,i.cantidad,
					a.autorizacion_int,a.autorizacion_ext,a.observacion,
					k.tipo_afiliado_nombre

					FROM os_ordenes_servicios as a, pacientes as b, planes c,
					terceros d, tipos_planes as e, os_internas as f, cups g,
					servicios h,os_maestro i,tipos_afiliado k

					WHERE
					a.orden_servicio_id=i.orden_servicio_id
					AND i.numero_orden_id=f.numero_orden_id
					AND a.tipo_id_paciente=b.tipo_id_paciente
					AND a.paciente_id=b.paciente_id
					AND a.tipo_id_paciente='$TipoId'
					AND a.paciente_id='$PacienteId'
					AND a.servicio=h.servicio
					AND g.cargo=f.cargo
					AND c.plan_id=a.plan_id
					AND e.sw_tipo_plan=c.sw_tipo_plan
					AND c.tercero_id=d.tercero_id
					AND c.tipo_tercero_id=d.tipo_id_tercero

 					AND f.departamento='".$_SESSION['SOLICITUD']['DPTO']."'
					AND i.sw_estado=3
					AND a.tipo_afiliado_id=k.tipo_afiliado_id
					AND DATE(i.fecha_activacion) <= NOW()
					ORDER BY f.numero_orden_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al traer las 0rdenes de servicios";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if($spia==true)
			{
				return $result->RecordCount();
			}
			while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					$result->Close();
					return $var;
}


	/*
		Funcion que trae el numero de cumplimiento generado
		al darse la atencion
	*/
	function TraerNumeroCumplimiento($orden_id)
	{
				list($dbconn) = GetDBconn();

				$query = "SELECT numero_cumplimiento,fecha_cumplimiento
				FROM os_cumplimientos_detalle
				WHERE numero_orden_id = ".$orden_id." AND
				departamento = '".$_SESSION['SOLICITUD']['DPTO']."' order by numero_cumplimiento asc";

				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al traer el numero de cumplimiento";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				return $var[]=$result->GetRowAssoc($ToUpper = false);
	}



	function ReporteFichaLaboratorio()
		{
				if (!IncludeFile("classes/reports/reports.class.php"))
				{
						$this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
						return false;
        }
				$AND=" ";
				$search="";
			//	print_r($_REQUEST['sel']);exit;
				$arr=$_REQUEST['sel'];
				if(sizeof($arr)>0)
				{		$search="";
						$union= "";
						for($k=0;$k<sizeof($arr);$k++)
            {
							if($k==0)
							{
								$union = ' and  (';
							}
							else
							{

								$union = ' or ';
							}
							$search.= "$union a.numero_orden_id= ".$arr[$k]."";
						}
						$search.=")";
				}
				else
				{		$AND=" ";
						$search="";
				}

				 list($dbconn) = GetDBconn();
/*
				 $query = "SELECT numero_cumplimiento, fecha_cumplimiento, departamento
				FROM os_cumplimientos_detalle
				WHERE numero_orden_id = ".$_REQUEST['numero_orden_id']." AND
				departamento = '".$_SESSION['LABORATORIO']['DPTO']."'";

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
							$datos[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
						}
				}*/

				$query = "SELECT c.historia_prefijo, c.historia_numero,
				a.numero_cumplimiento, a.departamento,
				a.tipo_id_paciente,	a.paciente_id, a.fecha_cumplimiento,
				btrim(b.primer_nombre||' '||b.segundo_nombre||' '|| b.primer_apellido||
				' '||b.segundo_apellido,'')	as nombre FROM os_cumplimientos a
				left join historias_clinicas c on (a.paciente_id = c.paciente_id AND
				a.tipo_id_paciente =	c.tipo_id_paciente), pacientes b

				WHERE  a.paciente_id = b.paciente_id AND a.tipo_id_paciente =
				b.tipo_id_paciente AND a.numero_cumplimiento = ".$_REQUEST['numero']."
				AND a.fecha_cumplimiento = '".$_REQUEST['fecha_cumplimiento']."'
				AND	a.departamento = '".$_SESSION['SOLICITUD']['DPTO']."'

				order by a.fecha_cumplimiento, a.numero_cumplimiento";

				/*
        $query = "SELECT a.numero_cumplimiento, a.departamento,
				a.tipo_id_paciente,	a.paciente_id, a.fecha_cumplimiento,
				btrim(b.primer_nombre||' '||b.segundo_nombre||' '|| b.primer_apellido||
				' '||b.segundo_apellido,'')	as nombre FROM os_cumplimientos a,pacientes b
				WHERE  a.paciente_id = b.paciente_id AND a.tipo_id_paciente =
				b.tipo_id_paciente AND a.numero_cumplimiento = ".$datos[0][numero_cumplimiento]."
				AND a.fecha_cumplimiento = '".$datos[0][fecha_cumplimiento]."'
				AND	a.departamento = '".$datos[0][departamento]."'
        order by a.fecha_cumplimiento, a.numero_cumplimiento";
*/
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


				$var[0][razon_social]=$_SESSION['SOLICITUD']['NOM_EMPRESA'];

       /* $query = "SELECT a.numero_orden_id, b.sw_estado, c.cargo, c.descripcion
				FROM os_cumplimientos_detalle as a, os_maestro as b, cups as c
				WHERE a.numero_cumplimiento = ".$var[0][numero_cumplimiento]." AND
				a.fecha_cumplimiento = '".$var[0][fecha_cumplimiento]."' AND
				a.departamento = '".$var[0][departamento]."'
				AND a.numero_orden_id = b.numero_orden_id
				AND b.cargo_cups = c.cargo  order by a.numero_orden_id asc";*/

			 $query = "SELECT z.descripcion as dpto, a.numero_orden_id, b.sw_estado, e.tipo_os_lista_id, e.nombre_lista, c.cargo, c.descripcion
				FROM os_cumplimientos_detalle as a, os_maestro as b, cups as c left join
				tipos_os_listas_trabajo_detalle as d on (c.grupo_tipo_cargo = d.grupo_tipo_cargo
				AND c.tipo_cargo = d.tipo_cargo) left join tipos_os_listas_trabajo as e
				on (d.tipo_os_lista_id = e.tipo_os_lista_id), departamentos as z
				WHERE a.numero_cumplimiento = ".$var[0][numero_cumplimiento]." AND
				a.fecha_cumplimiento = '".$var[0][fecha_cumplimiento]."' AND
				a.departamento = '".$var[0][departamento]."' AND a.numero_orden_id = b.numero_orden_id
				and a.departamento=z.departamento
				AND b.cargo_cups = c.cargo 
				AND a.departamento = e.departamento $search
				order by e.tipo_os_lista_id, a.numero_orden_id asc";

				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al buscar en la consulta de medicamentos recetados";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
					while (!$result->EOF)
						{
							$vector[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
						}
				}
				$var[0][cargos]=$vector;


				unset($vector);

				$classReport = new reports;
				$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
        $reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='Os_Atencion',$reporte_name='rotulo_laboratorio_lt',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
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
				//$this->ListadoPacientesEvolucionCerrada();
				if(empty($_REQUEST['destino']))
    		{  $this->FrmOrdenar($_REQUEST['nom'],$_REQUEST['tipoid'],$_REQUEST['id']); }
				else
				{  $this->FormaImpresionCumplimiento($_REQUEST['orden']);  }

        return true;
		}




/*
	* Esta funcion solo cambia el estado en os_maestro de 1 a 3
	* asi realiza el cumplimiento! funcion de duvan .
	* @return boolean
	*/
	function InsertarCargo()
	{
		//IncludeLib("tarifario_cargos");
		list($dbconn) = GetDBconn();


		 $query="SELECT secuencia_os_cumplimiento('".$_SESSION['SOLICITUD']['DPTO']."')";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al insertar en cuentas_detalle";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;	}

				$serial=$result->fields[0];//generamos el numero de cumplimiento

				 $query="INSERT INTO os_cumplimientos
							(numero_cumplimiento,
							fecha_cumplimiento,departamento,tipo_id_paciente,paciente_id)
							VALUES(".$serial.",'".date("Y-m-d")."','".$_SESSION['SOLICITUD']['DPTO']."','".$_REQUEST['tipo_id']."','".$_REQUEST['pac']."')";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al insertar en os_cumplimientos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;	}




 		 foreach($_REQUEST['op'] as $index=>$codigo)
		{
					$valores=explode(",",$codigo);// "--->>".print_r($valores);exiT;

					$query="SELECT tarifario_id,cargo FROM os_maestro_cargos
									WHERE numero_orden_id=".$valores[0]."";
					$resulta=$dbconn->execute($query);
					if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
					}
					$dbconn->StartTrans();
// 					while(!$resulta->EOF)
// 					{
// 									$datos=$this->DatosOs($valores[0]);print_r($datos);
// 									$Liq=LiquidarCargoCuenta($_REQUEST['cuenta'],$resulta->fields[0],$resulta->fields[1],$valores[5],0,0,false,false,0,$valores[7],$_REQUEST['plan'],$datos[tipo_afiliado_id],$datos[rango],$datos[semanas_cotizadas],false);
// 								//print_R($Liq);exit;
// 									$DescuentoEmp=$Liq[valor_descuento_empresa];
// 									$DescuentoPac=$Liq[valor_descuento_paciente];
// 									$Moderadora=$Liq[cuota_moderadora];
// 									$Precio=$Liq[precio_plan];
// 									$ValorCargo=$Liq[valor_cargo];
// 									$ValorPac=$Liq[copago];
// 									$ValorNo=$Liq[valor_no_cubierto];
// 									$ValorCub=$Liq[valor_cubierto];
// 									$ValEmpresa=$Liq[valor_empresa];
// 									$facturado=$Liq[facturado];
// 									$AutoExt=$valores[3];
// 									$AutoInt=$valores[4];
//
// 									$codigo=$Liq[codigo_agrupamiento_id];
// 									if(empty($codigo))
// 									{ $codigo='NULL'; }
// 									else
// 									{  $codigo="'$codigo'"; }
//
// 									if(empty($AutoExt)){$AutoExt='NULL';}
// 									if(empty($AutoInt)){$AutoInt='NULL';}
//
// 								$query = "INSERT INTO cuentas_detalle (
// 																		empresa_id,
// 																		centro_utilidad,
// 																		numerodecuenta,
// 																		departamento,
// 																		tarifario_id,
// 																		cargo,
// 																		cantidad,
// 																		precio,
// 																		valor_cargo,
// 																		valor_nocubierto,
// 																		valor_cubierto,
// 																		usuario_id,
// 																		facturado,
// 																		fecha_cargo,
// 																		fecha_registro,
// 																		valor_descuento_empresa,
// 																		valor_descuento_paciente,
// 																		autorizacion_int,
// 																		autorizacion_ext,
// 																		servicio_cargo,
// 																		porcentaje_gravamen,
// 																		sw_cuota_paciente,
// 																		sw_cuota_moderadora,
// 																		codigo_agrupamiento_id,
// 																		cargo_cups)
// 																VALUES ('".$_SESSION['LABORATORIO']['EMPRESA_ID']."',
// 																'".$_SESSION['LABORATORIO']['CENTROUTILIDAD']."',".$_REQUEST['cuenta'].",'".$_SESSION['LABORATORIO']['DPTO']."','".$resulta->fields[0]."','".$resulta->fields[1]."',$valores[5],
// 																$Precio,$ValorCargo,
// 																$ValorNo,$ValorCub,".UserGetUID().",$facturado,'now()',
// 																'now()',$DescuentoEmp,$DescuentoPac,
// 																$AutoInt,$AutoExt,'$valores[7]',
// 																".$Liq[porcentaje_gravamen].",".$Liq[sw_cuota_paciente].",".$Liq[sw_cuota_moderadora].",$codigo,'".$datos[0][cargo_cups]."')";
//
//    	//											$dbconn->StartTrans();
// 											$dbconn->Execute($query);
//
// 											if ($dbconn->ErrorNo() != 0) {
// 													$this->error = "Error al insertar en cuentas_detalle";
// 													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 													$dbconn->RollbackTrans();
// 													return false;
// 											}
// 											$resulta->MoveNext();
// 							}
				$query="UPDATE os_maestro SET sw_estado='3' where numero_orden_id='$valores[0]'";
				$dbconn->Execute($query);

							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al actualizar en os_maestro";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
							}
				//funcion q inserta en os_cumplimientos_detalle y os_cumplimientos.
				$this->InsertCumplimiento_Y_Detalle($_SESSION['SOLICITUD']['DPTO'],$valores[0],$_REQUEST['tipo_id'],$_REQUEST['pac'],$dbconn,$serial);
				$dbconn->CompleteTrans();   //termina la transaccion
			}


			$action2=ModuloGetURL('app','SolicitudManualAmbulatoria','user','RetornoCumplimiento');

			$this->FormaMensaje('CUMPLIMIENTO REALIZADO SATISFACTORIAMENTE!','INFORMACION',$action2,'volver');
			return true;
	}


/*funcion que deja listo al paciente para que sea visto en las hojas de trabajo*/
	function InsertCumplimiento_Y_Detalle($dpto,$norden,$tipoId,$PacId,&$dbconn,$serial,$user)
	{
			//list($dbconn) = GetDBconn();

			if(empty($user))
			{
				$query="INSERT INTO os_cumplimientos_detalle
							(numero_orden_id,numero_cumplimiento,
							fecha_cumplimiento,departamento)
							VALUES('$norden',".$serial.",
							'".date("Y-m-d")."','$dpto')";
			}
			else
			{
				$query="INSERT INTO os_cumplimientos_detalle
							(numero_orden_id,numero_cumplimiento,
							fecha_cumplimiento,departamento,usuario_id)
							VALUES('$norden',".$serial.",
							'".date("Y-m-d")."','$dpto',$user)";
			}

			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al insertar en os_cumplimientos_detalle";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;	}

					return true;
	}



		/**
		*
		*/
		function GuardarDatosSolicitud()
		{
						if($_REQUEST['Serv']==-1 || !$_REQUEST['Fecha'])
						{
								if($_REQUEST['Serv']==-1){ $this->frmError["Serv"]=1; }
								if(!$_REQUEST['Fecha']){ $this->frmError["Fecha"]=1; }
								$this->frmError["MensajeError"]="Faltan datos obligatorios.";
								if(!$this->FormaDatosSolicitud()){
												return false;
								}
								return true;
						}

						/*if($_REQUEST['Fecha'] > date('d/m/Y'))
						{
										$this->frmError["Fecha"]=1;
										$this->frmError["MensajeError"]="La Fecha debe ser anterior a la actual.";
										if(!$this->FormaDatosSolicitud()){
														return false;
										}
										return true;
						}*/

						$_SESSION['SOLICITUD']['DATOS']['MEDICO']=$_REQUEST['Medico'];
						$f=explode('/',$_REQUEST['Fecha']);
						$Fecha=$f[2].'-'.$f[1].'-'.$f[0];
						$_SESSION['SOLICITUD']['DATOS']['FECHA']=$Fecha;
						$_SESSION['SOLICITUD']['DATOS']['ENTIDAD']=$_REQUEST['Origen'];
						$_SESSION['SOLICITUD']['DATOS']['SERVICIO']=$_REQUEST['Serv'];
						$_SESSION['SOLICITUD']['DATOS']['OBSERVACION']=$_REQUEST['Observacion'];


						$_SESSION['SOLICITUD']['DATOS']['CAMA']=$_REQUEST['cama'];

						if(!empty($_REQUEST['MedInt']))
						{
								$_SESSION['SOLICITUD']['DATOS']['MEDICO']=$_REQUEST['MedInt'];
						}

						if(!empty($_REQUEST['departamento']))
						{
								$_SESSION['SOLICITUD']['DATOS']['DEPARTAMENTO']=$_REQUEST['departamento'];
						}

						$this->Apoyos();
						return true;
		}

		/**
		*
		*/
		function Menu()
		{
				unset($_SESSION['SOLICITUD']['DATOS']);
				unset($_SESSION['SOLICITUD']['PACIENTE']);
				$this->FormaBuscar();
				return true;
		}

    /**
    * Busca el nombre del paciente
    * @access public
    * @return array
    * @param string tipo de documento
    * @param int numero de documento
    */
    function NombrePaciente($TipoDocumento,$Documento)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido as nombre
                                            FROM pacientes
                                            WHERE paciente_id='$Documento' AND tipo_id_paciente ='$TipoDocumento'";
            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            $vars=$resulta->GetRowAssoc($ToUpper = false);
            return $vars;
    }

    /**
    *
    */
    function TiposServicios()
    {
            list($dbconn) = GetDBconn();
            $query = "select servicio, descripcion from servicios where sw_asistencial=1";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
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
		function Busqueda_Avanzada()
		{
					list($dbconn) = GetDBconn();
					$opcion      = ($_REQUEST['criterio1apoyo']);
					$cargo       = ($_REQUEST['cargoapoyo']);
					$descripcion =STRTOUPPER($_REQUEST['descripcionapoyo']);

					$filtroTipoCargo = '';
					$busqueda1 = '';
					$busqueda2 = '';

					if ($cargo != '')
					{
							$busqueda1 =" AND a.cargo LIKE '$cargo%'";
					}

					if ($descripcion != '')
					{
							$busqueda2 ="AND a.descripcion LIKE '%$descripcion%'";
					}


					if(empty($_REQUEST['conteoapoyo']))
					{
							if($opcion == '002')
							{		//frecuentes
									$query = "SELECT count(a.cargo)
									FROM cups a,apoyod_tipos b, departamentos_cargos as c, apoyod_solicitud_frecuencia as d
									WHERE a.cargo = d.cargo and d.departamento='".$_SESSION['SOLICITUD']['DPTO']."'
									and a.grupo_tipo_cargo = b.apoyod_tipo_id
									and a.cargo=c.cargo and c.departamento='".$_SESSION['SOLICITUD']['DPTO']."'
									$dpto $espe $busqueda1 $busqueda2";

									/*$query= "SELECT count(*)
									FROM apoyod_solicitud_frecuencia a, cups b,
									apoyod_tipos c
									WHERE a.cargo = b.cargo
									AND b.grupo_tipo_cargo = c.apoyod_tipo_id
									$dpto $espe $busqueda1 $busqueda2";*/
							}
							else
							{
									$query = "SELECT count(a.cargo)
									FROM cups a,apoyod_tipos b, departamentos_cargos as c
									WHERE a.grupo_tipo_cargo = b.apoyod_tipo_id
									and a.cargo=c.cargo and c.departamento='".$_SESSION['SOLICITUD']['DPTO']."'
									$filtroTipoCargo    $busqueda1 $busqueda2";

									/*$query = "SELECT count(*)
									FROM cups a,apoyod_tipos b
									WHERE a.grupo_tipo_cargo = b.apoyod_tipo_id
									$filtroTipoCargo    $busqueda1 $busqueda2";*/
							}

							$resulta = $dbconn->Execute($query);

							if ($dbconn->ErrorNo() != 0)
							{
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}
							list($this->conteo)=$resulta->FetchRow();

					}
					else
					{
							$this->conteo=$_REQUEST['conteoapoyo'];
					}
					if(!$_REQUEST['Ofapoyo'])
					{
							$Of='0';
					}
					else
					{
							$Of=$_REQUEST['Ofapoyo'];
							if($Of > $this->conteo)
							{
									$Of=0;
									$_REQUEST['Ofapoyo']=0;
									$_REQUEST['paso1apoyo']=1;
							}
					}
					if($opcion == '002')
					{
                                                        $query = "SELECT DISTINCT a.cargo, a.descripcion, b.apoyod_tipo_id, b.descripcion as tipo
							FROM cups a,apoyod_tipos b, departamentos_cargos as c, apoyod_solicitud_frecuencia as d
							WHERE a.cargo = d.cargo and  d.departamento='".$_SESSION['SOLICITUD']['DPTO']."'
							and a.grupo_tipo_cargo = b.apoyod_tipo_id
							and a.cargo=c.cargo and c.departamento='".$_SESSION['SOLICITUD']['DPTO']."'
							$dpto $espe $busqueda1 $busqueda2
							order by a.descripcion, a.cargo
							LIMIT ".$this->limit." OFFSET $Of;";
					}
					else
					{
							$query = "SELECT a.cargo, a.descripcion, b.apoyod_tipo_id, b.descripcion as tipo
							FROM cups a,apoyod_tipos b, departamentos_cargos as c
							WHERE a.grupo_tipo_cargo = b.apoyod_tipo_id
							and a.cargo=c.cargo and c.departamento='".$_SESSION['SOLICITUD']['DPTO']."'
							$filtroTipoCargo    $busqueda1 $busqueda2
							order by b.apoyod_tipo_id, a.cargo
							LIMIT ".$this->limit." OFFSET $Of;";
					}
					$resulta = $dbconn->Execute($query);

					if ($dbconn->ErrorNo() != 0)
					{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
                                        //$this->conteo=$resulta->RecordCount();
					$i=0;
					while(!$resulta->EOF)
					{
							$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
							$resulta->MoveNext();
							$i++;
					}

					if($this->conteo==='0')
					{
									$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
					}

					$this->frmForma($var);
					return true;
		}

		/**
		*
		*/
		function BuscarDatosTmp()
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT a.*, b.*
									FROM tmp_solicitud_manual as a, tmp_solicitud_manual_detalle as b
									WHERE a.codigo=".$_SESSION['SOLICITUD']['SERIAL']."
									AND a.tipo_id_paciente='".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."'
									AND a.paciente_id='".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."'
									AND a.tmp_solicitud_manual_id=b.tmp_solicitud_manual_id
									AND a.usuario_id=".UserGetUID()."
									order by a.cargo_cups";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				while (!$result->EOF)
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
		function InsertarTmp($cups,$tipo,$vector)
		{
						list($dbconn) = GetDBconn();
						$dbconn->BeginTrans();

						$query=" SELECT nextval('tmp_solicitud_manual_tmp_solicitud_manual_id_seq')";
						$result=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error INSERT INTO cuentas_detalle ";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
						}
						$id=$result->fields[0];

						$query = "INSERT INTO tmp_solicitud_manual (
													tmp_solicitud_manual_id,
													codigo,
													tipo_id_paciente,
													paciente_id,
													apoyod_tipo_id,
													cargo_cups,
													fecha_registro,
													usuario_id)
											VALUES ($id,".$_SESSION['SOLICITUD']['SERIAL'].",'".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."','".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."','$tipo','$cups','now()',".UserGetUID().")";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error INSERT INTO cuentas_detalle ";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
						}

						for($i=0; $i<sizeof($vector); $i++)
						{
								$query = "INSERT INTO tmp_solicitud_manual_detalle (
															tmp_solicitud_manual_id,
															tarifario_id,
															cargo,
															descripcion,
															cantidad)
													VALUES ($id,'".$vector[$i]['tarifario']."','".$vector[$i]['cargo']."','".$vector[$i]['descripcion']."',".$vector[$i]['cantidad'].")";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error INSERT INTO cuentas_detalle ";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
								}
						}
						$dbconn->CommitTrans();
						return true;
		}

		/**
		*
		*/
		function GuardarApoyo()
		{
				IncludeLib("malla_validadora");
				//0 cargo_cups 1 apoyod_tipo_id
				//$v=explode('//',$_REQUEST['apoyo']);
				$x=ValidarCargoMalla($_REQUEST['cargo'],$_SESSION['SOLICITUD']['PACIENTE']['plan_id'],$_SESSION['SOLICITUD']['SERVICIO']);
				//pasa la malla
				if(is_numeric($x))
				{
						//varias equivalencias
						if($x==2)
						{
								$this->FormaVariasEquivalencias();
								return true;
						}
				}
				elseif(is_array($x))
				{		//paso la malla
						//un vector [] tarifario cargo descricpion cantidad
						$vector[0]=array('tarifario'=>$x[tarifario],'cargo'=>$x[cargo],'descripcion'=>$x[descripcion],'cantidad'=>1);
						$insertar=$this->InsertarTmp($_REQUEST['cargo'],$_REQUEST['apoyod_tipo_id'],$vector);
						if(!empty($insertar))
						{		$this->frmError["MensajeError"]="SOLICITUD GUARDADA";  }
						else
						{		$this->frmError["MensajeError"]="ERROR EN LA INSERCCION";  }

						$this->frmForma('');
						return true;
				}
				else
				{		//se quedo en la malla
						$this->frmError["MensajeError"]="NO SE PUEDE SOLICITAR PORQUE: $x";
						$this->frmForma('');
						return true;
				}
		}


		/**
		*
		*/
		function GuardarEquivalencias()
		{
				$f=0;
				foreach($_REQUEST as $k => $v)
				{
						if(substr_count($k,'Equi'))
						{  $f++;  }
				}

				if($f==0)
				{
						$this->frmError["MensajeError"]="DEBE ELEGIR AL MENOS UNA EQUIVALENCIA";
						$this->FormaVariasEquivalencias();
						return true;
				}

				$vector='';
				foreach($_REQUEST as $k => $v)
				{
						if(substr_count($k,'Equi'))
						{
								//0 tarifario 1cargo 2 descripcion 3 cantidad
								$dat=explode('//',$v);
								$vector[]=array('tarifario'=>$dat[0],'cargo'=>$dat[1],'descripcion'=>$dat[2],'cantidad'=>1);
						}
				}

				$insertar=$this->InsertarTmp($_REQUEST['cups'],$_REQUEST['apoyod_tipo_id'],$vector);
				if(!empty($insertar))
				{		$this->frmError["MensajeError"]="SOLICITUD GUARDADA";  }
				else
				{		$this->frmError["MensajeError"]="ERROR EN LA INSERCCION";  }

				$this->frmForma('');
				return true;
		}


	/**
	*
	*/
	function ValdiarEquivalencias($plan_id,$cargo)
	{
        list($dbconn) = GetDBconn();
   			$query = "(  SELECT b.plan_id, a.grupo_tarifario_id, a.subgrupo_tarifario_id,
											a.grupo_tipo_cargo, a.tipo_cargo, a.tarifario_id, a.cargo,
											a.descripcion, a.precio, a.gravamen, b.porcentaje, b.por_cobertura,
											b.sw_descuento, a.sw_cantidad, e.sw_copagos, g.cargo_base
											FROM tarifarios_detalle a, plan_tarifario b,
											subgrupos_tarifarios e, tarifarios_equivalencias as g
											WHERE	g.cargo_base='$cargo' and g.cargo=a.cargo
											and g.tarifario_id=a.tarifario_id and
											b.plan_id = $plan_id and
											b.grupo_tarifario_id = a.grupo_tarifario_id AND
											b.subgrupo_tarifario_id    = a.subgrupo_tarifario_id AND
											b.tarifario_id = a.tarifario_id AND
											a.grupo_tarifario_id<>'00' AND
											a.grupo_tipo_cargo<>'SYS' AND
											e.subgrupo_tarifario_id = a.subgrupo_tarifario_id AND
											e.grupo_tarifario_id = a.grupo_tarifario_id AND
											excepciones(b.plan_id,b.tarifario_id, a.cargo) = 0
                    )
                    UNION
                    (
                        SELECT 	b.plan_id, a.grupo_tarifario_id, a.subgrupo_tarifario_id,
												a.grupo_tipo_cargo, a.tipo_cargo, a.tarifario_id, a.cargo,
												a.descripcion, a.precio, a.gravamen, b.porcentaje, b.por_cobertura,
												b.sw_descuento, a.sw_cantidad, e.sw_copagos, g.cargo_base
												FROM tarifarios_detalle a, excepciones b,
												subgrupos_tarifarios e, tarifarios_equivalencias as g
												WHERE g.cargo_base='$cargo' and g.cargo=a.cargo
												and g.tarifario_id=a.tarifario_id and
												b.plan_id = $plan_id AND
                        b.tarifario_id = a.tarifario_id AND
                        b.sw_no_contratado = 0 AND
                        b.cargo = a.cargo AND
                        e.grupo_tarifario_id = a.grupo_tarifario_id AND
                        e.subgrupo_tarifario_id = a.subgrupo_tarifario_id
                    )";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }

				while (!$result->EOF)
				{
					$vars[]= $result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
				$result->Close();
				return $vars;
	}


	/**
	*
	*/
	function EliminarCargo()
	{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();

			$query =" DELETE FROM tmp_solicitud_manual_detalle WHERE tmp_solicitud_manual_id=".$_REQUEST['id']."
								AND	tmp_solicitud_manual_detalle_id=".$_REQUEST['idDetalle']."";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error DELETE FROM tmp_solicitud_manual_detalle";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
			}

			$query =" SELECT * FROM tmp_solicitud_manual_detalle WHERE tmp_solicitud_manual_id=".$_REQUEST['id']."";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error SELECT";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			if($result->EOF)
			{
					$query =" DELETE FROM tmp_solicitud_manual WHERE tmp_solicitud_manual_id=".$_REQUEST['id']."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error DELETE FROM tmp_solicitud_manual";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
					}
			}

			$dbconn->CommitTrans();
			$this->frmError["MensajeError"]="SE ELIMINO";

			$this->frmForma('');
			return true;
	}

	/**
	*
	*/
	function Cumplir()
	{
			$this->FormaCumplir();
			return true;
	}

	/**
	*
	*/
	function CrearOs()
	{
				list($dbconn) = GetDBconn();
				$query = "SELECT a.*
									FROM tmp_solicitud_manual as a
									WHERE a.codigo=".$_SESSION['SOLICITUD']['SERIAL']."
									AND a.tipo_id_paciente='".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."'
									AND a.paciente_id='".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."'
									AND a.usuario_id=".UserGetUID()."
									order by a.cargo_cups";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				while (!$result->EOF)
				{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
				$result->Close();


				$query="SELECT nextval('os_ordenes_servicios_orden_servicio_id_seq')";
				$result=$dbconn->Execute($query);
				$orden=$result->fields[0];
				$query = "INSERT INTO os_ordenes_servicios
															(orden_servicio_id,
															autorizacion_int,
															autorizacion_ext,
															plan_id,
															tipo_afiliado_id,
															rango,
															semanas_cotizadas,
															servicio,
															tipo_id_paciente,
															paciente_id,
															usuario_id,
															fecha_registro,
															observacion)
				VALUES($orden,1,NULL,".$_SESSION['SOLICITUD']['PACIENTE']['plan_id'].",'".$_SESSION['SOLICITUD']['PACIENTE']['tipo_afiliado_id']."',
				'".$_SESSION['SOLICITUD']['PACIENTE']['rango']."',".$_SESSION['SOLICITUD']['PACIENTE']['semanas'].",
				'".$_SESSION['SOLICITUD']['DATOS']['SERVICIO']."','".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."','".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."',
				".UserGetUID().",'now()','')";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error INSERT INTO os_ordenes_servicios";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}

				$query3="INSERT INTO hc_os_solicitudes_manuales_datos_adicionales(
																	orden_servicio_id,
																	cama,
																	departamento)
						VALUES($orden,'".$_SESSION['SOLICITUD']['DATOS']['CAMA']."','".$_SESSION['SOLICITUD']['DATOS']['DEPARTAMENTO']."')";
				$dbconn->Execute($query3);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al insertar en hc_os_solicitudes_manuales";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}

				$query="SELECT secuencia_os_cumplimiento('".$_SESSION['SOLICITUD']['DPTO']."')";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
							$this->error = "Error al insertar en cuentas_detalle";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
				}
				$serial=$result->fields[0];//generamos el numero de cumplimiento

				$query = "INSERT INTO os_cumplimientos(
																numero_cumplimiento,
																fecha_cumplimiento,
																departamento,
																tipo_id_paciente,
																paciente_id)
									VALUES(".$serial.",'".date("Y-m-d")."','".$_SESSION['SOLICITUD']['DPTO']."','".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."','".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."')";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
				}

				for($i=0; $i<sizeof($var); $i++)
				{
						$query1="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
						$result=$dbconn->Execute($query1);
						$hc_os_solicitud_id=$result->fields[0];

						$query2="INSERT INTO hc_os_solicitudes
										  (hc_os_solicitud_id, evolucion_id, cargo, os_tipo_solicitud_id, plan_id, paciente_id, tipo_id_paciente)
									 VALUES($hc_os_solicitud_id,NULL,'".$var[$i][cargo_cups]."', 'APD',
										   ".$_SESSION['SOLICITUD']['PACIENTE']['plan_id'].",
                                                     '".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."',
                                                     '".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."')";
						$dbconn->Execute($query2);
						if ($dbconn->ErrorNo() != 0)
						{
								$this->error = "Error al insertar en hc_os_solicitudes";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
						}

						$query3="INSERT INTO hc_os_solicitudes_apoyod
														(hc_os_solicitud_id, apoyod_tipo_id)
										VALUES($hc_os_solicitud_id, '".$var[$i][apoyod_tipo_id]."');";
						$dbconn->Execute($query3);
						if ($dbconn->ErrorNo() != 0)
						{
								$this->error = "Error al insertar en hc_os_solicitudes_apoyod";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
						}

						$query3="INSERT INTO hc_os_solicitudes_manuales
								VALUES($hc_os_solicitud_id, '".$_SESSION['SOLICITUD']['DATOS']['FECHA']."',
								'".$_SESSION['SOLICITUD']['DATOS']['SERVICIO']."','".$_SESSION['SOLICITUD']['DATOS']['MEDICO']."',
								'".$_SESSION['SOLICITUD']['DATOS']['ENTIDAD']."','".$_SESSION['SOLICITUD']['DATOS']['OBSERVACION']."',
								'".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."','".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."',
								'now()',".UserGetUID().",'".$_SESSION['SOLICITUD']['EMPRESA']."',
								'".$_SESSION['SOLICITUD']['PACIENTE']['tipo_afiliado_id']."','".$_SESSION['SOLICITUD']['PACIENTE']['rango']."',".$_SESSION['SOLICITUD']['PACIENTE']['semanas'].");";
						$dbconn->Execute($query3);
						if ($dbconn->ErrorNo() != 0)
						{
								$this->error = "Error al insertar en hc_os_solicitudes_manuales";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
						}

						//para las ordenes
						/*$query="SELECT nextval('os_ordenes_servicios_orden_servicio_id_seq')";
						$result=$dbconn->Execute($query);
						$orden=$result->fields[0];
						$query = "INSERT INTO os_ordenes_servicios
																	(orden_servicio_id,
																	autorizacion_int,
																	autorizacion_ext,
																	plan_id,
																	tipo_afiliado_id,
																	rango,
																	semanas_cotizadas,
																	servicio,
																	tipo_id_paciente,
																	paciente_id,
																	usuario_id,
																	fecha_registro,
																	observacion)
						VALUES($orden,1,NULL,".$_SESSION['SOLICITUD']['PACIENTE']['plan_id'].",'".$_SESSION['SOLICITUD']['PACIENTE']['tipo_afiliado_id']."',
						'".$_SESSION['SOLICITUD']['PACIENTE']['rango']."',".$_SESSION['SOLICITUD']['PACIENTE']['semanas'].",
						'".$_SESSION['SOLICITUD']['DATOS']['SERVICIO']."','".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."','".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."',
						".UserGetUID().",'now()','')";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
								$this->error = "Error INSERT INTO os_ordenes_servicios";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
						}*/

						$plan=$_SESSION['SOLICITUD']['PACIENTE']['plan_id'];
						$query = "select * from os_tipos_periodos_planes
											where plan_id=".$plan." and cargo='".$var[$i][cargo_cups]."'";
						$result=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
														//echo "salida 2 <br>";
														return false;
						}
						if(!$result->EOF)
						{
								$vars=$result->GetRowAssoc($ToUpper = false);
								$Fecha=$this->FechaStamp($fecha_solicitud);
								$infoCadena = explode ('/',$Fecha);
								$intervalo=$this->HoraStamp($fecha_solicitud);
								$infoCadena1 = explode (':', $intervalo);
								$fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_tramite_os]),$infoCadena[2]));
								if($fechaAct < date("Y-m-d H:i:s"))
								{  $fechaAct=date("Y-m-d H:i:s");  }
								$Fecha=$this->FechaStamp($fechaAct);
								$infoCadena = explode ('/',$Fecha);
								$intervalo=$this->HoraStamp($fechaAct);
								$infoCadena1 = explode (':', $intervalo);
								$venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_vigencia]),$infoCadena[2]));
								//fecha refrendar
								$Fecha=$this->FechaStamp($venc);
								$infoCadena = explode ('/',$Fecha);
								$intervalo=$this->HoraStamp($venc);
								$infoCadena1 = explode (':', $intervalo);
								$refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
						}
						else
						{                //si no hay unos tiempos especificos para el cargo toma los genericos
								$query = "select * from os_tipos_periodos_tramites
													where cargo='".$var[$i][cargo_cups]."'";
								$result=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
								      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																//echo "salida 3 <br>";
																return false;
								}
								if(!$result->EOF)
								{
										$vars=$result->GetRowAssoc($ToUpper = false);
										$Fecha=$this->FechaStamp($fecha_solicitud);
										$infoCadena = explode ('/',$Fecha);
										$intervalo=$this->HoraStamp($fecha_solicitud);
										$infoCadena1 = explode (':', $intervalo);
										$fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_tramite_os]),$infoCadena[2]));
										if($fechaAct < date("Y-m-d H:i:s"))
										{  $fechaAct=date("Y-m-d H:i:s");  }
										$Fecha=$this->FechaStamp($fechaAct);
										$infoCadena = explode ('/',$Fecha);
										$intervalo=$this->HoraStamp($fechaAct);
										$infoCadena1 = explode (':', $intervalo);
										$venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_vigencia]),$infoCadena[2]));
										//fecha refrendar
										$Fecha=$this->FechaStamp($venc);
										$infoCadena = explode ('/',$Fecha);
										$intervalo=$this->HoraStamp($venc);
										$infoCadena1 = explode (':', $intervalo);
										$refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
								}
								else
								{
										$tramite=ModuloGetVar('app','CentroAutorizacion','dias_tramite_os');
										$vigencia=ModuloGetVar('app','CentroAutorizacion','dias_vigencia');
										$vars=$result->GetRowAssoc($ToUpper = false);
										$Fecha=$this->FechaStamp($fecha_solicitud);
										$infoCadena = explode ('/',$Fecha);
										$intervalo=$this->HoraStamp($fecha_solicitud);
										$infoCadena1 = explode (':', $intervalo);
										$fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$tramite),$infoCadena[2]));
										if($fechaAct < date("Y-m-d H:i:s"))
										{  $fechaAct=date("Y-m-d H:i:s");  }
										$Fecha=$this->FechaStamp($fechaAct);
										$infoCadena = explode ('/',$Fecha);
										$intervalo=$this->HoraStamp($fechaAct);
										$infoCadena1 = explode (':', $intervalo);
										$venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$vigencia),$infoCadena[2]));
										//fecha refrendar
										$Fecha=$this->FechaStamp($venc);
										$infoCadena = explode ('/',$Fecha);
										$intervalo=$this->HoraStamp($venc);
										$infoCadena1 = explode (':', $intervalo);
										$refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
								}

								$query="SELECT nextval('os_maestro_numero_orden_id_seq')";
								$result=$dbconn->Execute($query);
								$numorden=$result->fields[0];

								$query = "INSERT INTO os_maestro
																		(numero_orden_id,
																		orden_servicio_id,
																		sw_estado,
																		fecha_vencimiento,
																		hc_os_solicitud_id,
																		fecha_activacion,
																		cantidad,
																		cargo_cups,
																		fecha_refrendar)
								VALUES($numorden,$orden,3,'$venc',".$hc_os_solicitud_id.",'$fechaAct',1,'".$var[$i][cargo_cups]."','$refrendar')";
								$result=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$dbconn->RollbackTrans();
												return false;
								}
								//insertar en hc_os_autorizaciones para que le aparezca a claudia
								$query = "INSERT INTO hc_os_autorizaciones
																				(autorizacion_int,autorizacion_ext,
																				hc_os_solicitud_id)
																		VALUES(1,1,'".$hc_os_solicitud_id."')";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																$dbconn->RollbackTrans();
																//echo "salida 5 <br>";
																return false;
								}

								$query = "INSERT INTO os_cumplimientos_detalle(
																				numero_orden_id,
																				numero_cumplimiento,
																				fecha_cumplimiento,
																				departamento,
																				sw_estado)
													VALUES($numorden,".$serial.",'".date("Y-m-d")."','".$_SESSION['SOLICITUD']['DPTO']."','0')";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$dbconn->RollbackTrans();
												return false;
								}

								$query = "SELECT a.*, b.*
													FROM tmp_solicitud_manual as a, tmp_solicitud_manual_detalle as b
													WHERE a.codigo=".$_SESSION['SOLICITUD']['SERIAL']."
													AND a.tipo_id_paciente='".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."'
													AND a.paciente_id='".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."'
													AND a.tmp_solicitud_manual_id=b.tmp_solicitud_manual_id
													AND a.usuario_id=".UserGetUID()." AND a.cargo_cups='".$var[$i][cargo_cups]."'
													order by a.cargo_cups";
								$result = $dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
								}
								while (!$result->EOF)
								{
									$arr[]=$result->GetRowAssoc($ToUpper = false);
									$result->MoveNext();
								}

								for($j=0; $j<sizeof($arr); $j++)
								{
											$query = "INSERT INTO os_maestro_cargos
																				(numero_orden_id,
																				tarifario_id,
																				cargo)
																VALUES($numorden,'".$arr[$j][tarifario_id]."','".$arr[$j][cargo]."')";
											$dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
												   					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																		$dbconn->RollbackTrans();
																		//echo "salida 6<br>";
																		return false;
											}
								}//fin for os
								$query = "INSERT INTO os_internas(numero_orden_id,
																										cargo,
																										departamento)
													VALUES($numorden,'".$var[$i][cargo_cups]."','".$_SESSION['SOLICITUD']['DPTO']."')";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
															$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																$dbconn->RollbackTrans();
																//echo "salida 7<br>";
																return false;
								}
								//actualiza a 0 para indicar que ya paso por el proceso de autorizacion
								$query = "UPDATE hc_os_solicitudes SET    sw_estado=0
													WHERE hc_os_solicitud_id=".$hc_os_solicitud_id."";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
																//echo "salida 8<br>";
																$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$dbconn->RollbackTrans();
												return false;
								}
						}
				}

				$this->frmError["MensajeError"] = 'LA ORDEN No. '.$orden.' FUE GENERADA.';
				$this->FormaImpresionCumplimiento($orden);
				//$accion=ModuloGetURL('app','SolicitudManualAmbulatoria','user','LlamarFormaBuscar');
				//$this-> FormaMensaje($Mensaje,'SOLICITUD MANUAL',$accion,'');
				return true;
	}

	/**
	*
	*/
	function Apoyos()
	{
					$this->frmForma();
					return true;
	}

		/**
		*
		*/
		function BuscarCamposObligatorios()
		{
					list($dbconn) = GetDBconn();
					$query="SELECT campo,sw_mostrar,sw_obligatorio FROM pacientes_campos_obligatorios";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}

					while(!$result->EOF){
							$var[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}

					$result->Close();
					return $var;
		}

	/**
	* Busca los diferentes tipos de identificacion de los paciente
	* @access public
	* @return array
	*/
	function tipo_id_paciente()
  {
					list($dbconn) = GetDBconn();
					$query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
					$result = $dbconn->Execute($query);

					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					else{
							if($result->EOF){
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
									return false;
							}
									while (!$result->EOF) {
											$vars[$result->fields[0]]=$result->fields[1];
											$result->MoveNext();
									}
					}
					$result->Close();
          return $vars;
	}

	/**
	*
	*/
	function BuscarDepartamento()
	{
					list($dbconn) = GetDBconn();
					$query = "SELECT a.* FROM departamentos as a, servicios as b
										WHERE a.empresa_id= '".$_SESSION['SOLICITUD']['EMPRESA']."'
										and a.servicio=b.servicio and b.sw_asistencial=1
										ORDER BY descripcion";
					$result = $dbconn->Execute($query);

					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					else{
							if($result->EOF){
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
									return false;
							}
									while (!$result->EOF) {
											$vars[]=$result->GetRowAssoc($ToUpper = false);;
											$result->MoveNext();
									}
					}
					$result->Close();
          return $vars;
	}

	/**
	*
	*/
	function Profesionales()
	{
					list($dbconn) = GetDBconn();
					$query = "SELECT * FROM profesionales
										WHERE tipo_profesional in(1,2)
										ORDER BY nombre";
					$result = $dbconn->Execute($query);

					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					else{
							if($result->EOF){
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
									return false;
							}
									while (!$result->EOF) {
											$vars[]=$result->GetRowAssoc($ToUpper = false);;
											$result->MoveNext();
									}
					}
					$result->Close();
          return $vars;
	}


	/**
	*
	*/
	function TraerOSestado3($orden)
	{
			list($dbconn) = GetDBconn();
			$query="SELECT
						c.plan_id,c.plan_descripcion,a.servicio,h.descripcion as serv_des,
						sw_cargo_multidpto as switche,
						CASE c.sw_tipo_plan
						WHEN '0' THEN d.nombre_tercero
						WHEN '1' THEN 'SOAT'
						WHEN '2' THEN 'PARTICULAR'
						WHEN '3' THEN 'CAPITACION - '||d.nombre_tercero
						ELSE e.descripcion END,
						a.tipo_afiliado_id,a.rango,a.orden_servicio_id,f.numero_orden_id,a.fecha_registro,
						i.fecha_vencimiento, f.cargo as cargoi,g.descripcion as des1,i.cantidad,
						a.autorizacion_int,a.autorizacion_ext,a.observacion,
						k.tipo_afiliado_nombre
						FROM os_ordenes_servicios as a, pacientes as b, planes c,
						terceros d, tipos_planes as e, os_internas as f, cups g,
						servicios h,os_maestro i,tipos_afiliado k
						WHERE
						a.orden_servicio_id=i.orden_servicio_id
						AND i.numero_orden_id=f.numero_orden_id
						AND a.tipo_id_paciente=b.tipo_id_paciente
						AND a.paciente_id=b.paciente_id
						AND a.orden_servicio_id=$orden
						AND a.servicio=h.servicio
						AND g.cargo=f.cargo
						AND c.plan_id=a.plan_id
						AND e.sw_tipo_plan=c.sw_tipo_plan
						AND c.tercero_id=d.tercero_id
						AND c.tipo_tercero_id=d.tipo_id_tercero
						AND f.departamento='".$_SESSION['SOLICITUD']['DPTO']."'
						AND i.sw_estado=3
						AND a.tipo_afiliado_id=k.tipo_afiliado_id
						AND DATE(i.fecha_activacion) <= NOW()
						ORDER BY f.numero_orden_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer las 0rdenes de servicios";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
			}
			$result->Close();
			return $var;
	}


//------------------------------------------------------------------------------------

}//fin clase user

?>

