 <?php

/**
 * $Id: app_Triage_user.php,v 1.39 2006/11/14 13:30:29 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo logico de triage de los pacientes.
 */

/**
* Clase app_Triage_user
*
* Contiene los metodos para realizar el triage y admision de los pacientes
*/

class app_Triage_user extends classModulo
{

        var $classVista;
        var $frmError=array();
        var $Funcionario;
        var $limit;
        var $conteo;
				//var $BOOKMARK_DIAGNOSTICO;

    /**
    * Es el contructor de la clase
    * @return boolean
    */
    function app_Triage_user()
    {
            $this->frmError=array();
            $this->limit=GetLimitBrowser();
						//$this->BOOKMARK_DIAGNOSTICO = false;
            return true;
    }


    /**
    * Busca los puntos de admision de urgencias a los que tiene permiso el usuario
    * @access public
    * @return boolean
    */
    function main()
    {
            unset($_SESSION['TRIAGE']);
            unset($_SESSION['SEGURIDAD']);
            $SystemId=UserGetUID();
            if(!empty($_SESSION['SEGURIDAD']['PTOADMISION']['URGENCIAS']))
            {
                        $this->salida.= gui_theme_menu_acceso('ADMISIONES',$_SESSION['SEGURIDAD']['PTOADMISION']['URGENCIAS']['arreglo'],$_SESSION['SEGURIDAD']['PTOADMISION']['URGENCIAS']['admon'],$_SESSION['SEGURIDAD']['PTOADMISION']['URGENCIAS']['url'],ModuloGetURL('system','Menu'));
                        return true;
            }
            list($dbconn) = GetDBconn();
						//$dconn->debug = true;
            GLOBAL $ADODB_FETCH_MODE;
            $query = "select c.servicio, b.tipo_admision_id, b.descripcion as descripcion5, c.empresa_id,
											c.centro_utilidad, d.razon_social as descripcion1,
											e.descripcion as descripcion2, b.tipo_admision_id,
											b.punto_admision_id, b.sw_triage, b.departamento, c.descripcion as descripcion4,
											f.unidad_funcional, f.descripcion as descripcion3, b.sw_soat
											from puntos_admisiones_usuarios as a, puntos_admisiones as b,
											departamentos as c, empresas as d, centros_utilidad as e,
											unidades_funcionales as f
											where a.usuario_id=$SystemId and b.tipo_admision_id='UR'
											and a.punto_admision_id=b.punto_admision_id and b.departamento=c.departamento
											and d.empresa_id=c.empresa_id and c.empresa_id=e.empresa_id
											and c.centro_utilidad=e.centro_utilidad and e.empresa_id=f.empresa_id
											and e.centro_utilidad=f.centro_utilidad and c.unidad_funcional=f.unidad_funcional
											order by f.empresa_id, f.centro_utilidad, f.unidad_funcional";
                      
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $resulta=$dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }

            while ($data = $resulta->FetchRow()) {
                $admon[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']][$data['descripcion4']][$data['descripcion5']]=$data;
                $seguridad[$data['empresa_id']][$data['centro_utilidad']][$data['unidad_funcional']][$data['departamento']][$data['punto_admision_id']]=1;
            }

            $url[0]='app';
            $url[1]='Triage';
            $url[2]='user';
            $url[3]='Menus';
            $url[4]='Admon';

            $arreglo[0]='EMPRESA';
            $arreglo[1]='CENTRO UTILIDAD';
            $arreglo[2]='UNIDAD FUNCIONAL';
            $arreglo[3]='DEPARTAMENTO';
            $arreglo[4]='ADMISIONES';

            $_SESSION['SEGURIDAD']['PTOADMISION']['URGENCIAS']['arreglo']=$arreglo;
            $_SESSION['SEGURIDAD']['PTOADMISION']['URGENCIAS']['admon']=$admon;
            $_SESSION['SEGURIDAD']['PTOADMISION']['URGENCIAS']['url']=$url;
            $_SESSION['SEGURIDAD']['PTOADMISION']['URGENCIAS']['puntos']=$seguridad;
            $this->salida.= gui_theme_menu_acceso('ADMISIONES',$_SESSION['SEGURIDAD']['PTOADMISION']['URGENCIAS']['arreglo'],$_SESSION['SEGURIDAD']['PTOADMISION']['URGENCIAS']['admon'],$_SESSION['SEGURIDAD']['PTOADMISION']['URGENCIAS']['url'],ModuloGetURL('system','Menu'));
            return true;
    }

		/**
		*
		*/
		function Triage()
		{
            unset($_SESSION['TRIAGE']['PUNTO']);
						unset($_SESSION['TRIAGE']);
            unset($_SESSION['SEGURIDAD']);
            $SystemId=UserGetUID();
            if(!empty($_SESSION['SEGURIDAD']['PTOTRIAGE']['URGENCIAS']))
            {
                        $this->salida.= gui_theme_menu_acceso('TRIAGE',$_SESSION['SEGURIDAD']['PTOTRIAGE']['URGENCIAS']['arreglo'],$_SESSION['SEGURIDAD']['PTOTRIAGE']['URGENCIAS']['admon'],$_SESSION['SEGURIDAD']['PTOTRIAGE']['URGENCIAS']['url'],ModuloGetURL('system','Menu'));
                        return true;
            }
            list($dbconn) = GetDBconn();
            GLOBAL $ADODB_FETCH_MODE;
            $query = "select c.servicio, b.descripcion as descripcion5, c.empresa_id,
											d.razon_social as descripcion1,e.descripcion as descripcion2,
											b.punto_triage_id, b.departamento, c.descripcion as descripcion4,
											f.unidad_funcional, f.descripcion as descripcion3,c.centro_utilidad
											from userpermisos_puntos_triage as a, puntos_triage as b,
											departamentos as c, empresas as d, centros_utilidad as e,
											unidades_funcionales as f
											where a.usuario_id=$SystemId
											and a.punto_triage_id=b.punto_triage_id and b.departamento=c.departamento
											and d.empresa_id=c.empresa_id and c.empresa_id=e.empresa_id
											and c.centro_utilidad=e.centro_utilidad and e.empresa_id=f.empresa_id
											and e.centro_utilidad=f.centro_utilidad and c.unidad_funcional=f.unidad_funcional
											order by f.empresa_id, f.centro_utilidad, f.unidad_funcional";
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $resulta=$dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }

            while ($data = $resulta->FetchRow()) {
                $admon[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']][$data['descripcion4']][$data['descripcion5']]=$data;
                $seguridad[$data['empresa_id']][$data['centro_utilidad']][$data['unidad_funcional']][$data['departamento']][$data['punto_triage_id']]=1;
            }

            $url[0]='app';
            $url[1]='Triage';
            $url[2]='user';
            $url[3]='UserTriage';
            $url[4]='Triage';

            $arreglo[0]='EMPRESA';
            $arreglo[1]='CENTRO UTILIDAD';
            $arreglo[2]='UNIDAD FUNCIONAL';
            $arreglo[3]='DEPARTAMENTO';
            $arreglo[4]='TRIAGE';

            $_SESSION['SEGURIDAD']['PTOTRIAGE']['URGENCIAS']['arreglo']=$arreglo;
            $_SESSION['SEGURIDAD']['PTOTRIAGE']['URGENCIAS']['triage']=$admon;
            $_SESSION['SEGURIDAD']['PTOTRIAGE']['URGENCIAS']['url']=$url;
            $_SESSION['SEGURIDAD']['PTOTRIAGE']['URGENCIAS']['puntos']=$seguridad;
            $this->salida.= gui_theme_menu_acceso('TRIAGE',$_SESSION['SEGURIDAD']['PTOTRIAGE']['URGENCIAS']['arreglo'],$_SESSION['SEGURIDAD']['PTOTRIAGE']['URGENCIAS']['triage'],$_SESSION['SEGURIDAD']['PTOTRIAGE']['URGENCIAS']['url'],ModuloGetURL('system','Menu'));
            return true;
		}

		/**
		*
		*/
		function UserTriage()
		{
            if(empty($_SESSION['TRIAGE']['PUNTO']['EMPRESA']))
            {
                    if(empty($_SESSION['SEGURIDAD']['PTOTRIAGE']['URGENCIAS']['puntos'][$_REQUEST['Triage']['empresa_id']][$_REQUEST['Triage']['centro_utilidad']][$_REQUEST['Triage']['unidad_funcional']][$_REQUEST['Triage']['departamento']][$_REQUEST['Triage']['punto_triage_id']]))
                    {
                            $this->error = "Error de Seguridad.";
                            $this->mensajeDeError = "Violación a la Seguridad.";
                            return false;
                    }
										$_SESSION['TRIAGE']['PUNTO']['EMPRESA']=$_REQUEST['Triage']['empresa_id'];
										$_SESSION['TRIAGE']['PUNTO']['CENTROUTILIDAD']=$_REQUEST['Triage']['centro_utilidad'];
										$_SESSION['TRIAGE']['PUNTO']['UNIDADFUNCIONAL']=$_REQUEST['Triage']['unidad_funcional'];
										$_SESSION['TRIAGE']['PUNTO']['PTOTRIAGE']=$_REQUEST['Triage']['punto_triage_id'];
										$_SESSION['TRIAGE']['PUNTO']['DPTO']=$_REQUEST['Triage']['departamento'];
										$_SESSION['TRIAGE']['PUNTO']['SERVICIO']=$_REQUEST['Triage']['servicio'];
										$_SESSION['TRIAGE']['PUNTO']['TIPO']='URGENCIAS';
										$_SESSION['TRIAGE']['SWTRIAGE']=true;
										//1 especialista - 2 medico - 3 enfermera - 4 aux. enfermeria
										IncludeLib('historia_clinica');
                   	$_SESSION['TRIAGE']['PUNTO']['FUNCIONARIO']=GetTipoProfesional(UserGetUID());
            }
						
						$this->BorrarProceso();
											
      			if(!$this->FormaMenuTriage()){
                return false;
            }
            return true;
		}

		/**
		*
		*/
		function PuntosAdmon()
		{
			list($dbconn) = GetDBconn();
			$query = " 	SELECT a.punto_admision_id, b.descripcion || ' [ ' || (select count(*) from triages
														where sw_estado='0' and punto_admision_id=a.punto_admision_id and punto_triage_id=".$_SESSION['TRIAGE']['PUNTO']['PTOTRIAGE'].") || ' ]' as descripcion
									FROM puntos_triage_admision as a, puntos_admisiones as b
									WHERE a.punto_triage_id=".$_SESSION['TRIAGE']['PUNTO']['PTOTRIAGE']."
									AND a.punto_admision_id=b.punto_admision_id";
			$result=$dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al eliminar en la Base de Datos";
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

		/**
		*
		*/
		function CantidadListado()
		{
				$empresa=$_SESSION['TRIAGE']['PUNTO']['EMPRESA'];
				$dpto=$_SESSION['TRIAGE']['PUNTO']['DPTO'];

				list($dbconn) = GetDBconn();
				$query = "select count(*) from triages
									where sw_estado='0' AND empresa_id='$empresa'
									and punto_triage_id=".$_SESSION['TRIAGE']['PUNTO']['PTOTRIAGE']."";
				$result=$dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al eliminar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				if(!$result->EOF)
				{
						$var=$result->fields[0];
				}
				return $var;
		}

		/**
		*
		*/
		function NombrePunto($pto)
		{
			list($dbconn) = GetDBconn();
			$query = " 	SELECT descripcion
									FROM puntos_admisiones
									WHERE punto_admision_id=$pto";
			$result=$dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al eliminar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			return $result->fields[0];
		}


    /**
    * Llama la forma del menu de admisiones
    * @access public
    * @return boolean
    */
    function Menus()
    {
            unset($_SESSION['SPY']);
            unset($_SESSION['CONT']);
            if(empty($_SESSION['TRIAGE']['EMPRESA']))
            {
                    if(empty($_SESSION['SEGURIDAD']['PTOADMISION']['URGENCIAS']['puntos'][$_REQUEST['Admon']['empresa_id']][$_REQUEST['Admon']['centro_utilidad']][$_REQUEST['Admon']['unidad_funcional']][$_REQUEST['Admon']['departamento']][$_REQUEST['Admon']['punto_admision_id']]))
                    {
                            $this->error = "Error de Seguridad.";
                            $this->mensajeDeError = "Violación a la Seguridad.";
                            return false;
                    }
												$_SESSION['TRIAGE']['EMPRESA']=$_REQUEST['Admon']['empresa_id'];
												$_SESSION['TRIAGE']['NOMEMPRESA']=$_REQUEST['Admon']['descripcion1'];
												$_SESSION['TRIAGE']['CENTROUTILIDAD']=$_REQUEST['Admon']['centro_utilidad'];
												$_SESSION['TRIAGE']['UNIDADFUNCIONAL']=$_REQUEST['Admon']['unidad_funcional'];
												$_SESSION['TRIAGE']['TIPOPTO']=$_REQUEST['Admon']['tipo_admision_id'];
												$_SESSION['TRIAGE']['PTOADMON']=$_REQUEST['Admon']['punto_admision_id'];
												$_SESSION['TRIAGE']['SWTRIAGE']=$_REQUEST['Admon']['sw_triage'];
												$_SESSION['TRIAGE']['DPTO']=$_REQUEST['Admon']['departamento'];
												$_SESSION['TRIAGE']['SWSOAT']=$_REQUEST['Admon']['sw_soat'];
												$_SESSION['TRIAGE']['SERVICIO']=$_REQUEST['Admon']['servicio'];
												$_SESSION['TRIAGE']['TIPO']='URGENCIAS';
                    //1 es medico - 2 es enfermera
                    IncludeLib('historia_clinica');
                    $_SESSION['TRIAGE']['FUNCIONARIO']=GetTipoProfesional(UserGetUID());
            }
      			if(!$this->FormaMenus()){
                return false;
            }
            return true;
    }


    /**
    * Valida los datos de la ventana inicial para buscar el paciente
    * @access public
    * @return boolean
    * @param string tipo de documento
    * @param int numero de documento
    * @param string plan
    */
    function ValidarDatosPrincipales($TipoDocumento,$Documento,$Plan)
    {
            if($TipoDocumento!='AS' && $TipoDocumento!='MS')
            {
                    if(!$Documento || !$TipoDocumento || $Plan==-1){
                            if(!$Documento){ $this->frmError["Documento"]=1; }
                            if(!$TipoDocumento){ $this->frmError["TipoDocumento"]=1; }
                            if($Plan==-1){ $this->frmError["Responsable"]=1; }
                            $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                            return false;
                    }
                    return true;
            }
            else
            {
                    if($Plan==-1){
                            if($Plan==-1){ $this->frmError["Responsable"]=1; }
                                $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                                return false;
                    }
                    return true;
            }
    }


    /**
    * Busca si el paciente tiene una cuenta abierta
    * @access public
    * @return boolean
    */
    function BuscarIngresoPaciente()
    {       unset($_SESSION['AUTOPACIENTE']['NoAutorizacion']);
						unset($_SESSION['TRIAGE']['PACIENTE']);
						$EmpresaId=$_SESSION['TRIAGE']['EMPRESA'];
						$CU=$_SESSION['TRIAGE']['CENTROUTILIDAD'];
						$Plan=$_REQUEST['Responsable'];
						$TipoDocumento=$_REQUEST['TipoDocumento'];
						$Documento=trim($_REQUEST['Documento']);
						$empresa=$_SESSION['TRIAGE']['EMPRESA'];
						$CU=$_SESSION['TRIAGE']['CENTROUTILIDAD'];
						$pto=$_SESSION['TRIAGE']['PTOADMON'];
						$dpto=$_SESSION['TRIAGE']['DPTO'];

						$_SESSION['PACIENTES']['PACIENTE']['modulo']='Triage';
						$_SESSION['PACIENTES']['PACIENTE']['tipo']='user';
						$_SESSION['PACIENTES']['PACIENTE']['contenedor']='app';
						$_SESSION['PACIENTES']['PACIENTE']['metodo']='Terminar';
						$_SESSION['PACIENTES']['PACIENTE']['plan_id']=$Plan;
						$_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$TipoDocumento;
						$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$Documento;
						$_SESSION['AUTOPACIENTE']['NoAutorizacion']=$_REQUEST['NoAutorizacion'];

            $validar=$this->ValidarDatosPrincipales($TipoDocumento,$Documento,$Plan);
            if($validar)
            {
									if(($TipoDocumento=='AS' OR $TipoDocumento=='MS') AND empty($Documento))
									{  $Documento=$this->CallMetodoExterno('app','Pacientes','user','IdentifiacionNN');  }

									$Paciente=$this->ReturnModuloExterno('app','Pacientes','user');
									if(!is_object($Paciente))
									{
													$this->error = "La clase Pacientes no se pudo instanciar";
													$this->mensajeDeError = "";
													return false;
									}
									$_SESSION['PACIENTES']['RETORNO']['argumentos']=array('TipoDocumento'=>$_REQUEST['TipoDocumento'],'Documento'=>$_REQUEST['Documento'],'Responsable'=>$_REQUEST['Responsable'],'HOMONIMO'=>true);
									$_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
									$_SESSION['PACIENTES']['RETORNO']['modulo']='Triage';
									$_SESSION['PACIENTES']['RETORNO']['tipo']='user';
									$_SESSION['PACIENTES']['RETORNO']['metodo']='BuscarIngresoPaciente';
									if(!$Paciente->BuscarIngresoActivoPaciente($TipoDocumento,$Documento,$EmpresaId,$Plan,$accion=array('contenedor'=>'app','modulo'=>'Triage','tipo'=>'user','metodo'=>'Buscar')))
									{
													$this->error = $Paciente->error ;
													$this->mensajeDeError = $Paciente->mensajeDeError;
													unset($Paciente);
													return false;
									}
									else
									{
											if(!$Paciente->TipoRetorno AND empty($_REQUEST['HOMONIMO']))
											{
																	$this->salida .= $Paciente->GetSalida();
																	unset($Paciente);
																	return true;
											}
											else
											{            unset($Paciente);
																	list($dbconn) = GetDBconn();
																	$query = "SELECT sw_tipo_plan
																											FROM planes
																											WHERE estado='1' and plan_id='".$Plan."'
																											and fecha_final >= now() and fecha_inicio <= now()";
																	$results = $dbconn->Execute($query);
																	$this->PedirDatosPaciente($TipoDocumento,$Documento,$Plan);
																	return true;
											}
									}
            }
            else
            {        unset($Paciente);
                    if(!$this->FormaBuscar($TipoDocumento,$Documento,$Plan)){
                            return false;
                    }
                    return true;
            }
    }


    /**
    * Aqui continua el proceso de admision cuando es el caso que el paciente no esta en la
    * tabla de pacientes y se debe referir al modulo de Pacientes. Este es el retorno de pacientes.
    * @access public  
    * @return boolean
    */
    function Terminar()
    {
                list($dbconn) = GetDBconn();
                $query = "SELECT sw_tipo_plan
                                    FROM planes
                                    WHERE estado='1' and plan_id='".$_SESSION['PACIENTES']['PACIENTE']['plan_id']."'
                                    and fecha_final >= now() and fecha_inicio <= now()";
                $results = $dbconn->Execute($query);
                if($results->fields[0]==1)
                {
                        $this->PedirDatosPaciente($_SESSION['PACIENTES']['PACIENTE']['paciente_id'],$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'],$_SESSION['PACIENTES']['PACIENTE']['plan_id']);
                        unset($Paciente);
                        return true;
                }
                else
                {
                        $this->AutorizarPaciente($_SESSION['PACIENTES']['PACIENTE']['paciente_id'],$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'],$_SESSION['PACIENTES']['PACIENTE']['plan_id']);
                        unset($Paciente);
                        return true;
                }

    }

    /**
  * Valida los derechos de un paciente segun su responsable
    * @access public
    * @return boolean
    * @param string tipo de documento
    * @param int numero de documento
    * @param int plan_id
    * @param string nivel del plan
    */
    function ValidarDerechos($TipoId,$PacienteId,$PlanId,$Nivel)
    {
            $_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
            $_SESSION['PACIENTES']['RETORNO']['modulo']='Triage';
            $_SESSION['PACIENTES']['RETORNO']['tipo']='user';
            $_SESSION['PACIENTES']['RETORNO']['metodo']='ValidarDerechos';

            if(!$TipoId && !$PacienteId)
            {
                    $TipoId=$_REQUEST['TipoId'];
                    $PacienteId=$_REQUEST['PacienteId'];
                    $PlanId=$_REQUEST['Responsable'];
                    $Nivel=$_REQUEST['Triage'];
            }

            list($dbconn) = GetDBconn();
            $query = "SELECT sw_afiliacion,sw_tipo_plan
											FROM planes
											WHERE plan_id='$PlanId'";
            $results = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            else
            {
                        $this->EstacionEnfermeria($TipoId,$PacienteId,$PlanId,$Nivel);
                        return true;
            }
    }


		/**
		*
		*/
		function AdmitirDirectamente()
		{
					$var=$_REQUEST['var'];
					list($dbconn) = GetDBconn();
					$query ="update triages set sw_estado='3' where triage_id=".$var[triage_id]."";
					$results = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}

					$_SESSION['TRIAGE']['PACIENTE']['paciente_id']=$var[paciente_id];
					$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente']=$var[tipo_id_paciente];
					$_SESSION['TRIAGE']['PACIENTE']['plan_id']=$var[plan_id];

					$this->AutorizarPaciente();
					return true;
		}

//-------------------------------AUTORIZACIONES---------------------------------------------
	/**
	*
	*/
	function AdmitirPaciente()
	{
			$_SESSION['TRIAGE']['PACIENTE']['paciente_id']=$_REQUEST['PacienteId'];
			$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente']=$_REQUEST['TipoId'];
			$_SESSION['TRIAGE']['PACIENTE']['plan_id']=$_REQUEST['Responsable'];
			$_SESSION['TRIAGE']['PACIENTE']['triage_id']=$_REQUEST['Triage'];
			$_SESSION['TRIAGE']['PACIENTE']['ADMITIR']=$_REQUEST['Nivel'];
			if($_SESSION['TRIAGE']['PACIENTE']['triage_id'])
				$_SESSION['ADMISIONES']['TIPO'] = 'URGENCIAS';
			$this->AutorizarPacienteListado();
			return true;
	}


    /**
    * Llama el modulo de autorizaciones
    * @access public
    * @return boolean
    * @param string tipo de documento
    * @param int numero de documento
    * @param int plan_id
    */
    function AutorizarPacienteListado()
    {
				unset($_SESSION['AUTORIZACIONES']);
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$_SESSION['TRIAGE']['PACIENTE']['paciente_id'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$_SESSION['TRIAGE']['PACIENTE']['plan_id'];

				if(empty($_SESSION['TRIAGE']['PROTOCOLO']))
				{
										list($dbconn) = GetDBconn();
										$query = "select protocolos from planes
																				where plan_id=".$_SESSION['TRIAGE']['PACIENTE']['plan_id']."";
										$result=$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0) {
														$this->error = "Error select protocolos";
														$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
														return false;
										}
										$_SESSION['TRIAGE']['PROTOCOLO']=$result->fields[0];
										$result->Close();
				}
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['EMPLEADOR']=true;
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']=$_SESSION['TRIAGE']['TIPO'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']=$_SESSION['TRIAGE']['SERVICIO'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_AUTORIZACION']='Admon';
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']=array();
				$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
				$_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='Triage';
				$_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';
				$_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='RetornoAutorizacionListado';
				$this->ReturnMetodoExterno('app','Autorizacion','user','SolicitudAutorizacion');
				return true;
    }

    /**
    * Llama el modulo de autorizaciones
    * @access public
    * @return boolean
    */
    function RetornoAutorizacionListado()
    {
						unset($_SESSION['ADMISIONES']);
						$_SESSION['ADMISIONES']['PACIENTE']['tipo_afiliado_id']=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id'];
						$_SESSION['ADMISIONES']['PACIENTE']['rango']=$_SESSION['AUTORIZACIONES']['RETORNO']['rango'];
						$_SESSION['ADMISIONES']['PACIENTE']['semanas']=$_SESSION['AUTORIZACIONES']['RETORNO']['semanas'];
						$_SESSION['ADMISIONES']['PACIENTE']['observacion_ingreso']=$_SESSION['AUTORIZACIONES']['RETORNO']['observacion_ingreso'];
						$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_SESSION['AUTORIZACIONES']['RETORNO']['paciente_id'];
						$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_id_paciente'];
						$_SESSION['ADMISIONES']['PACIENTE']['plan_id']=$_SESSION['AUTORIZACIONES']['RETORNO']['plan_id'];
						$_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];
						$_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACIONEXT']=$_SESSION['AUTORIZACIONES']['RETORNO']['ext'];

						$Mensaje=$_SESSION['AUTORIZACIONES']['RETORNO']['Mensaje'];
						$TipoServicio=$_SESSION['AUTORIZACIONES']['RETORNO']['TIPO_SERVICIO'];
						if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['ext'])){ $_SESSION['AUTORIZACIONES']['RETORNO']['ext']='NULL'; }
						if(empty($TipoServicio))
						{  $TipoServicio=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO'];  }
						$_SESSION['TRIAGE']['PACIENTE']['AUTORIZACION']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];
						//empleador
						if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']))
						{
								$_SESSION['ADMISIONES']['PACIENTE']['tipo_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['tipo_empleador'];
								$_SESSION['ADMISIONES']['PACIENTE']['id_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['id_empleador'];
						}

						if(!empty($_SESSION['AUTORIZACIONES']['NOAUTO']))
						{
									list($dbconn) = GetDBconn();
									$query = "update triages set sw_estado='3'
														where triage_id=".$_SESSION['TRIAGE']['PACIENTE']['triage_id']."";
									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
													$this->error = "Error al Guardar en triages";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													$dbconn->RollbackTrans();
													return false;
									}

									if(empty($Mensaje))
									{   $Mensaje = 'NO SE AUTORIZO LA ADMISION.';   }
									$accion=ModuloGetURL('app','Triage','user','ListarPacientesAdmisiones');
									if(!$this-> FormaMensaje($Mensaje,'AUTORIZACIONES',$accion,'')){
									return false;
									}
									return true;
						}

						unset($_SESSION['AUTORIZACIONES']);
						if(empty($_SESSION['TRIAGE']['PACIENTE']['AUTORIZACION']) && $TipoServicio=='URGENCIAS')
						{
								list($dbconn) = GetDBconn();
								if(empty($Mensaje))
								{   $Mensaje = 'No se pudo realizar la Autorización para la Admisión Urgencias.';   }
								$accion=ModuloGetURL('app','Triage','user','ListarPacientesAdmisiones');
								if(!$this-> FormaMensaje($Mensaje,'AUTORIZACIONES',$accion,'')){
								return false;
								}
								return true;
						}

						unset($_SESSION['PACIENTE']['INGRESO']);
            if(empty($_SESSION['TRIAGE']['PACIENTE']['INGRESO']))
            {
									if(empty($_SESSION['TRIAGE']['JAIME']))
									{
													$_SESSION['ADMISION']['RETORNO']['contenedor']='app';
													$_SESSION['ADMISION']['RETORNO']['modulo']='Triage';
													$_SESSION['ADMISION']['RETORNO']['tipo']='user';
													$_SESSION['ADMISION']['RETORNO']['metodo']='LlamarFormaPedirNivel';
									}

									if(!empty($_SESSION['TRIAGE']['PACIENTE']['ADMITIR']))
									{  $accion=ModuloGetURL('app','Triage','user','AdmisionSinClasificacion');  }

									//llamado al metodo de ingreso
									$_SESSION['ADMISIONES']['RETORNO']['contenedor']='app';
									$_SESSION['ADMISIONES']['RETORNO']['modulo']='Triage';
									$_SESSION['ADMISIONES']['RETORNO']['tipo']='user';
									$_SESSION['ADMISIONES']['RETORNO']['metodo']='RetornoIngreso';
									$_SESSION['ADMISIONES']['RETORNO']['argumentos']=array();

									$_SESSION['ADMISIONES']['SWSOAT']=$_SESSION['TRIAGE']['SWSOAT'];
									$_SESSION['ADMISIONES']['PACIENTE']['departamento']=$_SESSION['TRIAGE']['DPTO'];
									$_SESSION['ADMISIONES']['PACIENTE']['REMISION']=$_SESSION['TRIAGE']['PACIENTE']['REMISION'];
									$_SESSION['ADMISIONES']['PACIENTE']['triage_id']=$_SESSION['TRIAGE']['PACIENTE']['triage_id'];
									$_SESSION['ADMISIONES']['EMPRESA']=$_SESSION['TRIAGE']['EMPRESA'];
									$_SESSION['ADMISIONES']['CENTROUTILIDAD']=$_SESSION['TRIAGE']['CENTROUTILIDAD'];
									$_SESSION['ADMISIONES']['TIPO']=$_SESSION['TRIAGE']['TIPO'];
									$_SESSION['ADMISIONES']['PACIENTE']['punto_admision_id']=$_SESSION['TRIAGE']['PTOADMON'];
									if(empty($_SESSION['TRIAGE']['PACIENTE']['ADMITIR']))
									{
											//quiere decir q es insertar un ingreso
											$_SESSION['ADMISIONES']['INGRESO']['ingreso']=true;
									}
									elseif(!empty($_SESSION['TRIAGE']['PACIENTE']['ADMITIR']))
									{   $_SESSION['ADMISIONES']['INGRESO']['ingresotmp']=true;    }

									$this->ReturnMetodoExterno('app','Admisiones','user','LlamarFormaIngreso');
									return true;
									//fin cambio
            }
            else
            {
									$_SESSION['ADMISION']['RETORNO']['ingreso']=$_SESSION['TRIAGE']['PACIENTE']['INGRESO'];
									$this->TerminarIngreso();
									return true;
            }
    }


    /**
    * Llama el modulo de autorizaciones
    * @access public
    * @return boolean
    * @param string tipo de documento
    * @param int numero de documento
    * @param int plan_id
    */
    function AutorizarPaciente($TipoDocumento,$Documento,$Plan)
    {
								unset($_SESSION['AUTORIZACIONES']);
								if(!empty($_SESSION['TRIAGE']['PACIENTE']['paciente_id'])
									AND !empty($_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente']))
								{
										$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$_SESSION['TRIAGE']['PACIENTE']['paciente_id'];
										$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente'];
										$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$_SESSION['TRIAGE']['PACIENTE']['plan_id'];
								}
								else
								{
										$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$Documento;
										$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$TipoDocumento;
										$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$Plan;
								}

								if(empty($_SESSION['TRIAGE']['PROTOCOLO']))
                {
                            list($dbconn) = GetDBconn();
                            $query = "select protocolos from planes
                                                where plan_id=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."";
                            $result=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error select protocolos";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                            }
                            $_SESSION['TRIAGE']['PROTOCOLO']=$result->fields[0];
                            $result->Close();
                }
                $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']=$_SESSION['TRIAGE']['TIPO'];
                $_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']=$_SESSION['TRIAGE']['SERVICIO'];
                $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_AUTORIZACION']='Admon';
                $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']=array('NoAutorizacion'=>$_SESSION['AUTOPACIENTE']['NoAutorizacion']);
								$_SESSION['AUTORIZACIONES']['AUTORIZAR']['EMPLEADOR']=true;
                $_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
                $_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='Triage';
                $_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';
                $_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='RetornoAutorizacion';

                $this->ReturnMetodoExterno('app','Autorizacion','user','SolicitudAutorizacion');
                return true;
    }

    /**
    * Llama el modulo de autorizaciones
    * @access public
    * @return boolean
    */
    function RetornoAutorizacion()
    {
						unset($_SESSION['ADMISIONES']);
						$_SESSION['ADMISIONES']['PACIENTE']['tipo_afiliado_id']=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id'];
						$_SESSION['ADMISIONES']['PACIENTE']['rango']=$_SESSION['AUTORIZACIONES']['RETORNO']['rango'];
						$_SESSION['ADMISIONES']['PACIENTE']['semanas']=$_SESSION['AUTORIZACIONES']['RETORNO']['semanas'];
						$_SESSION['ADMISIONES']['PACIENTE']['observacion_ingreso']=$_SESSION['AUTORIZACIONES']['RETORNO']['observacion_ingreso'];
						$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_SESSION['AUTORIZACIONES']['RETORNO']['paciente_id'];
						$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_id_paciente'];
						$_SESSION['ADMISIONES']['PACIENTE']['plan_id']=$_SESSION['AUTORIZACIONES']['RETORNO']['plan_id'];
						$_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];
						$_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACIONEXT']=$_SESSION['AUTORIZACIONES']['RETORNO']['ext'];

						$Mensaje=$_SESSION['AUTORIZACIONES']['RETORNO']['Mensaje'];
						$TipoServicio=$_SESSION['AUTORIZACIONES']['RETORNO']['TIPO_SERVICIO'];
						if(empty($TipoServicio))
						{  $TipoServicio=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO'];  }
							if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['ext'])){  $_SESSION['AUTORIZACIONES']['RETORNO']['ext']='NULL'; }
						$_SESSION['TRIAGE']['PACIENTE']['AUTORIZACION']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];
						//empleador
						if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']))
						{
								$_SESSION['ADMISIONES']['PACIENTE']['tipo_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['tipo_empleador'];
								$_SESSION['ADMISIONES']['PACIENTE']['id_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['id_empleador'];
						}

						if(!empty($_SESSION['AUTORIZACIONES']['NOAUTO']))
						{
									if(empty($Mensaje))
									{   $Mensaje = 'NO SE AUTORIZO LA ADMISION.';   }
									$accion=ModuloGetURL('app','Triage','user','Buscar');
									if(!$this-> FormaMensaje($Mensaje,'AUTORIZACIONES',$accion,'')){
									return false;
									}
									return true;
						}

						unset($_SESSION['AUTORIZACIONES']);
						if(empty($_SESSION['TRIAGE']['PACIENTE']['AUTORIZACION']) && $TipoServicio=='URGENCIAS')
						{
												if(empty($Mensaje))
												{   $Mensaje = 'No se pudo realizar la Autorización para la Admisión Urgencias.';   }
												$accion=ModuloGetURL('app','Triage','user','Buscar');
												if(!$this-> FormaMensaje($Mensaje,'AUTORIZACIONES',$accion,'')){
												return false;
												}
												return true;
						}
						elseif(empty($_SESSION['TRIAGE']['PACIENTE']['AUTORIZACION']) && $TipoServicio=='HOSPITALIZACION')
						{
												if(empty($Mensaje))
												{   $Mensaje = 'No se pudo realizar la Autorización para la Admisión Hospitalización.';   }
												if($_SESSION['TRIAGE']['TIPOORDEN']=='Translado')
												{  $accion=ModuloGetURL('app','Triage','user','BuscarTranslado');   }
												elseif($_SESSION['TRIAGE']['TIPOORDEN']=='Externa')
												{  $accion=ModuloGetURL('app','Triage','user','Buscar',array('TIPOORDEN'=>'externa'));  }
												else
												{  $accion=ModuloGetURL('app','Triage','user','MetodoBuscarHospitalizacion');  }

												if(!$this-> FormaMensaje($Mensaje,'AUTORIZACIONES',$accion,'')){
												return false;
												}
												return true;
						}

						unset($_SESSION['PACIENTE']['INGRESO']);
            if(empty($_SESSION['TRIAGE']['PACIENTE']['INGRESO']))
            {
									if(empty($_SESSION['TRIAGE']['JAIME']))
									{
													$_SESSION['ADMISION']['RETORNO']['contenedor']='app';
													$_SESSION['ADMISION']['RETORNO']['modulo']='Triage';
													$_SESSION['ADMISION']['RETORNO']['tipo']='user';
													$_SESSION['ADMISION']['RETORNO']['metodo']='LlamarFormaPedirNivel';
									}

									if(!empty($_SESSION['TRIAGE']['PACIENTE']['ADMITIR']))
									{  $accion=ModuloGetURL('app','Triage','user','AdmisionSinClasificacion');  }

									//llamado al metodo de ingreso
									$_SESSION['ADMISIONES']['RETORNO']['contenedor']='app';
									$_SESSION['ADMISIONES']['RETORNO']['modulo']='Triage';
									$_SESSION['ADMISIONES']['RETORNO']['tipo']='user';
									$_SESSION['ADMISIONES']['RETORNO']['metodo']='RetornoIngreso';
									$_SESSION['ADMISIONES']['RETORNO']['argumentos']=array();

									$_SESSION['ADMISIONES']['SWSOAT']=$_SESSION['TRIAGE']['SWSOAT'];
									$_SESSION['ADMISIONES']['PACIENTE']['departamento']=$_SESSION['TRIAGE']['DPTO'];
									$_SESSION['ADMISIONES']['PACIENTE']['REMISION']=$_SESSION['TRIAGE']['PACIENTE']['REMISION'];
									$_SESSION['ADMISIONES']['PACIENTE']['triage_id']=$_SESSION['TRIAGE']['PACIENTE']['triage_id'];
									$_SESSION['ADMISIONES']['EMPRESA']=$_SESSION['TRIAGE']['EMPRESA'];
									$_SESSION['ADMISIONES']['CENTROUTILIDAD']=$_SESSION['TRIAGE']['CENTROUTILIDAD'];
									$_SESSION['ADMISIONES']['TIPO']=$_SESSION['TRIAGE']['TIPO'];
									$_SESSION['ADMISIONES']['PACIENTE']['punto_admision_id']=$_SESSION['TRIAGE']['PTOADMON'];
									if(empty($_SESSION['TRIAGE']['PACIENTE']['ADMITIR']))
									{
											//quiere decir q es insertar un ingreso
											$_SESSION['ADMISIONES']['INGRESO']['ingreso']=true;
									}
									elseif(!empty($_SESSION['TRIAGE']['PACIENTE']['ADMITIR']))
									{   $_SESSION['ADMISIONES']['INGRESO']['ingresotmp']=true;    }

									$this->ReturnMetodoExterno('app','Admisiones','user','LlamarFormaIngreso');
									return true;
									//fin cambio
            }
            else
            {
									$_SESSION['ADMISION']['RETORNO']['ingreso']=$_SESSION['TRIAGE']['PACIENTE']['INGRESO'];
									$this->TerminarIngreso();
									return true;
            }
    }

		function LlamarIngresoAdmisiones()
		{


		}

    /**
    * Llama la forma de pedir datos del modulo pacientes
    * @access public
    * @return boolean
    * @param string tipo de documento
    * @param int numero de documento
    * @param int plan_id
    * @param int nivel del plan
    */
    function PedirDatosPaciente($TipoId,$PacienteId,$PlanId)
    {
                $_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$PacienteId;
                $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$TipoId;
                $_SESSION['PACIENTES']['PACIENTE']['plan_id']=$PlanId;
                $_SESSION['PACIENTES']['RETORNO']['argumentos']=array();
                $_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
                $_SESSION['PACIENTES']['RETORNO']['modulo']='Triage';
                $_SESSION['PACIENTES']['RETORNO']['tipo']='user';
								if($_SESSION['TRIAGE']['TIPO']!='HOSPITALIZACION')
								{  $_SESSION['PACIENTES']['RETORNO']['metodo']='Nuevo';  }
								else
								{  $_SESSION['PACIENTES']['RETORNO']['metodo']='AutoHospitalizacion';  }
                $_SESSION['PACIENTES']['PACIENTE']['ARREGLO']=$_SESSION['TRIAGE']['AUTORIZACIONES']['ARREGLO'];

                $this->ReturnMetodoExterno('app','Pacientes','user','PedirDatos');
                return true;
    }

		/**
		*
		*/
		function AutoHospitalizacion()
		{
					$_SESSION['TRIAGE']['PACIENTE']['plan_id']=$_SESSION['PACIENTES']['PACIENTE']['plan_id'];
					$_SESSION['TRIAGE']['PACIENTE']['paciente_id']=$_SESSION['PACIENTES']['PACIENTE']['paciente_id'];
					$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente']=$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'];

				 	if(!$_SESSION['PACIENTES']['RETORNO']['PASO'])
					{
								$this->FormaBuscar();
								return true;
					}


				unset($_SESSION['PACIENTES']);
				$this->AutorizarPaciente();
				return true;
		}
    /**
    * Llama la forma de pedir datos del modulo pacientes
    * @access public
    * @return boolean
    * @param string tipo de documento
    * @param int numero de documento
    * @param int plan_id
    * @param int nivel del plan
    */
    function PedirDatosPacienteTriage($TipoId,$PacienteId,$PlanId)
    {
                $_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$PacienteId;
                $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$TipoId;
                $_SESSION['PACIENTES']['PACIENTE']['plan_id']=$PlanId;
                $_SESSION['PACIENTES']['RETORNO']['argumentos']=array();
                $_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
                $_SESSION['PACIENTES']['RETORNO']['modulo']='Triage';
                $_SESSION['PACIENTES']['RETORNO']['tipo']='user';
                $_SESSION['PACIENTES']['RETORNO']['metodo']='RetornoPacienteTriage';

                $this->ReturnMetodoExterno('app','Pacientes','user','PedirDatos');
                return true;
    }
