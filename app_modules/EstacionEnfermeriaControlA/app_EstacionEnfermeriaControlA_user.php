<?
/**
*		class app_EstacionEnfermeria_user
*
*		Clase que maneja todas los metodos que llaman a las vistas relacionadas a la estación de Enfermería
*		ubicadas en la clase hija html
*		ubicacion => app_modules/EstacionEnfermeria/app_EstacionEnfermeria_user.php
*		fecha creación => 04/05/2004 10:35 am
*
*		@Author => jairo Duvan Diaz Martinez
*		@version =>
*		@package SIIS
*/
class app_EstacionEnfermeriaControlA_user extends classModulo
{
	var $frmError = array();


	/**
	*		app_EstacionEnfermeria_user()
	*
	*		constructor
	*
	*		@Author Darling Dorado
	*		@access Public
	*		@return bool
	*/
	function app_EstacionEnfermeriaControlA_user()//Constructor padre
	{
		return true;
	}

	/**
	*
	*/
	function main($estacion,$tipo)
	{
		return true;
	}//FIN main


	/**
		*		CallControlesPacientes
		*
		*		Lista todos los pacientes de estacion y dependiendo del control, muestra
		*		el listado de los controles de los pacientes
		*
		*		@Author Arley Velasquez C
		*		@access Public
		*		@return bool
		*/
		function CallControlesPacientes($estacion,$descripcion,$datos_estacion)
		{
		if(!$estacion)
		{
			  $estacion = $_REQUEST['estacion'];
				$datos_estacion = $_REQUEST['datos_estacion'];
				$descripcion = $datos_estacion['control_descripcion'];
			//	print_r($datos_estacion);
		}

//print_r($_REQUEST['estacion']);
//exit;
			//if(!$this->ControlesPacientes($caso,$estacion,$control,$_REQUEST[paciente]))
			$this->ControlesPacientes($estacion,$descripcion,$datos_estacion);
			return true;
		}


		function CallFrmSolicitudE($paciente,$estacion)
		{
		//print_r($_REQUEST);
		if(empty($paciente))
		{$paciente=$_REQUEST['paciente'];$estacion=$_REQUEST['estacion'];}
		$this->FrmSolicitudE($paciente,$estacion,$datos_estacion);
		return true;
		}