//---------------------------------LO NUEVO-------------------------------------------


		/*
		*
		*/
		function CambiarPtoAdmon()
		{
					$var=$_REQUEST['var'];
					list($dbconn) = GetDBconn();
					$query ="update triages set punto_admision_id=".$_SESSION['TRIAGE']['PTOADMON']."
							where triage_id=".$var[triage_id]."";
					$results = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error update triages";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}

					$pto=$this->PuntosTriage();
					if(sizeof($pto)==1)
					{
									$query ="update triages set punto_triage_id=".$pto[0][punto_triage_id]."
														where triage_id=".$var[triage_id]."";
									$results = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error update triages";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
									}

									$nom=$this->NombrePtoTriage($pto[0][punto_triage_id]);
									$mensaje='El paciente pasa a clasificación de Triage al Punto '.$nom;
									$accion=ModuloGetURL('app','Triage','user','Buscar');
									if(!$this->FormaMensaje($mensaje,'PACIENTES CLASIFICACION TRIAGE',$accion,$boton)){
												return false;
									}
									return true;
					}
					else
					{
									$this->FormaCambiarPtoTriage($pto,$var[triage_id]);
									return true;
					}
		}

		/**
		*
		*/
		function ActualizarPtoTriage()
		{
				if($_REQUEST['Punto']==-1)
				{
						$this->frmError["Punto"]=1;
						$this->frmError["MensajeError"]='Debe Elegir el Punto de Admisión.';
						if(!$this->FormaCambiarPtoTriage($_REQUEST['var'],$_REQUEST['Triage'])){
							return false;
						}
						return true;
				}
				list($dbconn) = GetDBconn();
				$query ="update triages set punto_triage_id=".$_REQUEST['Punto']."
									where triage_id=".$_REQUEST['Triage']."";
				$results = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error update triages";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				$nom=$this->NombrePtoTriage($_REQUEST['Punto']);
				$mensaje='El paciente pasa a clasificación de Triage al Punto '.$nom;
				$accion=ModuloGetURL('app','Triage','user','Buscar');
				if(!$this->FormaMensaje($mensaje,'PACIENTES CLASIFICACION TRIAGE',$accion,$boton)){
							return false;
				}
				return true;
		}


		/**
		*
		*/
		function CambiarPtoTriage()
		{
				if($_REQUEST['Punto']==-1)
				{
						$this->frmError["Punto"]=1;
						$this->frmError["MensajeError"]='Debe Elegir el Punto de Admisión.';
						if(!$this->FormaCambiarPtoTriage($_REQUEST['msg'],$_REQUEST['Triage'])){
							return false;
						}
						return true;
				}
				list($dbconn) = GetDBconn();
				$query ="update triages set punto_triage_id=".$_REQUEST['Punto']."
									where triage_id=".$_REQUEST['Triage']."";
				$results = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error update triages";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				$nom=$this->NombrePtoTriage($_REQUEST['Punto']);
				$mensaje='El paciente pasa a clasificación de Triage al Punto '.$nom;
				$accion=ModuloGetURL('app','Triage','user','FormaBuscarTriage');
				if(!$this->FormaMensaje($mensaje,'PACIENTES CLASIFICACION TRIAGE',$accion,$boton)){
							return false;
				}
				return true;
		}


		//llamado jaime
		/**
		*
		*/
		function LlamarClasificacionMedico()
		{
				if(empty($_SESSION['TRIAGE']['ATENCION']['RETORNO']['contenedor']) AND empty($_SESSION['TRIAGE']['ATENCION']['RETORNO']['modulo'])
					AND empty($_SESSION['TRIAGE']['ATENCION']['RETORNO']['metodo']))
				{
								$this->error = "TRIAGE ";
								$this->mensajeDeError = "MALO JAIME.";
								return false;
				}

				unset($_SESSION['TRIAGE']['DIAGNOSTICO']);
				unset($_SESSION['TRIAGE']['CAUSAS']);
				$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente']=$_SESSION['TRIAGE']['ATENCION']['tipo_id_paciente'];
				$_SESSION['TRIAGE']['PACIENTE']['paciente_id']=$_SESSION['TRIAGE']['ATENCION']['paciente_id'];
				$_SESSION['TRIAGE']['PACIENTE']['plan_id']=$_SESSION['TRIAGE']['ATENCION']['plan_id'];
				$_SESSION['TRIAGE']['PACIENTE']['triage_id']=$_SESSION['TRIAGE']['ATENCION']['triage_id'];
				$_SESSION['TRIAGE']['ATENCION']['Atencion']=true;
				$_SESSION['TRIAGE']['PUNTO']['PTOTRIAGE']=$_SESSION['TRIAGE']['ATENCION']['punto_triage_id'];
				$_SESSION['TRIAGE']['PUNTO']['PTOADMON']=$_SESSION['TRIAGE']['ATENCION']['punto_admision_id'];
				$_SESSION['TRIAGE']['PUNTO']['NOATENDER']=$_SESSION['TRIAGE']['ATENCION']['sw_no_atender'];

				$this->FormaClasificacionTriage();
				return true;
		}



		/**
		*
		*/
		function AdmisionDirecta()
		{
					$var=$_REQUEST['var'];
					list($dbconn) = GetDBconn();
					$query = "INSERT UPDATE triages SET sw_estado='3'
										WHERE triage_id=".$var[triage_id]."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al UPDATE en triages";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}

		}

		/**
		*
		*/
		function BuscarNoAtender()
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT a.tipo_id_paciente, a.paciente_id, a.triage_id, a.hora_llegada,
									a.punto_triage_id, a.punto_admision_id, a.nivel_triage_asistencial, a.plan_id,
									b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' ' ||b.segundo_apellido as nombre,
									c.plan_descripcion, d.descripcion as descadmon, e.descripcion,
									f.estacion_id, g.descripcion as descenf
									FROM triages as a, pacientes as b, planes as c, puntos_admisiones as d, puntos_triage as e,
									triage_no_atencion as f, estaciones_enfermeria as g
									WHERE a.sw_no_atender=1 and a.nivel_triage_id=0 and a.tipo_id_paciente=b.tipo_id_paciente
									and a.paciente_id=b.paciente_id and a.plan_id=c.plan_id and a.punto_admision_id=d.punto_admision_id
									and a.punto_triage_id=e.punto_triage_id
									and f.estacion_id=g.estacion_id
									and a.sw_estado != '9'
									and a.triage_id=f.triage_id order by a.hora_llegada";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al eliminar en la Base de Datos";
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
		/**
		*
		*/
		function PuntosTriage()
		{
			list($dbconn) = GetDBconn();
			$query = " 	SELECT a.punto_triage_id, b.descripcion || ' [ ' || (select count(*) from triages where sw_estado='0' and punto_triage_id=a.punto_triage_id) || ' ]' as descripcion
									FROM puntos_triage_admision as a, puntos_triage as b
									WHERE a.punto_triage_id=b.punto_triage_id
									AND a.punto_admision_id=".$_SESSION['TRIAGE']['PTOADMON']."";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al eliminar en la Base de Datos";
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

		/**
		*
		*/
		function TodosPuntosTriage()
		{
			list($dbconn) = GetDBconn();
			$query = " 	SELECT b.punto_triage_id, b.descripcion || ' [ ' || (select count(*) from triages where sw_estado='0' and punto_triage_id=b.punto_triage_id) || ' ]' as descripcion
									FROM puntos_triage as b";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al eliminar en la Base de Datos";
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


		/**
		*
		**/
		function Nuevo()
		{
				if(empty($_SESSION['TRIAGE']['PACIENTE']['paciente_id'])
					AND empty($_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente']))
				{
						$_SESSION['TRIAGE']['PACIENTE']['plan_id']=$_SESSION['PACIENTES']['PACIENTE']['plan_id'];
						$_SESSION['TRIAGE']['PACIENTE']['paciente_id']=$_SESSION['PACIENTES']['PACIENTE']['paciente_id'];
						$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente']=$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'];
				}

				if(!empty($_SESSION['PACIENTES']))
				{
						if(empty($_SESSION['PACIENTES']['RETORNO']['PASO']))
						{
										$mensaje='No se termino en proceso de Admisiòn.';
										$accion=ModuloGetURL('app','Triage','user','Buscar');
										if(!$this->FormaMensaje($mensaje,'ADMISION',$accion,$boton)){
																return false;
										}
										return true;
						}
				}

				unset($_SESSION['PACIENTES']);

				if(!empty($_SESSION['TRIAGE']['SWTRIAGE']))
				{
							list($dbconn) = GetDBconn();
							$query = "SELECT a.sw_estado, a.tipo_id_paciente, a.paciente_id, a.triage_id, a.hora_llegada,
												a.punto_triage_id, a.punto_admision_id, a.nivel_triage_asistencial, a.plan_id,
												b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' ' ||b.segundo_apellido as nombre,
												c.plan_descripcion, d.descripcion as descadmon, e.descripcion
												FROM triages as a, pacientes as b, planes as c, puntos_admisiones as d, puntos_triage as e
												WHERE  a.tipo_id_paciente='".$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente']."'
												and a.paciente_id='".$_SESSION['TRIAGE']['PACIENTE']['paciente_id']."'
												and a.sw_no_atender=0 and a.sw_estado in(0,1,4)
												and a.tipo_id_paciente=b.tipo_id_paciente
												and a.paciente_id=b.paciente_id and a.plan_id=c.plan_id and a.punto_admision_id=d.punto_admision_id
												and a.punto_triage_id=e.punto_triage_id";
							$resulta=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Base de Datos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}
							if(!$resulta->EOF)
							{
									$var=$resulta->GetRowAssoc($ToUpper = false);
									//el paciente no hay sido admitido pero fue clasificado
									if($var[sw_estado]==1 OR $var[sw_estado]==4)
									{
											$mensaje='El paciente '.$var[tipo_id_paciente].' '.$var[paciente_id].' '.$var[nombre].', ya se encuentra Clasificado en el Triage del Punto '.$var[descripcion].', Esta en espera de
												ser Admitido en el Punto '.$var[descadmon];
											$accion=ModuloGetURL('app','Triage','user','Buscar');
											$this->FormaMensaje($mensaje,'PACIENTES TRIAGE',$accion,$boton);
											return true;
									}
									else
									{	//el paciente no ha sido clasificado
											$this->FormaYaTriage($var);
											return true;
									}
							}
							else
							{
									$this->FormaNuevo();
									return true;
							}
				}
				else
				{
				//cuando no se hace triage
				//$this->AutorizarPaciente();
				$this->FormaNuevo();
				return true;
				}
		}

		/**
		*
		*/
		function ContadorListado()
		{
					list($dbconn) = GetDBconn();
					$query = " select count(triage_id)
											from ((SELECT a.paciente_id, a.tipo_id_paciente, a.departamento,
											a.empresa_id FROM triages as a WHERE a.nivel_triage_id!=0
											AND sw_estado='0')
											except (SELECT a.paciente_id, a.tipo_id_paciente, b.departamento, b.empresa_id
											FROM ingresos as a, departamentos as b where a.estado=1
											and a.departamento=b.departamento)) as a,
											triages as b
											where a.paciente_id=b.paciente_id and a.tipo_id_paciente=b.tipo_id_paciente
											and a.empresa_id='".$_SESSION['TRIAGE']['EMPRESA']."'
											and a.departamento='".$_SESSION['TRIAGE']['DPTO']."'
											and b.punto_admision_id='".$_SESSION['TRIAGE']['PTOADMON']."'";
					$result=$dbconn->Execute($query);

					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al eliminar en la Base de Datos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					return $result->fields[0];
		}


		/**
		*
		*/
		function BuscarPacienteTriage()
		{
					$validar=$this->ValidarDatosPrincipales($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Responsable']);
					
          if($validar)
					{
								if($_REQUEST['TipoDocumento']=='AS' || $_REQUEST['TipoDocumento']=='MS')
								{  $_REQUEST['Documento']=$this->CallMetodoExterno('app','Pacientes','user','IdentifiacionNN');  }

								$Paciente=$this->ReturnModuloExterno('app','Pacientes','user');
								if(!is_object($Paciente))
								{
												$this->error = "La clase Pacientes no se pudo instanciar";
												$this->mensajeDeError = "";
												return false;
								}
								$_SESSION['PACIENTES']['RETORNO']['argumentos']=array('TipoDocumento'=>$_REQUEST['TipoDocumento'],'Documento'=>$_REQUEST['Documento'],'Responsable'=>$_REQUEST['Responsable'],'HOMONIMO'=>true);
								$_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
								$_SESSION['PACIENTES']['RETORNO']['modulo']='Triage';
								$_SESSION['PACIENTES']['RETORNO']['tipo']='user';
								$_SESSION['PACIENTES']['RETORNO']['metodo']='BuscarPacienteTriage';
								if(!$Paciente->BuscarIngresoActivoPaciente($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_SESSION['TRIAGE']['PUNTO']['EMPRESA'],$_REQUEST['Responsable'],$accion=array('contenedor'=>'app','modulo'=>'Triage','tipo'=>'user','metodo'=>'BuscarPacienteTriage')))
								{
												$this->error = $Paciente->error ;
												$this->mensajeDeError = $Paciente->mensajeDeError;
												unset($Paciente);
												return false;
								}
								else
								{
												if(!$Paciente->TipoRetorno AND empty($_REQUEST['HOMONIMO']))
												{
																		$this->salida .= $Paciente->GetSalida();
																		unset($Paciente);
																		return true;
												}
												else
												{         	unset($Paciente);
																		$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente']=$_REQUEST['TipoDocumento'];
																		$_SESSION['TRIAGE']['PACIENTE']['paciente_id']=$_REQUEST['Documento'];
																		$_SESSION['TRIAGE']['PACIENTE']['plan_id']=$_REQUEST['Responsable'];
																		list($dbconn) = GetDBconn();
																		$query = "SELECT a.triage_id,a.punto_triage_id,a.tipo_id_paciente,a.paciente_id, b.descripcion, c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre,
																							d.descripcion
																							FROM triages as a
																							LEFT JOIN puntos_admisiones as b on(a.punto_admision_id=b.punto_admision_id)
																							LEFT JOIN puntos_triage as d on(a.punto_triage_id=d.punto_triage_id), pacientes as c
																							WHERE a.empresa_id='".$_SESSION['TRIAGE']['PUNTO']['EMPRESA']."'
																							AND a.sw_estado='0'
																							AND a.tipo_id_paciente='".$_REQUEST['TipoDocumento']."'
																							AND a.paciente_id='".$_REQUEST['Documento']."'
																							AND a.tipo_id_paciente=c.tipo_id_paciente and a.paciente_id=c.paciente_id
																							ORDER BY a.hora_llegada";
																		$result=$dbconn->Execute($query);
																		if ($dbconn->ErrorNo() != 0) {
																				$this->error = "Error";
																				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																				return false;
																		}
																		if(!$result->EOF)
																		{
																				$mensaje='El Paciente '.$_REQUEST['TipoDocumento'].' '.$_REQUEST['Documento'] .' '.$result->fields[5].' se encuentra en el Triage del Punto '.$result->fields[4].', Triage '.$result->fields[6];
																				$this->FormaPtoTriagePaciente($mensaje,$result->fields[0],$result->fields[1]);
																				return true;
																		}
																		else
																		{
																					$query = "SELECT c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre
																										FROM triages as a, pacientes as c
																										WHERE a.empresa_id='".$_SESSION['TRIAGE']['PUNTO']['EMPRESA']."'
																										AND a.sw_estado='5'
																										AND a.tipo_id_paciente='".$_REQUEST['TipoDocumento']."'
																										AND a.paciente_id='".$_REQUEST['Documento']."'
																										AND a.tipo_id_paciente=c.tipo_id_paciente and a.paciente_id=c.paciente_id";
																					$result=$dbconn->Execute($query);
																					if ($dbconn->ErrorNo() != 0) {
																							$this->error = "Error sw_estado='5'";
																							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																							return false;
																					}
																					if(!$result->EOF)
																					{
																								//esta en pendientes por admitir en el medico
																								$mensaje='El Paciente '.$_REQUEST['TipoDocumento'].' '.$_REQUEST['Documento'] .' '.$result->fields[0].' se encuentra pendiente de ser atendido.';
																								$accion=ModuloGetURL('app','Triage','user','FormaBuscarTriage');
																								$this->FormaMensaje($mensaje,'PACIENTES TRIAGE',$accion,$boton);
																								return true;
																					}
																					else
																					{
																						//no esta en ningun triage de la empresa
																						$this->PedirDatosPacienteTriage($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Responsable']);
																						return true;
																				}
																		}
												}
								}
					}
					else
					{
								if(!$this->FormaBuscarTriage($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Responsable'])){
										return false;
								}
								return true;
					}
		}

		/**
		*
		*/
		function ElegirPuntoAdmision($tipo,$documento,$plan)
		{
					list($dbconn) = GetDBconn();
					$query = "SELECT a.punto_admision_id, b.descripcion
										FROM puntos_triage_admision as a, puntos_admisiones as b
										WHERE a.punto_triage_id=".$_SESSION['TRIAGE']['PUNTO']['PTOTRIAGE']."
										and a.punto_admision_id=b.punto_admision_id";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en triages";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					while(!$result->EOF)
					{
						$var[]= $result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
					}
					$result->Close();

					if(sizeof($var)>1)
					{
							$this->FormaPuntos($var);
							return true;
					}
					else
					{			//solo tiene un punto
								$_SESSION['TRIAGE']['PUNTO']['PTOADMON']=$var[0][punto_admision_id];
								$this->PedirDatosPacienteTriage($tipo,$documento,$plan);
								return true;
					}
		}


		/**
		*
		*/
		function InsertarTriage()
		{
					list($dbconn) = GetDBconn();
					$query = "INSERT UPDATE triages SET hora_llegada='now()'
										WHERE triage_id=".$_REQUEST['Triage']."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en triages";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					else
					{
							$mensaje='El paciente pasa a clasificación de Triage';
							$accion=ModuloGetURL('app','Triage','user','Buscar');
							if(!$this->FormaMensaje($mensaje,'PACIENTES CLASIFICACION TRIAGE',$accion,$boton)){
													return false;
							}
							return true;
					}
		}

		/**
		*
		*/
		function RetornoPacienteTriage()
		{
					//si se cancelo en proceso de tomar datos del paciente
					if(empty($_SESSION['PACIENTES']['RETORNO']['PASO']))
					{
							unset($_SESSION['PACIENTES']);
							$this->FormaBuscarTriage();
							return true;
					}
					else
					{
								$_SESSION['TRIAGE']['PACIENTE']['paciente_id']=$_SESSION['PACIENTES']['PACIENTE']['paciente_id'];
								$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente']=$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'];
								$_SESSION['TRIAGE']['PACIENTE']['plan_id']=$_SESSION['PACIENTES']['PACIENTE']['plan_id'];
								unset($_SESSION['PACIENTES']);
								//se llenan los datos del paciente  y no esta en triage
								$_SESSION['TRIAGE']['PACIENTE']['triage_id']='';
								$_SESSION['TRIAGE']['PACIENTE']['SIGNOS']=TRUE;
								//$this->FormaSignosVitalesTriage('','','','','','','','');
								$this->FormaNuevo();
								return true;
					}
		}

		/**
		*
		*/
		function LlamarFormaClasificacionTriage()
		{
				if($_REQUEST['Punto']==-1)
				{
						$this->frmError["Punto"]=1;
						$this->frmError["MensajeError"]='Debe Elegir el Punto de Admisión.';
						if(!$this->FormaPuntos($_REQUEST['var'])){
							return false;
						}
						return true;
				}

				$_SESSION['TRIAGE']['PUNTO']['PTOADMON']=$_REQUEST['Punto'];
				$this->FormaClasificacionTriage();
				return true;
		}


  /**
	 * Llama la forma ConfirmarAccion (forma de mensaje de dos botones).
   * @ access public
	 * @ return boolean
	 */
	function ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2)
	{
			if(empty($Titulo))
			{
				$arreglo=$_REQUEST['arreglo'];
				$Cuenta=$_REQUEST['Cuenta'];
				$c=$_REQUEST['c'];
				$m=$_REQUEST['m'];
				$me=$_REQUEST['me'];
				$me2=$_REQUEST['me2'];
				$mensaje=$_REQUEST['mensaje'];
				$Titulo=$_REQUEST['titulo'];
				$boton1=$_REQUEST['boton1'];
				$boton2=$_REQUEST['boton2'];
			}

				$this->salida=ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,array($c,$m,'user',$me,$arreglo),array($c,$m,'user',$me2,$arreglo));
				return true;
	}


//----------------------------------------------------------------------------------------
    /**
    * Valida si el punto de admision tiene una o varios estaciones de enfermeria asociadas
    * @access public
    * @return boolean
    * @param string tipo de documento
    * @param int numero de documento
    * @param int plan_id
    * @param int nivel del plan
    */
    function EstacionEnfermeria($TipoId,$PacienteId,$PlanId,$Nivel)
    {
					$_SESSION['TRIAGE']['PACIENTE']['paciente_id']=$PacienteId;
					$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente']=$TipoId;
					$_SESSION['TRIAGE']['PACIENTE']['plan_id']=$PlanId;

					$Estaciones=$this->BuscarEstaciones();
					if(sizeof($Estaciones)==1)
					{
									$EstId=$Estaciones[0][estacion_id];
									$DesEst=$Estaciones[0][descripcion];
									$Dpto=$Estaciones[0][departamento];
									$_SESSION['TRIAGE']['PACIENTE']['estacion_id']=$EstId;
									$_SESSION['TRIAGE']['PACIENTE']['departamento']=$Dpto;

									$this->LlamarIngreso();
									return true;
					}
					else
					{
									$this->FormaElegirEstacion();
									return true;
					}
    }

    /**
    * Busca las estaciones de enfermeria
    * @access public
    * @return array
    */
    function BuscarEstaciones()
    {
            $PtoAdmon=$_SESSION['TRIAGE']['PTOADMON'];
            list($dbconn) = GetDBconn();
            if($_SESSION['TRIAGE']['TIPO']=='HOSPITALIZACION' && $_SESSION['TRIAGE']['TIPOORDEN']!='Externa')
            {
                        $query = "SELECT b.estacion_id, b.descripcion, b.departamento
                                            FROM estaciones_enfermeria as b
                                            WHERE  b.departamento='".$_SESSION['TRIAGE']['DPTOEST']."'";
            }
            else
            {
                        $query = "SELECT a.estacion_id, b.descripcion, b.departamento
                                            FROM puntos_admisiones_estaciones as a, estaciones_enfermeria as b
                                            WHERE a.punto_admision_id='$PtoAdmon' AND a.estacion_id=b.estacion_id";
            }
            $result = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $i=0;
            while(!$result->EOF)
            {
                    $var[$i]=$result->GetRowAssoc($ToUpper = false);
                    $i++;
                    $result->MoveNext();
            }
            return $var;
    }


		/**
		*
		*/
    function BuscarTodasEstaciones()
    {
				list($dbconn) = GetDBconn();
				$query = "SELECT distinct a.estacion_id, b.descripcion, b.departamento
									FROM puntos_admisiones_estaciones as a,
									estaciones_enfermeria as b, puntos_admisiones as c
									WHERE c.tipo_admision_id='UR' AND c.punto_admision_id=a.punto_admision_id
									AND a.estacion_id=b.estacion_id";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				while(!$result->EOF)
				{
								$var[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
				}
				$result->Close();
				return $var;
    }

    /**
    * Llama el metodo CambiarIdentificacionPaciente del modulo pacientes para cambiar la identificacion
    * @access public
    * @return boolean
    */
     function CambioIdentificacion()
     {
                $_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$_REQUEST['PacienteId'];
                $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['TipoId'];
                $_SESSION['PACIENTES']['RETORNO']['argumentos']=array();
                $_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
                $_SESSION['PACIENTES']['RETORNO']['modulo']='Triage';
                $_SESSION['PACIENTES']['RETORNO']['tipo']='user';
                $_SESSION['PACIENTES']['RETORNO']['metodo']='MetodoBuscar';

                $this->ReturnMetodoExterno('app','Pacientes','user','CambiarIdentificacionPaciente');
                return true;
     }

    /**
    * Llama el modulo de pacientes para la unificacion de historias.
    * @access public
    * @return boolean
    */
     function UnificarHistorias()
     {
                $_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$_REQUEST['PacienteId'];
                $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['TipoId'];
                $_SESSION['PACIENTES']['RETORNO']['argumentos']=array();
                $_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
                $_SESSION['PACIENTES']['RETORNO']['modulo']='Triage';
                $_SESSION['PACIENTES']['RETORNO']['tipo']='user';
                $_SESSION['PACIENTES']['RETORNO']['metodo']='MetodoBuscar';

                $this->ReturnMetodoExterno('app','Pacientes','user','UnificarHistorias');
                return true;
     }


//----------------------------FIN AUTORIZACIONES--------------------------------------

    /**
    * Llama la forma FormaModificarAdmision
    * @access public
    * @return boolean
    */
     function MetodoModificarAdmision()
     {
                 if(!$_SESSION['PACIENTES']['PACIENTE']['ingreso'])
                {
                        $TipoId=$_REQUEST['TipoId'];
                        $PacienteId=$_REQUEST['PacienteId'];
                        $Nivel=$_REQUEST['Nivel'];
                        $PlanId=$_REQUEST['PlanId'];
                        $Ingreso=$_REQUEST['Ingreso'];
                        $_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$PacienteId;
                        $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$TipoId;
                        $_SESSION['PACIENTES']['PACIENTE']['plan_id']=$PlanId;
                        $_SESSION['PACIENTES']['PACIENTE']['nivel']=$Nivel;
                        $_SESSION['PACIENTES']['PACIENTE']['ingreso']=$Ingreso;
                        $_SESSION['PACIENTES']['RETORNO']['argumentos']=array();
                        $_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
                        $_SESSION['PACIENTES']['RETORNO']['modulo']='Triage';
                        $_SESSION['PACIENTES']['RETORNO']['tipo']='user';
                        $_SESSION['PACIENTES']['RETORNO']['metodo']='MetodoModificarAdmision';
                }
                else
                {
                        $PacienteId=$_SESSION['PACIENTES']['PACIENTE']['paciente_id'];
                        $TipoId=$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'];
                        $PlanId=$_SESSION['PACIENTES']['PACIENTE']['plan_id'];
                        $Nivel=$_SESSION['PACIENTES']['PACIENTE']['nivel'];
                        $Ingreso=$_SESSION['PACIENTES']['PACIENTE']['ingreso'];
                }
                $this->FormaModificarAdmision($TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso);
                return true;
     }

		/**
		*
		*/
		function LlamarDatosAdmisionPaciente()
		{
					$pto=$this->PuntosTriage();
					if(sizeof($pto)==1)
					{
									$_SESSION['TRIAGE']['PACIENTE']['PTOTRIAGE']=$pto[0][punto_triage_id];
									$this->DatosAdmisionPaciente();
									return true;
					}
					else
					{
									$this->FormaPuntosTriage($pto);
									return true;
					}
		}

		/**
		*
		*/
		function DefinirTriage()
		{
				if($_REQUEST['Punto']==-1)
				{
						$this->frmError["Punto"]=1;
						$this->frmError["MensajeError"]='Debe Elegir el Punto de Triage.';
						if(!$this->FormaPuntosTriage($_REQUEST['var'])){
							return false;
						}
						return true;
				}

				$_SESSION['TRIAGE']['PACIENTE']['PTOTRIAGE']=$_REQUEST['Punto'];
				$this->DatosAdmisionPaciente();
				return true;
		}


		/**
		*
		*/
		function NombrePtoTriage($pto)
		{
				list($dbconn) = GetDBconn();
				$query = " SELECT descripcion FROM puntos_triage
										WHERE punto_triage_id='$pto'";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$resulta->Close();
				return $resulta->fields[0];
		}


	/**
	* Llama la funcion validarderechos y si el triage esta activado inserta en triages
	* @access public
	* @return boolean
	*/
    function DatosAdmisionPaciente()
    {
		           if(!empty($_SESSION['TRIAGE']['JAIME']))
                {
												$_SESSION['TRIAGE']['PACIENTE']['tipo_afiliado_id']=$_SESSION['TRIAGE']['JAIME']['tipo_afiliado_id'];
												$_SESSION['TRIAGE']['PACIENTE']['rango']=$_SESSION['TRIAGE']['JAIME']['rango'];
												$_SESSION['TRIAGE']['PACIENTE']['AUTORIZACION']=$_SESSION['TRIAGE']['JAIME']['AUTORIZACION'];
												$_SESSION['TRIAGE']['PACIENTE']['plan_id']=$_SESSION['PACIENTES']['PACIENTE']['plan_id'];
												$_SESSION['TRIAGE']['PACIENTE']['paciente_id']=$_SESSION['PACIENTES']['PACIENTE']['paciente_id'];
												$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente']=$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'];
												unset($_SESSION['PACIENTES']);
                }

                $PacienteId=$_SESSION['TRIAGE']['PACIENTE']['paciente_id'];
                $TipoId=$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente'];
                $Responsable=$_SESSION['TRIAGE']['PACIENTE']['plan_id'];
                $empresa=$_SESSION['TRIAGE']['EMPRESA'];
                $cu=$_SESSION['TRIAGE']['CENTROUTILIDAD'];
                $pto=$_SESSION['TRIAGE']['PTOADMON'];
                $dpto=$_SESSION['TRIAGE']['DPTO'];
								$SystemId=UserGetUID();
								$FechaRegistro=date("Y-m-d H:i:s");

              list($dbconn) = GetDBconn();
								$dbconn->BeginTrans();
                //triage activo
                if($_SESSION['TRIAGE']['SWTRIAGE'])
								{
										$query=" SELECT nextval('triages_triage_id_seq')";
										$result=$dbconn->Execute($query);
										$triage=$result->fields[0];

										$query = "INSERT INTO triages (			triage_id,
																												hora_llegada,
																												tipo_id_paciente,
																												paciente_id,
																												plan_id,
																												nivel_triage_id,
																												observacion_medico,
																												observacion_enfermera,
																												motivo_consulta,
																												usuario_id,
																												empresa_id,
																												centro_utilidad,
																												punto_admision_id,
																												departamento,
																												autorizacion_int,
																												autorizacion_ext,
																												tipo_afiliado_id,
																												rango,
																												semanas_cotizadas,
																												sw_estado,
																												punto_triage_id
                                                       )
												VALUES ($triage,'$FechaRegistro','$TipoId','$PacienteId','$Responsable','0','$ObservacionM','$ObservacionE','$MotivoConsulta',$SystemId,'$empresa','$cu',$pto,'$dpto',NULL,NULL,NULL,NULL,0,'0',".$_SESSION['TRIAGE']['PACIENTE']['PTOTRIAGE'].")";
											$dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0) {
															$this->error = "Error al Guardar en triages";
															$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
															$dbconn->RollbackTrans();
															return false;
											}
											else
											{
														if(!empty($_SESSION['TRIAGE']['PACIENTE']['REMISION']))
														{
																$query = "UPDATE pacientes_remitidos SET triage_id=$triage
																					WHERE paciente_remitido_id=".$_SESSION['TRIAGE']['PACIENTE']['REMISION']."";
																$dbconn->Execute($query);
																if ($dbconn->ErrorNo() != 0) {
																				$this->error = "Error al Guardar en triages";
																				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																				$dbconn->RollbackTrans();
																				return false;
																}
														}

														$dbconn->CommitTrans();
														$nom=$this->NombrePtoTriage($_SESSION['TRIAGE']['PACIENTE']['PTOTRIAGE']);
														$mensaje='El paciente pasa a clasificación de Triage al Punto '.$nom;
														$accion=ModuloGetURL('app','Triage','user','Buscar');
														if(!$this->FormaMensaje($mensaje,'PACIENTES CLASIFICACION TRIAGE',$accion,$boton)){
																				return false;
														}
														return true;
											}
                }
                else
								{//triage desactivado
													if(!$this->ValidarDerechos($TipoId,$PacienteId,$Responsable,$Nivel)){
																			return false;
													}
													return true;
                }
    }

    /**
    * Llama la forma que muestra el listado de los pacientes por clasificar
    * @access public
    * @return boolean
    */
    function LlamaListadoTriage()
        {
                if(!$this->ListarPacientes()){
                    return false;
                }
            return true;
    }


    /**
    * Busca los datos del tercero_soat.
    * @access public
    * @return array
    */
    function TercerosSoat()
    {
					list($dbconn) = GetDBconn();
					$query="SELECT a.tercero_id, a.tipo_id_tercero, b.nombre_tercero
													FROM terceros_soat as a, terceros as b
													where a.tercero_id=b.tercero_id AND a.tipo_id_tercero=b.tipo_id_tercero";
					$result=$dbconn->Execute($query);

					while(!$result->EOF)
					{
									$var[]=$result->GetRowAssoc($ToUpper = false);
									$result->MoveNext();
					}
      		return $var;
    }


    /**
    * Llama la FormaBuscar que busca los pacientes
    * @access public
    * @return boolean
    */
    function Buscar()
    {
            unset($_SESSION['TRIAGE']['AUTORIZACIONES']['ARREGLO']);
            unset($_SESSION['TRIAGE']['PACIENTE']);
            $TipoId=$_REQUEST['TipoId'];
            $PacienteId=$_REQUEST['PacienteId'];
            $Plan=$_REQUEST['PlanId'];
            $_SESSION['TRIAGE']['TIPOORDEN']=$_REQUEST['TIPOORDEN'];

            if(!$this->FormaBuscar($TipoId,$PacienteId,$Plan)){
                    return false;
            }
            return true;
    }

    /**
    * Busca los niveles del plan del responsable del paciente
    * @access public
    * @return array
    * @param string plan_id
    */
     function Niveles()
     {
					list($dbconn) = GetDBconn();
					$query="SELECT DISTINCT rango
													FROM planes_rangos
													WHERE plan_id='".$_SESSION['TRIAGE']['PACIENTE']['plan_id']."'";
					$result=$dbconn->Execute($query);

					while(!$result->EOF){
							$niveles[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					return $niveles;
     }

     /**
        * Busca en el triage el plan
        * @access public
        * @return array
        * @param string tipo de documento
        * @param int numero de documento
     */
     function BuscarResponsableTriage($tipo,$documento)
     {
                $emp=$_SESSION['TRIAGE']['EMPRESA'];
                $cu=$_SESSION['TRIAGE']['CENTROUTILIDAD'];
                $dpto=$_SESSION['TRIAGE']['DPTO'];

                list($dbconn) = GetDBconn();
                $query = "SELECT plan_id FROM triages
                                    WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'
                                    AND empresa_id='$emp' AND centro_utilidad='$cu' AND departamento='$dpto'
																		AND sw_estado='0'";
                $result = $dbconn->Execute($query);
                return $result->fields[0];
     }

    /**
    * Llama la forma FormaMetodoBuscar
    * @access public
    * @return boolean
    */
     function MetodoBuscar()
     {
                 unset($_SESSION['PACIENTES']);
                $Busqueda=$_REQUEST['TipoBusqueda'];
                $this->FormaMetodoBuscar($Busqueda,$mensaje,$D,$arr,$f);
                return true;
     }

     /**
     *
     */
     function BuscarProtocolo($Plan)
     {
                list($dbconn) = GetDBconn();
                $query = "SELECT protocolos FROM planes WHERE plan_id='$Plan'";
                $results = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }

                $var=$results->GetRowAssoc($ToUpper = false);
                return $var;
     }

    /**
    * Busca el tercero_id y el plan_descripcion de la table planes.
    * @access public
    * @return array
    * @param string id del plan
    * @param int ingreso
    */
   function BuscarPlanes($PlanId,$Ingreso)
     {
                list($dbconn) = GetDBconn();
                $query = "SELECT sw_tipo_plan FROM planes WHERE plan_id='$PlanId'";
                $results = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                $sw=$results->fields[0];
                //soat
                if($sw==1)
                {
                     $query = "SELECT  b.nombre_tercero, c.plan_descripcion, e.tipo_id_tercero, e.tercero_id, c.protocolos
                                                FROM ingresos_soat as a, terceros as b, planes as c,
                                                soat_eventos as d, soat_polizas as e
                                                WHERE a.ingreso=$Ingreso AND a.evento=d.evento AND e.tipo_id_tercero=b.tipo_id_tercero
                                                AND e.tercero_id =b.tercero_id AND c.plan_id='$PlanId' AND d.poliza=e.poliza";

                }
                //cliente
                if($sw==0)
                {
                   	 $query = "SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, b.nombre_tercero, a.protocolos
                                                FROM planes as a, terceros as b
                                                WHERE a.plan_id='$PlanId' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
                }
                //particular
                if($sw==2)
                {
                        $query = "select b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre_tercero,
																	c.plan_descripcion, b.tipo_id_paciente as tipo_id_tercero, b.paciente_id as tercero_id, c.protocolos
																	from ingresos as a, pacientes as b, planes as c
																	where a.ingreso='$Ingreso' and a.paciente_id=b.paciente_id and a.tipo_id_paciente=b.tipo_id_paciente
																	and c.plan_id='$PlanId'";
                }
                //capitado
                if($sw==3)
                {
                     $query = "SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, b.nombre_tercero, a.protocolos
																FROM planes as a, terceros as b
																WHERE a.plan_id='$PlanId' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
                }
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                $var=$result->GetRowAssoc($ToUpper = false);
                $result->Close();
                return $var;
     }


    /**
    * Busca la descripcion de tipo de sexo especifico
    * @access public
    * @return string
    * @param string sexo_id
    */
  function NombreSexoPac($Sexo)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT descripcion FROM tipo_sexo WHERE sexo_id='$Sexo'";
            $result = $dbconn->Execute($query);
    return $result->fields[0];
    }

    /**
    * Busca la descripcion de tipo de sexo especifico
    * @access public
    * @return string
    * @param string id del estado del civil
    */
  function NombreEstadoCivil($Estado)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT descripcion FROM tipo_estado_civil WHERE tipo_estado_civil_id='$Estado'";
            $result = $dbconn->Execute($query);
    return $result->fields[0];
    }

    /**
    * Busca el nombre del responsable
    * @access public
    * @return string
    * @param string plan_id
    */
  function Responsable($Responsable)
    {
            list($dbconn) = GetDBconn();
            $query = " SELECT b.nombre_tercero, a.plan_descripcion
                                            FROM planes as a, terceros as b
                                            WHERE a.plan_id='$Responsable' AND a.tercero_id=b.tercero_id
                                            AND a.tipo_tercero_id=b.tipo_id_tercero";
            $result = $dbconn->Execute($query);
            $NomTercero=$result->fields[0].' '.$result->fields[1];
            return $NomTercero;
    }

    /**
    * Busca la descripcion del plan
    * @access public
    * @return string
    * @param string plan_id
    */
  function NombrePlan($Responsable)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT plan_descripcion FROM planes WHERE plan_id='$Responsable'";
            $result = $dbconn->Execute($query);
            $NomTercero=$result->fields[0];
            return $NomTercero;
    }

    /**
    * Llama la FormaImpresion
    * @access public
    * @return boolean
    * @param string plan_id
    */
    function Imprimir()
    {
       $TipoId=$_REQUEST['TipoId'];
         $PacienteId=$_REQUEST['PacienteId'];
         $Ingreso=$_REQUEST['Ingreso'];
         $FechaIngreso=$_REQUEST['FechaIngreso'];
         $Estado=$_REQUEST['Estado'];

            if(!$this->FormaImpresion($TipoId,$PacienteId,$Ingreso,$FechaIngreso,$Estado)){
                    return false;
                }
            return true;
    }

    /**
    * Busca los datos de los pacientes que han ingresado
    * @access public
    * @return boolean
    */
  function BuscarListadoIngresos()
    {
            $dpto=$_SESSION['TRIAGE']['DPTO'];
            if(empty($_SESSION['SPY']))
            {
                    list($dbconn) = GetDBconn();
                    $query = "SELECT a.ingreso, a.tipo_id_paciente, a.paciente_id,
                                                    a.fecha_registro,a.estado,
                                                    c.tipo_afiliado_id,a.comentario,
                                                    b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre
                                        FROM ingresos as a, pacientes as b, cuentas as c
                                        WHERE a.estado=1 AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id
                                        AND a.departamento='$dpto' and a.ingreso=c.ingreso";
                    $result = $dbconn->Execute($query);
                    $_SESSION['SPY']=$result->RecordCount();
            }

            if($result->EOF){
                        $mensaje='No hay pacientes ingresados.';
                        $accion=ModuloGetURL('app','Triage','user','Menus');
                        $boton='MENU';
                        if(!$this->FormaMensaje($mensaje,'LISTADO PACIENTES INGRESO',$accion,$boton)){
                                return false;
                        }
                        return true;
            }

         if(!$this->ListadoImpresion()){
        return false;
         }
    return true;
    }

    /**
    * Llama la forma de listado de los ingresos activos para su impresion.
    * @access public
    * @return boolean
    */
    function ListadoImpresion()
    {
            $limit=$this->limit;
            $NUM=$_REQUEST['Of'];
            if(!$NUM)
            {   $NUM='0';   }
            foreach($_REQUEST as $v=>$v1)
            {
                if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID')
                {   $vec[$v]=$v1;   }
            }
            $_REQUEST['Of']=$NUM;

            $dpto=$_SESSION['TRIAGE']['DPTO'];
            list($dbconn) = GetDBconn();
            $query = "SELECT a.ingreso, a.tipo_id_paciente, a.paciente_id,
                                                    a.fecha_registro,a.estado,
                                                    c.tipo_afiliado_id,a.comentario,
                                                    b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre
                                        FROM ingresos as a, pacientes as b, cuentas as c
                                        WHERE a.estado=1 AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id
                                        AND a.departamento='$dpto' and a.ingreso=c.ingreso LIMIT $limit OFFSET $NUM";
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

                if(empty($_SESSION['SPY']))
                {
                    $query = "SELECT a.ingreso, a.tipo_id_paciente, a.paciente_id,
                                                    a.fecha_registro,a.estado,
                                                    c.tipo_afiliado_id,a.comentario,
                                                    b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre
                                        FROM ingresos as a, pacientes as b, cuentas as c
                                        WHERE a.estado=1 AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id
                                        AND a.departamento='$dpto' and a.ingreso=c.ingreso";
                        $result = $dbconn->Execute($query);
                        $_SESSION['SPY']=$result->RecordCount();
                }

            //return $vars;
         if(!$this->FormaListadoIngresos($vars)){
        return false;
         }
    return true;
    }



    /**
    * Busca el motivo de la consulta del triage
    * @access public
    * @return string
    * @param string tipo de documento
    * @param int numero del documento
    */
    function BuscarMotivoConsulta($triage)
    {
				$emp=$_SESSION['TRIAGE']['EMPRESA'];
				$cu=$_SESSION['TRIAGE']['CENTROUTILIDAD'];
				$dpto=$_SESSION['TRIAGE']['DPTO'];

				list($dbconn) = GetDBconn();
				$query = "SELECT motivo_consulta FROM triages
														WHERE triage_id=$triage";
				$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				if(!$result->EOF)
				{ $var=$result->fields[0]; }
				return $var;
    }


		function NivelTriage($triage)
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT nivel_triage_asistencial FROM triages WHERE triage_id=$triage";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				if(!$result->EOF)
				{ $var=$result->fields[0]; }
				return $var;
		}

    /**
    * Busca la fecha y hora de llegada del paciente al triage
    * @access public
    * @return date
    * @param string tipo de documento
    * @param int numero del documento
    */
  function BuscarFechaTriage($tipo,$documento)
    {
            $emp=$_SESSION['TRIAGE']['EMPRESA'];
            $cu=$_SESSION['TRIAGE']['CENTROUTILIDAD'];
            $dpto=$_SESSION['TRIAGE']['DPTO'];
            list($dbconn) = GetDBconn();
            $query = "SELECT hora_llegada FROM triages
                                WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'
                                AND empresa_id='$emp' AND centro_utilidad='$cu' AND departamento='$dpto'
																AND sw_estado='1'";
            $result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}
						else{
										if($result->EOF){
												$this->error = "Error al Cargar el Modulo";
												$this->mensajeDeError = "La tabla maestra 'tipo_id_paciente' esta vacia ";
												return false;
										}
						}
						$result->Close();
          return $result->fields[0];
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
    * Busca el tipo de is del tercero y la descripcion
    * @access public
    * @return array
    */
    function tipo_id_terceros()
  {
            list($dbconn) = GetDBconn();
            $query = "SELECT tipo_id_tercero,descripcion FROM tipo_id_terceros ORDER BY indice_de_orden";
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
    * Busca el nombre del tipo de identificacion de un paciente
    * @access public
    * @return string
    * @param string tipo de documento
    */
    function mostrar_id_paciente($TipoId)
  {
            list($dbconn) = GetDBconn();
            $query = "SELECT descripcion FROM tipos_id_pacientes WHERE tipo_id_paciente='$TipoId'";
            $result = $dbconn->Execute($query);
            $datos=$result->RecordCount();

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
                }
                if($datos){
                $Tipo=$result->fields[0];
                $result->Close();
                }
        return $Tipo;
    }

    /**
    * Busca el nombre del pais
    * @access public
    * @return array
    * @param int codigo del pais
    */
    function nombre_pais($Pais)
  {
            list($dbconn) = GetDBconn();
            $query = "SELECT pais FROM tipo_pais WHERE tipo_pais_id='$Pais'";
            $result = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else{

                    if($result->EOF){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
                        return false;
                    }
                }
                $result->Close();
        return $result->fields[0];
    }

    /**
    * Busca el nombre del departamento
    * @access public
    * @return array
    * @param int codigo del pais
  * @param int codigo del departamento
    */
    function nombre_dpto($Pais,$Dpto)
  {
            list($dbconn) = GetDBconn();
            $query = "SELECT * FROM tipo_dptos WHERE tipo_pais_id='$Pais' AND tipo_dpto_id='$Dpto'";
            $result = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else{

                    if($result->EOF){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
                        return false;
                    }
                }
                $result->Close();
        return $result->fields[2];
    }

    /**
    * Busca el nombre de la ciudad o municipio
    * @access public
    * @return array
    * @param int codigo del pais
  * @param int codigo del departamento
    * @param int codigo del municipio
    */
    function nombre_ciudad($Pais,$Dpto,$Mpio)
  {
            list($dbconn) = GetDBconn();
            $query = "SELECT * FROM tipo_mpios WHERE tipo_pais_id='$Pais' AND tipo_dpto_id='$Dpto' AND tipo_mpio_id='$Mpio'";
            $result = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else{

                    if($result->EOF){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
                        return false;
                    }
                }
                $result->Close();
        return $result->fields[3];
    }


    /**
    * Busca los pacientes que estan en el triage
    * @access public
    * @return boolean
    */
    function ListarPacientes()
    {
						$this->BorrarProceso();			
            $empresa=$_SESSION['TRIAGE']['PUNTO']['EMPRESA'];
						if(empty($_SESSION['TRIAGE']['PUNTO']['ADMON']))
						{	$_SESSION['TRIAGE']['PUNTO']['ADMON']=$_REQUEST['Admon'];  }
            $pto=$_SESSION['TRIAGE']['PUNTO']['ADMON'];
            $dpto=$_SESSION['TRIAGE']['PUNTO']['DPTO'];

            $limit=$this->limit;
            $NUM=$_REQUEST['Of'];
            if(!$NUM)
            {   $NUM='0';   }
            foreach($_REQUEST as $v=>$v1)
            {
                if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID')
                {   $vec[$v]=$v1;   }
            }
            $_REQUEST['Of']=$NUM;

            list($dbconn) = GetDBconn();

						$query = "select count(*) from triages
									where sw_estado='0' AND empresa_id='$empresa'
									and punto_triage_id=".$_SESSION['TRIAGE']['PUNTO']['PTOTRIAGE']."";

						if(empty($_SESSION['CONT']))
						{
									$query = "SELECT distinct a.tipo_id_paciente,a.paciente_id, c.descripcion, a.hora_llegada, a.plan_id, a.punto_triage_id, a.punto_admision_id, a.triage_id, a.nivel_triage_asistencial
											FROM triages as a
											left join puntos_triage_admision as b on(a.punto_admision_id=b.punto_admision_id )
											LEFT JOIN puntos_admisiones as c on(b.punto_admision_id=c.punto_admision_id )
											WHERE a.empresa_id='$empresa' AND a.punto_triage_id=".$_SESSION['TRIAGE']['PUNTO']['PTOTRIAGE']."
											AND a.sw_estado='0'";
										$result1 = $dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0) {
												$this->error = "Error cont";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												return false;
										}
										else
										{
												if(!$result1->EOF)
												{      $_SESSION['CONT']=$result1->RecordCount();        }
										}
						}

						$query = "SELECT distinct a.tipo_id_paciente,a.paciente_id, c.descripcion, a.hora_llegada, a.plan_id, a.punto_triage_id, a.punto_admision_id, a.triage_id, a.nivel_triage_asistencial, e.usuario_id as proceso
											FROM triages as a
											left join puntos_triage_admision as b on(a.punto_admision_id=b.punto_admision_id )
											LEFT JOIN puntos_admisiones as c on(b.punto_admision_id=c.punto_admision_id )
											left join triages_proceso as e on(a.tipo_id_paciente=e.tipo_id_paciente AND a.paciente_id=e.paciente_id)
											WHERE a.empresa_id='$empresa' AND a.punto_triage_id=".$_SESSION['TRIAGE']['PUNTO']['PTOTRIAGE']."
											AND a.sw_estado='0'
											ORDER BY a.hora_llegada LIMIT $limit OFFSET $NUM";
						$result1 = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}
						else{
								if($result1->EOF){
														$mensaje='No hay pacientes en el triage.';
														$accion=ModuloGetURL('app','Triage','user','UserTriage');
														$boton='MENU';
														if(!$this->FormaMensaje($mensaje,'LISTADO PACIENTES TRIAGE',$accion,$boton)){
																				return false;
														}
														return true;
								}
								while (!$result1->EOF) {
										$arr[]=$result1->GetRowAssoc($ToUpper = false);
										$result1->MoveNext();
								}
						}
        $result1->Close();			
				
        $this->ListadoPacienteTriage($arr);
        return true;
    }


    /**
    * Busca los pacientes que ya pueden ser admitidos
    * @access public
    * @return boolean
    */
    function ListarPacientesAdmisiones()
    {
            $empresa=$_SESSION['TRIAGE']['EMPRESA'];
            $dpto=$_SESSION['TRIAGE']['DPTO'];
            $pto=$_SESSION['TRIAGE']['PTOADMON'];

            $limit=$this->limit;
            $NUM=$_REQUEST['Of'];
            if(!$NUM)
            {   $NUM='0';   }
            foreach($_REQUEST as $v=>$v1)
            {
                if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID')
                {   $vec[$v]=$v1;   }
            }
            $_REQUEST['Of']=$NUM;

            if($_SESSION['TRIAGE']['SWTRIAGE'])
            {
								list($dbconn) = GetDBconn();
								if(empty($_SESSION['CONT']))
								{
														$query = "select a.*
																	from triages as a
																	where a.sw_estado in (1,4) AND a.empresa_id='$empresa'
																	and a.sw_no_atender=0
																	and a.punto_admision_id='$pto'";
														$result = $dbconn->Execute($query);
														if ($dbconn->ErrorNo() != 0) {
																$this->error = "Error al Cargar el Modulo";
																$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																return false;
														}
														else
														{
																		if(!$result->EOF)
																		{  $_SESSION['CONT']=$result->RecordCount();  }
														}
								}

								$query = " select a.*
														from triages as a
														where a.sw_estado in (1,4) AND a.empresa_id='$empresa'
														and a.sw_no_atender=0
														and a.punto_admision_id='$pto' LIMIT $limit OFFSET $NUM";
								$result = $dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
								}
                else
                {
											if($result->EOF){
																	$mensaje='No hay pacientes para Admitir.';
																	$accion=ModuloGetURL('app','Triage','user','Menus');
																	if(!$this->FormaMensaje($mensaje,'LISTADO PACIENTES ADMISIONES',$accion,$boton)){
																					return false;
																	}
																	return true;
											}
											else{
															while(!$result->EOF)
															{
																			$arr[]=$result->GetRowAssoc(false);
																			$result->MoveNext();
															}
													$result->Close();
													if(!$this->ListadoPacienteAdmisiones($arr)){
															return false;
													}
													return true;
										}
            }
        }
    		else
				{//si el triage no esta desactivado
						$this->FormaBuscar();
						return true;
        }
    }


    /**
    * Busca los datos de un paciente cuando ya existe
    * @access public
    * @return array
    * @param string tipo de documento
    * @param int numero de documento
    */
 function DatosPaciente($tipo,$documento)
 {
            list($dbconn) = GetDBconn();
            $query = "SELECT fecha_nacimiento,
                              residencia_direccion,
															residencia_telefono,
															tipo_estado_civil_id,
															sexo_id,
															primer_nombre,
															primer_apellido,
															tipo_pais_id,
															tipo_dpto_id,
															tipo_mpio_id
                      FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
            $result = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else{
                        if($result->EOF){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "La tabla 'pacientes' esta vacia ";
                            return false;
                        }
                        $vars[0]=$result->fields[0];
                        $vars[1]=$result->fields[1];
                        $vars[2]=$result->fields[2];
                        $vars[3]=$result->fields[3];
                        $vars[4]=$result->fields[4];
                        $vars[5]=$result->fields[5];
                        $vars[6]=$result->fields[6];
                        $vars[7]=$result->fields[7];
                        $vars[8]=$result->fields[8];
                        $vars[9]=$result->fields[9];
                }
                $result->Close();

        return $vars;
 }


    /**
    * Busca los datos basicos de los pacientes necesarios para el listado se triage
    * la fecha de resgistro, el primer nombre y apellido
    * @access public
    * @return array
    * @param string tipo de documento
    * @param int numero de documento
    */
 function BuscarDatosPaciente($tipo,$documento)
 {
            list($dbconn) = GetDBconn();
            $query = "SELECT fecha_registro,primer_nombre,segundo_nombre,primer_apellido
                                FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
            $result = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else{
                        if($result->EOF){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "La tabla 'pacientes' esta vacia ";
                            return false;
                        }
                        while (!$result->EOF) {
                            $vars[$result->fields[0]]=$result->fields[1]." ".$result->fields[2]." ".$result->fields[3];
                            $result->MoveNext();
                        }
                }
        $result->Close();
        return $vars;
 }

    /**
    * Datos del paciente cuando esta en triage.
    * @access public
    * @return array
    * @param string tipo de documento
    * @param int numero de documento
    */
 function HoraTriage($triage)
 {
			list($dbconn) = GetDBconn();
			$query = "SELECT a.hora_llegada FROM triages as a
								WHERE a.triage_id=$triage
								AND sw_estado='0'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			if(!$result->EOF)
			{  $vars=$result->fields[0]; }
			$result->Close();
			return $vars;
 	}


    /**
    * Busca los datos basicos de los pacientes necesarios para el listado se triage
    * la fecha de resgistro, el primer nombre y apellido
    * @access public
    * @return array
    * @param string tipo de documento
    * @param int numero de documento
    */
    function BuscarNombresPaciente($tipo,$documento)
    {
            list($dbconn) = GetDBconn();
         	  $query = "SELECT primer_nombre,segundo_nombre FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
            $result = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else{

                    if($result->EOF){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "La tabla 'pacientes' esta vacia ";
                        return false;
                    }
                }

            $Nombres=$result->fields[0]." ".$result->fields[1];
            $result->Close();
        return $Nombres;
    }


    /**
    * Busca los apellidos de un paciente
    * @access public
    * @return array
    * @param string tipo de documento
    * @param int numero de documento
    */
    function BuscarApellidosPaciente($tipo,$documento)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT primer_apellido,segundo_apellido FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
            $result = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else{

                    if($result->EOF){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "La tabla 'paciente' esta vacia ";
                        return false;
                    }
                }
                $result->Close();
                $Apellidos=$result->fields[0]." ".$result->fields[1];
        return $Apellidos;
    }



        /**
        * Busca los datos del paciente que se va ha ingresar para mostrarlos y var si es
        * necesaria alguna modificacion.
        * @access public
        * @return array
        * @param string tipo de documento
        * @param int numero de documento
        */
        function tiposAtenciones()
        {
                list($dbconn) = GetDBconn();
                $query = "SELECT tipo_atencion_id,descripcion FROM tipo_atencion WHERE tipo_atencion_id!='0' ORDER BY indice_de_orden";
                $result = $dbconn->Execute($query);
                $datos=$result->RecordCount();

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else{
                    if($result->EOF){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "La tabla maestra 'tipo_atencion' esta vacia";
                        return false;
                    }
                        $i=0;
                        while (!$result->EOF) {
                            $vars[$result->fields[0]]=$result->fields[1];
                            $result->MoveNext();
                            $i++;
                        }
                    }
                $result->Close();
                return $vars;
        }


    /**
  * Actualizar datos del garante de un paciente
    * @access public
    * @return boolean
    */
    function ActualizarDatosGarantes()
    {
            $Ingreso=$_REQUEST['Ingreso'];
            $GaranteId=$_REQUEST['GaranteId'];
            $TipoId=$_REQUEST['TipoId'];
            $GaranteId1=$_REQUEST['GaranteId'];
            $TipoId1=$_REQUEST['TipoId'];
            $PrimerApellido=strtoupper($_REQUEST['PrimerApellido']);
            $SegundoApellido=strtoupper($_REQUEST['SegundoApellido']);
            $PrimerNombre=strtoupper($_REQUEST['PrimerNombre']);
            $SegundoNombre=strtoupper($_REQUEST['SegundoNombre']);
            $Direccion=$_REQUEST['Direccion'];
            $Telefono=$_REQUEST['Telefono'];
            $accion=$_REQUEST['accion'];
            $Update=$_REQUEST['Update'];

						if(!$GaranteId || $TipoId==-1 || !$PrimerNombre || !$PrimerApellido || !$Direccion || !$Telefono){
														if(!$GaranteId){ $this->frmError["GaranteId"]=1; }
														if(!$TipoId){ $this->frmError["TipoId"]=1; }
														if(!$PrimerNombre){ $this->frmError["PrimerNombre"]=1; }
														if(!$PrimerApellido){ $this->frmError["PrimerApellido"]=1; }
														if(!$Direccion){ $this->frmError["Direccion"]=1; }
														if(!$Telefono){ $this->frmError["Telefono"]=1; }
																$this->frmError["MensajeError"]="Faltan datos obligatorios.";
																if(!$this->FormaGarantes($TipoId,$GaranteId,$Ingreso,$Update)){
																				return false;
																}
																return true;
						}

						list($dbconn) = GetDBconn();
						$query = "UPDATE garantes SET
																																		tipo_id_tercero='$TipoId',
																																		garante_id='$GaranteId',
																																		primer_nombre_garante='$PrimerNombre',
																																		segundo_nombre_garante='$SegundoNombre',
																																		primer_apellido_garante='$PrimerApellido',
																																		segundo_apellido_garante='$SegundoApellido',
																																		direccion_garante='$Direccion',
																																		telefono_garante='$Telefono'
																		WHERE ingreso='$Ingreso' AND garante_id='$GaranteId' AND tipo_id_tercero='$TipoId'";
						$dbconn->Execute($query);

						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar en la Base de Datos";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
						}
						$mensaje='Los datos del Garante se guardaron correctamente.';
						if(!$this->FormaMensaje($mensaje,'GARANTES',$accion,$boton)){
												return false;
						}
						return true;
    }


    /**
  * Inserta el nivel del plan del paciente
    * @access public
    * @return boolean
    */
  function InsertarNivel()
    {
                $Responsable=$_REQUEST['Responsable'];
                $Nivel=$_REQUEST['Nivel'];
								$TipoId=$_REQUEST['TipoId'];
								$PacienteId=$_REQUEST['PacienteId'];
                $FechaRegistro=date("Y-m-d H:i:s");
                $SystemId=UserGetUID();
                $empresa=$_SESSION['TRIAGE']['EMPRESA'];
                $cu=$_SESSION['TRIAGE']['CENTROUTILIDAD'];
                $pto=$_SESSION['TRIAGE']['PTOADMON'];
                $dpto=$_SESSION['TRIAGE']['DPTO'];

                list($dbconn) = GetDBconn();
                if($_SESSION['TRIAGE']['SWTRIAGE']){
                    $query = "INSERT INTO triages (
                                                hora_llegada,
                                                tipo_id_paciente,
                                                paciente_id,
                                                plan_id,
                                                nivel,
                                                nivel_triage_id,
                                                observacion_medico,
                                                observacion_enfermera,
                                                motivo_consulta,
                                                usuario_id,
                                                empresa_id,
                                                centro_utilidad,
                                                punto_admision_id,
                                                departamento)
                                        VALUES ('$FechaRegistro','$TipoId',$PacienteId,'$Responsable','$Nivel','0','$ObservacionM','$ObservacionE','$MotivoConsulta',$SystemId,'$empresa','$cu',$pto,'$dpto')";
                    $dbconn->Execute($query);

                    if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Guardar en la Base de Datos";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                    }
                            $mensaje='El paciente pasa a clasificación de Triage';
                            $accion=ModuloGetURL('app','Triage','user','Buscar');
                            if(!$this->FormaMensaje($mensaje,'PACIENTES CLASIFICACION TRIAGE',$accion,$boton)){
                                        return false;
                            }
                            return true;
            }
            else{//triage desactivado
                            if(!$this->ValidarDerechosNew($TipoId,$PacienteId,$Responsable,$Nivel)){
                                        return false;
                            }
                            return true;
            }
    }

    /**
    * Llama el modulo pacientes para pedir el acudiente
    * @access public
    * @return boolean
    * @param int ingreso
    */
    function TerminarIngreso()
    {
                $PacienteId=$_SESSION['TRIAGE']['PACIENTE']['paciente_id'];
                $TipoId=$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente'];
                $PlanId=$_SESSION['TRIAGE']['PACIENTE']['plan_id'];
                $Nivel=$_SESSION['TRIAGE']['PACIENTE']['nivel'];
                $Estacion=$_SESSION['TRIAGE']['PACIENTE']['estacion_id'];
                $Dpto=$_SESSION['TRIAGE']['PACIENTE']['departamento'];
                $Ingreso =$_SESSION['TRIAGE']['PACIENTE']['INGRESO'];
                $Orden=$_SESSION['TRIAGE']['ORDEN'];
								//$_SESSION['TRIAGE']['PACIENTE']['INGRESO']=$Ingreso;
								//$_SESSION['TRIAGE']['PACIENTE']['estacion_id']=$_SESSION['ADMISION']['PACIENTE']['estacion_id'];
								//$_SESSION['ADMISIONES']['PACIENTE']['estacion_id']
								//unset($_SESSION['ADMISION']['RETORNO']);
								
                list($dbconn) = GetDBconn();

                if($_SESSION['TRIAGE']['TIPO']=='HOSPITALIZACION' && $_SESSION['TRIAGE']['TIPOORDEN']=='Traslado')
                {
                        $query = " INSERT INTO pendientes_x_hospitalizar(ingreso,
																																			estacion_destino,
																																			estacion_origen,
																																			orden_hospitalizacion_id,
																																			traslado)
                                        VALUES($Ingreso,'$Estacion',NULL,$Orden,'2')";
                        $dbconn->BeginTrans();
                        $resulta=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar en pendientes_x_hospitalizar";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                        }
                        else
                        {
                                $Ingreso=$_SESSION['TRIAGE']['PACIENTE']['INGRESO'];
                                $Auto=$_SESSION['TRIAGE']['PACIENTE']['AUTORIZACION'];

                                $query = " UPDATE ordenes_hospitalizacion SET hospitalizado=2,
																														autorizacion=$Auto,
																														ingreso=$Ingreso
                                                        WHERE orden_hospitalizacion_id=$Orden";
                                $resulta=$dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                                $this->error = "Error al actualizar hospitalizado=2";
                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                $dbconn->RollbackTrans();
                                                return true;
                                }
                                else
                                {
                                                $query = " UPDATE ingresos SET departamento_actual='$Dpto'
                                                                        WHERE ingreso=$Ingreso";
                                                $resulta=$dbconn->Execute($query);
                                                if ($dbconn->ErrorNo() != 0) {
                                                                $this->error = "Error al actualizar hospitalizado=2";
                                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                $dbconn->RollbackTrans();
                                                                return true;
                                                }
                                                else
                                                {
                                                        $dbconn->CommitTrans();
                                                }
                                }
                        }
                }

                if($_SESSION['TRIAGE']['TIPO']=='HOSPITALIZACION' && $_SESSION['TRIAGE']['TIPOORDEN']=='Interna')
                {
                        $query = " INSERT INTO pendientes_x_hospitalizar(ingreso,
																																	estacion_destino,
																																	estacion_origen,
																																	orden_hospitalizacion_id)
                                    VALUES($Ingreso,'$Estacion',NULL,$Orden)";
                        $dbconn->BeginTrans();
                        $resulta=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar en pendientes_x_hospitalizar";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                        }
                        else
                        {
                                $Ingreso=$_SESSION['TRIAGE']['PACIENTE']['INGRESO'];
                                $Auto=$_SESSION['TRIAGE']['PACIENTE']['AUTORIZACION'];

                                $query = " UPDATE ordenes_hospitalizacion SET hospitalizado='2',
																																									autorizacion=$Auto,
																																									ingreso=$Ingreso
                                                        WHERE orden_hospitalizacion_id=$Orden";
                                $resulta=$dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                                $this->error = "Error al actualizar hospitalizado=2";
                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                $dbconn->RollbackTrans();
                                                return true;
                                }
                                else
                                {  $dbconn->CommitTrans();  }
                        }
                }

            		if($_SESSION['TRIAGE']['TIPOORDEN']=='Externa')
                {
                        $this->LlamarFormaOrdenExterna();
												return true;
                }
                elseif($_SESSION['TRIAGE']['TIPOORDEN']=='Interna')
                {
                        $this->MensajeFinal();
												return true;
                }
                elseif($_SESSION['TRIAGE']['TIPOORDEN']=='Translado')
                {
                        $this->BuscarTranslado();
												return true;
                }
								else
                {
                        $this->MensajeFinal();
												return true;
                }
    }

    /**
    * Busca el mensaje final segun el caso del tipo de admision (UR y HS).
    * @access public
    * @return boolean
    */
    function MensajeFinal()
    {
                unset($_SESSION['TRIAGE']['PACIENTE']);
                //unset($_SESSION['PACIENTES']['RETORNO']);

                if($_SESSION['TRIAGE']['TIPO']=='URGENCIAS')
                {
                         $mesanje='EL PACIENTE FUE ADMITIDO.';
                        $accion=ModuloGetURL('app','Triage','user','ListarPacientesAdmisiones');
                }
                elseif($_SESSION['TRIAGE']['TIPO']=='HOSPITALIZACION')
                {
                        $mesanje='LA ORDEN FUE APROBADA.';
                        if($_SESSION['TRIAGE']['TIPOORDEN']=='Externa')
                        {  $accion=ModuloGetURL('app','Triage','user','Buscar');  }
                        else
                        {  $accion=ModuloGetURL('app','Triage','user','ListadoAdmisionHospitalizacion'); }
                }

                $this->FormaMensaje($mesanje,'ADMISION',$accion,$boton);
                return true;
    }

    /**
    * Llama la forma FormaOrdenExterna.
    * @access public
    * @return boolean
    */
    function LlamarFormaOrdenExterna()
    {
                if(!$this->FormaOrdenExterna('','','','','','','','','')){
                        return false;
                }
                return true;
    }

    /**
    * Busca el sw_tipo_plan que corresponde al plan
    * @access public
    * @return array
    * @param int nivel del plan
    */
    function BuscarSW($Responsable)
    {
                list($dbconn) = GetDBconn();
                 $query = "SELECT  sw_tipo_plan
                                    FROM planes
                                    WHERE plan_id='$Responsable'";
                $results = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }
            return $results->fields[0];
    }


    /**
    * Busca el nombre de la estacion
    * @access public
    * @return boolean
    * @param int estacion id
    */
    function BuscarNombreEstacio($EstId)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT descripcion
                                FROM estaciones_enfermeria
                                WHERE estacion_id='$EstId'";
            $result = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            return $result->fields[0];
    }



    /**
  * Busca la descripcion del sexo
    * @access public
    * @return string
    * @param string tipo de documento
    * @param int numero de documento
    */
    function NombreSexo($TipoId,$PacienteId)
    {
                list($dbconn) = GetDBconn();
                $query = "SELECT sexo_id FROM pacientes WHERE tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId'";
                $result = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }

                $s=$result->fields[0];
                list($dbconn) = GetDBconn();
                $query = "SELECT descripcion FROM tipo_sexo WHERE sexo_id='$s'";
                $results = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }

                $result->Close();
                return $results->fields[0];
    }


    /**
  * Busca la fecha de nacimiento de un paciente
    * @access public
    * @return date
    * @param string tipo de documento
    * @param int numero de documento
    */
        function Edad($TipoId,$PacienteId)
        {
                list($dbconn) = GetDBconn();
                $query = "SELECT fecha_nacimiento FROM pacientes WHERE tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId'";
                $result = $dbconn->Execute($query);

                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    else{

                        if($result->EOF){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "La tabla 'paciente' esta vacia ";
                            return false;
                        }
                    }
                $result->Close();
                $FechaNacimiento=$result->fields[0];
                return $FechaNacimiento;
        }


    /**
  * Llama la FormaSignosVitalesTriage
    * @access public
    * @return date
    */
		function SignosVitalesTriage()
		{
					list($dbconn) = GetDBconn();
					$query = "SELECT * FROM triages_proceso
										WHERE tipo_id_paciente='".$_REQUEST['TipoId']."'
										AND paciente_id='".$_REQUEST['PacienteId']."'";
					$result = $dbconn->Execute($query);
					if($result->EOF)
					{
							$query = "INSERT INTO triages_proceso(
																				tipo_id_paciente,
																				paciente_id,
																				usuario_id,
																				fecha_registro)
												VALUES('".$_REQUEST['TipoId']."','".$_REQUEST['PacienteId']."',".UserGetUID().",'now()')";
							$results=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}
							$results->Close();
					}
					$result->Close();
				
					$_SESSION['TRIAGE']['PACIENTE']['paciente_id']=$_REQUEST['PacienteId'];
					$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente']=$_REQUEST['TipoId'];
					$_SESSION['TRIAGE']['PACIENTE']['triage_id']=$_REQUEST['Triage'];
					$_SESSION['TRIAGE']['PACIENTE']['plan_id']=$_REQUEST['Plan'];
					$_SESSION['TRIAGE']['PACIENTE']['punto_admision_id']=$_REQUEST['Admon'];
					if(!$this->FormaSignosVitalesTriage()){
							return false;
					}
					return true;
        }


    /**
  * Insertar los signos vitales que se toman en el triage
    * @access public
    * @return boolean
    */
		function InsertarSignosVitalesTriage()
		{
     // print_r($_REQUEST);
							$TipoId=$_REQUEST['TipoId'];
							$PacienteId=$_REQUEST['PacienteId'];
							$MotivoConsulta=$_REQUEST['MotivoConsulta'];
							$ObservacionesE=$_REQUEST['ObservacionesEnfermera'];
							$fc=$_REQUEST['frecuenciaCardiaca'];
							$fr=$_REQUEST['frecuenciaRespiratoria'];
							$temperatura=$_REQUEST['temperatura'];
							$peso=$_REQUEST['peso'];
							$tAlta=$_REQUEST['taAlta'];
							$tBaja=$_REQUEST['taBaja'];
							$fechaSistema=date("Y-m-d H:i:s");
							$TriageId=$_SESSION['TRIAGE']['PACIENTE']['triage_id'];
							$usuario=UserGetUID();

							if(!$MotivoConsulta){
												$this->frmErrorF["MotivoConsulta"]=1;
												$this->frmErrorF["MensajeError"]="Debe escribir el Motivo de la Consulta.";
												if(!$this->FormaSignosVitalesTriage($MotivoConsulta,$ObservacionesEnfermera,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja)){
																return false;
												}
										return true;
							}

							//valida signos vitales
							$val = $this->ValidarSignosVitales($fc,$fr,$tAlta,$tBaja,$temperatura,$peso,$_REQUEST['ocular'],$_REQUEST['verbal'],$_REQUEST['motora'],$_REQUEST['sato']);
							if(empty($val))
							{
									if(!$this->FormaSignosVitalesTriage($MotivoConsulta,$ObservacionesEnfermera,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja)){
														return false;
										}
										return true;
							}
                
								if(empty($_REQUEST['Nivel']))
								{
										$this->frmError["Nivel"]=1;
										$this->frmError["MensajeError"]="Debe Elegir el Nivel de Clasificacion.";
										if(!$this->FormaSignosVitalesTriage($MotivoConsulta,$ObservacionesEnfermera,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja)){
														return false;
										}
										return true;
								}

								if($_REQUEST['eva'] === '0')
								{  $_REQUEST['eva'] = 0;  }
								elseif($_REQUEST['eva'] > 0)
								{  $_REQUEST['eva'] = $_REQUEST['eva'];  }
								else
								{  $_REQUEST['eva']='NULL';  }

								$sw=0;

								if($_REQUEST['Punto']==-1)
								{
										$_REQUEST['Punto']='NULL';
										$estado=0;
								}
								else
								{  $estado=4;  }

								if($_REQUEST['Punto']=='N')
								{   $sw=1;   }

								if(empty($peso))
								{  $peso='NULL';  }
								else
								{  $peso="'".$peso."'";  }

								if(empty($_REQUEST['sato']))
								{  $_REQUEST['sato']='NULL';  }
								else
								{  $_REQUEST['sato']="'".$_REQUEST['sato']."'";  }

								if(empty($temperatura))
								{  $temperatura='NULL';  }
								else
								{  $temperatura="'".$temperatura."'";  }

								if(empty($tBaja) OR empty($tAlta))
								{
									$tAlta='NULL';
									$tBaja='NULL';
								}

								list($dbconn) = GetDBconn();
								$dbconn->BeginTrans();

								$TriageId=$_SESSION['TRIAGE']['PACIENTE']['triage_id'];

								if(empty($_SESSION['TRIAGE']['PACIENTE']['triage_id']))
								{
											$query="SELECT nextval('triages_triage_id_seq')";
											$result=$dbconn->Execute($query);
											$TriageId=$result->fields[0];
											$_SESSION['TRIAGE']['PACIENTE']['triage_id']=$TriageId;
											$empresa=$_SESSION['TRIAGE']['PUNTO']['EMPRESA'];
											$cu=$_SESSION['TRIAGE']['PUNTO']['CENTROUTILIDAD'];
											$dpto=$_SESSION['TRIAGE']['PUNTO']['DPTO'];

											$query = "INSERT INTO triages (triage_id,
																											hora_llegada,
																											tipo_id_paciente,
																											paciente_id,
																											plan_id,
																											nivel_triage_id,
																											observacion_medico,
																											observacion_enfermera,
																											motivo_consulta,
																											usuario_id,
																											empresa_id,
																											centro_utilidad,
																											departamento,
																											autorizacion_int,
																											autorizacion_ext,
																											tipo_afiliado_id,
																											rango,
																											semanas_cotizadas,
																											sw_estado,
																											punto_triage_id,
																											sw_no_atender,
                                                      usuario_clasificacion)
											VALUES ($TriageId,'now()','$TipoId','$PacienteId','".$_SESSION['TRIAGE']['PACIENTE']['plan_id']."','".$_REQUEST['Nivel']."','$ObservacionM','$ObservacionesE','$MotivoConsulta',".UserGetUID().",'".$_SESSION['TRIAGE']['PUNTO']['EMPRESA']."','$cu','$dpto',NULL,NULL,NULL,NULL,0,'0',".$_SESSION['TRIAGE']['PUNTO']['PTOTRIAGE'].",'$sw',".UserGetUID().")";
											
                      $dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0) {
														$this->error = "Error al Guardar en triages";
														$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
														$dbconn->RollbackTrans();
														return false;
											}
								}

								if(!empty($_SESSION['TRIAGE']['PACIENTE']['REMISION']))
								{
										$query = "UPDATE pacientes_remitidos SET triage_id=$TriageId
															WHERE paciente_remitido_id=".$_SESSION['TRIAGE']['PACIENTE']['REMISION']."";
										$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0) {
														$this->error = "Error UPDATE pacientes_remitidos";
														$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
														$dbconn->RollbackTrans();
														return false;
										}
								}

								$query = "select count(signos_vitales_fc) from signos_vitales_triages where triage_id=$TriageId";
								$result=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar en signos_vitales_triages";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
								}
								//no se han tomado signos
								if($result->fields[0]==0)
								{
										$query = "INSERT INTO signos_vitales_triages ( signos_vitales_fc,
																																		signos_vitales_fr,
																																		signos_vitales_temperatura,
																																		signos_vitales_peso,
																																		signos_vitales_taalta,
																																		signos_vitales_tabaja,
																																		fecha,
																																		triage_id,
																																		usuario_id,
																																		evaluacion_dolor,
																																		respuesta_motora_id,
																																		respuesta_verbal_id,
																																		apertura_ocular_id,
																																		tipo_glasgow,
																																		sato2)
															VALUES ('$fc','$fr',$temperatura,$peso,$tAlta,$tBaja,'$fechaSistema',$TriageId,$usuario,".$_REQUEST['eva'].",'".$_REQUEST['motora']."','".$_REQUEST['verbal']."','".$_REQUEST['ocular']."','".$_REQUEST['niño']."',".$_REQUEST['sato'].")";
								}
								else
								{
										$query = "UPDATE signos_vitales_triages SET
																			signos_vitales_fc='$fc',
																			signos_vitales_fr='$fr',
																			signos_vitales_temperatura=$temperatura,
																			signos_vitales_peso=$peso,
																			signos_vitales_taalta=$tAlta,
																			signos_vitales_tabaja=$tBaja,
																			evaluacion_dolor=".$_REQUEST['eva'].",
																			fecha='now()',
																			usuario_id=".UserGetUID().",
																			respuesta_motora_id='".$_REQUEST['motora']."',
																			respuesta_verbal_id='".$_REQUEST['verbal']."',
																			apertura_ocular_id='".$_REQUEST['ocular']."',
																			tipo_glasgow='".$_REQUEST['niño']."',
																			sato2=".$_REQUEST['sato']."
															WHERE triage_id='$TriageId'";
								}
							$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "1Error al Guardar en signos_vitales_triages";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
								}

								//if($_REQUEST['PuntoFinal']=='N')
								if($_REQUEST['Punto']=='N')
								{
											//va ha elegir ha que estacion remite el paciente
											$query = "UPDATE triages SET observacion_enfermera='$ObservacionesE',motivo_consulta='$MotivoConsulta',
																nivel_triage_asistencial=".$_REQUEST['Nivel'].",
																sw_estado='0', sw_no_atender=$sw, usuario_clasificacion = ".UserGetUID().", fecha_clasificacion = 'now()'
																WHERE triage_id='$TriageId'";
								}
								else
								{			//no eligio un punto
											if($_REQUEST['Punto']=='NULL')
											{
													$query = "UPDATE triages SET observacion_enfermera='$ObservacionesE',motivo_consulta='$MotivoConsulta',
																		nivel_triage_asistencial=".$_REQUEST['Nivel'].",
																		sw_estado='$estado', sw_no_atender=$sw, usuario_clasificacion = ".UserGetUID().", fecha_clasificacion = 'now()'
																		WHERE triage_id='$TriageId'";
											}
											else
											{
													$query = "UPDATE triages SET observacion_enfermera='$ObservacionesE',motivo_consulta='$MotivoConsulta',
																		nivel_triage_asistencial=".$_REQUEST['Nivel'].",
																		sw_estado='$estado', sw_no_atender=$sw,
																		punto_admision_id=".$_REQUEST['Punto'].", usuario_clasificacion = ".UserGetUID().", fecha_clasificacion = 'now()'
																		WHERE triage_id='$TriageId'";
											}
								}
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error UPDATE triages1";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
								}
								
                                        $dbconn->CommitTrans();
								//la enfermera remitio
								if($_REQUEST['Punto']=='N')
								{
											$this->FormaMuestraEstaciones();
											return true;
								}

								unset($_SESSION['TRIAGE']['PACIENTE']['REMISION']);
								if($sw==0)
								{
											$mesanje='Los Signos Vitales del Pacientes se guardaron correctamente.';
											if($sw==1)
											{ $mesanje='Los Signos Vitales del Pacientes se guardaron correctamente. El Paciente Fue Clasificado por el Auxiliar Como No atender.';   }
											if(!empty($_SESSION['TRIAGE']['PACIENTE']['triage_id']))
											{  $accion=ModuloGetURL('app','Triage','user','ListarPacientes');  }
											else
											{  $accion=ModuloGetURL('app','Triage','user','FormaBuscarTriage');  }
											$this->FormaMensaje($mesanje,'SIGNOS VITALES',$accion,$boton,$TriageId,$empresa);
											return true;
								}
								else
								{
											$query = "select punto_admision_id from triages
																where triage_id=".$TriageId."";
											$result=$dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0) {
													$this->error = "Error UPDATE triages2";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													return false;
											}
											unset($_SESSION['TRIAGE']['ESTACION']);
											$_SESSION['TRIAGE']['PTOADMON']=$result->fields[0];
											$Estaciones=$this->BuscarEstaciones();
											if(sizeof($Estaciones)==1)
											{
															$query = "insert into triage_no_atencion values($TriageId,'".$Estaciones[0][estacion_id]."')";
															$result=$dbconn->Execute($query);
															if ($dbconn->ErrorNo() != 0) {
																	$this->error = "Error UPDATE triages3";
																	$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																	return false;
															}

															$Mensaje = 'Los Signos Vitales del Pacientes se guardaron correctamente. Fue Asignado a la Estacion de Enfermeria '.$Estaciones[0][descripcion];
															$accion=ModuloGetURL('app','Triage','user','ListarPacientes');
															if(!$this-> FormaMensaje($Mensaje,'AUTORIZACIONES',$accion,'')){
															return false;
															}
															return true;
											}
											else
											{
															$_SESSION['TRIAGE']['ESTACION']=true;
															$this->FormaElegirEstacion();
															return true;
											}
								}
		}

		/**
		*
		*/
		function CancelarRemisionEnfermera()
		{
					list($dbconn) = GetDBconn();
					$query = "UPDATE triages SET sw_no_atender='0'
										WHERE triage_id=".$_SESSION['TRIAGE']['PACIENTE']['triage_id']."";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error UPDATE triages11";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}

					$this->FormaBuscarTriage();
					return true;
		}

		/**
		*
		*/
		function RemitirPacienteEstacion()
		{
					//0 estacion 1departamento 2descripcion 3punto admision
					$var=explode(',',$_REQUEST['Estacion']);

					list($dbconn) = GetDBconn();
 					$dbconn->BeginTrans();
					$query = "UPDATE triages SET sw_estado='4'
										WHERE triage_id=".$_SESSION['TRIAGE']['PACIENTE']['triage_id']."";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error UPDATE triages3";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
					}

					$query = "insert into triage_no_atencion values
					(".$_SESSION['TRIAGE']['PACIENTE']['triage_id'].",".$var[0].")";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error UPDATE triages3";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
					}

					$dbconn->CommitTrans();
					$Mensaje = 'El Paciente fue Asignado a la Estacion de Enfermeria '.$var[2];
					$accion=ModuloGetURL('app','Triage','user','FormaBuscarTriage');
					if(!$this-> FormaMensaje($Mensaje,'TRIAGE',$accion,'')){
					return false;
					}
					return true;
		}

		/**
		*
		*/
		function EstacionTriage()
		{
					if($_REQUEST['Estacion']==-1){
							if($_REQUEST['Estacion']==-1){ $this->frmError["Estacion"]=1; }
							$this->frmError["MensajeError"]="Debe elegir la Estación.";
							if(!$this->FormaElegirEstacion()){
											return false;
							}
							return true;
					}

					$est=explode(',',$_REQUEST['Estacion']);
					list($dbconn) = GetDBconn();
					$query = "insert into triage_no_atencion values(".$_SESSION['TRIAGE']['PACIENTE']['triage_id'].",".$est[0].")";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error UPDATE triages";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}

					$Mensaje = 'Los Signos Vitales del Pacientes se guardaron correctamente. Fue Asignado a la Estacion de Enfermeria '.$est[3];
					$accion=ModuloGetURL('app','Triage','user','ListarPacientes');
					if(!$this-> FormaMensaje($Mensaje,'AUTORIZACIONES',$accion,'')){
					return false;
					}
					return true;
		}


		/**
		* Busca el id del triage que se le asigno a un paciente
		* @access public
		* @return int
		* @param string tipo de documento
		* @param int numero del documento
		*/
		function buscaridtriage($TipoId,$PacienteId)
		{
					list($dbconn) = GetDBconn();
					$query = "SELECT triage_id FROM triages
												WHERE tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId'
												AND empresa_id='".$_SESSION['TRIAGE']['PUNTO']['EMPRESA']."'
												AND punto_admision_id=".$_SESSION['TRIAGE']['PUNTO']['ADMON']."
												AND sw_estado='0'";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					if(!$result->EOF)
					{		$vars=$result->fields[0];	  }
					$result->Close();
					return $vars;
        }


    /**
  * Busca los diferentes tipos de atenciones
    * @access public
    * @return array
    */
        function tipo_atencion()
        {
                list($dbconn) = GetDBconn();
                $query = "SELECT * FROM tipo_atencion ORDER BY indice_de_orden";
                $result1 = $dbconn->Execute($query);

                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    else{

                        if($result1->EOF){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "La tabla 'tipo_atencion' esta vacia ";
                            return false;
                        }
                            while (!$result1->EOF) {
                            $arr[$result1->fields[0]]=$result1->fields[1];
                            $result1->MoveNext();
                            }
                    }
                    $result1->Close();
            return $arr;
        }


    /**
  * Busca los diferentes tipos de responsable (planes)
    * @access public
    * @return array
    */
        function responsables()
        {
                    list($dbconn) = GetDBconn();
                    $query="SELECT plan_id,plan_descripcion,tercero_id,tipo_tercero_id FROM planes
                                    WHERE fecha_final >= now() and estado=1 and fecha_inicio <= now() order by plan_descripcion";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
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

    /**
  * Busca los diferentes tipos de responsable (planes)
    * @access public
    * @return string
    * @param int id del tercero
    * @param string tipo_id_tercero
    */
     function BuscarNombreTercero($TerceroId,$TipoTercero)
     {
            list($dbconn) = GetDBconn();
                $query="SELECT nombre_tercero FROM terceros WHERE tercero_id='$TerceroId' AND tipo_id_tercero='$TipoTercero'";
                $result = $dbconn->Execute($query);

                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
            $result->Close();
            return $result->fields[0];
     }


    /**
  * Busca los tipos de causas externas
    * @access public
    * @return array
    */
        function Causa_Externa()
        {
                list($dbconn) = GetDBconn();
                $query = "SELECT * FROM causas_externas order by causa_externa_id";
                $result = $dbconn->Execute($query);

                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    else{
                        if($result->EOF){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "La tabla maestra 'causas_externas' esta vacia ";
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
  * Busca los diferentes tipos de vias de ingreso
    * @access public
    * @return array
    */
        function Via_Ingreso()
        {
                list($dbconn) = GetDBconn();
                $query = "SELECT * FROM vias_ingreso order by via_ingreso_id";
                $result = $dbconn->Execute($query);

                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    else{
                        if($result->EOF){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "La tabla maestra 'vias_ingreso' esta vacia ";
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
		* Busca los diferentes tipos de afiliados
		* @access public
		* @return array
		*/
		function Tipo_Afiliado()
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT DISTINCT a.tipo_afiliado_nombre, a.tipo_afiliado_id
									FROM tipos_afiliado as a, planes_rangos as b
									WHERE b.plan_id='".$_SESSION['TRIAGE']['PACIENTE']['plan_id']."'
									and b.tipo_afiliado_id=a.tipo_afiliado_id";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
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
		* Busca los diferentes tipos de afiliados
		* @access public
		* @return array
		*/
		function NombreTipoAfiliado($TipoAfiliado)
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT tipo_afiliado_nombre FROM tipos_afiliado WHERE tipo_afiliado_id='$TipoAfiliado'";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$resulta->Close();
				return $resulta->fields[0];
		}



    /**
  * Busca los diferentes tipos de estados que puede tener un afiliado
    * @access public
    * @return array
    */
 function Estado_Afiliado()
 {
            list($dbconn) = GetDBconn();
            $query = "SELECT tipo_estado_afiliado_id,descripcion FROM tipo_estados_afiliados";
            $results = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            else{
                    if($results->EOF){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "La tabla maestra 'estados_afiliados' esta vacia ";
                        return false;
                    }
                    while (!$results->EOF) {
                            $vars[$results->fields[0]]=$results->fields[1];
                            $results->MoveNext();
                    }
        }
        $results->Close();
      return $vars;
    }
//------------------------------CLASIFICACION TRIAGE-------------------------------------//
    /**
		* Llama la FormaClasificacionTriage
    * @access public
    * @return boolean
    */
    function ClasificacionTriage()
    {
					list($dbconn) = GetDBconn();
					$query = "SELECT * FROM triages_proceso
										WHERE tipo_id_paciente='".$_REQUEST['TipoId']."'
										AND paciente_id='".$_REQUEST['PacienteId']."'";
					$result = $dbconn->Execute($query);
					if($result->EOF)
					{
							$query = "INSERT INTO triages_proceso(
																				tipo_id_paciente,
																				paciente_id,
																				usuario_id,
																				fecha_registro)
												VALUES('".$_REQUEST['TipoId']."','".$_REQUEST['PacienteId']."',".UserGetUID().",'now()')";
							$results=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}
							$results->Close();
					}
					$result->Close();

								
					unset($_SESSION['TRIAGE']['DIAGNOSTICO']);
					unset($_SESSION['TRIAGE']['CAUSAS']);
					$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente']=$_REQUEST['TipoId'];
					$_SESSION['TRIAGE']['PACIENTE']['paciente_id']=$_REQUEST['PacienteId'];
					$_SESSION['TRIAGE']['PACIENTE']['plan_id']=$_REQUEST['Plan'];
					$_SESSION['TRIAGE']['PACIENTE']['triage_id']=$_REQUEST['Triage'];
					if(!$this->FormaClasificacionTriage()){
							return false;
					}
					return true;
    }
    /**
		* Busca los niveles del triage
    * @access public
    * @return array
    */
    function NivelesTriage()
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT * FROM niveles_triages WHERE nivel_triage_id!='0' ORDER BY indice_de_orden";
            $result = $dbconn->Execute($query);
            $datos=$result->RecordCount();

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            else{
                if($result->EOF){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "La tabla maestra 'niveles_triages' esta vacia";
                    return false;
                }
                    $i=0;
                    while (!$result->EOF) {
                        $vars[$result->fields[0]]=$result->fields[1];
                        $result->MoveNext();
                        $i++;
                    }
                }
            $result->Close();
						
            return $vars;
    }
    /**
		* Busca las descripciones de las acciones que corresponden a un nivel triage
    * @access public
    * @return string
    * @param int nivel de triage
    */
    function AccionesTriage($nivel)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT accion FROM niveles_triages WHERE nivel_triage_id=$nivel ORDER BY indice_de_orden";
            $result = $dbconn->Execute($query);
            $datos=$result->RecordCount();

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }else{
                if($result->EOF){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "La tabla maestra 'niveles_triages' esta vacia";
                    return false;
                }
            }
            $result->Close();
            return $result->fields[2];
    }


    /*Busca los signos vitales de un paciente
    * @access public
    * @return array
    * @param string tipo documento
    * @param int numero documento
    */
		function BuscarSignosVitales($TriageId)
		{
				if(!empty($TriageId))
				{
							list($dbconn) = GetDBconn();
							$query = "SELECT signos_vitales_fc,signos_vitales_fr,signos_vitales_temperatura,
												signos_vitales_peso,signos_vitales_taalta,signos_vitales_tabaja,fecha,triage_id,
												usuario_id,evaluacion_dolor,respuesta_motora_id,respuesta_verbal_id,
												apertura_ocular_id,tipo_glasgow,sato2
												FROM signos_vitales_triages
												WHERE triage_id='$TriageId'";
							$result = $dbconn->Execute($query);
							if(!$result->EOF)
							{
										$datos=$result->RecordCount();

										if ($dbconn->ErrorNo() != 0) {
												$this->error = "Error al Cargar el Modulo";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												return false;
										}
										if($datos!=0){
														$signos=$result->fields[0].'-'.$result->fields[1].'-'.$result->fields[2].'-'.$result->fields[3].'-'.$result->fields[4].'-'.$result->fields[5].'-'.$result->fields[9].'-'.$result->fields[10].'-'.$result->fields[11].'-'.$result->fields[12].'-'.$result->fields[14];
										}
							}
							$result->Close();
				}
				return $signos;
		}


		/**
		*
		*/
		function EliminarDiagnostico()
		{
				unset($_SESSION['TRIAGE']['DIAGNOSTICO'][$_REQUEST['codigoED']]);
				//$this->BOOKMARK_DIAGNOSTICO = true;
				$_REQUEST=$_REQUEST['dat'];

				$this->frmError["MensajeError"]="El Diagnostico fue Eliminado..";

				if(!$this->FormaClasificacionTriage($_REQUEST['MotivoConsulta'],$_REQUEST['ObservacionesMedico'],$_REQUEST['fc'],$_REQUEST['fr'],$_REQUEST['Te'],$_REQUEST['Peso'],$_REQUEST['taAlta'],$_REQUEST['taBaja'],'')){
						return false;
				}
				return true;
		}

		/**
		*
		*/
		function LlamarBuscarDiagnostico()
		{
						//$this->BOOKMARK_DIAGNOSTICO = true;
						$diag=$this->BusquedaDiagnostico($_REQUEST['codigoDiag'],$_REQUEST['descripcionDiag']);
						if(!$this->FormaClasificacionTriage($_REQUEST['MotivoConsulta'],$_REQUEST['ObservacionesMedico'],$_REQUEST['fc'],$_REQUEST['fr'],$_REQUEST['Te'],$_REQUEST['Peso'],$_REQUEST['taAlta'],$_REQUEST['taBaja'],$diag)){
								return false;
						}
						return true;
		}

    /**
  * Inserta los datos del triage de un paciente
    * @access public
    * @return boolean
    */
    function InsertarDatosTriage()
    {
          $MotivoConsulta=$_REQUEST['MotivoConsulta'];
          $seleccionTriage=$_REQUEST['seleccion'];
          $nivelTotal=$_REQUEST['nivel'];
          $observacion=$_REQUEST['ObservacionesMedico'];
          $fechaSistema=date("Y-m-d H:i:s");
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $Bandera=$_REQUEST['Bandera'];
          $fc=$_REQUEST['frecuenciaCardiaca'];
          $fr=$_REQUEST['frecuenciaRespiratoria'];
          $temperatura=$_REQUEST['temperatura'];
          $peso=$_REQUEST['peso'];
          $tAlta=$_REQUEST['taAlta'];
          $tBaja=$_REQUEST['taBaja'];
     
          $empresa=$_SESSION['TRIAGE']['PUNTO']['EMPRESA'];
          $cu=$_SESSION['TRIAGE']['PUNTO']['CENTROUTILIDAD'];
          $dpto=$_SESSION['TRIAGE']['PUNTO']['DPTO'];
          $ptoT=$_SESSION['TRIAGE']['PUNTO']['PTOTRIAGE'];
          if(empty($ptoT)){ $ptoT='NULL'; }
     
          unset($_SESSION['TRIAGE']['CAUSAS']);
          foreach($_REQUEST as $k => $v)
          {
                    if(substr_count($k,'seleccion'))
                    {		$_SESSION['TRIAGE']['CAUSAS'][$v]=$v;   }
          }
     
          if($_REQUEST['GuardarDiag'])
          {
                    foreach($_REQUEST as $k => $v)
                    {
                              if(substr_count($k,'diag'))
                              {
                                        //0 dig 1 nombre
                                        $var=explode('||',$v);
                                        $_SESSION['TRIAGE']['DIAGNOSTICO'][$var[0]][$var[1]]=$var[0];
                              }
                    }
     
                    //$this->BOOKMARK_DIAGNOSTICO = true;
                    if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja,$diag)){
                              return false;
                    }
                    return true;
          }
     
          if($_REQUEST['GuardarCausas'])
          {
                    foreach($_REQUEST as $k => $v)
                    {
                              if(substr_count($k,'caupro'))
                              {
                                        $_SESSION['TRIAGE']['CAUSAS'][$v]=$v;
                              }
                    }
     
                    if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja,$diag)){
                              return false;
                    }
                    return true;
          }
     
          if(!empty($_REQUEST['Diagnostico']))
          {
                    $diag=$this->BusquedaDiagnostico($_REQUEST['codigoDiag'],$_REQUEST['descripcionDiag']);
                    //$this->BOOKMARK_DIAGNOSTICO = true;
                    if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja,$diag,$causas)){
                              return false;
                    }
                    return true;
          }
     
          if(!empty($_REQUEST['CausasPro']))
          {
                    $causas=$this->BusquedaCausas($_REQUEST['nivelcausa'],$_REQUEST['signo'],$_REQUEST['causa']);
                    if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja,$diag,$causas)){
                              return false;
                    }
                    return true;
          }
     
          if(!$MotivoConsulta){
                              $this->frmErrorF["MotivoConsulta"]=1;
                              $this->frmErrorF["MensajeError"]="Debe escribir el Motivo de la Consulta.";
                              if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja)){
                                                  return false;
                              }
                              return true;
          }
     
          $Diag=ModuloGetVar('app','Triage','DiagnosticoObligatorio');
          if($Diag==1 AND empty($_SESSION['TRIAGE']['DIAGNOSTICO']))
          {
                         $this->frmErrorF["diagnostico"]==1;
                         $this->frmErrorF["MensajeError"]="DEBE ELEGIR EL DIAGNOSTICO.";
                         if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja)){
                                             return false;
                         }
                         return true;
          }
     
          $DiagDerivar=ModuloGetVar('app','Triage','DiagnosticoDerivarNivelObligatorio');
          if(empty($_SESSION['TRIAGE']['DIAGNOSTICO']) AND $_REQUEST['Punto']=='N' AND $DiagDerivar==1)
          {
                         //$this->BOOKMARK_DIAGNOSTICO = true;
                         $this->frmErrorF["diagnostico"]==1;
                         $this->frmErrorF["MensajeError"]="DEBE ELEGIR EL DIAGNOSTICO PARA LA REMISION.";
                         if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja)){
                                             return false;
                         }
                         return true;
          }
     
          //valida signos vitales
          $val = $this->ValidarSignosVitales($fc,$fr,$tAlta,$tBaja,$temperatura,$peso,$_REQUEST['ocular'],$_REQUEST['verbal'],$_REQUEST['motora'],$_REQUEST['sato']);
          if(empty($val))
          {
                         if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja)){
                                             return false;
                         }
                         return true;
          }
     
          $elementos=count($seleccionTriage);
     
          $CausasObli=ModuloGetVar('app','Triage','CausasProbablesObligatorias');
          if($CausasObli==1)
          {
                    $f=0;
                    foreach($_REQUEST as $k => $v)
                    {
                              if(substr_count($k,'seleccion'))
                              {  $f++;		}
                    }
                    if($f==0){
                    $this->frmError["MensajeError"]="Debe elegir Causas Probables.";
                         if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja)){
                                             return false;
                         }
                         return true;
                    }
          }
     
          if(!$nivelTotal){
                         $this->frmErrorF2["MensajeError"]="Debe elegir un Nivel de Calsificación Triage.";
                         if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja)){
                                             return false;
                         }
                         return true;
          }
     
          if(!empty($_SESSION['TRIAGE']['ATENCION']['PENDIENTE']))
          {
                    if(empty($_REQUEST['admitir']))
                    {
                         $this->frmErrorF["admitir"]==1;
                         $this->frmErrorF["MensajeError"]="Debe elegir la Acción a Seguir con el Paciente.";
                         $this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja);
                         return true;
                    }
          }
     
          if($_REQUEST['Punto']==-1)
          {
                                   $this->frmError["Punto"]=1;
                                   $this->frmError["MensajeError"]="Elija El Punto de Admisión.";
                                   if(!$this->FormaClasificacionTriage($MotivoConsulta,$Observaciones,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja)){
                                                       return false;
                                   }
                                   return true;
          }
     
          if(empty($nivelTotal)){  $nivelTotal=0; }
     
          $sw=0;
          $new='';
          if($_REQUEST['Punto']=='N')
          {
               $sw=1;  $pto='NULL';
               $new='';
          }
          else
          {
               $pto=$_REQUEST['Punto'];
               $new=",punto_admision_id=".$_REQUEST['Punto']."";
          }
     
          $TriageId=$_SESSION['TRIAGE']['PACIENTE']['triage_id'];
     
          if(empty($peso))
          {  $peso='NULL';  }
          else
          {  $peso="'".$peso."'";  }
     
          if(empty($temperatura))
          {  $temperatura='NULL';  }
          else
          {  $temperatura="'".$temperatura."'";  }
     
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          //triage activo
     
          if(empty($TriageId))
          {
                    unset($_SESSION['TRIAGE']['PACIENTE']['triage_id']);
                    $query="SELECT nextval('triages_triage_id_seq')";
                    $result=$dbconn->Execute($query);
                    $TriageId=$result->fields[0];
                    $_SESSION['TRIAGE']['PACIENTE']['triage_id']=$TriageId;
     
                    $query = "INSERT INTO triages (
                                                       triage_id,
                                                       hora_llegada,
                                                       tipo_id_paciente,
                                                       paciente_id,
                                                       plan_id,
                                                       nivel_triage_id,
                                                       observacion_medico,
                                                       observacion_enfermera,
                                                       motivo_consulta,
                                                       usuario_id,
                                                       empresa_id,
                                                       centro_utilidad,
                                                       punto_admision_id,
                                                       departamento,
                                                       autorizacion_int,
                                                       autorizacion_ext,
                                                       tipo_afiliado_id,
                                                       rango,
                                                       semanas_cotizadas,
                                                       sw_estado,
                                                       sw_no_atender,
                                                       punto_triage_id,
                                                       impresion_diagnostica,
                                                       usuario_clasificacion,
                                                       fecha_clasificacion)
                    VALUES ($TriageId,'now()','".$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente']."','".$_SESSION['TRIAGE']['PACIENTE']['paciente_id']."','".$_SESSION['TRIAGE']['PACIENTE']['plan_id']."','$nivelTotal','$ObservacionM','$ObservacionE','$MotivoConsulta',".UserGetUID().",'$empresa','$cu',$pto,'$dpto',NULL,NULL,NULL,NULL,0,'1',$sw,$ptoT,'".$_REQUEST['impresionDiag']."',".UserGetUID().",'now()')";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar en triages1";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                    }
               }
               else
               {
                         $query = "UPDATE triages SET observacion_medico='$observacion',
                                   nivel_triage_id='$nivelTotal',
                                   motivo_consulta='$MotivoConsulta',
                                   sw_estado='1',
                                   sw_no_atender=$sw,
                                   impresion_diagnostica='".$_REQUEST['impresionDiag']."',
                                   usuario_clasificacion=".UserGetUID().",
                                   fecha_clasificacion='now()'
                                   $new
                                   WHERE triage_id='$TriageId'";
                         $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0) {
                              $this->error = "Error al Guardar en la Base de Datos";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
               }
     
               foreach($_SESSION['TRIAGE']['DIAGNOSTICO'] as $k => $v)
               {
                    $query = "INSERT INTO triages_diagnosticos (
                                             triage_id,
                                             diagnostico_id)
                              VALUES ($TriageId,'$k')";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                              $this->error = "Error INSERT INTO remisiones_pacientes_diagnosticos ";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                    }
               }
     
               if($_REQUEST['eva'] === '0')
               {  $_REQUEST['eva'] = 0;  }
               elseif($_REQUEST['eva'] > 0)
               {  $_REQUEST['eva'] = $_REQUEST['eva'];  }
               else
               {  $_REQUEST['eva']='NULL';  }
     
               if(empty($_REQUEST['sato']))
               {  $_REQUEST['sato']='NULL';  }
               else
               {  $_REQUEST['sato']="'".$_REQUEST['sato']."'";  }
     
               if(empty($tBaja) OR empty($tAlta))
               {
                    $tAlta='NULL';
                    $tBaja='NULL';
               }
     
               if(empty($fc))
               { $fc = 'NULL'; }
               
               if(empty($fr))
               { $fr = 'NULL'; }
               
               if($Bandera=='0')
               {
                    $query = "INSERT INTO signos_vitales_triages(signos_vitales_fc,
                                                                 signos_vitales_fr,
                                                                 signos_vitales_temperatura,
                                                                 signos_vitales_peso,
                                                                 signos_vitales_taalta,
                                                                 signos_vitales_tabaja,
                                                                 fecha,
                                                                 triage_id,
                                                                 usuario_id,
                                                                 evaluacion_dolor,
                                                                 respuesta_motora_id,
                                                                 respuesta_verbal_id,
                                                                 apertura_ocular_id,
                                                                 tipo_glasgow,
                                                                 sato2)
                                   VALUES (".$fc.",".$fr.",$temperatura,$peso,$tAlta,$tBaja,'$fechaSistema','$TriageId',".UserGetUID().",".$_REQUEST['eva'].",'".$_REQUEST['motora']."','".$_REQUEST['verbal']."','".$_REQUEST['ocular']."','".$_REQUEST['niño']."',".$_REQUEST['sato'].")";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
               else
               {
                    $query = "UPDATE signos_vitales_triages SET
                                                            signos_vitales_fc='$fc',
                                                            signos_vitales_fr='$fr',
                                                            signos_vitales_temperatura=$temperatura,
                                                            signos_vitales_peso=$peso,
                                                            signos_vitales_taalta=$tAlta,
                                                            signos_vitales_tabaja=$tBaja,
                                                            evaluacion_dolor=".$_REQUEST['eva'].",
                                                            fecha='now()',
                                                            usuario_id=".UserGetUID().",
                                                            respuesta_motora_id='".$_REQUEST['motora']."',
                                                            respuesta_verbal_id='".$_REQUEST['verbal']."',
                                                            apertura_ocular_id='".$_REQUEST['ocular']."',
                                                            tipo_glasgow='".$_REQUEST['niño']."',
                                                            sato2=".$_REQUEST['sato']."
                              WHERE triage_id='$TriageId'";
                         $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0) {
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
     
               if(!empty($_SESSION['TRIAGE']['PACIENTE']['REMISION']))
               {
                         $query = "UPDATE pacientes_remitidos SET triage_id=$TriageId
                                                  WHERE paciente_remitido_id=".$_SESSION['TRIAGE']['PACIENTE']['REMISION']."";
                         $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0) {
                                             $this->error = "Error al Guardar en triages";
                                             $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                             $dbconn->RollbackTrans();
                                             return false;
                         }
               }
     
               foreach($_REQUEST as $k => $v)
               {
                    if(substr_count($k,'seleccion'))
                    {
                         $query = "SELECT * FROM causas_probables WHERE causa_probable_id=$v";
                         $result=$dbconn->Execute($query);
                         $nivelTriage=$result->fields[2];
                         $sintomaTriage=$result->fields[3];
     
                         if ($dbconn->ErrorNo() != 0) {
                                             $this->error = "Error SELECT * FROM causas_probables";
                                             $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                             return false;
                         }else{
     
                                   if($result->EOF){
                                             $this->error = "Error SELECT * FROM causas_probables";
                                             $this->mensajeDeError = "La tabla maestra 'causas_probables' esta vacia ";
                                             return false;
                                   }
                         }
                         $query = "INSERT INTO chequeo_triages ( fecha_registro,
                                                                 triage_id,
                                                                 nivel_triage_id,
                                                                 signo_sintoma_id,
                                                                 causa_probable_id,
                                                                 usuario_id
                                                                 )
                                                       VALUES ('now()','$TriageId','$nivelTriage','$sintomaTriage','$v',".UserGetUID().")";
                         $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0) {
                              $this->error = "Error INSERT INTO chequeo_triages";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                    }
               }
     
               $dbconn->CommitTrans();
               //NO ATENDER
               if($sw==1)
               {
                    $_SESSION['REMISIONES']['DATOS']['paciente_id']=$_REQUEST['PacienteId'];
                    $_SESSION['REMISIONES']['DATOS']['tipo_id_paciente']=$_REQUEST['TipoId'];
                    $_SESSION['REMISIONES']['DATOS']['triage_id']=$TriageId;
                    $_SESSION['REMISIONES']['RETORNO']['contenedor']='app';
                    $_SESSION['REMISIONES']['RETORNO']['modulo']='Triage';
                    $_SESSION['REMISIONES']['RETORNO']['tipo']='user';
                    $_SESSION['REMISIONES']['RETORNO']['metodo']='RetornoRemision';
                    $_SESSION['REMISIONES']['RETORNO']['argumentos']=array();
                    $this->ReturnMetodoExterno('app','Remisiones','user','main');
                    return true;
               }
     
               if(!empty($_SESSION['TRIAGE']['ATENCION']['PENDIENTE']))
               {
                    $this->DefinirAdmision($_REQUEST['admitir']);
                    return true;
               }
     
               unset($_SESSION['TRIAGE']['PACIENTE']['REMISION']);
               //se asigno a un pto admision
               if($sw==0)
               {
                    if(!empty($_SESSION['TRIAGE']['ATENCION']))
                    {	//CUANDO LO LLAMA JAIME
                         $contenedor=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['contenedor'];
                         $modulo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['modulo'];
                         $tipo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['tipo'];
                         $metodo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['metodo'];
                         $argumentos=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['argumentos'];
                         $accion=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
                         $this->FormaMensaje($mesanje,'INGRESAR TRIAGE',$accion,$boton);
                         //$this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
                         return true;
                    }
     
                    //aqui
                    $Nombre=$this->NombrePaciente($_REQUEST['TipoId'],$_REQUEST['PacienteId']);
                    $nombre=$Nombre[primer_nombre]." ".$Nombre[segundo_nombre]." ".$Nombre[primer_apellido]." ".$Nombre[segundo_apellido];
                    $vector=array('triage_id'=>$_SESSION['TRIAGE']['PACIENTE']['triage_id'],'empresa'=>$_SESSION['TRIAGE']['NOMEMPRESA'],'nombre'=>$nombre);
                    $mensaje='La Clasificacion del Triage se Realizo Correctamente.';
                    $accion=ModuloGetURL('app','Triage','user','FormaMenuTriage');
                    $this->FormaImprimir($vector,'Admisiones','triage',$accion,$mensaje);
                    //$this->FormaMensaje($mensaje,'INGRESAR TRIAGE',$accion,$boton);
                    return true;
               }
               else
               {
                    //no atender
                    unset($_SESSION['TRIAGE']['ESTACION']);
                    $mesanje='La Clasificacion del Triage se Realizo Correctamente.';
                    if(!empty($_SESSION['TRIAGE']['ATENCION']))
                    {	//CUANDO LO LLAMA JAIME
                         $contenedor=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['contenedor'];
                         $modulo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['modulo'];
                         $tipo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['tipo'];
                         $metodo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['metodo'];
                         $argumentos=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['argumentos'];
                         $accion=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
                         $this->FormaMensaje($mesanje,'INGRESAR TRIAGE',$accion,$boton);
                         //$this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
                         return true;
                    }
     
                    //aqui
                    $Nombre=$this->NombrePaciente($_REQUEST['TipoId'],$_REQUEST['PacienteId']);
                    $nombre=$Nombre[primer_nombre]." ".$Nombre[segundo_nombre]." ".$Nombre[primer_apellido]." ".$Nombre[segundo_apellido];
                    $vector=array('triage_id'=>$_SESSION['TRIAGE']['PACIENTE']['triage_id'],'empresa'=>$_SESSION['TRIAGE']['NOMEMPRESA'],'nombre'=>$nombre);
                    $mensaje='La Clasificacion del Triage se Realizo Correctamente.';
                    $accion=ModuloGetURL('app','Triage','user','FormaMenuTriage');
                    $this->FormaImprimir($vector,'Admisiones','triage',$accion,$mensaje);
                    //$this->FormaMensaje($mensaje,'INGRESAR TRIAGE',$accion,$boton);
                    return true;
               }
          }


		/**
		*
		*/
		function BuscarDiagnosticoTriage($triage)
		{
				list($dbconn) = GetDBconn();
				$query = " SELECT a.diagnostico_id, b.diagnostico_nombre
									FROM triages_diagnosticos as a, diagnosticos as b
									WHERE a.triage_id=$triage and a.diagnostico_id=b.diagnostico_id";
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
		function BusquedaCausas($nivel,$signo,$causa)
		{
					list($dbconn) = GetDBconn();
					$signo = strtoupper($signo);
					$causa = strtoupper($causa);

					$busqueda1 = '';
					$busqueda2 = '';
					$busqueda3 = '';

					if ($nivel != -1)
					{  $busqueda1 =" AND a.nivel_triage_id='$nivel'";  }

					if ($signo != '')
					{  $busqueda2 ="AND upper(b.descripcion) LIKE '%$signo%'";  }

					if ($causa != '')
					{  $busqueda3 =" AND upper(c.descripcion) LIKE '%$causa%'";  }

					$query = "SELECT a.nivel_triage_id,a.descripcion as desnivel, a.accion,
										b.signo_sintoma_id, b.descripcion as dessigno,
										c.causa_probable_id, c.descripcion as descausa
										FROM niveles_triages as a, signos_sintomas as b, causas_probables as c
										WHERE a.nivel_triage_id=b.nivel_triage_id
										and b.signo_sintoma_id=c.signo_sintoma_id
										$busqueda3 $busqueda1 $busqueda2
										ORDER BY a.indice_de_orden, b.indice_de_orden, c.indice_de_orden";
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

					return $var;
		}

		/**
		*
		*/
		function BusquedaDiagnostico($codigo,$descripcion)
		{
					list($dbconn) = GetDBconn();
					$codigo =STRTOUPPER($codigo);
					$descripcion =STRTOUPPER($descripcion);

					$busqueda1 = '';
					$busqueda2 = '';

					if ($codigo != '')
					{  $busqueda1 =" AND diagnostico_id LIKE '$codigo%'";  }

					if ($descripcion != '')
					{  $busqueda2 ="AND diagnostico_nombre LIKE '%$descripcion%'";  }

					if(empty($_REQUEST['conteo']))
					{
							$query = "SELECT count(*) FROM diagnosticos
												WHERE diagnostico_id is not null
												$busqueda1 $busqueda2";
							$resulta = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
									$this->error = "Error al Cargar el Modulo";
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

					$query = "SELECT * FROM diagnosticos
										WHERE diagnostico_id is not null
										$busqueda1 $busqueda2
										order by nivel LIMIT ".$this->limit." OFFSET $Of;";
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
									return false;
					}

					return $var;
		}


		/**
		*
		*/
		function BuscarPacientesPendientes($triage)
		{
				list($dbconn) = GetDBconn();
				$query = "select * from triages_pendientes_admitir where triage_id=$triage";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$var=$resulta->GetRowAssoc($ToUpper = false);
				return $var;
		}

    /**
  * Inserta los datos del triage de un paciente
    * @access public
    * @return boolean
    */
    function ActualizarDatosTriage()
    {
				$MotivoConsulta=$_REQUEST['MotivoConsulta'];
				$seleccionTriage=$_REQUEST['seleccion'];
				$nivelTotal=$_REQUEST['nivel'];
				$observacion=$_REQUEST['ObservacionesMedico'];
				$TipoId=$_REQUEST['TipoId'];
				$PacienteId=$_REQUEST['PacienteId'];
				$Bandera=$_REQUEST['Bandera'];
				$fc=$_REQUEST['frecuenciaCardiaca'];
				$fr=$_REQUEST['frecuenciaRespiratoria'];
				$temperatura=$_REQUEST['temperatura'];
				$peso=$_REQUEST['Peso'];
				$tAlta=$_REQUEST['taAlta'];
				$tBaja=$_REQUEST['taBaja'];
				$TriageId=$_SESSION['TRIAGE']['PACIENTE']['triage_id'];

				unset($_SESSION['TRIAGE']['CAUSAS']);
				foreach($_REQUEST as $k => $v)
				{
						if(substr_count($k,'seleccion'))
						{		$_SESSION['TRIAGE']['CAUSAS'][$v]=$v;   }
				}

				if($_REQUEST['GuardarDiag'])
				{
						foreach($_REQUEST as $k => $v)
						{
								if(substr_count($k,'diag'))
								{
										//0 dig 1 nombre
										$var=explode('||',$v);
										$_SESSION['TRIAGE']['DIAGNOSTICO'][$var[0]][$var[1]]=$var[0];
								}
						}

						//$this->BOOKMARK_DIAGNOSTICO=true;
						if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja,$diag)){
								return false;
						}
						return true;
				}

				if($_REQUEST['GuardarCausas'])
				{
						foreach($_REQUEST as $k => $v)
						{
								if(substr_count($k,'caupro'))
								{
										$_SESSION['TRIAGE']['CAUSAS'][$v]=$v;
								}
						}

						if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja,$diag)){
								return false;
						}
						return true;
				}

				if(!empty($_REQUEST['Diagnostico']))
				{
						//$this->BOOKMARK_DIAGNOSTICO=true;
						$diag=$this->BusquedaDiagnostico($_REQUEST['codigoDiag'],$_REQUEST['descripcionDiag']);
						if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja,$diag)){
								return false;
						}
						return true;
				}


				if(!empty($_REQUEST['CausasPro']))
				{
						$causas=$this->BusquedaCausas($_REQUEST['nivelcausa'],$_REQUEST['signo'],$_REQUEST['causa']);
						if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja,$diag,$causas)){
								return false;
						}
						return true;
				}				

				if(!$MotivoConsulta){
								$this->frmErrorF["MotivoConsulta"]=1;
								$this->frmErrorF["MensajeError"]="7777Debe escribir el Motivo de la Consulta.";
								if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja)){
												return false;
								}
								return true;
				}

				$Diag=ModuloGetVar('app','Triage','DiagnosticoObligatorio');
 				if($Diag==1 AND empty($_SESSION['TRIAGE']['DIAGNOSTICO']))
				{
							$this->frmErrorF["diagnostico"]==1;
							$this->frmErrorF["MensajeError"]="DEBE ELEGIR EL DIAGNOSTICO.";
							if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja)){
											return false;
							}
							return true;
				}

				$DiagDerivar=ModuloGetVar('app','Triage','DiagnosticoDerivarNivelObligatorio');
 				if(empty($_SESSION['TRIAGE']['DIAGNOSTICO']) AND $_REQUEST['Punto']=='N' AND $DiagDerivar==1)
				{
							$this->frmErrorF["diagnostico"]==1;
							$this->frmErrorF["MensajeError"]="DEBE ELEGIR EL DIAGNOSTICO PARA LA REMISION.";
							//$this->BOOKMARK_DIAGNOSTICO=true;
							if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja)){
											return false;
							}
							return true;
				}
				//valida signos vitales
				$val = $this->ValidarSignosVitales($fc,$fr,$tAlta,$tBaja,$temperatura,$peso,$_REQUEST['ocular'],$_REQUEST['verbal'],$_REQUEST['motora'],$_REQUEST['sato']);
				if(empty($val))
				{
							if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja)){
											return false;
							}
							return true;
				}

					$CausasObli=ModuloGetVar('app','Triage','CausasProbablesObligatorias');
					if($CausasObli==1)
					{
							$f=0;
							foreach($_REQUEST as $k => $v)
							{
									if(substr_count($k,'seleccion'))
									{  $f++;		}
							}

							if($f==0){
											$this->frmError["MensajeError"]="Debe elegir Causas Probables.";
											if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja)){
															return false;
											}
											return true;
							}
					}

				if(!$nivelTotal){
							$this->frmErrorF2["MensajeError"]="Debe elegir un Nivel de Calsificación Triage.";
							if(!$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja)){
											return false;
							}
							return true;
				}

				if(!empty($_SESSION['TRIAGE']['ATENCION']['PENDIENTE']))
				{
						if(empty($_REQUEST['admitir']))
						{
							$this->frmError["admitir"]=1;
							$this->frmError["MensajeError"]="Debe elegir la Acción a Seguir con el Paciente.";
							$this->FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja);
							return true;
						}
				}

				if($_REQUEST['Punto']==-1)
				{
									$this->frmError["Punto"]=1;
									$this->frmError["MensajeError"]="Elija El Punto de Admisión.";
									if(!$this->FormaSignosVitalesTriage($MotivoConsulta,$ObservacionesEnfermera,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja)){
													return false;
									}
									return true;
				}

				$sw=0;
				if($_REQUEST['Punto']=='N') {  $sw=1;  }

				$cod=$_REQUEST['codigo'];
				if(empty($_REQUEST['codigo']))
				{   $cod='NULL';  }
				else
				{   $cod="'$cod'";  }

				if(empty($peso))
				{  $peso='NULL';  }
				else
				{  $peso="'".$peso."'";  }

				if(empty($temperatura))
				{  $temperatura='NULL';  }
				else
				{  $temperatura="'".$temperatura."'";  }

				if($_REQUEST['eva'] === '0')
				{  $_REQUEST['eva'] = 0;  }
				elseif($_REQUEST['eva'] > 0)
				{  $_REQUEST['eva'] = $_REQUEST['eva'];  }
				else
				{  $_REQUEST['eva']='NULL';  }

				if(empty($_REQUEST['sato']))
				{  $_REQUEST['sato']='NULL';  }
				else
				{  $_REQUEST['sato']="'".$_REQUEST['sato']."'";  }

				if(empty($tBaja) OR empty($tAlta))
				{
					$tAlta='NULL';
					$tBaja='NULL';
				}

				list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();

				$query = "UPDATE triages SET observacion_medico='$observacion',nivel_triage_id='$nivelTotal',
							motivo_consulta='$MotivoConsulta', sw_estado='1', sw_no_atender=$sw,
							usuario_clasificacion=".UserGetUID()."
							WHERE triage_id='$TriageId'";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}

					if($Bandera=='0')
					{//no hay signos vitales
										$query = "INSERT INTO signos_vitales_triages ( signos_vitales_fc,
																																		signos_vitales_fr,
																																		signos_vitales_temperatura,
																																		signos_vitales_peso,
																																		signos_vitales_taalta,
																																		signos_vitales_tabaja,
																																		fecha,
																																		triage_id,
																																		usuario_id,
																																		evaluacion_dolor,
																																		respuesta_motora_id,
																																		respuesta_verbal_id,
																																		apertura_ocular_id,
																																		tipo_glasgow,
																																		sato2)
															VALUES ('$fc','$fr',$temperatura,$peso,$tAlta,$tBaja,'$fechaSistema','$TriageId','$usuario',".$_REQUEST['eva'].",'".$_REQUEST['motora']."','".$_REQUEST['verbal']."','".$_REQUEST['ocular']."','".$_REQUEST['niño']."',".$_REQUEST['sato'].")";
										$dbconn->Execute($query);

											if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error INSERT INTO signos_vitales_triages";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
								}
					}
					else
					{
										$query = "UPDATE signos_vitales_triages SET
																			signos_vitales_fc='$fc',
																			signos_vitales_fr='$fr',
																			signos_vitales_temperatura=$temperatura,
																			signos_vitales_peso=$peso,
																			signos_vitales_taalta=$tAlta,
																			signos_vitales_tabaja=$tBaja,
																			evaluacion_dolor=".$_REQUEST['eva'].",
																			fecha='now()',
																			usuario_id=".UserGetUID().",
																			respuesta_motora_id='".$_REQUEST['motora']."',
																			respuesta_verbal_id='".$_REQUEST['verbal']."',
																			apertura_ocular_id='".$_REQUEST['ocular']."',
																			tipo_glasgow='".$_REQUEST['niño']."',
																			sato2=".$_REQUEST['sato']."
															WHERE triage_id='$TriageId'";
										$dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0) {
											$this->error = "ErrorUPDATE signos_vitales_triages";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
								}
					}

					foreach($_SESSION['TRIAGE']['DIAGNOSTICO'] as $k => $v)
					{
								$query = "INSERT INTO triages_diagnosticos (
															triage_id,
															diagnostico_id)
													VALUES ($TriageId,'$k')";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error INSERT INTO remisiones_pacientes_diagnosticos ";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
								}
					}

					foreach($_REQUEST as $k => $v)
					{
							if(substr_count($k,'seleccion'))
							{
									$query = "SELECT * FROM causas_probables WHERE causa_probable_id=$v";
									$result=$dbconn->Execute($query);
									$nivelTriage=$result->fields[2];
									$sintomaTriage=$result->fields[3];

									if ($dbconn->ErrorNo() != 0) {
													$this->error = "Error SELECT * FROM causas_probables";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													return false;
									}else{

											if($result->EOF){
													$this->error = "Error SELECT * FROM causas_probables";
													$this->mensajeDeError = "La tabla maestra 'causas_probables' esta vacia ";
													return false;
											}
									}
									$query = "INSERT INTO chequeo_triages( fecha_registro,
																												triage_id,
																												nivel_triage_id,
																												signo_sintoma_id,
																												causa_probable_id,
																												usuario_id
																										)
																	VALUES ('now()','$TriageId','$nivelTriage','$sintomaTriage','$v',".UserGetUID().")";
									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error INSERT INTO chequeo_triages";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
									}
							}
					}

					$dbconn->CommitTrans();

					if(!empty($_SESSION['TRIAGE']['PUNTO']['NOATENDER']) AND $_REQUEST['Punto']=='N')
					{
								$_SESSION['REMISIONES']['DATOS']['paciente_id']=$PacienteId;
								$_SESSION['REMISIONES']['DATOS']['tipo_id_paciente']=$TipoId;
								$_SESSION['REMISIONES']['DATOS']['triage_id']=$TriageId;
								$_SESSION['REMISIONES']['RETORNO']['contenedor']='app';
								$_SESSION['REMISIONES']['RETORNO']['modulo']='Triage';
								$_SESSION['REMISIONES']['RETORNO']['tipo']='user';
								$_SESSION['REMISIONES']['RETORNO']['metodo']='RetornoRemision';
								$_SESSION['REMISIONES']['RETORNO']['argumentos']=array();

								$this->ReturnMetodoExterno('app','Remisiones','user','main');
								return true;
					}
					if(!empty($_SESSION['TRIAGE']['ATENCION']['PENDIENTE']))
					{
							$query = "UPDATE triages SET sw_estado='6' WHERE triage_id='$TriageId'";
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Base de Datos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}

							$this->DefinirAdmision($TriageId,$_REQUEST['admitir']);
							return true;
					}

					if(!empty($_SESSION['TRIAGE']['ATENCION']) OR !empty($_SESSION['TRIAGE']['PUNTO']['NOATENDER']))
					{			//CUANDO LO LLAMA JAIME
								$query = "UPDATE triages SET punto_admision_id=".$_REQUEST['Punto'].",
															sw_estado=1
															WHERE triage_id='$TriageId'";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar en la Base de Datos";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
								}

								$contenedor=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['contenedor'];
								$modulo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['modulo'];
								$tipo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['tipo'];
								$metodo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['metodo'];
								$argumentos=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['argumentos'];
								$_SESSION['RETORNO']['TRIAGE']['ATENCION']=true;
								$this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
								return true;
					}

					//aqui
					$nombre=$Nombre[primer_nombre]." ".$Nombre[segundo_nombre]." ".$Nombre[primer_apellido]." ".$Nombre[segundo_apellido];
					$vector=array('triage_id'=>$_SESSION['TRIAGE']['PACIENTE']['triage_id'],'empresa'=>$_SESSION['TRIAGE']['NOMEMPRESA'],'nombre'=>$nombre);
					$mensaje='La Clasificacion del Triage se Realizo Correctamente.';
					$accion=ModuloGetURL('app','Triage','user','FormaMenuTriage');
					$this->FormaImprimir($vector,'Admisiones','triage',$accion,$mensaje);
					//$this->FormaMensaje($mesanje,'INGRESAR TRIAGE',$accion,$boton);
					return true;
    }

	/**
	*
	*/
	function DatosTriage($triage)
	{
				list($dbconn) = GetDBconn();
				$query = "SELECT empresa_id,nivel_triage_id, motivo_consulta, observacion_medico,
									punto_admision_id,tipo_id_paciente, paciente_id
									FROM triages WHERE triage_id=$triage";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				$vars=$result->GetRowAssoc($ToUpper = false);
				$result->Close();
				return $vars;
	}

	/**
	*
	*/
	function BuscarPtoAdmision($pto)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT punto_admision_id,descripcion,departamento
								FROM puntos_admisiones as a
								WHERE tipo_admision_id=(select tipo_admision_id from puntos_admisiones where punto_admision_id=$pto)
								AND punto_admision_id <> $pto";
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
    * Busca las estaciones de enfermeria
    * @access public
    * @return array
    */
    function BuscarEstacionesCambio($PtoAdmon)
    {
				list($dbconn) = GetDBconn();
				$query = "SELECT a.estacion_id, b.descripcion, b.departamento
									FROM puntos_admisiones_estaciones as a, estaciones_enfermeria as b
									WHERE a.punto_admision_id=$PtoAdmon AND a.estacion_id=b.estacion_id";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				while(!$result->EOF)
				{
						$var[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
				$result->Close();
				return $var;
    }

		/**
		*
		*/
		function RemitirPunto()
		{
				$arr=$this->BuscarEstacionesCambio($_REQUEST['punto']);
				if(sizeof($arr)==1)
				{		//solo hay una estacion para el punto
						$this->Admision($_REQUEST['punto'],$arr[0][estacion_id].",".$arr[0][departamento]);
						return true;
				}
				else
				{		//hay varias estaciones para el punto
						$this->FormaElegirEstacionCambio($arr,$_REQUEST['punto']);
						return true;
				}
		}

		/**
		*
		*/
		function RemitirEstacion()
		{
					//ya tiene el punto y eligio la estacion va a admitir
					$this->Admision($_REQUEST['punto'],$_REQUEST['estacion']);
					return true;
		}

		/**
		*
		*/
		function Admision($pto,$estacion)
		{
				$var=$this->DatosPendientesAdmitir($_SESSION['TRIAGE']['PACIENTE']['triage_id']);
				//0 id 1 departamento
				$est=explode(',',$estacion);
				$CausaExterna=ModuloGetVar('app','Triage','RipsCausaExternaDefault');

				list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();

				$query="SELECT nextval('ingresos_ingreso_seq')";
				$result=$dbconn->Execute($query);
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error ingresos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
				}
				$IngresoId=$result->fields[0];

				if(empty($var[autorizacion_int]))
				{   $var[autorizacion_int] = 1;  }

				if(empty($var[autorizacion_ext]))
				{   $var[autorizacion_ext] = 0;  }

				$query = "INSERT INTO ingresos (ingreso,
																				tipo_id_paciente,
																				paciente_id,
																				fecha_ingreso,
																				causa_externa_id,
																				via_ingreso_id,
																				comentario,
																				departamento,
																				estado,
																				fecha_registro,
																				usuario_id,
																				departamento_actual,
																				autorizacion_int,
																				autorizacion_ext)
									VALUES($IngresoId,'".$var[tipo_id_paciente]."','".$var[paciente_id]."','".$var[fecha_registro]."','$CausaExterna','".$var[via_ingreso_id]."','".$var[comentarios]."','".$est[1]."','1','now()',".$var[usuario_id].",'".$est[1]."',".$var[autorizacion_int].",".$var[autorizacion_ext].")";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error ingresos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
				}
				//si es de SOAT
				if(!empty($var[evento]))
				{
						$query = "INSERT INTO ingresos_soat( ingreso, evento)
																VALUES($IngresoId,$var[evento])";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar en la Base de Datos";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
						}

						$query = "INSERT INTO soat_consumos_internos (evento,numerodecuenta)
											VALUES($Evento,$Cuenta)";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar en la Base de Datos";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
						}
				}

				$sqls="INSERT into pacientes_urgencias(
																						ingreso,
																						estacion_id,
																						triage_id)
							VALUES($IngresoId,'$est[0]',".$_SESSION['TRIAGE']['PACIENTE']['triage_id'].")";
				$result = $dbconn->Execute($sqls);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}

				if(empty($var[autorizacion_ext]))
				{  $var[autorizacion_ext]='NULL';  }
				if(empty($var[autorizacion_int]))
				{  $var[autorizacion_int]='NULL';  }

				$query = "INSERT INTO cuentas ( empresa_id,
																				centro_utilidad,
																				ingreso,
																				plan_id,
																				estado,
																				usuario_id,
																				fecha_registro,
																				tipo_afiliado_id,
																				rango,
																				autorizacion_int,
																				autorizacion_ext,
																				semanas_cotizadas)
									VALUES('".$var[empresa_id]."','".$var[centro_utilidad]."',$IngresoId,".$var[plan_id].",1,".$var[usuario_id].",'now()','".$var[tipo_afiliado_id]."','".$var[rango]."',".$var[autorizacion_int].",".$var[autorizacion_ext].",".$var[semanas_cotizadas].")";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error cuentas";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
				}


				$query = "select paciente_remitido_id from pacientes_remitidos
											where triage_id=".$_SESSION['TRIAGE']['PACIENTE']['triage_id']."";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en triages";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
				}
				if(!$result->EOF)
				{
						$query = "UPDATE pacientes_remitidos SET ingreso=$IngresoId
											WHERE paciente_remitido_id=".$result->fields[0]."";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar en triages";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
						}
				}

				$query = "update triages set sw_estado='2' where triage_id=".$_SESSION['TRIAGE']['PACIENTE']['triage_id']."";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en triages";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
				}

				$query = "UPDATE autorizaciones SET ingreso=$IngresoId
									WHERE autorizacion=".$var[autorizacion_int]."";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Tabal autorizaiones";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
				}

				$query = "delete from triages_pendientes_admitir where triage_id=".$_SESSION['TRIAGE']['PACIENTE']['triage_id']."";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "delete autorizaciones_solicitudes_cargos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				$dbconn->CommitTrans();
				$contenedor=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['contenedor'];
				$modulo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['modulo'];
				$tipo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['tipo'];
				$metodo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['metodo'];
				$argumentos=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['argumentos'];
				$_SESSION['RETORNO']['TRIAGE']['ATENCION']=true;
				$this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
				return true;
		}


		/***
		*
		*/
		function DefinirAdmision($TriageId,$accion)
		{
				//admitir paciente a la estacion
				if($accion==1)
				{
						$this->InsertarAdmision($TriageId);
						return true;
				}
				elseif($accion==3)
				{		//cambiar punto admision
						$dat=$this->DatosTriage($_SESSION['TRIAGE']['PACIENTE']['triage_id']);
						$var=$this->BuscarPtoAdmision($dat[punto_admision_id]);
						//si solo hay un punto admision
						if(sizeof($var)==1)
						{
								$arr=$this->BuscarEstacionesCambio($dat[punto_admision_id]);
								if(sizeof($arr)==1)
								{		//solo hay una estacion para el punto
										//solo hay un pto y una estacion va a la admision
										$this->Admision($dat[punto_admision_id],$arr[0][estacion_id].",".$arr[0][departamento]);
										return true;
								}
								else
								{		//hay varias estaciones para el punto
										$this->FormaElegirEstacionCambio($arr,$dat[punto_admision_id]);
										return true;
								}
						}
						else
						{		//hay varios puntos de admision
								$this->FormaElegirPuntos($var,$dat[punto_admision_id]);
								return true;
						}
				}
				else
				{		//remitio el paciente
						$var=$this->DatosPendientesAdmitir($TriageId);
						$_SESSION['REMISIONES']['DATOS']['paciente_id']=$var[paciente_id];
						$_SESSION['REMISIONES']['DATOS']['tipo_id_paciente']=$var[tipo_id_paciente];
						$_SESSION['REMISIONES']['DATOS']['triage_id']=$TriageId;
						$_SESSION['REMISIONES']['RETORNO']['contenedor']='app';
						$_SESSION['REMISIONES']['RETORNO']['modulo']='Triage';
						$_SESSION['REMISIONES']['RETORNO']['tipo']='user';
						$_SESSION['REMISIONES']['RETORNO']['metodo']='RetornoRemision';
						$_SESSION['REMISIONES']['RETORNO']['argumentos']=array();
						//$this->FormaTiposRemision($TriageId);
						$this->ReturnMetodoExterno('app','Remisiones','user','main');
						return true;
				}
		}

		/**
		*
		*/
		function RetornoRemision()
		{
				//$arr=$_SESSION['REMISIONES']['RETORNO']['ARREGLO'];
				$this->FormaImpresionRemision($arr);
				return true;
		}

		/**
		*
		*/
		function InsertarAdmision($triage)
		{
				$var=$this->DatosPendientesAdmitir($triage);
				$CausaExterna=ModuloGetVar('app','Triage','RipsCausaExternaDefault');

				list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();

				$query="SELECT nextval('ingresos_ingreso_seq')";
				$result=$dbconn->Execute($query);
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error ingresos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
				}
				$IngresoId=$result->fields[0];

				if(empty($var[autorizacion_int]))
				{   $var[autorizacion_int] = 1;  }

				if(empty($var[autorizacion_ext]))
				{   $var[autorizacion_ext] = 0;  }

				$query = "INSERT INTO ingresos (ingreso,
																				tipo_id_paciente,
																				paciente_id,
																				fecha_ingreso,
																				causa_externa_id,
																				via_ingreso_id,
																				comentario,
																				departamento,
																				estado,
																				fecha_registro,
																				usuario_id,
																				departamento_actual,
																				autorizacion_int,
																				autorizacion_ext)
									VALUES($IngresoId,'".$var[tipo_id_paciente]."','".$var[paciente_id]."','".$var[fecha_registro]."','$CausaExterna','".$var[via_ingreso_id]."','".$var[comentarios]."','".$var[departamento]."','1','now()',".UserGetUID().",'".$var[departamento]."',".$var[autorizacion_int].",".$var[autorizacion_ext].")";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error ingresos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
				}
				//si es de SOAT
				if(!empty($var[evento]))
				{
						$query = "INSERT INTO ingresos_soat( ingreso, evento)
																VALUES($IngresoId,$var[evento])";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar en la Base de Datos";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
						}

						$query = "INSERT INTO soat_consumos_internos (evento,numerodecuenta)
											VALUES($Evento,$Cuenta)";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar en la Base de Datos";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
						}
				}

				if(empty($var[paciente_urgencia_consultorio_id]))
				{  $var[paciente_urgencia_consultorio_id]='NULL';  }
				$sqls="INSERT into pacientes_urgencias(
																						ingreso,
																						estacion_id,
																						triage_id,
																						paciente_urgencia_consultorio_id)
							VALUES($IngresoId,'".$var[estacion_id]."',$triage,".$var[paciente_urgencia_consultorio_id].")";
				$result = $dbconn->Execute($sqls);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}

				if(empty($var[autorizacion_ext]))
				{  $var[autorizacion_ext]='NULL';  }
				if(empty($var[autorizacion_int]))
				{  $var[autorizacion_int]='NULL';  }

				$query = "INSERT INTO cuentas ( empresa_id,
																				centro_utilidad,
																				ingreso,
																				plan_id,
																				estado,
																				usuario_id,
																				fecha_registro,
																				tipo_afiliado_id,
																				rango,
																				autorizacion_int,
																				autorizacion_ext,
																				semanas_cotizadas)
									VALUES('".$var[empresa_id]."','".$var[centro_utilidad]."',$IngresoId,".$var[plan_id].",1,".UserGetUID().",'now()','".$var[tipo_afiliado_id]."','".$var[rango]."',".$var[autorizacion_int].",".$var[autorizacion_ext].",".$var[semanas_cotizadas].")";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error cuentas";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
				}


				$query = "select paciente_remitido_id from pacientes_remitidos
											where triage_id=$triage";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en triages";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
				}
				if(!$result->EOF)
				{
						$query = "UPDATE pacientes_remitidos SET ingreso=$IngresoId
											WHERE paciente_remitido_id=".$result->fields[0]."";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar en triages";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
						}
				}

				$query = "update triages set sw_estado='2',ingreso=$IngresoId where triage_id=$triage";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en triages";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
				}

				$query = "UPDATE autorizaciones SET ingreso=$IngresoId
									WHERE autorizacion=".$var[autorizacion_int]."";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Tabal autorizaiones";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
				}

				$query = "delete from triages_pendientes_admitir where triage_id=$triage";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "delete autorizaciones_solicitudes_cargos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				$dbconn->CommitTrans();

				$contenedor=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['contenedor'];
				$modulo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['modulo'];
				$tipo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['tipo'];
				$metodo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['metodo'];
				$argumentos=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['argumentos'];
				$_SESSION['RETORNO']['TRIAGE']['ATENCION']=true;
				$this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
				return true;
		}


    /**
  * Busca la identificacion de un paciente teniendo el ingreso
    * @access public
    * @return array
    * @param int numero de ingreso
    */
    function BuscarIdentificacionPaciente($ingreso)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT * FROM ingresos WHERE ingreso='$ingreso'";
            $result = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else{
                    if($result->EOF){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "La tabla 'ingresos' esta vacia ";
                        return false;
                    }
                }
                $result->Close();
                $Identificacion=$result->fields[1]."-".$result->fields[2];
        return $Identificacion;
    }

    /**
  * Busca la identificacion de un paciente teniendo el ingreso
    * @access public
    * @return array
  * @param string tipo de documento
    * @param int numero del documento
    */
    function TraerDatosUsuarios($TipoId,$PacienteId)
    {
            list($dbconn) = GetDBconn();
            $TipoId;
            $PacienteId;
            $query = "SELECT tipo_id_paciente,paciente_id,primer_nombre,segundo_nombre,primer_apellido,segundo_apellido,fecha_nacimiento,sexo_id,tipo_estado_civil_id,ocupacion_id,residencia_direccion,residencia_telefono,zona_residencia,tipo_pais_id,tipo_dpto_id,tipo_mpio_id  FROM pacientes WHERE tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId'";
            $result = $dbconn->Execute($query);
            $datos=$result->RecordCount();

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }else{
                if($result->EOF){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "La tabla maestra 'pacientes' esta vacia ";
                    return false;
                }
                if($datos!=0){
                    $datosResultado=$result->fields[0]." ".$result->fields[1]." ".$result->fields[2]." ".$result->fields[3]." ".$result->fields[4]." ".$result->fields[5]." ".$result->fields[6]." ".$result->fields[7]." ".$result->fields[8]." ".$result->fields[9]." ".$result->fields[10]." ".$result->fields[11]." ".$result->fields[12]." ".$result->fields[13]." ".$result->fields[14]." ".$result->fields[15];
                }
            }
            return $datosResultado;
            $result->Close();
}


//---------------------MODIFICACION------------------------------//


 function BuscarTodasAdm()
    {
            $EmpresaId=$_SESSION['TRIAGE']['EMPRESA'];
            $CentroU=$_SESSION['TRIAGE']['CENTROUTILIDAD'];
            if($CentroU)
            { $CU="and a.centro_utilidad='$CentroU'"; }

            list($dbconn) = GetDBconn();
            $query=" select  a.primer_apellido, a.segundo_apellido, a.primer_nombre,
                            a.segundo_nombre, a.fecha_nacimiento, a.fecha_nacimiento_es_calculada,
                            a.residencia_direccion, a.residencia_telefono, a.zona_residencia,
                            a.ocupacion_id, a.sexo_id, a.tipo_estado_civil_id,
                            a.foto, a.tipo_pais_id, a.tipo_dpto_id, a.tipo_mpio_id, b.ingreso,
                            b.fecha_ingreso, b.causa_externa_id, b.via_ingreso_id, c.tipo_afiliado_id,
                            b.comentario, c.rango, c.plan_id, d.evento, a.tipo_id_paciente, a.paciente_id,
                            c.numerodecuenta, h.historia_numero, h.historia_prefijo, a.nombre_madre,
                            case b.estado when 1 then 'A' else 'I' end as estado
                            from pacientes a, ingresos b left join ingresos_soat d
                            on (b.ingreso=d.ingreso),
                            cuentas c, historias_clinicas h
                            where b.estado!=0 and
                            a.tipo_id_paciente=b.tipo_id_paciente and a.paciente_id=b.paciente_id
                            and b.ingreso=c.ingreso and a.tipo_id_paciente=h.tipo_id_paciente
                            and a.paciente_id=h.paciente_id
														and b.estado=1";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
             $vars=$this->LlenaMatriz($result);
            return $vars;
    }
    /**
  * Busca los datos de una admision segun el tipo de busqueda
    * @access public
    * @return boolean
    */
  function BuscarAdmision()
    {
//--------------------------------------
				$_REQUEST['prefijo']=strtoupper($_REQUEST['prefijo']);
				$filtroTipoDocumento = '';
				$filtroDocumento='';
				$filtroNombres='';
				$filtroIngreso='';
				$filtroCuenta='';
				$filtroPrefijo='';
				$filtroHC='';
				$filtroCama='';
				$filtroPieza='';

				if(!empty($_REQUEST[prefijo]))
				{  $filtroPrefijo="and h.historia_prefijo='".$_REQUEST[prefijo]."'";  }

				if(!empty($_REQUEST[Cama]))
				{  $filtroCama="and i.cama='".$_REQUEST[Cama]."'";  }

				if(!empty($_REQUEST[Pieza]))
				{  $filtroPieza="and j.pieza='".$_REQUEST[Pieza]."'";  }

				if(!empty($_REQUEST[historia]))
				{  $filtroHC="and h.historia_numero='".$_REQUEST[historia]."'";  }

				if($_REQUEST[Departamento]!=-1)
				{  $filtroDepto="and b.departamento='".$_REQUEST[Departamento]."'";  }

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
												$filtroNombres.=" and (upper(c.primer_nombre||' '||c.segundo_nombre||' '||
																														c.primer_apellido||' '||c.segundo_apellido) like '%".strtoupper($_REQUEST[Nombres])."%')";
										}
						}
				}
				if(!empty($_REQUEST[Ingreso]))
				{   $filtroIngreso=" AND a.ingreso =".$_REQUEST[Ingreso]."";   }

				if(!empty($_REQUEST[Cuenta]))
				{   $filtroCuenta=" AND a.numerodecuenta =".$_REQUEST[Cuenta]."";   }

				if(empty($_REQUEST['Of'])){ $_REQUEST['Of']=0; }

				list($dbconn) = GetDBconn();
				if(empty($_REQUEST['paso']))
				{
						$query = "select c.tipo_id_paciente, c.paciente_id,c.primer_apellido,
										c.segundo_apellido, c.primer_nombre, c.segundo_nombre, c.fecha_nacimiento,
										c.fecha_nacimiento_es_calculada, c.residencia_direccion, c.residencia_telefono,
										c.zona_residencia, c.ocupacion_id, c.sexo_id, c.tipo_estado_civil_id, c.foto,
										c.tipo_pais_id, c.tipo_dpto_id, c.tipo_mpio_id,  c.nombre_madre, a.numerodecuenta,b.ingreso,
										b.fecha_ingreso, b.causa_externa_id,
										b.via_ingreso_id, a.tipo_afiliado_id, b.comentario, a.rango, a.plan_id,
										h.historia_numero, h.historia_prefijo, i.cama, j.pieza
										FROM cuentas as a
										left join movimientos_habitacion as i on (a.numerodecuenta=i.numerodecuenta and i.fecha_egreso is null)
										left join camas as j on (i.cama=j.cama ),
										ingresos as b left join ingresos_soat as d on (b.ingreso=d.ingreso),
										pacientes as c, historias_clinicas h
										WHERE a.ingreso=b.ingreso and b.estado!=0
										$filtroTipoDocumento $filtroDocumento
										$filtroNombres $filtroCuenta $filtroIngreso
										$filtroHC $filtroPrefijo $filtroCama $filtroPieza
										and b.tipo_id_paciente=c.tipo_id_paciente and b.paciente_id=c.paciente_id
										and b.tipo_id_paciente=h.tipo_id_paciente and b.paciente_id=h.paciente_id";
						$result=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al buscar";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
						}
						if(!$result->EOF)
						{
								$_SESSION['SPYB']=$result->RecordCount();
						}
						$result->Close();
				}

				$query = "select a.* from(
										select c.tipo_id_paciente, c.paciente_id,c.primer_apellido,
										c.segundo_apellido, c.primer_nombre, c.segundo_nombre, c.fecha_nacimiento,
										c.fecha_nacimiento_es_calculada, c.residencia_direccion, c.residencia_telefono,
										c.zona_residencia, c.ocupacion_id, c.sexo_id, c.tipo_estado_civil_id, c.foto,
										c.tipo_pais_id, c.tipo_dpto_id, c.tipo_mpio_id,  c.nombre_madre, a.numerodecuenta,b.ingreso,
										b.fecha_ingreso, b.causa_externa_id,
										b.via_ingreso_id, a.tipo_afiliado_id, b.comentario, a.rango, a.plan_id,
										h.historia_numero, h.historia_prefijo, i.cama, j.pieza
										FROM cuentas as a
										left join movimientos_habitacion as i on (a.numerodecuenta=i.numerodecuenta and i.fecha_egreso is null)
										left join camas as j on (i.cama=j.cama ),
										ingresos as b left join ingresos_soat as d on (b.ingreso=d.ingreso),
										pacientes as c, historias_clinicas h
										WHERE a.ingreso=b.ingreso and b.estado!=0
										$filtroTipoDocumento $filtroDocumento
										$filtroNombres $filtroCuenta $filtroIngreso
										$filtroHC $filtroPrefijo $filtroCama $filtroPieza
										and b.tipo_id_paciente=c.tipo_id_paciente and b.paciente_id=c.paciente_id
										and b.tipo_id_paciente=h.tipo_id_paciente and b.paciente_id=h.paciente_id
									) as a
									order by a.numerodecuenta
									LIMIT ".$this->limit." OFFSET ".$_REQUEST['Of']."";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al buscar";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				if($result->EOF)
				{  $this->frmError["MensajeError"]="LA BUSQUEDA NO ARROJO RESULTADOS.";  }

				while(!$result->EOF)
				{
								$var[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
				}
				$this->FormaMetodoBuscar($var);
				return true;
    }


    /**
  * Llena una matriz con los datos de la admision encontrada
    * @access public
    * @return array
    * @param array resultado del query de la busqueda
    */
    function  LlenaMatriz($result)
    {
            while(!$result->EOF)
            {
                    $vars[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }
            $result->Close();
            return $vars;
    }


    /**
  * Modifica los datos de una admision
    * @access public
    * @return boolean
    */
  	function ModificarDatosAdmision()
    {
					$Ingreso=$_REQUEST['Ingreso'];
					$PlanId=$_REQUEST['Responsable'];
					$Nivel=$_REQUEST['Nivel'];
					$Poliza=$_REQUEST['Poliza'];
					$PolizaAnt=$_REQUEST['PolizaAnt'];
					$FechaIngreso=$_REQUEST['FechaIngreso'];
					$CausaExterna=$_REQUEST['CausaExterna'];
					$ViaIngreso=$_REQUEST['ViaIngreso'];
					$TipoAfiliado=$_REQUEST['TipoAfiliado'];
					$Comentarios=$_REQUEST['Comentario'];
					$PacienteId=$_REQUEST['PacienteId'];
					$TipoId=$_REQUEST['TipoId'];

					$sw=$this->BuscarSW($PlanId);
					if($sw!=1){
											if($ViaIngreso==-1 || $TipoAfiliado==-1){
															if($CausaExterna==-1){ $this->frmError["CausaExterna"]=1; }
															if($ViaIngreso==-1){ $this->frmError["ViaIngreso"]=1; }
															if($TipoAfiliado==-1){ $this->frmError["TipoAfiliado"]=1; }
															$this->frmError["MensajeError"]="Faltan datos obligatorios.";
																	if(!$this->FormaModificarAdmision($TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso)){
																			return false;
																	}
																	return true;
											}
					}
					if($sw==1){
											if($ViaIngreso==-1 || $Estado==-1 || $Poliza==''){
															if($CausaExterna==-1){ $this->frmError["CausaExterna"]=1; }
															if($ViaIngreso==-1){ $this->frmError["ViaIngreso"]=1; }
															if($Estado==-1){ $this->frmError["Estado"]=1; }
															if($Poliza==''){ $this->frmError["poliza"]=1; }
															$this->frmError["MensajeError"]="Faltan datos obligatorios.";
																	if(!$this->FormaModificarAdmision($TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso)){
																					return false;
																	}
																	return true;
											}
					}

					$f=explode('/',$FechaIngreso);
					$fec=$f[2].'-'.$f[1].'-'.$f[0];

					$validar = $this->ValidarFecha($fec);
					if(empty($validar))
					{
							if(!$this->FormaModificarAdmision($TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso)){
											return false;
							}
							return true;
					}

					list($dbconn) = GetDBconn();
					if($Poliza){
									$query = "UPDATE soat_polizas SET
																					poliza='$Poliza'
																			WHERE poliza='$PolizaAnt'";
									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
													$this->error = "Error al Guardar en la Base de Datos";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													return false;
									}
					}

					/*if(!$TipoAfiliado){  $TipoAfiliado='NULL'; }
					else{ $TipoAfiliado="'$TipoAfiliado'"; }*/
					$query = "UPDATE ingresos SET
															fecha_ingreso='$fec',
															via_ingreso_id='$ViaIngreso',
															comentario='$Comentarios'
										WHERE ingreso=$Ingreso";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error ingresos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					else
					{
								$query = "UPDATE cuentas SET
																			tipo_afiliado_id='$TipoAfiliado',
																			rango='$Nivel'
																		WHERE ingreso=$Ingreso";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
												$this->error = "Error cuentas";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												return false;
								}
								else
								{
														$mensaje='La actualización se realizó correctamente.';
														$accion=ModuloGetURL('app','Triage','user','MetodoBuscar');
														if(!$this->FormaMensaje($mensaje,'ACTUALIZACION DATOS ADMISION',$accion,$boton)){
																		return false;
														}
														return true;
								}
					}
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
            $query = "SELECT primer_nombre,segundo_nombre,fecha_nacimiento,
											primer_apellido,segundo_apellido,tipo_id_paciente, paciente_id, sexo_id
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
    * Busca los datos del ingreso del paciente
    * @access public
    * @return array
    * @param int ingreso
    */
  function BuscarDatosIngresoPaciente($Ingreso)
    {
            list($dbconn) = GetDBconn();
            $query = "select b.poliza, c.fecha_ingreso, c.causa_externa_id, c.via_ingreso_id,
                                c.comentario, d.tipo_afiliado_id
                                from (ingresos c left join ingresos_soat a on (c.ingreso=a.ingreso))
                                left join soat_eventos as b on (a.evento=b.evento), cuentas as d
                                where c.ingreso=$Ingreso and c.ingreso=d.ingreso";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $vars=$result->GetRowAssoc($ToUpper = false);
            return $vars;
    }