		function InsertarControlE()
		{

			list($dbconn) = GetDBconn();
			$paciente=$_REQUEST['paciente'];
			$estacion=$_REQUEST['estacion'];

			if(empty($_REQUEST['fech']))
			{
				$this->frmError["MensajeError"]="FALTAN DATOS NECESARIOS";
				$this->CallFrmSolicitudE($paciente,$estacion);
				return true;
			}

		 $_REQUEST['fech']=str_replace("/","-",$_REQUEST['fech']);

			//revisar formato
			$control=explode("-",$_REQUEST['fech']);


			if($control[1] >12)
			{
				$this->frmError["MensajeError"]="FORMAT0 DE FECHA ERRONEO,DEBE SER DD-MM-AA !";
				$this->CallFrmSolicitudE($paciente,$estacion);
				return true;
			}

			if($control[0] >31)
			{
				$this->frmError["MensajeError"]="FORMAT0 DE FECHA ERRONEO,DEBE SER DD-MM-AA !";
				$this->CallFrmSolicitudE($paciente,$estacion);
				return true;
			}

			if(strlen($control[0])>2)
			{
				$fecha=$_REQUEST['fech'];
			}
			elseif(strlen($control[2])>2)
			{
				$_REQUEST['fech']=str_replace("/","-",$_REQUEST['fech']);
				$f=explode("-",$_REQUEST['fech']);
				$fecha=$f[2]."-".$f[1]."-".$f[0];
			}
			else
			{
				$this->frmError["MensajeError"]="FORMAT0 DE FECHA ERRONEO !";
				$this->CallFrmSolicitudE($paciente,$estacion);
				return true;
			}

			if(strtotime($fecha)< strtotime(date("Y-m-d")))
			{
				$this->frmError["MensajeError"]="LA FECHA DEBE SER CORRECTA, DE HOY O POSTERIOR";
				$this->CallFrmSolicitudE($paciente,$estacion);
				return true;
			}
			 $fecha.=' '.$_REQUEST['hora'].':'.$_REQUEST['min'].':'.'00';
      //echo "<br>".$fecha='2004-06-24 05:00:00';exit;
			if($_REQUEST['ayuno']==on)
			{$sw_ayuno='1';}else{$sw_ayuno='0';};


			$query="SELECT COUNT(*) FROM
								hc_solicitudes_dietas_ayunos WHERE ingreso= ".$paciente['ingreso']."
								AND fecha='".$fecha."'";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					if($result->fields[0]>0){$sw_ayuno='0';}


			  $querys="INSERT INTO
							hc_control_apoyosd_pendientes
							(ingreso,fecha,sw_ayuno,observacion,usuario_id,fecha_registro)
							VALUES(".$paciente['ingreso'].",'$fecha','$sw_ayuno','".$_REQUEST['obs']."'
							,".UserGetUID().",'".date("Y-m-d H:m")."')	";
					$resulta = $dbconn->Execute($querys);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar el hc_control_apoyod_pendientes";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			//esto es por que esta asignado una ayuno a esta persona
			//entonces saldra en prescripcion de dietas..
			if($sw_ayuno=='1')
			{

				$query="SELECT COUNT(*) FROM
								hc_solicitudes_dietas_ayunos WHERE ingreso= ".$paciente['ingreso']."
								AND fecha='".$fecha."'";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					if($result->fields[0]<1)
					{

					 $query="INSERT INTO
									hc_solicitudes_dietas_ayunos
									(ingreso,fecha,motivo,usuario_id,hora_inicio_ayuno,hora_fin_ayuno,fecha_registro)
									VALUES(".$paciente['ingreso'].",'$fecha','Solicitud Examen',".UserGetUID().",'".date("H:m")."','".date("H:m")."','".date("Y-m-d H:m")."')	";
							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
									$this->error = "Error al insertar hc_solicitudes_dietas_ayunos ";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}
					}
			}
				$mensaje ="CUMPLIMIENTO DE LA PROGRAMACION REALIZADA CON EXITO";
				$titulo = "MENSAJE DE CONFIRMACION";
				$accion = ModuloGetURL('app','EstacionEnfermeriaControlA','user','CallControlesPacientes',array("estacion"=>$estacion,"datos_estacion"=>$paciente));
				$boton = "VOLVER AL LISTADO";
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
		}


		function RevisarAyunoProgramacion($ingreso,$fecha)
		{			list($dbconn) = GetDBconn();
					$fecha1=explode(" ",$fecha);
					$query="SELECT COUNT(*) FROM
								hc_solicitudes_dietas_ayunos WHERE ingreso= '".$ingreso."'
								AND fecha='".$fecha1[0]."'";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar el hc_control_apoyod_pendientes";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
					return $result->fields[0];
		}




		function GetExamenes($ingreso)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT  a.fecha,a.evolucion_id,d.descripcion,
							f.sw_estado,retornartitulo_apoyod(b.cargo) as des
							FROM
							hc_evoluciones a,
							hc_os_solicitudes_apoyod c,
							apoyod_tipos d,hc_os_solicitudes b
							LEFT JOIN hc_os_autorizaciones e on
							(e.hc_os_solicitud_id =b.hc_os_solicitud_id)
							LEFT JOIN autorizaciones f on(e.autorizacion_int=f.autorizacion)


							WHERE
							a.ingreso='$ingreso'
							AND
							a.evolucion_id=b.evolucion_id
							AND
							c.hc_os_solicitud_id=b.hc_os_solicitud_id
							AND
							c.apoyod_tipo_id=d.apoyod_tipo_id";

							$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$i=0;
				while(!$result->EOF)
				{
					$var[$i]= $result->GetRowAssoc($ToUpper = false);
					$i++;
          $result->MoveNext();
				}
			return $var;
		}


	function CumplirProgramacion($ingreso)
		{
		$estacion=$_SESSION['CONTROLA']['ESTACIONX'];
//print_r($_REQUEST['datos_estacion']);
//print_R(urldecode($estacion));exit;
//echo  "-->".print_r($_SESSION['CONTROLA']['ESTACIONX']);exit;
		$hc_control=$_REQUEST['id'];
		$ingreso=$_REQUEST['ingreso'];
			list($dbconn) = GetDBconn();
			$query="UPDATE hc_control_apoyosd_pendientes
							SET usuario_confirma=".UserGetUID().",
							fecha_registro_confirma='".date("Y-m-d H:m:s")."'
							WHERE ingreso='$ingreso' AND 	hc_control_pend_id=$hc_control";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				unset($_SESSION['CONTROLA']['ESTACIONX']);
				$mensaje ="CUMPLIMIENTO DE LA PROGRAMACION REALIZADA CON EXITO";
				$titulo = "MENSAJE DE CONFIRMACION";
				$accion = ModuloGetURL('app','EstacionEnfermeriaControlA','user','CallControlesPacientes',array("estacion"=>$estacion,"datos_estacion"=>$_REQUEST['datos_estacion']));
				$boton = "VOLVER AL LISTADO";
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
		}




		function GetFechaProgramacion($ingreso)
		{
			list($dbconn) = GetDBconn();
		 $query="SELECT  fecha,observacion,hc_control_pend_id,sw_ayuno
							FROM  hc_control_apoyosd_pendientes
							WHERE ingreso='$ingreso' AND usuario_confirma ISNULL
							ORDER BY fecha asc";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$i=0;
				while(!$result->EOF)
				{
					$var[$i]= $result->GetRowAssoc($ToUpper = false);
					$i++;
          $result->MoveNext();
				}
			return $var;
		}
//-----------------------------------------------------------------------------
}//fin class
?>