//-------------------------------------HOSPITALIZACION------------------------------------+

    /**
    * Busca los puntos de admision de hospitalizacion a los que tiene permiso el usuario
    * que muestra el listado de los pacientes que estan en triage
    * @access public
    * @return boolean
    */
    function Hospitalizacion()
    {
            unset($_SESSION['TRIAGE']);
            $SystemId=UserGetUID();
            unset($_SESSION['SEGURIDAD']);
            if(!empty($_SESSION['SEGURIDAD']['PTOADMISION']['HOSPITALIZACION']))
            {
                        $this->salida.= gui_theme_menu_acceso('ADMISIONES',$_SESSION['SEGURIDAD']['PTOADMISION']['HOSPITALIZACION']['arreglo'],$_SESSION['SEGURIDAD']['PTOADMISION']['HOSPITALIZACION']['hosp'],$_SESSION['SEGURIDAD']['PTOADMISION']['HOSPITALIZACION']['url'],ModuloGetURL('system','Menu'));
                        return true;
            }
            list($dbconn) = GetDBconn();
            GLOBAL $ADODB_FETCH_MODE;
            $query = "select b.tipo_admision_id, b.descripcion as descripcion5, c.empresa_id,
                                c.centro_utilidad, d.razon_social as descripcion1,
                                e.descripcion as descripcion2, b.tipo_admision_id,
                                b.punto_admision_id, b.sw_triage, b.departamento, c.descripcion as descripcion4,
                                f.unidad_funcional, f.descripcion as descripcion3, b.sw_soat, c.servicio
                                from puntos_admisiones_usuarios as a, puntos_admisiones as b,
                                departamentos as c, empresas as d, centros_utilidad as e,
                                unidades_funcionales as f
                                where a.usuario_id=$SystemId and b.tipo_admision_id='HS'
                                and a.punto_admision_id=b.punto_admision_id and b.departamento=c.departamento
                                and d.empresa_id=c.empresa_id and c.empresa_id=e.empresa_id
                                and c.centro_utilidad=e.centro_utilidad and e.empresa_id=f.empresa_id
                                and e.centro_utilidad=f.centro_utilidad and c.unidad_funcional=f.unidad_funcional
                                order by f.empresa_id, f.centro_utilidad, f.unidad_funcional";
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $resulta=$dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }

            while ($data = $resulta->FetchRow()) {
                $admon[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']][$data['descripcion4']][$data['descripcion5']]=$data;
                $seguridad[$data['empresa_id']][$data['centro_utilidad']][$data['unidad_funcional']][$data['departamento']][$data['punto_admision_id']]=1;
            }

            $url[0]='app';
            $url[1]='Triage';
            $url[2]='user';
            $url[3]='MenusHospitalizacion';
            $url[4]='Hosp';
            $arreglo[0]='EMPRESA';
            $arreglo[1]='CENTRO UTILIDAD';
            $arreglo[2]='UNIDAD FUNCIONAL';
            $arreglo[3]='DEPARTAMENTO';
            $arreglo[4]='ADMISIONES';

            $_SESSION['SEGURIDAD']['PTOADMISION']['HOSPITALIZACION']['arreglo']=$arreglo;
            $_SESSION['SEGURIDAD']['PTOADMISION']['HOSPITALIZACION']['hosp']=$admon;
            $_SESSION['SEGURIDAD']['PTOADMISION']['HOSPITALIZACION']['url']=$url;
            $_SESSION['SEGURIDAD']['PTOADMISION']['HOSPITALIZACION']['puntos']=$seguridad;
            $this->salida.= gui_theme_menu_acceso('ADMISIONES',$_SESSION['SEGURIDAD']['PTOADMISION']['HOSPITALIZACION']['arreglo'],$_SESSION['SEGURIDAD']['PTOADMISION']['HOSPITALIZACION']['hosp'],$_SESSION['SEGURIDAD']['PTOADMISION']['HOSPITALIZACION']['url'],ModuloGetURL('system','Menu'));
            return true;
    }

    /**
    * Llama la forma del menu de admisiones
    * @access public
    * @return boolean
    */
    function MenusHospitalizacion()
    {
            unset($_SESSION['COUNT']);
            unset($_SESSION['TRIAGE']['PACIENTE']);
            if(empty($_SESSION['TRIAGE']['EMPRESA']))
            {
                    if(empty($_SESSION['SEGURIDAD']['PTOADMISION']['HOSPITALIZACION']['puntos'][$_REQUEST['Hosp']['empresa_id']][$_REQUEST['Hosp']['centro_utilidad']][$_REQUEST['Hosp']['unidad_funcional']][$_REQUEST['Hosp']['departamento']][$_REQUEST['Hosp']['punto_admision_id']]))
                    {
                            $this->error = "Error de Seguridad.";
                            $this->mensajeDeError = "Violación a la Seguridad.";
                            return false;
                    }
                    $_SESSION['TRIAGE']['EMPRESA']=$_REQUEST['Hosp']['empresa_id'];
                    $_SESSION['TRIAGE']['CENTROUTILIDAD']=$_REQUEST['Hosp']['centro_utilidad'];
                    $_SESSION['TRIAGE']['UNIDADFUNCIONAL']=$_REQUEST['Hosp']['unidad_funcional'];
                    $_SESSION['TRIAGE']['TIPOPTO']=$_REQUEST['Hosp']['tipo_admision_id'];
                    $_SESSION['TRIAGE']['PTOADMON']=$_REQUEST['Hosp']['punto_admision_id'];
                    $_SESSION['TRIAGE']['SWTRIAGE']=$_REQUEST['Hosp']['sw_triage'];
                    $_SESSION['TRIAGE']['DPTO']=$_REQUEST['Hosp']['departamento'];
                    $_SESSION['TRIAGE']['SWSOAT']=$_REQUEST['Hosp']['sw_soat'];
                    $_SESSION['TRIAGE']['SERVICIO']=$_REQUEST['Hosp']['servicio'];
                    $_SESSION['TRIAGE']['TIPO']='HOSPITALIZACION';
            }
      if(!$this->FormaMenusHospitalizacion()){
                return false;
            }
            return true;
    }


    /**
    * Llama la forma FormaMetodoBuscar
    * @access public
    * @return boolean
    */
     function MetodoBuscarHospitalizacion()
     {
                $Busqueda=$_REQUEST['TipoBusqueda'];
                $this->ListadoAdmisionHospitalizacion($Busqueda,$mensaje,$D,'',$f);
                return true;
     }


    /**
  * Busca los datos de una admision segun el tipo de busqueda
    * @access public
    * @return boolean
    */
  function BuscarAdmisionHospitalizacion()
    {
                $Buscar=$_REQUEST['Buscar'];
                $Busqueda=$_REQUEST['TipoBusqueda'];
                $TipoBuscar=$_REQUEST['TipoBuscar'];
                $BuscarCompleto=$_REQUEST['BuscarCompleto'];
								unset($_SESSION['CONTADOR']);

                $NUM=$_REQUEST['Of'];
                if($Buscar)
                {   unset($_SESSION['CONTADOR']);  }
                if(!$Busqueda)
                {$new=$TipoBuscar;}
                if(!$NUM)
                {   $NUM='0';   }
                foreach($_REQUEST as $v=>$v1)
                {
                    if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID')
                    {   $vec[$v]=$v1;   }
                }
                $_REQUEST['Of']=$NUM;


                if(!empty($BuscarCompleto))
                {
                        if(empty($_SESSION['CONTADOR'])){
                                $conteo=$this->RecordSearchCompletoH();
                                $_SESSION['CONTADOR']=$conteo;
                        }
                        $Datos=$this->BuscarCompletoH($NUM);
                        if($Datos){
                                        $this->ListadoAdmisionHospitalizacion($Busqueda='',$mensaje,$D=1,$Datos,$f=true);
                                        return true;
                        }
                        else{
                                        $mensaje='No hay Ordenes de Hospitalización.';
                                        $this->ListadoAdmisionHospitalizacion($Busqueda='',$mensaje,$D,$Datos,$f=true);
                                        return true;
                        }
                }

                list($dbconn) = GetDBconn();
                if($TipoBuscar==1){
                                        $TipoId=$_REQUEST['TipoDocumento'];
                                        $PacienteId=trim($_REQUEST['Documento']);
                                                if(!$PacienteId){
                                                            if(!$PacienteId){ $this->frmError["Documento"]=1; }
                                                                $this->frmError["MensajeError"]="Debe digitar el Número del Documento.";
                                                                if(!$this->ListadoAdmisionHospitalizacion($Busqueda,$mensaje,$D,$arr,$f)){
                                                                        return false;
                                                                }
                                                                return true;
                                                }
                                                if(empty($_SESSION['CONTADOR'])){
                                                        $conteo=$this->RecordSearchH1($TipoId,$PacienteId);
                                                        $_SESSION['CONTADOR']=$conteo;
                                                }
                                                $Datos=$this->BuscarH1($TipoId,$PacienteId,$NUM);
                                                if($Datos){
                                                                $this->ListadoAdmisionHospitalizacion($Busqueda='',$mensaje,$D=1,$Datos,$f=true);
                                                                return true;
                                                }
                                                else{
                                                                $mensaje='La busqueda no arrojo resultados.';
                                                                $this->ListadoAdmisionHospitalizacion($Busqueda='',$mensaje,$D,$Datos,$f=true);
                                                                return true;
                                                }
                }


                if($TipoBuscar==4){
                                    $Orden=$_REQUEST['Orden'];
                                    if(!$Orden){
                                                if(!$Orden){ $this->frmError["Orden"]=1; }
                                                    $this->frmError["MensajeError"]="Debe digitar el Número de Orden.";
                                                    if(!$this->ListadoAdmisionHospitalizacion($TipoBuscar,$mensaje,$D,$Datos,$f=false)){
                                                            return false;
                                                    }
                                                    return true;
                                    }
                                    if(empty($_SESSION['CONTADOR'])){
                                            $conteo=$this->RecordSearchH4($Orden);
                                            $_SESSION['CONTADOR']=$conteo;
                                    }
                                    $Datos=$this->BuscarH4($Orden,$NUM);
                                    if($Datos){
                                                                $this->ListadoAdmisionHospitalizacion($Busqueda='',$mensaje,$D=1,$Datos,$f=true);
                                                                return true;
                                    }
                                    else{
                                                                $mensaje='La busqueda no arrojo resultados.';
                                                                $this->ListadoAdmisionHospitalizacion($Busqueda='',$mensaje,$D,$Datos,$f=true);
                                                                return true;
                                    }
                }
    }


    /**
    * Busca todos las ordenes de hospitalizacion con limit
    * @access public
    * @return array
    * @param int numero del offset
    */
    function BuscarCompletoH($NUM)
    {
            $NUM=$_REQUEST['Of'];
            if(!$NUM)
            {   $NUM='0';   }
            $limit=$this->limit;

            list($dbconn) = GetDBconn();
            $query = "SELECT a.departamento, a.orden_hospitalizacion_id, a.tipo_orden_id, a.fecha_orden, d.descripcion,
                                a.fecha_programacion,a.tipo_id_paciente, a.paciente_id,
                                b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as completo,
                                c.historia_numero, c.historia_prefijo
                                FROM ordenes_hospitalizacion as a, pacientes as b, historias_clinicas as c,
                                tipos_orden as d
                                WHERE a.hospitalizado='0' AND a.tipo_orden_id!=0 AND
                                a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id
                                AND a.tipo_id_paciente=c.tipo_id_paciente AND a.paciente_id=c.paciente_id
                                and a.tipo_orden_id=d.tipo_orden_id LIMIT $limit OFFSET $NUM";
                                //falta con evolucion
/*SELECT a.orden_hospitalizacion_id, a.tipo_orden_id, a.fecha_orden, a.fecha_programacion,a.tipo_id_paciente, a.paciente_id, b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as completo, c.historia_numero, c.historia_prefijo, d.evolucion_id FROM ordenes_hospitalizacion as a, pacientes as b, historias_clinicas as c, ordenes_hospitalizacion_internas as d
WHERE a.hospitalizado='0' AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id AND a.tipo_id_paciente=c.tipo_id_paciente AND a.paciente_id=c.paciente_id AND  a.orden_hospitalizacion_id=d.orden_hospitalizacion_id */
            if(!empty($query))
            {
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
            }
            return $vars;
    }


    /**
    * Busca todos las ordenes de hospitalizacion y hace un conteno para hacer el offset
    * @access public
    * @return array
    */
    function RecordSearchCompletoH()
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT a.departamento,a.orden_hospitalizacion_id, a.tipo_orden_id, a.fecha_orden,
                                a.fecha_programacion,a.tipo_id_paciente, a.paciente_id,
                                b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as completo,
                                c.historia_numero, c.historia_prefijo
                                FROM ordenes_hospitalizacion as a, pacientes as b, historias_clinicas as c
                                WHERE a.hospitalizado='0' AND a.tipo_orden_id!=0 AND
                                a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id
                                AND a.tipo_id_paciente=c.tipo_id_paciente AND a.paciente_id=c.paciente_id";
                                //falta con evolucion
/*SELECT a.orden_hospitalizacion_id, a.tipo_orden_id, a.fecha_orden, a.fecha_programacion,a.tipo_id_paciente, a.paciente_id, b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as completo, c.historia_numero, c.historia_prefijo, d.evolucion_id FROM ordenes_hospitalizacion as a, pacientes as b, historias_clinicas as c, ordenes_hospitalizacion_internas as d
WHERE a.hospitalizado='0' AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id AND a.tipo_id_paciente=c.tipo_id_paciente AND a.paciente_id=c.paciente_id AND  a.orden_hospitalizacion_id=d.orden_hospitalizacion_id */
            if(!empty($query))
            {
                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                        }
                        $vars=$result->RecordCount();
                        $result->Close();
            }
            return $vars;
    }



    /**
    * Busca las ordenes de hospitalizacion por documento del paciente
    * @access public
    * @return array
    * @param string tipo documento
    * @param int numero documento
    */
    function RecordSearchH1($TipoId,$PacienteId)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT a.departamento,a.orden_hospitalizacion_id, a.tipo_orden_id, a.fecha_orden,
                                a.fecha_programacion,a.tipo_id_paciente, a.paciente_id,
                                b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as completo,
                                c.historia_numero, c.historia_prefijo
                                FROM ordenes_hospitalizacion as a, pacientes as b, historias_clinicas as c
                                WHERE a.hospitalizado='0' AND a.tipo_orden_id!=0 AND
                                a.tipo_id_paciente='$TipoId' AND a.paciente_id='$PacienteId'
                                AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id
                                AND a.tipo_id_paciente=c.tipo_id_paciente AND a.paciente_id=c.paciente_id";
            if(!empty($query))
            {
                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                        }
                        $vars=$result->RecordCount();
                        $result->Close();
            }
            return $vars;
    }

    /**
    * Busca las ordenes de hospitalizacion por documento del paciente con limit
    * @access public
    * @return array
    * @param string tipo documento
    * @param int numero documento
    * @param int numero del offset
    */
    function BuscarH1($TipoId,$PacienteId,$NUM)
    {
            $NUM=$_REQUEST['Of'];
            if(!$NUM)
            {   $NUM='0';   }
            $limit=$this->limit;

            list($dbconn) = GetDBconn();
            $query = "SELECT a.departamento,a.orden_hospitalizacion_id, a.tipo_orden_id, a.fecha_orden, d.descripcion,
                                a.fecha_programacion,a.tipo_id_paciente, a.paciente_id,
                                b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as completo,
                                c.historia_numero, c.historia_prefijo
                                FROM ordenes_hospitalizacion as a, pacientes as b, historias_clinicas as c,
                                tipos_orden as d
                                WHERE a.hospitalizado='0' AND a.tipo_orden_id!=0 AND
                                a.tipo_id_paciente='$TipoId' AND a.paciente_id='$PacienteId'
                                AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id
                                AND a.tipo_id_paciente=c.tipo_id_paciente AND a.paciente_id=c.paciente_id
                                and a.tipo_orden_id=d.tipo_orden_id LIMIT $limit OFFSET $NUM";
            if(!empty($query))
            {
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
            }
            return $vars;
    }


    /**
    * Busca las ordenes de hospitalizacion por orden con limit
    * @access public
    * @return array
    * @param int orden
    * @param int numero del offset
    */
    function BuscarH4($Orden,$NUM)
    {
            $NUM=$_REQUEST['Of'];
            if(!$NUM)
            {   $NUM='0';   }
            $limit=$this->limit;

            list($dbconn) = GetDBconn();
            $query = "SELECT a.departamento,a.orden_hospitalizacion_id, a.tipo_orden_id, a.fecha_orden,
                                a.fecha_programacion,a.tipo_id_paciente, a.paciente_id,
                                b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as completo,
                                c.historia_numero, c.historia_prefijo, d.descripcion
                                FROM ordenes_hospitalizacion as a, pacientes as b, historias_clinicas as c,
                                tipos_orden as d
                                WHERE a.hospitalizado='0' AND a.tipo_orden_id!=0 AND a.orden_hospitalizacion_id=$Orden
                                AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id
                                AND a.tipo_id_paciente=c.tipo_id_paciente AND a.paciente_id=c.paciente_id
                                and a.tipo_orden_id=d.tipo_orden_id LIMIT $limit OFFSET $NUM";
            if(!empty($query))
            {
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
            }
            return $vars;
    }


    /**
    * Busca las ordenes de hospitalizacion por orden
    * @access public
    * @return array
    * @param int orden
    * @param int numero del offset
    */
    function RecordSearchH4($Orden)
    {
            $NUM=$_REQUEST['Of'];
            if(!$NUM)
            {   $NUM='0';   }
            $limit=$this->limit;

            list($dbconn) = GetDBconn();
            $query = "SELECT a.departamento,a.orden_hospitalizacion_id, a.tipo_orden_id, a.fecha_orden,
                                a.fecha_programacion,a.tipo_id_paciente, a.paciente_id,
                                b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as completo,
                                c.historia_numero, c.historia_prefijo
                                FROM ordenes_hospitalizacion as a, pacientes as b, historias_clinicas as c
                                WHERE a.hospitalizado='0' AND a.tipo_orden_id!=0 AND a.orden_hospitalizacion_id=$Orden
                                AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id
                                AND a.tipo_id_paciente=c.tipo_id_paciente AND a.paciente_id=c.paciente_id";
            if(!empty($query))
            {
                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                        }
                        $vars=$result->RecordCount();
                        $result->Close();
            }
            return $vars;
    }

    /**
    * Valida si el paciente tiene una cuenta abierta o si tiene ingreso o no
    * @access public
    * @return boolean
    */
    function VerificarDatosHospitalizacion()
    {
            $EmpresaId=$_SESSION['TRIAGE']['EMPRESA'];
            $dat=$_REQUEST['datos'];

            $TipoId=$dat[tipo_id_paciente];
            $PacienteId=$dat[paciente_id];
            $Orden=$dat[orden_hospitalizacion_id];

            $_SESSION['TRIAGE']['TIPOORDEN']=$dat[descripcion];
            $_SESSION['TRIAGE']['DPTOEST']=$dat[departamento];

            list($dbconn) = GetDBconn();
            $query = "SELECT paciente_fallecido,primer_nombre,segundo_nombre,
                                primer_apellido,segundo_apellido,tipo_id_paciente, paciente_id
                                FROM pacientes
                                WHERE paciente_id='$PacienteId' AND tipo_id_paciente ='$TipoId'";
            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            $vars=$resulta->GetRowAssoc($ToUpper = false);

            if($vars[paciente_fallecido])
            {
                    $this->FormaMensajePacienteFallecido($vars,$accion);
                    return true;
            }

            $query = " SELECT a.ingreso, c.numerodecuenta, c.plan_id
                                    FROM ingresos as a, cuentas as c
                                    WHERE a.estado=1 and a.paciente_id='$PacienteId' AND a.tipo_id_paciente ='$TipoId'
                                    AND a.ingreso=c.ingreso AND c.empresa_id='$EmpresaId' AND c.estado=1";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            else
            {        //tiene ingreso
                    if(!$result->EOF)
                    {
                            $Ingreso=$result->fields[0];
                            $Plan=$result->fields[2];
                            $_SESSION['TRIAGE']['PACIENTE']['INGRESO']=$Ingreso;
                            $_SESSION['TRIAGE']['ORDEN']=$Orden;
                            $_SESSION['TRIAGE']['PLAN']=$Plan;
														$_SESSION['TRIAGE']['PACIENTE']['plan_id']=$Plan;
														$_SESSION['TRIAGE']['PACIENTE']['paciente_id']=$PacienteId;
														$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente']=$TipoId;
                            $this->AutorizarPaciente();
                            return true;
                    }//no hay ingreso
                    else
                    {
                            $_SESSION['TRIAGE']['ORDEN']=$Orden;
                            $this->FormaResponsable($TipoId,$PacienteId,'');
                            return true;
                    }
            }
    }


    /**
    * Busca si el paciente tiene una cuenta abierta
    * @access public
    * @return boolean
    */
    function ValidarPacienteHospitalizacion()
    {
					$TipoDocumento=$_REQUEST['TipoId'];
					$Documento=trim($_REQUEST['PacienteId']);
					$Plan=$_REQUEST['Plan'];

					if($Plan==-1){
									if($Plan==-1){ $this->frmError["Responsable"]=1; }
															$this->frmError["MensajeError"]="Faltan datos obligatorios.";
															$this->FormaResponsable($TipoId,$PacienteId,$Responsable);
															return true;
					}

					$this->AutorizarPaciente($TipoDocumento,$Documento,$Plan);
					return true;
    }


    /**
    * Busca las entidad de las cuales puede venir la orden del paciente (orden externa)
    * @access public
    * @return array
    */
    function EntidadesOrigen()
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT sgsss,nombre_sgsss FROM sgsss";
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
    * Busca los datos basicos del paciente con su ingreso
    * @access public
    * @return array
    */
    function DatosBasicosPaciente()
    {
            $Ingreso=$_SESSION['ADMISION']['RETORNO']['ingreso'];
            list($dbconn) = GetDBconn();
            $query = "SELECT b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as completo,
                                a.ingreso, a.paciente_id, a.tipo_id_paciente
                                FROM ingresos as a, pacientes as b
                                WHERE a.ingreso=$Ingreso AND a.tipo_id_paciente=b.tipo_id_paciente
                                AND a.paciente_id=b.paciente_id";
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
	function ValidarFecha($fecha)
	{
			$x=explode("-",$fecha);
			if(strlen ($x[0])!=4 OR is_numeric($x[0])==0)
			{
					$this->frmError["MensajeError"]="Formato de Fecha Incorrecto ";
					return false;
			}
			if(strlen ($x[1])>2 OR is_numeric($x[1])==0 OR $x[1]==0 OR $x[1]>12)
			{
					$this->frmError["MensajeError"]="Formato de Fecha Incorrecto ";
					return false;
			}
			if(strlen ($x[2])>2 OR is_numeric($x[2])==0 OR $x[1]==0)
			{
					$this->frmError["MensajeError"]="Formato de Fecha Incorrecto ";
					return false;
			}
			return true;
	}

    /**
    * Valida que todos los datos de la orden externa sean correctos
    * @access public
    * @return boolean
    */
    function ValidarOrdenExterna()
    {
            $medico=$_REQUEST['Medico'];
            $cargo=$_REQUEST['cargo'];
            $codigo=$_REQUEST['codigo'];
            $diagnostico=$_REQUEST['Diagnostico'];
            $origen=$_REQUEST['Origen'];
            $observacion=$_REQUEST['Observacion'];
            $Fecha=$_REQUEST['Fecha'];
            $Hora=$_REQUEST['Hora'];
            $Min=$_REQUEST['Min'];

            if($origen==-1 || !$medico || !$codigo || !$Fecha || !$Hora || !$Min){
                    if($origen==-1){ $this->frmError["Origen"]=1; }
                    if(!$medico ){ $this->frmError["Medico"]=1; }
                    if(!$codigo){ $this->frmError["Diagnostico"]=1; }
                    if(!$Fecha){ $this->frmError["Fecha"]=1; }
                    if(!$Hora){ $this->frmError["Hora"]=1; }
                    if(!$Min){ $this->frmError["Hora"]=1; }
                    $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                        if(!$this->FormaOrdenExterna($medico,$cargo,$codigo,$diagnostico,$origen,$observacion,$Fecha,$Hora,$Min)){
                            return false;
                        }
                        return true;
            }

           	$f=explode('/',$Fecha);
            $fec=$f[2].'-'.$f[1].'-'.$f[0];
						$x=$this->ValidarFecha($fec);
						if(empty($x))
						{
										$this->frmError["Fecha"]=1;
                    $this->frmError["MensajeError"]="Formato de Fecha Incorrecto.";
										if(!$this->FormaOrdenExterna($medico,$cargo,$codigo,$diagnostico,$origen,$observacion,$Fecha,$Hora,$Min)){
												return false;
										}
										return true;
						}

            $FechaRegistro=date("Y-m-d H:i:s");
            $datos=$this->DatosBasicosPaciente();
            $PacienteId=$datos[paciente_id];
            $TipoId=$datos[tipo_id_paciente];
            $dpto=$_SESSION['TRIAGE']['DPTO'];
            $FechaP=$fec." ".$Hora.":".$Min;
            //$SystemId=UserGetUID();
            list($dbconn) = GetDBconn();
						$dbconn->BeginTrans();
            $query="SELECT nextval('ordenes_hospitalizacion_orden_hospitalizacion_id_seq')";
            $result=$dbconn->Execute($query);
         		if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos0";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }						
            $Orden=$result->fields[0];
            $Auto=$_SESSION['TRIAGE']['PACIENTE']['AUTORIZACION'];
            $Ingreso=$_SESSION['TRIAGE']['PACIENTE']['INGRESO'];

            $query = "INSERT INTO ordenes_hospitalizacion(orden_hospitalizacion_id,
                                                        fecha_orden,
                                                        fecha_programacion,
                                                        hospitalizado,
                                                        paciente_id,
                                                        tipo_id_paciente,
                                                        departamento,
                                                        tipo_orden_id,
                                                        autorizacion,
                                                        ingreso)
                     VALUES($Orden,'$FechaRegistro','$FechaP','0','$PacienteId','$TipoId','$dpto','0',$Auto,$Ingreso)";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos1";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            else
            {
                        $query = "INSERT INTO ordenes_hospitalizacion_externas(orden_hospitalizacion_id,
                                                                nombre_medico,
                                                                observaciones,
                                                                diagnostico_id,
                                                                sgsss)
                                                                VALUES($Orden,'$medico','$observacion','$codigo','$origen')";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Guardar en la Base de Datos2";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                        else
                        {
																		$Estacion=$_SESSION['TRIAGE']['PACIENTE']['estacion_id'];
																		//$Ingreso =$_SESSION['TRIAGE']['PACIENTE']['ingreso'];
                                    $query = " INSERT INTO pendientes_x_hospitalizar(ingreso,
																																										estacion_destino,
																																										estacion_origen,
																																										orden_hospitalizacion_id)
																							 VALUES($Ingreso,'$Estacion',NULL,$Orden)";
                                    $dbconn->BeginTrans();
                                    $resulta=$dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                                    $this->error = "Error al Guardar en pendientes_x_hospitalizar";
                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                    $dbconn->RollbackTrans();
                                                    return false;
                                    }
                                    else
                                    {
                                            $dbconn->CommitTrans();
                                            $this->MensajeFinal();
                                            return true;
                                    }
                        }
            }
    }

    /**
    * Lisitado de las ordenes de traslado con limit
    * @access public
    * @return boolean
    */
    function ListadoTranslado()
    {
            $NUM=$_REQUEST['Of'];
            if(!$NUM)
            {   $NUM='0';   }
            $limit=$this->limit;

            list($dbconn) = GetDBconn();
            $query = "SELECT a.departamento, a.orden_hospitalizacion_id, a.tipo_orden_id, a.fecha_orden,
                                a.fecha_programacion,a.tipo_id_paciente, a.paciente_id,
                                b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as completo,
                                c.historia_numero, c.historia_prefijo, d.descripcion
                                FROM ordenes_hospitalizacion as a, pacientes as b, historias_clinicas as c,
                                tipos_orden as d
                                WHERE a.hospitalizado='0' AND a.tipo_orden_id=2 AND
                                a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id
                                AND a.tipo_id_paciente=c.tipo_id_paciente AND a.paciente_id=c.paciente_id
                                and a.tipo_orden_id=d.tipo_orden_id  LIMIT $limit OFFSET $NUM";
                                //falta con evolucion
/*SELECT a.orden_hospitalizacion_id, a.tipo_orden_id, a.fecha_orden, a.fecha_programacion,a.tipo_id_paciente, a.paciente_id, b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as completo, c.historia_numero, c.historia_prefijo, d.evolucion_id FROM ordenes_hospitalizacion as a, pacientes as b, historias_clinicas as c, ordenes_hospitalizacion_internas as d
WHERE a.hospitalizado='0' AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id AND a.tipo_id_paciente=c.tipo_id_paciente AND a.paciente_id=c.paciente_id AND  a.orden_hospitalizacion_id=d.orden_hospitalizacion_id */
            if(!empty($query))
            {
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
            }
            $this->FormaListadoTranslado($vars);
            return true;
    }

    /**
    * Lista las ordenes de traslado, las cuenta para el offset
    * @access public
    * @return boolean
    */
    function BuscarTranslado()
    {
            $_SESSION['TRIAGE']['TIPOORDEN']='Translado';
            if(empty($_SESSION['COUNT']))
            {
                        list($dbconn) = GetDBconn();
                        $query = "SELECT a.departamento, a.orden_hospitalizacion_id, a.tipo_orden_id, a.fecha_orden,
																	a.fecha_programacion,a.tipo_id_paciente, a.paciente_id,
																	b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as completo,
																	c.historia_numero, c.historia_prefijo
																	FROM ordenes_hospitalizacion as a, pacientes as b, historias_clinicas as c
																	WHERE a.hospitalizado='0' AND a.tipo_orden_id=2 AND
																	a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id
																	AND a.tipo_id_paciente=c.tipo_id_paciente AND a.paciente_id=c.paciente_id";
                                            //falta con evolucion
            /*SELECT a.orden_hospitalizacion_id, a.tipo_orden_id, a.fecha_orden, a.fecha_programacion,a.tipo_id_paciente, a.paciente_id, b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as completo, c.historia_numero, c.historia_prefijo, d.evolucion_id FROM ordenes_hospitalizacion as a, pacientes as b, historias_clinicas as c, ordenes_hospitalizacion_internas as d
            WHERE a.hospitalizado='0' AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id AND a.tipo_id_paciente=c.tipo_id_paciente AND a.paciente_id=c.paciente_id AND  a.orden_hospitalizacion_id=d.orden_hospitalizacion_id */
                        if(!empty($query))
                        {
                                    $result = $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Cargar el Modulo";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                                    }
                                    $_SESSION['COUNT']=$result->RecordCount();
                                    $result->Close();
                        }
            }

            if($result->EOF){
                        $mensaje='No hay pacientes de translado.';
                        $accion=ModuloGetURL('app','Triage','user','MenusHospitalizacion');
                        $boton='MENU';
                        if(!$this->FormaMensaje($mensaje,'LISTADO PACIENTES TRANSLADO',$accion,$boton)){
                                return false;
                        }
                        return true;
            }

            $this->ListadoTranslado();
            return true;
    }

//----------------------REMISIONES-------------------------------
	/**
	*
	*/
	function CentrosRemision()
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT *
								FROM centros_remision";
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
	function CentrosRemisionNivel($nivel)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT *
								FROM centros_remision WHERE nivel=$nivel";
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
	function DatosRemision()
	{
			//si hay algo tiene que completar toda la remision
			if(!empty($_REQUEST['cargo']) || !empty($_REQUEST['remision']) || !empty($_REQUEST['entidad']) || !empty($_REQUEST['fecha']))
			{
					if(!empty($_REQUEST['hora']))
					{
							if(!is_numeric($_REQUEST['hora']) OR $_REQUEST['hora']>24)
							{
									$this->frmError["HoraAuto"]=1;
									$this->frmError["MensajeError"]="EL FORMATO DE LA HORA ES INCORRECTO.";
									$this->FormaNuevo();
									return true;
							}
					}
					if(!empty($_REQUEST['minuto']) OR $_REQUEST['minuto']>60)
					{
							if(!is_numeric($_REQUEST['minuto']))
							{
									$this->frmError["HoraAuto"]=1;
									$this->frmError["MensajeError"]="EL FORMATO DE LOS MINUTOS ES INCORRECTO.";
									$this->FormaNuevo();
									return true;
							}
					}

					if((!empty($_REQUEST['hora']) AND (empty($_REQUEST['minuto'])))
							OR (empty($_REQUEST['hora']) AND !empty($_REQUEST['minuto'])))
					{
									$this->frmError["HoraAuto"]=1;
									$this->frmError["MensajeError"]="DEBE DIGITAR LA HORA COMPLETA.";
									$this->FormaNuevo();
									return true;
					}

					if(!empty($_REQUEST['fecha']))
					{
							$f=explode('/',$_REQUEST['fecha']);
							$fech=$f[2].'-'.$f[1].'-'.$f[0];

							$val=$this->ValidarFecha($fech);
							if(empty($val))
							{
											$this->frmError["fecha"]=1;
											$this->frmError["MensajeError"]="FORMATO DE FECHA INCORRECTO.";
											$this->FormaNuevo();
											return true;
							}
					}

//|| empty($_REQUEST['remision'])
					if(empty($_REQUEST['cargo'])  || empty($_REQUEST['entidad']) || empty($_REQUEST['fecha']))
					{
							if(empty($_REQUEST['cargo']))
							{  $this->frmError["diagnostico"]=1;  }
							if(empty($_REQUEST['fecha']))
							{  $this->frmError["fecha"]=1;  }
							//if(empty($_REQUEST['remision']))
							//{  $this->frmError["remision"]=1;  }
							if(empty($_REQUEST['entidad']))
							{  $this->frmError["entidad"]=1;  }
							$this->frmError["MensajeError"]="Faltan Datos Obligatorios.";
							$this->FormaNuevo();
							return true;
					}
					if(!empty($_REQUEST['remision']))
					{
							if(is_numeric($_REQUEST['remision'])==0)
							{
									$this->frmError["remision"]=1;
									$this->frmError["MensajeError"]="El Número de Remision debe Ser Numerico.";
									$this->FormaNuevo();
									return true;
							}
					}
			}
//AND !empty($_REQUEST['remision']) 
			if(!empty($_REQUEST['cargo']) AND !empty($_REQUEST['entidad']) AND  !empty($_REQUEST['fecha']))
			{
					$f=explode('/',$_REQUEST['fecha']);
					$_REQUEST['fecha']=$f[2].'-'.$f[1].'-'.$f[0];

					list($dbconn) = GetDBconn();
					$query=" SELECT nextval('pacientes_remitidos_paciente_remitido_id_seq')";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					$remision=$result->fields[0];
					$hora='NULL';
					if(!empty($_REQUEST['hora']) AND !empty($_REQUEST['minuto']))
					{
							$hora=$_REQUEST['hora'].":".$_REQUEST['minuto'];
							$hora="'$hora'";
					}
					
					if(empty($_REQUEST['remision']))
					{ $_REQUEST['remision'] ='NULL';}
					
					$query = "INSERT INTO pacientes_remitidos (
																			paciente_remitido_id,
																			tipo_id_paciente,
																			paciente_id,
																			centro_remision,
																			numero_remision,
																			diagnostico_id,
																			observacion,
																			fecha_registro,
																			usuario_id,
																			fecha_remision,
																			hora_remision,
																			triage_id,
																			ingreso)
										VALUES($remision,'".$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente']."','".$_SESSION['TRIAGE']['PACIENTE']['paciente_id']."',
										'".$_REQUEST['entidad']."',".$_REQUEST['remision'].",'".$_REQUEST['codigo']."','".$_REQUEST['observacion']."','now()',".UserGetUID().",'".$_REQUEST['fecha']."',$hora,NULL,NULL)";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}

					$_SESSION['TRIAGE']['PACIENTE']['REMISION']=$remision;
			}

			unset($_SESSION['TRIAGE']['CAUSAS']);
			unset($_SESSION['TRIAGE']['DIAGNOSTICO']);

			if(!empty($_REQUEST['admitir']))
			{
					if(empty($_SESSION['TRIAGE']['SWTRIAGE']))
					{
					$this->AutorizarPaciente();
					return true;
					}
					else
					{
						$mensaje='ESTA SEGURO QUE VA HA ADMITIR AL PACIENTE.';
						$arreglo=array();
						$c='app';
						$m='Triage';
						$me='AutorizarPaciente';
						$me2='FormaNuevo';
						$Titulo='ADMITIR PACIENTE';
						$boton1='ACEPTAR';
						$boton2='CANCELAR';

						$this->ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
						return true;
					}
					//$this->AutorizarPaciente();
					//return true;
			}
			elseif(!empty($_REQUEST['triage']))
			{
					$this->LlamarDatosAdmisionPaciente();
					return true;
			}
			elseif(!empty($_REQUEST['signos']))
			{
					if($_SESSION['TRIAGE']['PUNTO']['FUNCIONARIO']==1 OR $_SESSION['TRIAGE']['PUNTO']['FUNCIONARIO']==2)
					{  $this->FormaClasificacionTriage();  }
					else
					{  $this->FormaSignosVitalesTriage();  }
					return true;
			}
	}


//----------------------------------------------------------
	/**
	*
	*/
	function DatosPendientesAdmitir($triage)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT * FROM triages_pendientes_admitir
								WHERE triage_id=$triage";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			$vars=$result->GetRowAssoc($ToUpper = false);
			$result->Close();
			return $vars;
	}

	/**
	*
	*/
	function Ocular()
	{
				list($dbconn) = GetDBconn();
				$query="SELECT apertura_ocular_id, descripcion
					  		FROM hc_tipos_apertura_ocular ORDER BY apertura_ocular_id ASC";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}

				while(!$result->EOF){
						$niveles[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
				return $niveles;
	}


	/**
	*
	*/
	function Verbal($fecha)
	{
				list($dbconn) = GetDBconn();
				$edad_paciente = CalcularEdad($fecha,date("Y-m-d"));
				if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_lactante'))
				{
					$query = "SELECT respuesta_verbal_id, descripcion_lactante as descripcion
								FROM hc_tipos_respuesta_verbal 	ORDER BY respuesta_verbal_id ASC";
				}
				else
				{
					$query = "SELECT respuesta_verbal_id, descripcion
								FROM hc_tipos_respuesta_verbal ORDER BY respuesta_verbal_id ASC";
				}
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}

				while(!$result->EOF){
						$niveles[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
				return $niveles;
	}

	/**
	*
	*/
	function Motora($fecha)
	{
			list($dbconn) = GetDBconn();
			$edad_paciente = CalcularEdad($fecha,date("Y-m-d"));
			if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_lactante'))
			{
				$query = "SELECT respuesta_motora_id, descripcion_lactante as descripcion
						 FROM hc_tipos_respuesta_motora ORDER BY respuesta_motora_id ASC";
			}
			else
			{
				$query = "SELECT respuesta_motora_id, descripcion
						 FROM hc_tipos_respuesta_motora ORDER BY respuesta_motora_id ASC";
			}
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}

				while(!$result->EOF){
						$niveles[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
				return $niveles;
	}


	/**
	*
	*/
	function LlamarBuscarPaciente()
	{
				unset($_SESSION['ADMISIONES']);
				$_SESSION['ADMISIONES']['EMPRESA']=$_SESSION['TRIAGE']['EMPRESA'];
				$_SESSION['ADMISIONES']['CENTROUTILIDAD']=$_SESSION['TRIAGE']['CENTROUTILIDAD'];
				$_SESSION['ADMISIONES']['RETORNO']['contenedor']='app';
				$_SESSION['ADMISIONES']['RETORNO']['modulo']='Triage';
				$_SESSION['ADMISIONES']['RETORNO']['tipo']='user';
				$_SESSION['ADMISIONES']['RETORNO']['metodo']='FormaMenus';
				$_SESSION['ADMISIONES']['RETORNO']['argumentos']=array();
				$_SESSION['ADMISIONES']['SERVICIO']=$_SESSION['TRIAGE']['SERVICIO'];
				$_SESSION['ADMISIONES']['TIPO']=$_SESSION['TRIAGE']['TIPO'];
				$_SESSION['ADMISIONES']['SWSOAT']= $_SESSION['TRIAGE']['SWSOAT'];
				$_SESSION['ADMISIONES']['DPTO']=$_SESSION['TRIAGE']['DPTO'];

				$this->ReturnMetodoExterno('app','Admisiones','user','ValidarDatos');
				return true;
	}

		/**
		*
		*/
		function SacarPacienteLista()
		{
				$this->FormaSacarLista($_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['triage_id'],$_REQUEST['ingreso'],$_REQUEST['metodoS']);
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
					$dbconn->CommitTrans();
					unset($_SESSION['CONT']);
					$this->frmError["MensajeError"]='EL PACIENTE FUE SACADO DE LA LISTA';

					if(!empty($_REQUEST['metodoS']))
					{  $this->$_REQUEST['metodoS']();  }
					else
					{  $this->ListarPacientes();  }
					return true;
		}

		/**
		*
		*/
		function ValidarSignosVitales($fc,$fr,$tAlta,$tBaja,$temperatura,$peso,$ocular,$verbal,$motora,$sato)
		{
				IncludeLib('funciones_admision');
				$signo = BuscarSignosObligatorios();

				if($signo['fc']['sw_mostrar']==1)
				{
					if($signo['fc']['sw_obligatorio']==1)
					{
							if(($fc==='0' AND $signo['fc']['sw_cero']==1) OR $fc > 0)
							{ 		}
							else
							{
									$this->frmError["frecuenciaCardiaca"]=1;
									$this->frmError["MensajeError"]="Faltan Signos Vitales.";
									return false;
							}
					}
				}

				if($signo['fr']['sw_mostrar']==1)
				{
					if($signo['fr']['sw_obligatorio']==1)
					{
						if(($fr==='0' AND $signo['fr']['sw_cero']==1) OR $fr > 0)
						{ 		}
						else
						{
								$this->frmError["frecuenciaRespiratoria"]=1;
								$this->frmError["MensajeError"]="Faltan Signos Vitales.";
								return false;
						}
					}
				}

				if($signo['tension']['sw_mostrar']==1)
				{
					if($signo['tension']['sw_obligatorio']==1)
					{
							if(($tAlta==='0' AND $signo['tension']['sw_cero']==1) OR $tAlta > 0)
							{ 		}
							else
							{
									$this->frmError["taAlta"]=1;
									$this->frmError["MensajeError"]="Faltan Signos Vitales.";
									return false;
							}
							if(($tBaja==='0' AND $signo['tension']['sw_cero']==1) OR $tBaja > 0)
							{ 		}
							else
							{
									$this->frmError["taAlta"]=1;
									$this->frmError["MensajeError"]="Faltan Signos Vitales.";
									return false;
							}
					}
					if($tAlta < $tBaja)
					{
							$this->frmError["taAlta"]=1;
							$this->frmError["MensajeError"]="Los valores de la tensión no son correctos.";
							return false;
					}
				}

				if($signo['sato']['sw_mostrar']==1)
				{
					if($signo['sato']['sw_obligatorio']==1)
					{
							if(($sato==='0' AND $signo['sato']['sw_cero']==1) OR $sato > 0)
							{ 		}
							else
							{
									$this->frmError["sato"]=1;
									$this->frmError["MensajeError"]="Faltan Signos Vitales.";
									return false;
							}
					}
				}


				if($fc)
				{
						if(is_numeric($fc)==0)
						{
								$this->frmError["frecuenciaCardiaca"]=1;
								$this->frmError["MensajeError"]="La Frecuencia Cardiaca debe ser numerica.";
								return false;
						}
				}
				if($fr)
				{
						if(is_numeric($fr)==0)
						{
								$this->frmError["frecuenciaRespiratoria"]=1;
								$this->frmError["MensajeError"]="La Frecuencia Respiratoria debe ser numerica.";
								return false;
						}
				}
				if($temperatura)
				{
						if(is_numeric($temperatura)==0)
						{
								$this->frmError["temperatura"]=1;
								$this->frmError["MensajeError"]="La Temperatura debe ser numerica.";
								return false;
						}
						if($temperatura > 43)
						{
								$this->frmError["temperatura"]=1;
								$this->frmError["MensajeError"]="La Temperatura excede el valor.";
								return false;
						}
				}

				if($peso)
				{
						if(is_numeric($peso)==0)
						{
								$this->frmError["peso"]=1;
								$this->frmError["MensajeError"]="La Peso debe ser numerico.";
								return false;
						}
				}

				if($tAlta)
				{
						if(is_numeric($tAlta)==0)
						{
								$this->frmError["taAlta"]=1;
								$this->frmError["MensajeError"]="La Tensión Arterial debe ser numerica.";
								return false;
						}
				}
				if($tBaja)
				{
						if(is_numeric($tBaja)==0)
						{
								$this->frmError["taAlta"]=1;
								$this->frmError["MensajeError"]="La Tensión Arterial debe ser numerica.";
								return false;
						}
				}

				if((empty($tAlta) AND !empty($tBaja)) OR (empty($tBaja) AND !empty($tAlta)))
				{
								$this->frmError["taAlta"]=1;
								$this->frmError["MensajeError"]="Debe digitar los dos rangos de la tensión.";
								return false;
				}

				if($sato)
				{
						if(is_numeric($sato)==0)
						{
								$this->frmError["sato"]=1;
								$this->frmError["MensajeError"]="La Saturacion de Oxigeno debe ser numerica.";
								return false;
						}
				}

				if($signo['glasgow']['sw_mostrar']==1 AND $signo['glasgow']['sw_obligatorio']==1 )
				{
					if(!empty($ocular) OR !empty($verbal) OR !empty($motora))
					{
							if(empty($ocular))
							{
									$this->frmError["MensajeError"]="Debe Definir la Apertura Ocular.";
									return false;
							}
							if(empty($verbal))
							{
									$this->frmError["MensajeError"]="Debe Definir la Respuesta Verbal.";
									return false;
							}
							if(empty($motora))
							{
									$this->frmError["MensajeError"]="Debe Definir la Respuesta Motora.";
									return false;
							}
					}
				}
				return true;
		}

		/**
		*
		*/
		function BuscarCausas($busqueda)
		{
				list($dbconn) = GetDBconn();
        
				$query = "SELECT a.nivel_triage_id,a.descripcion as desnivel, a.accion,
									b.signo_sintoma_id, b.descripcion as dessigno,
									c.causa_probable_id, c.descripcion as descausa
									FROM niveles_triages as a, signos_sintomas as b, causas_probables as c
									WHERE c.causa_probable_id in ($busqueda)
									and a.nivel_triage_id=b.nivel_triage_id
									and b.signo_sintoma_id=c.signo_sintoma_id
									ORDER BY a.indice_de_orden, b.indice_de_orden, c.indice_de_orden";
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

	function LlamarGarantes()
	{
			$_SESSION['GARANTE']['RETORNO']['contenedor']='app';
			$_SESSION['GARANTE']['RETORNO']['modulo']='Triage';
			$_SESSION['GARANTE']['RETORNO']['tipo']='user';
			$_SESSION['GARANTE']['RETORNO']['metodo']='BuscarListadoIngresos';
			$_SESSION['GARANTE']['RETORNO']['argumentos']=array();
			$_SESSION['GARANTE']['INGRESO']=$_REQUEST['Ingreso'];
			$_SESSION['ADMISIONES']['INGRESO']['ingreso']=true;
			$_SESSION['ADMISIONES']['PACIENTE']['INGRESO']=$_REQUEST['Ingreso'];

			$this->ReturnMetodoExterno('app','Admisiones','user','LlamarFormaGarantesExt');
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
								FROM triages as d, pacientes as c
								WHERE d.usuario_clasificacion=".UserGetUID()."
								and d.tipo_id_paciente=c.tipo_id_paciente
								and d.paciente_id=c.paciente_id
								and d.fecha_clasificacion > timestamp'".date("Y-m-d H:i")."' - interval '12 hour'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
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

	function BuscarObservacionEnfermera($triage)
	{
				list($dbconn) = GetDBconn();
				$query = "SELECT observacion_enfermera FROM triages
									WHERE triage_id=$triage";
				$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				if(!$result->EOF)
				{ $var=$result->fields[0]; }
				return $var;
	}
//------------------------------------------------
	function RetornoIngreso()
	{
			//quiere decir q es insertar un ingreso $_SESSION['ADMISIONES']['INGRESO']['ingreso']
			//quiere decir q es insertar un ingreso tmp $_SESSION['ADMISIONES']['INGRESO']['ingresotmp']
			if(!empty($_SESSION['ADMISIONES']['RETORNO']['CANCELAR']))
			{
					$this->FormaMenus();
					return true;
			}

			$_SESSION['ADMISION']['RETORNO']['ingreso']=$_SESSION['ADMISIONES']['PACIENTE']['INGRESO'];
			$_SESSION['TRIAGE']['PACIENTE']['INGRESO']=$_SESSION['ADMISIONES']['PACIENTE']['INGRESO'];
			$_SESSION['TRIAGE']['PACIENTE']['estacion_id']=$_SESSION['ADMISIONES']['PACIENTE']['estacion_id'];

			if(!empty($_SESSION['ADMISIONES']['PACIENTE']['INGRESO']))
			{
						$Mensaje = 'LOS DATOS DEL INGRESO FUERON GUARDADOS.';
						if($_SESSION['TRIAGE']['TIPO']=='HOSPITALIZACION')
						{
								$this->TerminarIngreso();
								return true;
						}
						/*else
						{  $met=TerminarIngreso;  }*/
			}
			/*elseif(!empty($_SESSION['ADMISIONES']['INGRESO']['ingresotmp']))
			{
					$Mensaje = 'LOS DATOS DEL INGRESO FUERON GUARDADOS.';
					$met=ListarPacientesAdmisiones;
			}*/

			/*$accion=ModuloGetURL('app','Triage','user',$met);
			if(!$this-> FormaMensaje($Mensaje,'ADMISIONES',$accion,''))
			{   return false;   }
			return true;*/
			$this->LlamadoCaja();
			return true;
	}

	function LlamadoCaja()
	{
			list($dbconn) = GetDBconn();
      GLOBAL $ADODB_FETCH_MODE;
			$query = "SELECT a.caja_id, b.sw_todos_cu, b.empresa_id, b.centro_utilidad,b.ip_address,
								b.descripcion as descripcion3, b.tipo_numeracion, d.razon_social as descripcion1,
								e.descripcion as descripcion2, b.cuenta_tipo_id, a.caja_id,b.tipo_numeracion_devoluciones
								FROM cajas_usuarios as a, cajas as b, documentos as c, empresas as d, centros_utilidad as e
								WHERE a.usuario_id=".UserGetUID()." and a.caja_id=b.caja_id
								and b.centro_utilidad='".$_SESSION['TRIAGE']['CENTROUTILIDAD']."' and b.cuenta_tipo_id='01'
								and b.empresa_id=d.empresa_id and d.empresa_id=e.empresa_id
								and b.centro_utilidad=e.centro_utilidad and b.tipo_numeracion=c.documento_id
								order by d.empresa_id, b.centro_utilidad, a.caja_id";
      $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer las 0rdenes de servicios";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->fileError = __FILE__;
					$this->lineError = __LINE__;
					return false;
			}

			if(!$result->EOF)
			{		//tiene cajas
					while($data = $result->FetchRow())
					{
						$caja[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']]=$data;
					}

					$url[0]='app';
					$url[1]='Triage';
					$url[2]='user';
					$url[3]='MenuCaja';
					$url[4]='Caja';
					$arreglo[0]='EMPRESA';
					$arreglo[1]='CENTRO UTILIDAD';
					$arreglo[2]='CAJA';

					$this->salida.= gui_theme_menu_acceso('CAJAS',$arreglo,$caja,$url,ModuloGetURL('app','Triage','user','FormaMenus'));
					return true;
			}
			else
			{		//no tiene cajas
					$Mensaje = 'LOS DATOS DEL INGRESO FUERON GUARDADOS.';
					$accion=ModuloGetURL('app','Triage','user','FormaMenus');
					if(!$this-> FormaMensaje($Mensaje,'ADMISIONES',$accion,''))
					{   return false;   }
					return true;
			}
	}

	function MenuCaja()
	{
			//lo que llega del link
      if(GetIPAddress()!=$_REQUEST['Caja']['ip_address'] AND !empty($_REQUEST['Caja']['ip_address']))
      {
        $accion=ModuloGetURL('app','Triage','user','FormaMenus');
        $this->FormaMensaje('NO PUEDE ACCESAR A LA CAJA [&nbsp;'.$_REQUEST['Caja']['descripcion3'].'&nbsp;] DESDE LA IP [&nbsp;'.GetIPAddress().'&nbsp;]','ERROR DE CONEXION A CAJA',$accion,'MENU');
        return true;
      }

			unset($_SESSION['CAJA']);
			$_SESSION['CAJA']['EMPRESA']=$_REQUEST['Caja']['empresa_id'];
			$_SESSION['CAJA']['CENTROUTILIDAD']=$_REQUEST['Caja']['centro_utilidad'];
			$_SESSION['CAJA']['TIPONUMERACION']=$_REQUEST['Caja']['tipo_numeracion'];
			$_SESSION['CAJA']['CAJAID']=$_REQUEST['Caja']['caja_id'];
			$_SESSION['CAJA']['TIPOCUENTA']=$_REQUEST['Caja']['cuenta_tipo_id'];
			$_SESSION['CAJA']['CU']=$_REQUEST['Caja']['sw_todos_cu'];
			$_SESSION['CAJA']['TIPONUMERACION_DEVOLUCIONES']=$_REQUEST['Caja']['tipo_numeracion_devoluciones'];
			//fin link caja

			list($dbconn) = GetDBconn();
			$query = "SELECT a.tipo_id_paciente, a.paciente_id, b.numerodecuenta, b.plan_id,
								b.rango, b.ingreso, a.fecha_registro
								FROM ingresos as a, cuentas as b
								WHERE a.ingreso=".$_SESSION['ADMISIONES']['PACIENTE']['INGRESO']."
								and a.ingreso=b.ingreso";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error datos cuenta";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->fileError = __FILE__;
					$this->lineError = __LINE__;
					return false;
			}

			$var=$result->GetRowAssoc($ToUpper = false);
			$result->Close();

			$_SESSION['CAJA']['CUENTA']=$var[numerodecuenta];
			$_SESSION['CAJA']['RETORNO']['contenedor']='app';
			$_SESSION['CAJA']['RETORNO']['modulo']='Triage';
			$_SESSION['CAJA']['RETORNO']['tipo']='user';
			$_SESSION['CAJA']['RETORNO']['metodo']='FormaMenus';
			$_SESSION['CAJA']['RETORNO']['argumentos']=array();

			$vector=array('Cuenta'=>$var[numerodecuenta],'TipoId'=>$var[tipo_id_paciente],'PacienteId'=>$var[paciente_id],'Nivel'=>$var[rango],'PlanId'=>$var[plan_id],'FechaC'=>$var[fecha_registro],'Ingreso'=>$var[ingreso]);
			$this->ReturnMetodoExterno('app','CajaGeneral','user','CajaHospitalaria',$vector);
			return true;
	}

//--------------------fin calse-----------------------------------------------------

	function LlamarFormaBuscarTriagesPacientes()
	{
			$this->FormaBuscarTriagesPacientes();
			return true;
	}


	function BuscarTriagePaciente()
	{
				$filtroTipoDocumento = '';
				$filtroDocumento='';
				$filtroNombres='';
				$filtroFecha='';

				if(!empty($_REQUEST['Fecha']) AND empty($_REQUEST['next']))
				{
						$f=explode('/',$_REQUEST['Fecha']);
						$_REQUEST['Fecha']=$f[2].'-'.$f[1].'-'.$f[0];

						$y=$this->ValidarFecha($_REQUEST['Fecha']);
						if(empty($y))
						{
								$this->frmError["FechaI"]=1;
								$this->frmError["MensajeError"]="Formato de Fecha Incorrecto.";
								$this->FormaBuscarTriagesPacientes();
								return true;
						}
				}

				if(!empty($_REQUEST['Fecha']) AND !empty($_REQUEST['Fecha']))
				{  $filtroFecha="and date(a.hora_llegada) = date('".$_REQUEST['Fecha']."')"; }

				if($_REQUEST[TipoDocumento]!='')
				{   $filtroTipoDocumento=" AND a.tipo_id_paciente = '".$_REQUEST[TipoDocumento]."'";   }

				if (!empty($_REQUEST[Documento]))
				{   $filtroDocumento =" AND a.paciente_id LIKE '".$_REQUEST[Documento]."%'";   }

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

      	list($dbconn) = GetDBconn();
		
				if(empty($_REQUEST['Of'])){ $_REQUEST['Of']=0; }
				list($dbconn) = GetDBconn();
				if(empty($_REQUEST['paso']))
				{
					$query = "SELECT b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
									b.tipo_id_paciente, b.paciente_id, a.triage_id
									FROM triages as a, pacientes as b
									WHERE a.empresa_id='".$_SESSION['TRIAGE']['PUNTO']['EMPRESA']."'
									AND a.sw_estado not in('3','0')
									AND a.tipo_id_paciente=b.tipo_id_paciente
									AND a.paciente_id=b.paciente_id
									$filtroDocumento $filtroNombres $filtroFecha";
						$result=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al buscar";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
						}
						if(!$result->EOF)
						{
								$_SESSION['SPYT']=$result->RecordCount();
						}
						$result->Close();
				}

				$query = "SELECT b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
									b.tipo_id_paciente, b.paciente_id, a.triage_id, a.hora_llegada
									FROM triages as a, pacientes as b
									WHERE a.empresa_id='".$_SESSION['TRIAGE']['PUNTO']['EMPRESA']."'
									AND a.sw_estado not in('3','0')
									AND a.tipo_id_paciente=b.tipo_id_paciente
									AND a.paciente_id=b.paciente_id
									$filtroDocumento $filtroNombres $filtroFecha
									order by b.tipo_id_paciente, b.paciente_id
									LIMIT ".$this->limit." OFFSET ".$_REQUEST['Of']."";
									
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Tabal autorizaiones";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}
				if(!$result->EOF)
				{
						while(!$result->EOF)
						{
										$var[]=$result->GetRowAssoc($ToUpper = false);
										$result->MoveNext();
						}
				}
				else
				{
					$this->frmError["MensajeError"]="La Busqueda no Arrojo Resultados.";
				}

				$this->FormaBuscarTriagesPacientes($var);
				return true;
	}


	function ConsultaTriageExt()
	{
			unset($_SESSION['ADMISIONES']['PACIENTE']);
			$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
			$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
			$_SESSION['ADMISIONES']['PACIENTE']['triage_id']=$_REQUEST['triage'];
			$_SESSION['ADMISIONES']['PACIENTE']['nombre']=$_REQUEST['nombre'];

			$_SESSION['ADMISIONES']['TRIAGE']['RETORNO']['contenedor']='app';
			$_SESSION['ADMISIONES']['TRIAGE']['RETORNO']['modulo']='Triage';
			$_SESSION['ADMISIONES']['TRIAGE']['RETORNO']['tipo']='user';
			$_SESSION['ADMISIONES']['TRIAGE']['RETORNO']['metodo']='BuscarTriagePaciente';
			$_SESSION['ADMISIONES']['TRIAGE']['RETORNO']['argumentos']=array('next'=>1,'TipoDocumento'=>$_REQUEST['TipoDocumento'],'Fecha'=>$_REQUEST['Fecha'],'Documento'=>$_REQUEST['Documento'],'Nombres'=>$_REQUEST['Nombres']);

			$this->ReturnMetodoExterno('app','Admisiones','user','ConsultaTriageExt');
			return true;
	}
	
	function BorrarProceso()
	{
			list($dbconn) = GetDBconn();
			$query = "DELETE FROM triages_proceso WHERE usuario_id=".UserGetUID()."";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error DELETE FROM hc_os_autorizaciones_proceso";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}		
			return true;
	}

	  /**
		* Busca los niveles del triage
    * @access public
    * @return array
    */
    function ObtenerNivelesTriage()
    {
			$sql  = "SELECT * FROM niveles_triages ";
			$sql .= "WHERE	nivel_triage_id !='0' ";
			$sql .= "ORDER BY indice_de_orden ";
			
			list($dbconn) = GetDBconn();
			$rst = $dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->Close();
			return $datos;
    }
}//fin clase user
?>