<?php
// hc_modules/classes/hc_classes_PlanTerapeutico.php  23/10/2003
// ----------------------------------------------------------------------
// Autor: Arley Velásquez C.
// Proposito: Manejador del plan terapeutico de los pacientes.
// $Id: hc_PlanTerapeutico.php,v 1.23 2006/05/23 22:58:02 tizziano Exp $
// ----------------------------------------------------------------------

class PlanTerapeutico extends hc_classModules
{
	var $limit;
	var $conteo;

		function PlanTerapeutico() //Constructor Padre
		{
      //LO AGREGO CLAUDIA
			$this->hc_classModules(); //constructor del padre
			$this->limit=GetLimitBrowser();
      //FIN AGREGO CLAUDIA

			$this->frmError = array();
			$this->error='';
			$this->empresa=SessionGetVar('SYSTEM_USUARIO_EMPRESA');
			$this->user_id=UserGetUID();

			return true;
		}//End function

		/*
		* GetConsulta() llama a la funcion FrmConsulta del submoduloHijo HTML para obtiener el
		* HTML de listado y lo retorna a la funcion xxx del modulo
		*/
		function GetConsulta()//Obtiene el HTMLde tipo consulta
		{
			$this->FrmConsulta();
			return $this->salida;
		}//End function


/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

	function GetVersion()
	{
		$informacion=array(
		'version'=>'1',
		'subversion'=>'0',
		'revision'=>'0',
		'fecha'=>'01/27/2005',
		'autor'=>'TIZZIANO PEREA OCORO',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
	}


/**
* Esta metodo captura los datos de la impresión de la Historia Clinica.
* @access private
* @return text Datos HTML de la pantalla.
*/

	function GetReporte_Html()
	{
		$imprimir=$this->frmHistoria();
		if($imprimir==false)
		{
			return true;
		}
		return $imprimir;
	}



/**
* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
*
* @access private
* @return text Datos HTML de la pantalla.
*/

	function GetEstado()
	{
		$pfj=$this->frmPrefijo;
        	list($dbconn) = GetDBconn();
		$query="SELECT count(*)
			FROM hc_medicamentos_recetados_hosp
			WHERE evolucion_id=".$this->evolucion.";";
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
			$estado=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		
		if ($estado[count] == 0)
		{
			return false;
		}
		else
		{
		 	return true;
		}
	}


		/*
		* GetForma() llama a la funcion FrmForma del submoduloHijo HTML para obtiener el
		* HTML del formulario y lo retorna a la funcion xxx del modulo
		*/
		function GetForma()
		{
			$pfj=$this->frmPrefijo;
			$action='';
			if (!empty($_REQUEST['subModuloAction']))
			{
				$action=$_REQUEST['subModuloAction'];
			}
			if (!empty($_REQUEST['accion'.$pfj]))
			{
				$action=$_REQUEST['accion'.$pfj];
			}
			$this->FrmForma($action);
			return $this->salida;
		}//End function




		function ValidaDatosMed()
		{
			if ($_REQUEST[$this->frmPrefijo."CANCELAR"]==="Cancelar"){
				unset($_REQUEST['subModuloAction']);
				$this->GetForma();
				return true;
 			}

			if ($_REQUEST[$this->frmPrefijo."horaTiempo"] == "horas" && $_REQUEST[$this->frmPrefijo."horaNumeros"])
				$minutos = ($_REQUEST[$this->frmPrefijo."horaNumeros"] * 60);
			else
				$minutos = $_REQUEST[$this->frmPrefijo."horaNumeros"];
			if (empty($minutos))
				$minutos=0;

			if (empty($_REQUEST[$this->frmPrefijo."datosIdMedicamento"])){
				$this->frmError[$this->frmPrefijo."MedicamentoID"]=1;
				$this->error=1;
			}
			if ($_REQUEST[$this->frmPrefijo."viaAdm"]=='-1'){
				$this->frmError[$this->frmPrefijo."ViaAdmon"]=1;
				$this->error=1;
			}
			if (empty($_REQUEST[$this->frmPrefijo."cantidadTotal"])){
				$this->frmError[$this->frmPrefijo."cantidadTotal"]=1;
				$this->error=1;
			}
			if (empty($_REQUEST[$this->frmPrefijo."cantidad"])){
				$this->frmError[$this->frmPrefijo."cantidad"]=1;
				$this->error=1;
			}
			if (empty($_REQUEST[$this->frmPrefijo."Opciones_Pt"])){
				$this->frmError[$this->frmPrefijo."Opciones_Pt"]=1;
				$this->error=1;
			}
			else
			{

				switch ($_REQUEST[$this->frmPrefijo."Opciones_Pt"])
				{
					case 1:
									if ($_REQUEST[$this->frmPrefijo."horaTiempo"]=="-1" || empty($_REQUEST[$this->frmPrefijo."horaNumeros"]))
									{
										$this->frmError[$this->frmPrefijo."Opcion1"]=1;
										$this->error=1;
									}
					break;
					case 2:
									if ( (empty($_REQUEST[$this->frmPrefijo."Duracion"]) && (!empty($_REQUEST[$this->frmPrefijo."Desayuno"]) || !empty($_REQUEST[$this->frmPrefijo."Almuerzo"]) || !empty($_REQUEST[$this->frmPrefijo."Cena"])) ) || (!empty($_REQUEST[$this->frmPrefijo."Duracion"]) && empty($_REQUEST[$this->frmPrefijo."Desayuno"]) && empty($_REQUEST[$this->frmPrefijo."Almuerzo"]) && empty($_REQUEST[$this->frmPrefijo."Cena"])) || (empty($_REQUEST[$this->frmPrefijo."Duracion"]) && empty($_REQUEST[$this->frmPrefijo."Desayuno"]) && empty($_REQUEST[$this->frmPrefijo."Almuerzo"]) && empty($_REQUEST[$this->frmPrefijo."Cena"])) )
									{
										$this->frmError[$this->frmPrefijo."Opcion2"]=1;
										$this->error=1;
									}
					break;
					case 3:
									if ($_REQUEST[$this->frmPrefijo."Durante"]=="-1")
									{
										$this->frmError[$this->frmPrefijo."Opcion3"]=1;
										$this->error=1;
									}
					break;
					case 4:
									if (empty($_REQUEST[$this->frmPrefijo."HEspecifica"]))
									{
										$this->frmError[$this->frmPrefijo."Opcion4"]=1;
										$this->error=1;
									}
					break;
				}
			}

			if (!empty($this->error)){
				$this->frmError["MensajeError"]="Verfique los campos en rojo";
				return false;
			}

			if ($_REQUEST[$this->frmPrefijo."datosEsPos"]==='NO' && empty($_REQUEST[$this->frmPrefijo.'JustificacionMedicamento'])){
				$_REQUEST[$this->frmPrefijo."ADDJ"]=1;
				SessionDelVar("REQUEST_MED_JUST");
				SessionDelVar("REQUEST_DATOS_JUST");
				$_REQUEST["DatMed"]=$_REQUEST;
				$this->FrmForma($this->frmPrefijo.'AddJust');
				return true;
			}
			else{
				if (!$this->InsertDatos()){
					return false;
				}
			}

			return true;
		}



		/*
		* InsertDatos()
		* Valida e Inserta los datos en la base de datos
		*/
		function InsertDatos()
		{
			list($dbconn) = GetDBconn();
			$datos_emp=$this->GetDatosEmpresas();
			$hesp="";
			$pos=0;
			$sw_estado=2;//Estado activo del medicamento

			if ($_REQUEST[$this->frmPrefijo."horaTiempo"] == "horas" && $_REQUEST[$this->frmPrefijo."horaNumeros"])
				$minutos = ($_REQUEST[$this->frmPrefijo."horaNumeros"] * 60);
			else
				$minutos = $_REQUEST[$this->frmPrefijo."horaNumeros"];
			if (empty($minutos))
				$minutos=0;

			if ($_REQUEST[$this->frmPrefijo."datosEsPos"]==='NO'){
				$pos=0;
			}
			else{
				$pos=1;
			}

			switch ($_REQUEST[$this->frmPrefijo."Opciones_Pt"])
			{
				case 1:
								$query = "INSERT INTO hc_medicamentos_recetados (medicamento_id,cantidad_total,via_administracion_id,cantidad,horario,observaciones,indicacion_suministro,fecha,evolucion_id,sw_estado,sw_pos,usuario_id,empresa_id,centro_utilidad,bodega,unidad_dosis)
													VALUES ('".$_REQUEST[$this->frmPrefijo."datosIdMedicamento"]."',".$_REQUEST[$this->frmPrefijo."cantidadTotal"].",'".$_REQUEST[$this->frmPrefijo."viaAdm"]."',".$_REQUEST[$this->frmPrefijo."cantidad"].",$minutos,'".$_REQUEST[$this->frmPrefijo."comentario"]."','".$_REQUEST[$this->frmPrefijo."IndSuministro"]."','".$_REQUEST[$this->frmPrefijo."datosFecha"]."',".$this->evolucion.",'$sw_estado','$pos',".$this->user_id.",'".$this->empresa."','".$datos_emp['centro_utilidad']."','".$_REQUEST[$this->frmPrefijo."datosBodega"]."','".$_REQUEST[$this->frmPrefijo."Uds"]."')";
				break;
				case 2:
								$query = "INSERT INTO hc_medicamentos_recetados (medicamento_id,cantidad_total,via_administracion_id,cantidad,horario,desayuno,almuerzo,comida,sw_rango,observaciones,indicacion_suministro,fecha,evolucion_id,sw_estado,sw_pos,usuario_id,empresa_id,centro_utilidad,bodega,unidad_dosis)
													VALUES ('".$_REQUEST[$this->frmPrefijo."datosIdMedicamento"]."',".$_REQUEST[$this->frmPrefijo."cantidadTotal"].",'".$_REQUEST[$this->frmPrefijo."viaAdm"]."',".$_REQUEST[$this->frmPrefijo."cantidad"].",$minutos,'".$_REQUEST[$this->frmPrefijo."Desayuno"]."','".$_REQUEST[$this->frmPrefijo."Almuerzo"]."','".$_REQUEST[$this->frmPrefijo."Cena"]."','".$_REQUEST[$this->frmPrefijo."Duracion"]."','".$_REQUEST[$this->frmPrefijo."comentario"]."','".$_REQUEST[$this->frmPrefijo."IndSuministro"]."','".$_REQUEST[$this->frmPrefijo."datosFecha"]."',".$this->evolucion.",'$sw_estado','$pos',".$this->user_id.",'".$this->empresa."','".$datos_emp['centro_utilidad']."','".$_REQUEST[$this->frmPrefijo."datosBodega"]."','".$_REQUEST[$this->frmPrefijo."Uds"]."')";
				break;
				case 3:
								$query = "INSERT INTO hc_medicamentos_recetados (medicamento_id,cantidad_total,via_administracion_id,cantidad,horario,observaciones,indicacion_suministro,fecha,evolucion_id,sw_estado,sw_pos,usuario_id,empresa_id,centro_utilidad,bodega,duracion_id,unidad_dosis)
													VALUES ('".$_REQUEST[$this->frmPrefijo."datosIdMedicamento"]."',".$_REQUEST[$this->frmPrefijo."cantidadTotal"].",'".$_REQUEST[$this->frmPrefijo."viaAdm"]."',".$_REQUEST[$this->frmPrefijo."cantidad"].",$minutos,'".$_REQUEST[$this->frmPrefijo."comentario"]."','".$_REQUEST[$this->frmPrefijo."IndSuministro"]."','".$_REQUEST[$this->frmPrefijo."datosFecha"]."',".$this->evolucion.",'$sw_estado','$pos',".$this->user_id.",'".$this->empresa."','".$datos_emp['centro_utilidad']."','".$_REQUEST[$this->frmPrefijo."datosBodega"]."','".$_REQUEST[$this->frmPrefijo."Durante"]."','".$_REQUEST[$this->frmPrefijo."Uds"]."')";
				break;
				case 4:
								$hesp="{";
								foreach($_REQUEST[$this->frmPrefijo."HEspecifica"] as $key => $value){
									if (!empty($_REQUEST[$this->frmPrefijo."HEspecifica"][$key+1]))
										$hesp.=$value.",";
									else
										$hesp.=$value;
								}
								$hesp.="}";
								$query = "INSERT INTO hc_medicamentos_recetados (medicamento_id,cantidad_total,via_administracion_id,cantidad,horario,observaciones,indicacion_suministro,fecha,evolucion_id,sw_estado,sw_pos,usuario_id,empresa_id,centro_utilidad,bodega,hora_especifica,unidad_dosis)
													VALUES ('".$_REQUEST[$this->frmPrefijo."datosIdMedicamento"]."',".$_REQUEST[$this->frmPrefijo."cantidadTotal"].",'".$_REQUEST[$this->frmPrefijo."viaAdm"]."',".$_REQUEST[$this->frmPrefijo."cantidad"].",$minutos,'".$_REQUEST[$this->frmPrefijo."comentario"]."','".$_REQUEST[$this->frmPrefijo."IndSuministro"]."','".$_REQUEST[$this->frmPrefijo."datosFecha"]."',".$this->evolucion.",'$sw_estado','$pos',".$this->user_id.",'".$this->empresa."','".$datos_emp['centro_utilidad']."','".$_REQUEST[$this->frmPrefijo."datosBodega"]."','$hesp','".$_REQUEST[$this->frmPrefijo."Uds"]."')";
				break;
			}

			$resultado=$dbconn->Execute($query);
				if (!$resultado)
				{
					$this->frmError[$this->frmPrefijo."MedicamentoID"]=1;
					if (!empty($this->error))
					{
						die(MsgOut($Modulo->error,$Modulo->mensajeDeError));
					}
					$this->frmError["MensajeError"]="El codigo del medicamento ya se encuentra en la formula medica";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}

			if ($_REQUEST[$this->frmPrefijo."datosEsPos"]==='SI'){
				unset($_REQUEST['subModuloAction']);
				$this->GetForma();
			}

			return true;
		}//End function



		function InsertarMezcla()
		{
			list($dbconn) = GetDBconn();
			$datos_emp=$this->GetDatosEmpresas();

			foreach(SessionGetVar($this->frmPrefijo.'REQUEST_BKUP') as $key => $value){
				if (!array_key_exists($key,$_REQUEST)){
					$_REQUEST[$key]=$value;
				}
			}

			$fecha= $_REQUEST[$this->frmPrefijo."datosFecha"];
			$cant = $_REQUEST[$this->frmPrefijo."datosCantidadMedicamento"];
			$frecuencia=$_REQUEST[$this->frmPrefijo."frecuencia"];
			$udsCalculo=$_REQUEST[$this->frmPrefijo."UdsCalculo"];
			$calculo=$_REQUEST[$this->frmPrefijo."calculo"];
			$observacion=$_REQUEST[$this->frmPrefijo."ObservacionesMezcla"];


			if (empty($frecuencia) && $_REQUEST[$this->frmPrefijo."SelectFr"]==="Horas")
			{
				$this->frmError[$this->frmPrefijo."Frecuencia"]=1;
				$this->error=1;
			}
			if (empty($cant))
			{
				$this->frmError[$this->frmPrefijo."cantidadMedicamento"]=1;
				$this->error=1;
			}
			if ($udsCalculo=='-1')
			{
				$this->frmError[$this->frmPrefijo."Conversion"]=1;
				$this->error=1;
			}

			if (!empty($this->error))
			{
				$this->frmError["MensajeError"]="Verfique los campos en rojo";
				$this->FrmForma($this->frmPrefijo."AddLiquidosP");
				return true;
			}

			if (empty($frecuencia)){
				$frecuencia=0;
			}

			if (!SessionIsSetVar($this->frmPrefijo.'DAT_MEZCLA') && !SessionIsSetVar($this->frmPrefijo.'MTZ_MEZCLAS')){
				unset($_REQUEST['subModuloAction']);
				$this->frmError["MensajeError"]="No existen liquidos parenterales para insertar.";
				$this->FrmForma($this->frmPrefijo."AddLiquidosP");
				return true;
			}


			$mtz_mezclas=array(0=>$this->frmPrefijo.'MTZ_MEZCLAS',1=>$this->frmPrefijo.'MTZ_MEZCLASB',2=>$this->frmPrefijo.'MTZ_MEDICAMENTOS_BODEGA',3=>$this->frmPrefijo.'DAT_MEZCLA',4=>$this->frmPrefijo.'REQUEST_BKUP');
			$serie="SELECT nextval('public.hc_mezclas_recetadas_mezcla_recetada_id_seq'::text)";
			$resultado=$this->Verifica_Conexion($serie,$dbconn);
				if (!$resultado) {
					$this->error = "Error al tratar de consultar la serie en \"hc_mezclas_recetadas\"<br>";
					$this->mensajeDeError = $serie."<br>".$dbconn->ErrorMsg();
					$this->DelSessionMezclas($mtz_mezclas);
					return false;
				}
			$serie=$resultado->fields[0];


			$query = "INSERT INTO hc_mezclas_recetadas(mezcla_recetada_id,cantidad,frecuencia,cantidad_calculo,unidad_calculo,observaciones,evolucion_id,usuario_id,sw_estado,fecha)
								VALUES ($serie,$cant,$frecuencia,$calculo,'$udsCalculo','$observacion',".$this->evolucion.",".$this->user_id.",'2','$fecha')";

			$bodegas=$this->Bodegas();
			$dbconn->BeginTrans();
			$resultado=$dbconn->Execute($query);
				if ($resultado)
				{
					foreach($_SESSION[$this->frmPrefijo.'DAT_MEZCLA'] as $key => $value){
						foreach($value['D_MEZCLA'] as $key1 => $valor){
							$queryMB="SELECT pos FROM medicamentos_bodega WHERE codigo_producto='".$valor['codigo']."' AND empresa_id='".$datos_emp['empresa_id']."' AND centro_utilidad='".$datos_emp['centro_utilidad']."' AND bodega='".$bodegas[0]['bodega']."'";
							$resultadoMB=$this->Verifica_Conexion($queryMB,$dbconn);
								if (!$resultadoMB) {
									$this->error = "Error al tratar de consultar el medicamento en la vista \"medicamentos_bodega\" con el codigo \"".$valor['codigo']."\" de la empresa \"".$datos_emp['empresa_id']."\" con el centro de utilidad \"".$datos_emp['centro_utilidad']."\" y la bodega \"".$bodegas[0]['bodega']."\"<br>";
									$this->mensajeDeError = $queryMB."<br>".$dbconn->ErrorMsg();
									$this->DelSessionMezclas($mtz_mezclas);
									return false;
								}
							$dataMB=$resultadoMB->FetchNextObject($toupper=false);
							$query = "INSERT INTO hc_mezclas_recetadas_medicamentos(mezcla_recetada_id,medicamento_id,empresa_id,centro_utilidad,bodega,cantidad,sw_pos,indicacion_suministro)
												VALUES ($serie,'".$valor['codigo']."','".$datos_emp['empresa_id']."','".$datos_emp['centro_utilidad']."','".$bodegas[0]['bodega']."','".$valor['cantidad']."','".$dataMB->pos."','".$valor['ind_suministro']."')";

							$resultado=$dbconn->Execute($query);
							if (!$resultado)
							{
								$this->error = "Error al ejecutar la consulta";
								$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								$this->DelSessionMezclas($mtz_mezclas);
								return false;
							}
						}
					}
					foreach($_SESSION[$this->frmPrefijo.'MTZ_MEZCLAS'] as $key => $value){
						$queryMB="SELECT pos FROM medicamentos_bodega WHERE codigo_producto='".$value['codigo']."' AND empresa_id='".$datos_emp['empresa_id']."' AND centro_utilidad='".$datos_emp['centro_utilidad']."' AND bodega='".$_SESSION[$this->frmPrefijo.'MTZ_MEDICAMENTOS_BODEGA'][$key]["bodegas"]."'";
						$resultadoMB=$this->Verifica_Conexion($queryMB,$dbconn);
							if (!$resultadoMB) {
								$this->error = "Error al tratar de consultar el medicamento en la vista \"medicamentos_bodega\" con el codigo \"".$value['codigo']."\" de la empresa \"".$datos_emp['empresa_id']."\" con el centro de utilidad \"".$datos_emp['centro_utilidad']."\" y la bodega \"".$bodegas[0]['bodega']."\"<br>";
								$this->mensajeDeError = $queryMB."<br>".$dbconn->ErrorMsg();
								$this->DelSessionMezclas($mtz_mezclas);
								return false;
							}
						$dataMB=$resultadoMB->FetchNextObject($toupper=false);
						$query = "INSERT INTO hc_mezclas_recetadas_medicamentos(mezcla_recetada_id,medicamento_id,empresa_id,centro_utilidad,bodega,cantidad,sw_pos)
											VALUES ($serie,'".$value['codigo']."','".$datos_emp['empresa_id']."','".$datos_emp['centro_utilidad']."','".$_SESSION[$this->frmPrefijo.'MTZ_MEZCLASB'][$key]['bodega']."','".$value['cantidad']."','".$dataMB->pos."')";
						
                              $resultado=$dbconn->Execute($query);
						if (!$resultado)
						{
							$this->error = "Error al ejecutar la consulta";
							$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							$this->DelSessionMezclas($mtz_mezclas);
							return false;
						}
					}
					$dbconn->CommitTrans();
				}
				else
				{
					$this->error = "Error al ejecutar la consulta.";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					$this->DelSessionMezclas($mtz_mezclas);
					return false;
				}

			$this->DelSessionMezclas($mtz_mezclas);
			unset($_REQUEST['subModuloAction']);
			$this->FrmForma('');
			return true;
		}



		function DelSessionMezclas($mezcla)
		{
			foreach ($mezcla as $key => $value){
				SessionDelVar($value);
			}
		}



		function GetDatosEmpresas()
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$datos=array();
			$query = "SELECT *
								FROM departamentos
								WHERE departamento='".$this->departamento."'";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado)
				{
					$this->error = "Error al tratar de realizar la consulta.<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}

			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $resultado->FetchRow();
		}


		function Bodegas()
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$bodegas=array();
			$datos=$this->GetDatosEmpresas();
			$query = "SELECT  b.bodega,
												b.descripcion,
												b.empresa_id,
												b.centro_utilidad,
												a.estacion_id
								FROM  bodegas_estaciones a,
											bodegas b
								WHERE b.bodega=a.bodega AND
											b.centro_utilidad=a.centro_utilidad AND
											b.empresa_id=a.empresa_id AND
											a.estacion_id='".$this->estacion."' AND
											a.empresa_id='".$datos['empresa_id']."' AND
											a.centro_utilidad='".$datos['centro_utilidad']."' AND
											b.estado='1'";
			
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado)
				{
					$this->error = "Error al tratar de realizar la consulta.<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}

				while ($data = $resultado->FetchRow()) {
					$bodegas[]=$data;
				}//End While
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $bodegas;
		}


		function InsertarSuspension()
		{
			$fecha=date("Y-m-d H:i:s");
			list($dbconn) = GetDBconn();
			$DatoMedicamento=$mezcla=array();
			$DatoMedicamento=$_POST[$this->frmPrefijo."MedID"];
			$evolucion=$_POST[$this->frmPrefijo."EvoId"];
			$mezcla=$_POST[$this->frmPrefijo."Mezcla"];
			$nota_suspension=$_POST[$this->frmPrefijo."NotaSuspension"];

			if (empty($nota_suspension))
			{
				$this->frmError[$this->frmPrefijo."NotaSuspension"]=1;
				$this->error=1;
			}
			if (!empty($this->error))
			{
				$this->frmError["MensajeError"]="Verfique los campos en rojo";
				$this->FrmForma($this->frmPrefijo."Suspender");
				return true;
			}

			if (empty($mezcla)) {
				$query = "UPDATE hc_medicamentos_recetados SET sw_estado='0', nota_suspension='".$nota_suspension."' WHERE evolucion_id=".$evolucion." AND medicamento_id='".$DatoMedicamento."' ";

				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al tratar de cambiar el estado (sw_estado='1') en \"hc_medicamentos_recetados\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			else {
				$query = "UPDATE hc_mezclas_recetadas SET sw_estado='0', nota_suspension='".$nota_suspension."' WHERE evolucion_id=".$evolucion." AND mezcla_recetada_id='".$DatoMedicamento."' ";
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al tratar de cambiar el estado (sw_estado='1') en \"hc_mezclas_recetadas\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			unset($_REQUEST['subModuloAction']);
			$this->FrmForma('');
			return true;
		}


		function Finalizar()
		{
			list($dbconn) = GetDBconn();
			$DatoMedicamento=$mezcla=array();
			$DatoMedicamento=$_REQUEST[$this->frmPrefijo."Medicamentos"];
			$mezcla=$_REQUEST[$this->frmPrefijo."Mezcla"];

			if (empty($mezcla)) {
				$query = "UPDATE hc_medicamentos_recetados SET sw_estado='1' WHERE evolucion_id=".$DatoMedicamento['EvoId']." AND medicamento_id='".$DatoMedicamento['MedID']."' ";
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al tratar de cambiar el estado (sw_estado='1') en \"hc_medicamentos_recetados\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			else {
				$query = "UPDATE hc_mezclas_recetadas SET sw_estado='1' WHERE evolucion_id=".$DatoMedicamento['EvoId']." AND mezcla_recetada_id='".$DatoMedicamento['MedID']."' ";
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al cambiar el estado (sw_estado='1') de \"hc_mezclas_recetadas\" en el query\n<br>$query";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			unset($_REQUEST['subModuloAction']);
			$this->FrmForma('');
			return true;
		}




		function Continuar()
		{
			list($dbconn) = GetDBconn();
			$DatoMedicamento=$mezcla=array();
			$DatoMedicamento=$_REQUEST[$this->frmPrefijo."Medicamentos"];
			$mezcla=$_REQUEST[$this->frmPrefijo."Mezcla"];

			if (empty($mezcla)) {
				$query = "UPDATE hc_medicamentos_recetados SET sw_estado='2' WHERE evolucion_id=".$DatoMedicamento['EvoId']." AND medicamento_id='".$DatoMedicamento['MedID']."' ";
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al cambiar el estado (sw_estado='2') de \"hc_medicamentos_recetados\" en el query\n<br>$query";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			else {
				$query = "UPDATE hc_mezclas_recetadas SET sw_estado='2' WHERE evolucion_id=".$DatoMedicamento['EvoId']." AND mezcla_recetada_id='".$DatoMedicamento['MedID']."' ";
				$resultado=$dbconn->Execute($query);
					if (!$resultado)
					{
						$this->error = "Error al cambiar el estado (sw_estado='2') de \"hc_medicamentos_recetados\" en el query\n<br>$query";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			unset($_REQUEST['subModuloAction']);
			$this->FrmForma('');
			return true;
		}


		function InsertarNota()
		{
			list($dbconn) = GetDBconn();
			if ($_POST[$this->frmPrefijo.'IngresarNota']=='IngresarNota')
			{
				$medicamento=$_POST[$this->frmPrefijo."Medicamento_id"];
				$evolucion=$_POST[$this->frmPrefijo."evolucion_id"];
				$fecha=$_POST[$this->frmPrefijo."Fecha"];
				$nota=$_POST[$this->frmPrefijo."notaDetSum"];
				$perfil=$_POST[$this->frmPrefijo."Perfil"];

				if ($perfil==1 || $perfil==2){
					$query="INSERT INTO hc_suministro_medicamentos_notas(nota_id,medicamento_id,evolucion_id,evolucion_nota_id,fecha,usuario_id,tipo_nota,nota)
									VALUES (nextval('public.hc_suministro_medicamentos_notas_nota_id_seq'::text),'$medicamento',$evolucion,".$this->evolucion.",'$fecha',".$this->user_id.",'1','$nota')";
				}
				elseif ($this->tipo_profesional==3 || $this->tipo_profesional==4) {
					$query="INSERT INTO hc_suministro_medicamentos_notas(nota_id,medicamento_id,evolucion_id,evolucion_nota_id,fecha,usuario_id,tipo_nota,nota)
									VALUES (nextval('public.hc_suministro_medicamentos_notas_nota_id_seq'::text),'$medicamento',$evolucion,".$this->evolucion.",'$fecha',".$this->user_id.",'2','$nota')";
				}

					$resultado=$this->Verifica_Conexion($query,$dbconn);
					if (!$resultado)
					{
						$this->error = "Error al tratar de insertar la nota en \"hc_suministro_medicamentos_notas\" <br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			elseif ($_POST[$this->frmPrefijo.'IngresarNota']=='IngresarMezcla')
			{
				$mezcla=$_POST[$this->frmPrefijo."Mezcla_id"];
				$evolucion=$_POST[$this->frmPrefijo."evolucion_id"];
				$fecha=$_POST[$this->frmPrefijo."Fecha"];
				$nota=$_POST[$this->frmPrefijo."notaDetSum"];
				$perfil=$_POST[$this->frmPrefijo."Perfil"];

				if ($perfil==1 || $perfil==2){
					$query="INSERT INTO hc_suministro_mezclas_notas(nota_id,mezcla_recetada_id,evolucion_id,evolucion_nota_id,fecha,usuario_id,tipo_nota,nota)
									VALUES (nextval('public.hc_suministro_mezclas_notas_nota_id_seq'::text),$mezcla,$evolucion,".$this->evolucion.",'$fecha',".$this->user_id.",'1','$nota')";
				}
				elseif ($perfil==3 || $perfil==4) {
					$query="INSERT INTO hc_suministro_mezclas_notas(nota_id,mezcla_recetada_id,evolucion_id,evolucion_nota_id,fecha,usuario_id,tipo_nota,nota)
									VALUES (nextval('public.hc_suministro_mezclas_notas_nota_id_seq'::text),$mezcla,$evolucion,".$this->evolucion.",'$fecha',".$this->user_id.",'2','$nota')";
				}

					$resultado=$this->Verifica_Conexion($query,$dbconn);
					if (!$resultado)
					{
						$this->error = "Error al tratar de insertar la nota en \"hc_suministro_medicamentos_notas\" <br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			unset($_REQUEST['subModuloAction']);
			$this->FrmForma('');
			return true;
		}


		function GetSuministroMedNotas($medicamento,$evolucion,$nota)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT *
							FROM hc_suministro_medicamentos_notas
							WHERE medicamento_id='".$medicamento."' AND
										evolucion_id=".$evolucion." AND
										tipo_nota='$nota' ";

			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consulta los suministros del medicamento en \"hc_suministro_medicamentos_notas\".<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			return $resultado;
		}


		function GetSuministroMezclasNotas($mezcla,$evolucion,$nota)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT *
							FROM hc_suministro_mezclas_notas
							WHERE mezcla_recetada_id=".$mezcla." AND
										evolucion_id=".$evolucion." AND
										tipo_nota='$nota' ";

			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consulta los suministros de la mezcla en \"hc_suministro_mezclas_notas\".<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			return $resultado;
		}


		function InsertarJust()
		{
			list($dbconn) = GetDBconn();
			$validaSi=$valida=0;

			if (empty($_REQUEST[$this->frmPrefijo."Error_AddJ"])){
				unset($_REQUEST);
				$_REQUEST=SessionGetVar("REQUEST_MED_JUST");
				if (!$this->InsertDatos())
					return false;
			}

			unset($_REQUEST);
			$_REQUEST=SessionGetVar("REQUEST_DATOS_JUST");

			$alterjusti=$_REQUEST[$this->frmPrefijo."AlterJusti"];
			$pac_id=$_REQUEST[$this->frmPrefijo."PaciIdJusti"];
			$fecha=$_REQUEST[$this->frmPrefijo."FechaJusti"];
			$cantidad=$_REQUEST[$this->frmPrefijo."CantOpt"];
			$med_id=$_REQUEST[$this->frmPrefijo."MedicamentoNPID"];


			if (!isset($_REQUEST[$this->frmPrefijo."DxJusti"]) && empty($_REQUEST["codigo".$this->frmPrefijo."datos"]) )
			{
				$this->frmError[$this->frmPrefijo."Diagnostico"]=1;
				$this->error="Error";
			}
			if (empty($_REQUEST[$this->frmPrefijo.'MedicamentoNPDT']))
			{
				$this->frmError[$this->frmPrefijo."MedicamentoNPos"]=1;
				$this->error="Error";
			}
			if (!empty($this->error))
			{
				$this->frmError["MensajeError"]="Verfique los campos en rojo";
				return false;
			}

			if (!empty($_REQUEST[$this->frmPrefijo."DxJusti"]) && empty($_REQUEST["codigo".$this->frmPrefijo."datos"])){
				$dx=$_REQUEST[$this->frmPrefijo."DxJusti"];
			}
			elseif (empty($_REQUEST[$this->frmPrefijo."DxJusti"]) && !empty($_REQUEST["codigo".$this->frmPrefijo."datos"])){
				$dx=$_REQUEST["codigo".$this->frmPrefijo."datos"];
			}
			else{
				$dx=$_REQUEST["codigo".$this->frmPrefijo."datos"];
			}

			$valida_datos=1;
			if ($alterjusti=="S")
			{
				for ($i=0;$i<$cantidad;$i++)
				{
					if (!empty($_REQUEST[$this->frmPrefijo."datosIdMedicamentoOpt".$i]))
						$validaSi=1;
					if (!empty($_REQUEST[$this->frmPrefijo."datosIdMedicamentoOpt".$i]) && (empty($_REQUEST[$this->frmPrefijo."DosisPJusti".$i]) || empty($_REQUEST[$this->frmPrefijo."TotalPJusti".$i])))
						$valida_datos=0;
				}
				if (!$validaSi || !$valida_datos)
				{
					$this->frmError[$this->frmPrefijo."AlternativasPU"]=1;
					$this->error="Error";
					$this->frmError["MensajeError"]="Verfique los campos en rojo.";
					return false;
				}
			}
			else
			{
				for ($i=0;$i<$cantidad;$i++)
				{
					if (!empty($_REQUEST[$this->frmPrefijo."datosIdMedicamentoOpt".$i]))
						$valida=1;
					if (!empty($_REQUEST[$this->frmPrefijo."datosIdMedicamentoOpt".$i]) && (empty($_REQUEST[$this->frmPrefijo."DosisPJusti".$i]) || empty($_REQUEST[$this->frmPrefijo."TotalPJusti".$i])))
						$valida_datos=0;
				}
				if ($valida || !$valida_datos)
				{
					$this->frmError[$this->frmPrefijo."AlternativasPU"]=1;
					$this->error="Error";
					$this->frmError["MensajeError"]="Verfique los campos en rojo";
					return false;
				}
			}

			if (($alterjusti=="S" && $validaSi) || ($alterjusti=="N" && !$valida))
			{
				$serie="SELECT nextval('public.hc_justificaciones_no_pos_justificacion_no_pos_id_seq')";
				$resultado=$this->Verifica_Conexion($serie,$dbconn);
					if (!$resultado)
					{
						$this->error = "Error al tratar de realizar la consulta.<br>";
						$this->mensajeDeError = $serie."<br>".$dbconn->ErrorMsg();
						return false;
					}
				$serie=$resultado->fields[0];

				$dbconn->BeginTrans();
				$query = "INSERT INTO hc_justificaciones_no_pos(justificacion_no_pos_id,tipo_diagnostico_id,evolucion_id,fecha,paciente_id,usuario_id,empresa_id,codigo_producto)
									VALUES (".$serie.",'$dx',".$this->evolucion.",'".$fecha."','".$pac_id."','".$this->user_id."','".$this->empresa."','".$med_id."')";
				$resultado=$dbconn->Execute($query);
					if ($resultado)
					{
						if (empty($_REQUEST['MezclaId'])){
							$query = "UPDATE hc_medicamentos_recetados SET justificacion_no_pos_id=".$serie.", dias_tto=".$_REQUEST[$this->frmPrefijo."MedicamentoNPDT"]." WHERE evolucion_id=".$this->evolucion." AND medicamento_id='".$med_id."'; ";
							$resultado=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0)
								{
									$this->error = "Error al tratar de realizar la consulta.<br>";
									$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
									$this->BorrarMedicamento();
									$dbconn->RollbackTrans();
									return false;
								}
						}
						else{
							$query = "UPDATE hc_mezclas_recetadas_medicamentos SET justificacion_no_pos_id=".$serie.", dias_tto=".$_REQUEST[$this->frmPrefijo."MedicamentoNPDT"]." WHERE mezcla_recetada_id=".$_REQUEST['MezclaId']." AND medicamento_id='".$med_id."'";

							$resultado=$dbconn->Execute($query);
								if (!$resultado)
								{
									$this->error = "Error al tratar de realizar la consulta.<br>";
									$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
								}
						}
					}
					else{
						$this->error = "Error al tratar de realizar la consulta.<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$this->BorrarMedicamento();
						$dbconn->RollbackTrans();
						return false;
					}

				for ($i=0;$i<$cantidad;$i++)
				{
					if (!empty($_REQUEST[$this->frmPrefijo."datosIdMedicamentoOpt".$i]))
					{
						$query = "INSERT INTO hc_posibilidades_terapeuticas_pos (justificacion_no_pos_id,medicamento_id,empresa_id,dosis_dia,cantidad_total,mejora,reaccion_secundaria,contraindicacion,otra)
											VALUES (".$serie.",'".$_REQUEST[$this->frmPrefijo."datosIdMedicamentoOpt".$i]."','".$this->empresa."',".$_REQUEST[$this->frmPrefijo."DosisPJusti".$i].",".$_REQUEST[$this->frmPrefijo."TotalPJusti".$i].",'".$_REQUEST[$this->frmPrefijo."MejoraPJusti".$i]."','".$_REQUEST[$this->frmPrefijo."ReaccionPJusti".$i]."','".$_REQUEST[$this->frmPrefijo."ContraPJusti".$i]."','".$_REQUEST[$this->frmPrefijo."OtraContraPJusti".$i]."')";
						$resultado=$dbconn->Execute($query);
							if (!$resultado)
							{
								$this->error = "Error al tratar de realizar la consulta.<br>";
								$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
								$this->BorrarMedicamento();
								$dbconn->RollbackTrans();
								return false;
							}
					}
				}

				$query="SELECT * FROM hc_criterios_justificacion_no_pos ;";
				$resultado=$dbconn->Execute($query);
					if (!$resultado)
					{
						$this->error = "Error al tratar de realizar la consulta.<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$this->BorrarMedicamento();
						$dbconn->RollbackTrans();
						return false;
					}

				$query="SELECT * FROM plantilla_justificacion_medicamentos WHERE codigo_producto='$med_id'";
				$resultadoPlantilla=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al tratar de realizar la consulta.<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$this->BorrarMedicamento();
						$dbconn->RollbackTrans();

						return false;
					}

				$contador=0;
				while ($data=$resultado->FetchNextObject($toUpper=false))
				{
					if (!$resultadoPlantilla->RecordCount())
					{
						$queryCriterios="INSERT INTO plantilla_justificacion_medicamentos (codigo_producto,empresa_id,usuario_id,criterio_id,respuesta)
															VALUES ('$med_id','".$this->empresa."',".$this->user_id.",".$data->criterio_id.",'".$_REQUEST[$this->frmPrefijo."CriterioNPN".$contador]."')";
						$resultadoCri=$dbconn->Execute($queryCriterios);
							if (!$resultadoCri)
							{
								$this->error = "Error al tratar de realizar la consulta.<br>";
								$this->mensajeDeError = $queryCriterios."<br>".$dbconn->ErrorMsg();
								$this->BorrarMedicamento();
								$dbconn->RollbackTrans();
								return false;
							}
					}
					if (!$data->sw_criterio_respuesta)
					{
						if ($_REQUEST[$this->frmPrefijo."CriterioNPN".$contador]=="S")
						{
							$queryCriterios="INSERT INTO hc_justificaciones_criterios (criterio_id,sw_criterio_respuesta,respuesta,justificacion_no_pos_id)
																VALUES (".$data->criterio_id.",'S','',".$serie.")";
							$resultadoCri=$dbconn->Execute($queryCriterios);
								if (!$resultadoCri)
								{
									$this->error = "Error al tratar de realizar la consulta.<br>";
									$this->mensajeDeError = $queryCriterios."<br>".$dbconn->ErrorMsg();
									$this->BorrarMedicamento();
									$dbconn->RollbackTrans();
									return false;
								}
						}
						elseif ($_REQUEST[$this->frmPrefijo."CriterioNPN".$contador]=="N" || empty($_REQUEST[$this->frmPrefijo."CriterioNPN"]))
							{
								$queryCriterios="INSERT INTO hc_justificaciones_criterios (criterio_id,sw_criterio_respuesta,respuesta,justificacion_no_pos_id)
																	VALUES (".$data->criterio_id.",'N','',".$serie.")";
								$resultadoCri=$dbconn->Execute($queryCriterios);
									if (!$resultadoCri)
									{
										$this->error = "Error al tratar de realizar la consulta.<br>";
										$this->mensajeDeError = $queryCriterios."<br>".$dbconn->ErrorMsg();
										$this->BorrarMedicamento();
										$dbconn->RollbackTrans();
										return false;
									}
							}
					}
					else
					{
						$queryCriterios="INSERT INTO hc_justificaciones_criterios (criterio_id,sw_criterio_respuesta,respuesta,justificacion_no_pos_id)
															VALUES (".$data->criterio_id.",'','".$_REQUEST[$this->frmPrefijo."CriterioNPN".$contador]."',".$serie.")";
						$resultadoCri=$dbconn->Execute($queryCriterios);
							if (!$resultadoCri)
							{
								$this->error = "Error al tratar de realizar la consulta.<br>";
								$this->mensajeDeError = $queryCriterios."<br>".$dbconn->ErrorMsg();
								$this->BorrarMedicamento();
								$dbconn->RollbackTrans();
								return false;
							}
					}
					$contador++;
				}
				$dbconn->CommitTrans();
			}

			SessionDelVar("REQUEST_MED_JUST");
			SessionDelVar("REQUEST_DATOS_JUST");
			unset($_REQUEST['subModuloAction']);
			$this->GetForma();
			return true;
		}



		function BorrarMedicamento()
		{
			list($dbconn) = GetDBconn();

			$Datos_Medicamentos=SessionGetVar("REQUEST_MED_JUST");
			if (!empty($Datos_Medicamentos)){
				$query="DELETE
								FROM hc_medicamentos_recetados
								WHERE
											medicamento_id='".$Datos_Medicamentos[$this->frmPrefijo.'datosIdMedicamento']."' AND
											evolucion_id=".$this->evolucion." ";

				$resultado=$dbconn->Execute($query);
					if (!$resultado)
					{
						$this->error = "Error al tratar de realizar la eliminacion de los medicamentos.<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}

			SessionDelVar("REQUEST_MED_JUST");
			SessionDelVar("REQUEST_DATOS_JUST");
			return true;
		}



		function GetDatosUsuario($usuario_id=false)
		{
			list($dbconn) = GetDBconn();
			GLOBAL $ADODB_FETCH_MODE;

			if (empty($usuario_id)){
				$query_user=" SELECT  p.*,
															esp.descripcion,
															pesp.especialidad
											FROM  profesionales p,
														terceros ter,
														profesionales_especialidades pesp,
														especialidades esp
											WHERE ter.usuario_id=".$this->usuario_id." AND
														ter.tipo_id_tercero=p.tipo_id_tercero AND
														ter.tercero_id=p.tercero_id AND
														pesp.tipo_id_tercero = p.tipo_id_tercero AND
														pesp.tercero_id = p.tercero_id AND
														esp.especialidad = pesp.especialidad ";
			}
			else{
				$query_user=" SELECT  p.*,
															esp.descripcion,
															pesp.especialidad
											FROM  profesionales p,
														terceros ter,
														profesionales_especialidades pesp,
														especialidades esp
											WHERE ter.usuario_id=".$usuario_id." AND
														ter.tipo_id_tercero=p.tipo_id_tercero AND
														ter.tercero_id=p.tercero_id AND
														pesp.tipo_id_tercero = p.tipo_id_tercero AND
														pesp.tercero_id = p.tercero_id AND
														esp.especialidad = pesp.especialidad ";
			}

			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query_user,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al tratar de realizar la consulta.<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $resultado->FetchRow();
		}


		function GetJustCriteriosRespuesta($just_id)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT cj.criterio_id, cj.sw_criterio_respuesta as cjcriterio_respuesta, cj.descripcion, jc.sw_criterio_respuesta, jc.respuesta
							FROM hc_criterios_justificacion_no_pos cj, hc_justificaciones_criterios jc
							WHERE cj.criterio_id=jc.criterio_id AND jc.justificacion_no_pos_id=".$just_id." ORDER BY cj.criterio_id";
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al tratar de realizar la consulta.<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			return $resultado;
		}



		function GetCriterioRespuesta($medicamento)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();

			$query="SELECT cj.*, plt.respuesta
							FROM hc_criterios_justificacion_no_pos AS cj
							LEFT JOIN
										plantilla_justificacion_medicamentos plt
							ON cj.criterio_id=plt.criterio_id AND plt.codigo_producto='$medicamento'";

			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al tratar de realizar la consulta.<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();

				return false;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $resultado;
		}


		function GetQueryBodegas($empresa,$c_u,$pos=false)
		{
			if ($pos){
				$queryBgas=urlencode("SELECT 	pos,
																			codigo_producto,
																			descripcion,
																			presentacion,
																			formfarmnombre,
																			concentracion,
																			principio_activo,
																			unidescripcion,
																			bodega
															FROM 		medicamentos_bodega
															WHERE 	empresa_id='".$empresa."' AND
																			pos='$pos' AND
																			centro_utilidad='".$c_u."' AND
																			estacion_id='".$this->estacion."'
														");
// 														echo "SELECT 	pos,
// 																			codigo_producto,
// 																			descripcion,
// 																			presentacion,
// 																			formfarmnombre,
// 																			concentracion,
// 																			principio_activo,
// 																			unidescripcion,
// 																			bodega
// 															FROM 		medicamentos_bodega
// 															WHERE 	empresa_id='".$empresa."' AND
// 																			pos='$pos' AND
// 																			centro_utilidad='".$c_u."' AND
// 																			estacion_id='".$this->estacion."'
// 														";
			}
			else{
				$queryBgas=urlencode("SELECT
																			CASE	WHEN pos=1 THEN 'SI'
																						ELSE 'NO'
																			END as pos,
																			codigo_producto,
																			descripcion,
																			presentacion,
																			formfarmnombre,
																			concentracion,
																			principio_activo,
																			unidescripcion,
																			bodega
															FROM 		medicamentos_bodega
															WHERE 	empresa_id='".$empresa."' AND
																			centro_utilidad='".$c_u."' AND
																			estacion_id='".$this->estacion."'
														");
// 														echo "SELECT
// 																			CASE	WHEN pos=1 THEN 'SI'
// 																						ELSE 'NO'
// 																			END as pos,
// 																			codigo_producto,
// 																			descripcion,
// 																			presentacion,
// 																			formfarmnombre,
// 																			concentracion,
// 																			principio_activo,
// 																			unidescripcion,
// 																			bodega
// 															FROM 		medicamentos_bodega
// 															WHERE 	empresa_id='".$empresa."' AND
// 																			centro_utilidad='".$c_u."' AND
// 																			estacion_id='".$this->estacion."'
// 														";
			}
			return $queryBgas;
		}


		function GetFrecuenciaUds()
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$frecu_uds=array();
			$query="SELECT * FROM hc_tipo_unidades_frecuencia ";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al tratar de consultar el maestro de \"hc_tipo_unidades_frecuencia\" <br>";
				$this->mensajeDeError = $query;
				return false;
			}
			while ($data = $resultado->FetchRow()) {
				$frecu_uds[]=$data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $frecu_uds;
		}


		function GetDuracion($valor)
		{
			list($dbconn) = GetDBconn();
			$option="";
			$query = "SELECT * FROM hc_horario";
			$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado)
				{
					$this->error = "Error al tratar de consultar el maestro de \"hc_horario\" <br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				while ($data = $resultado->FetchNextObject($toUpper=false))
				{
					if ($data->duracion_id==$valor)
						$option.="<option value='".$data->duracion_id."' selected>".$data->descripcion."</option>\n";
					else
						$option.="<option value='".$data->duracion_id."'>".$data->descripcion."</option>\n";
				}
			return $option;
		}



		function GetViasAdmon($via_id)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT * FROM hc_vias_administracion WHERE via_administracion_id='".$via_id."'";

			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al tratar de consultar las vias de administracion en  \"hc_vias_administracion\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			return $resultado;
		}


		function GetViasAdmonUds($via_admon,$via_unidad=false)
		{
			list($dbconn) = GetDBconn();
			if (!$via_unidad){
				$query="SELECT * FROM hc_vias_administracion_uds WHERE via_administracion_id='".$via_admon."' ORDER BY via_uds_id";
				$resultado=$this->Verifica_Conexion($query,$dbconn);
					if (!$resultado) {
						$this->error = "Error al tratar de consultar las unidades de las vias de administracion en  \"hc_vias_administracion_uds\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
				return $resultado;
			}
			else{
				$query="SELECT * FROM hc_vias_administracion_uds WHERE via_administracion_id='".$via_admon."' AND via_uds_id='".$via_unidad."'";
				$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado)
				{
					$this->error = "Error al tratar de consultar el maestro de \"hc_vias_administracion_uds\" <br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				$data = $resultado->FetchNextObject($toupper=false);
				return $data->descripcion;
			}
			return false;
		}


		function GetHorario($duracion)
		{
			list($dbconn) = GetDBconn();
			$query = "SELECT * FROM hc_horario WHERE duracion_id='".$duracion."'";
			$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado)
				{
					$this->error = "Error al tratar de consultar la tabla \"hc_horario\"<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
			return $resultado;
		}


		function GetMezclasGrupos($empresa,$c_u,$estacion_id,$grupo=false)
		{
			list($dbconn) = GetDBconn();
			if (!$grupo){
				$query = "SELECT * FROM hc_mezcla_grupos WHERE empresa_id='".$empresa."' AND centro_utilidad='".$c_u."' AND estacion_id='".$estacion_id."'";
				$resultadoMz=$this->Verifica_Conexion($query,$dbconn);
					if (!$resultadoMz) {
						$this->error = "Error al tratar de consultar los grupos de mezclas en \"hc_mezcla_grupos\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			else{
				$query = "SELECT * FROM hc_mezcla_grupos WHERE empresa_id='".$empresa."' AND centro_utilidad='".$c_u."' AND estacion_id='".$estacion_id."' AND mezcla_grupo_id=$grupo";
				$resultadoMz=$this->Verifica_Conexion($query,$dbconn);
					if (!$resultadoMz) {
						$this->error = "Error al tratar de consultar los grupos de mezclas en \"hc_mezcla_grupos\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			return $resultadoMz;
		}


		function GetMezclaMedicamentos($grupo_id,$medicamento=false)
		{
			list($dbconn) = GetDBconn();
			if (!$medicamento){
				$query = "SELECT * FROM hc_mezcla_medicamentos WHERE mezcla_grupo_id=".$grupo_id;
				$resultadoMzMed=$this->Verifica_Conexion($query,$dbconn);
					if (!$resultadoMzMed) {
						$this->error = "Error al tratar de consultar los medicamentos de los grupos de las mezclas en \"hc_mezcla_medicamentos\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			else{
				$query = "SELECT * FROM hc_mezcla_medicamentos WHERE mezcla_grupo_id=".$grupo_id." AND medicamento_id='$medicamento' ";
				$resultadoMzMed=$this->Verifica_Conexion($query,$dbconn);
					if (!$resultadoMzMed) {
						$this->error = "Error al tratar de consultar los medicamentos de los grupos de las mezclas en \"hc_mezcla_medicamentos\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			return $resultadoMzMed;
		}


		function GetGruposMezclas($empresa,$c_u,$estacion_id,$grupo=false)
		{
			list($dbconn) = GetDBconn();
			if (!$grupo){
				$query = "SELECT * FROM hc_grupos_mezclas WHERE empresa_id='".$empresa."' AND centro_utilidad='".$c_u."' AND estacion_id='".$estacion_id."'";
				$resultadoMz=$this->Verifica_Conexion($query,$dbconn);
					if (!$resultadoMz) {
						$this->error = "Error al tratar de consultar los grupos de mezclas en \"hc_grupos_mezclas\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			else{
				$query = "SELECT * FROM hc_grupos_mezclas WHERE empresa_id='".$empresa."' AND centro_utilidad='".$c_u."' AND estacion_id='".$estacion_id."' AND grupo_mezcla_id=$grupo";
				$resultadoMz=$this->Verifica_Conexion($query,$dbconn);
					if (!$resultadoMz) {
						$this->error = "Error al tratar de consultar los grupos de mezclas en \"hc_grupos_mezclas\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			return $resultadoMz;
		}


		function GetGrupoMezclaMedicamentos($grupo_id,$medicamento=false)
		{
			list($dbconn) = GetDBconn();
			if (!$medicamento){
				$query = "SELECT * FROM hc_grupos_mezclas_medicamentos WHERE grupo_mezcla_id=".$grupo_id;
				$resultadoMzMed=$this->Verifica_Conexion($query,$dbconn);
					if (!$resultadoMzMed) {
						$this->error = "Error al tratar de consultar los medicamentos de los grupos de las mezclas en \"hc_grupos_mezclas_medicamentos\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			else{
				$query = "SELECT * FROM hc_grupos_mezclas_medicamentos WHERE grupo_mezcla_id=".$grupo_id." AND medicamento_id='$medicamento' ";
				$resultadoMzMed=$this->Verifica_Conexion($query,$dbconn);
					if (!$resultadoMzMed) {
						$this->error = "Error al tratar de consultar los medicamentos de los grupos de las mezclas en \"hc_grupos_mezclas_medicamentos\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			return $resultadoMzMed;
		}


		function GetJustDxPaciente($evolucion)
		{
			list($dbconn) = GetDBconn();
			$queryDx="SELECT  tipo.diagnostico_nombre,
												ingreso.tipo_diagnostico_id
								FROM  diagnosticos tipo,
											hc_diagnosticos_ingreso ingreso
								WHERE tipo.diagnostico_id=ingreso.tipo_diagnostico_id AND
											ingreso.evolucion_id=$evolucion";


			$resultado=$this->Verifica_Conexion($queryDx,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al tratar de realizar la consulta.<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			return $resultado;
		}


		function GetJustDx($just_id)
		{
			list($dbconn) = GetDBconn();
			GLOBAL $ADODB_FETCH_MODE;

			$query="SELECT jnp.*, tdx.diagnostico_nombre
							FROM hc_justificaciones_no_pos jnp, diagnosticos tdx
							WHERE jnp.justificacion_no_pos_id=".$just_id." AND tdx.diagnostico_id=jnp.tipo_diagnostico_id; ";

			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado = $this->Verifica_Conexion($query,$dbconn);
				if (!$resultado)
				{
					$this->error = "Error al tratar de realizar la consulta.<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
			$datos=$resultado->FetchRow();
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $datos;
		}


		function GetPosibilidadesTer($just_np_id,$cu)
		{
			list($dbconn) = GetDBconn();
			GLOBAL $ADODB_FETCH_MODE;
			$datos_emp=$this->GetDatosEmpresas();
			$datos=array();

			$query="SELECT
										pt.*,
										mb.descripcion,
										mb.concentracion,
										mb.principio_activo,
										mb.unidescripcion,
										mb.formfarmnombre
							FROM
										hc_posibilidades_terapeuticas_pos pt,
										nombre_medicamento mb
							WHERE
										pt.justificacion_no_pos_id=".$just_np_id." AND
										mb.codigo_producto=pt.medicamento_id AND
										mb.empresa_id='".$this->empresa."'
									 ";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado)
				{
					$this->error = "Error al tratar de realizar la consulta.<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}

			while ($data=$resultado->FetchRow()){
				$datos[$data['justificacion_no_pos_id']][]=$data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $datos;
		}


		function GetMezclas($mezcla_id)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT * FROM hc_mezclas_recetadas_medicamentos WHERE mezcla_recetada_id=".$mezcla_id;

			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar los medicamentos de las mezclas recetadas en \"hc_mezclas_recetadas_medicamentos\" con la mezcla \"".$medicamentos['mezcla_recetada_id']."\" <br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			return $resultado;
		}



		/*
		* function ObtenerPlanTerapeutico($ingreso)
		* $ingreso es el ingreso del paciente
		* Se buscan los medicamentos suspendidos desde el ingreso y los medicamentos vigentes que
		* se le estan suministrando al paciente desde el ingreso
		* retorna un vector con el plan terapeutico del paciente (evolución,nombreMedicamento,formFarmaceutica,cantTotal,viaAdmon,cant,horario,estado,comentario)
		*/
		function ObtenerPlanTerapeutico($accion)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$cont=0;
			$vecPlanMedicamentos = array();//Vector que contiene el plan de medicamentos del paciente
			switch ($accion)
			{
				case 1://plan finalizado y/o suspendido
								$query = "SELECT c.codigo_producto, c.descripcion, c.presentacion, c.formfarmnombre, c.concentracion, c.unidescripcion,
																a.*,
																b.nombre AS vianombre
													FROM hc_medicamentos_recetados a,
																hc_vias_administracion b,
																medicamentos_bodega c,
																hc_evoluciones d
													WHERE a.medicamento_id=c.codigo_producto AND
																c.empresa_id=a.empresa_id AND
																c.centro_utilidad=a.centro_utilidad AND
																c.bodega=a.bodega AND
																c.estacion_id='".$this->estacion."' AND
																a.evolucion_id=d.evolucion_id AND
																d.ingreso=".$this->ingreso." AND
																a.sw_estado='1' AND
																a.via_administracion_id=b.via_administracion_id
																ORDER BY a.fecha DESC, a.sw_estado";

								$query2 = "SELECT b.justificacion_no_pos_id,
																a.*
													FROM ($query) AS a LEFT JOIN
																hc_justificaciones_no_posxxx b
													USING(empresa_id, codigo_producto, justificacion_no_pos_id)";

								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultMedicamentos=$this->Verifica_Conexion($query2,$dbconn);
									if (!$resultMedicamentos)
									{
										$this->error = "Error al tratar de realizar la consulta<br>";
										$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
										return false;
									}
								while ($data = $resultMedicamentos->FetchRow()) {
									$vecPlanMedicamentos[]=$data;
								}//End While
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								return $vecPlanMedicamentos;
				break;
				default://plan vigente (2)
								$query = "SELECT c.codigo_producto, c.descripcion, c.presentacion, c.formfarmnombre, c.concentracion, c.unidescripcion,
																a.*,
																b.nombre AS vianombre
													FROM hc_medicamentos_recetados a,
																hc_vias_administracion b,
																medicamentos_bodega c,
																hc_evoluciones d
													WHERE a.medicamento_id=c.codigo_producto AND
																c.empresa_id=a.empresa_id AND
																c.centro_utilidad=a.centro_utilidad AND
																a.evolucion_id=d.evolucion_id AND d.ingreso=".$this->ingreso." AND
																(a.sw_estado='2' OR a.sw_estado='0') AND
																c.bodega=a.bodega AND
																c.estacion_id='".$this->estacion."' AND
																a.via_administracion_id=b.via_administracion_id
																ORDER BY a.fecha DESC";

								$query2 = "SELECT b.justificacion_no_pos_id,
																a.*
													FROM ($query) AS a LEFT JOIN
																hc_justificaciones_no_posxxx b
													USING(empresa_id, codigo_producto, justificacion_no_pos_id)";

								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultMedicamentos=$this->Verifica_Conexion($query2,$dbconn);
									if (!$resultMedicamentos)
									{
										$this->error = "Error al tratar de realizar la consulta.<br>";
										$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
										return false;
									}

								while ($data = $resultMedicamentos->FetchRow()) {
									$vecPlanMedicamentos[]=$data;
								}//End While
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								return $vecPlanMedicamentos;
				break;
			}
		}//End function


				function ObtenerPlanTerpeuticoMezclas($accion)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$cont=0;
			$vecPlanMedicamentos = array();//Vector que contiene el plan de medicamentos de mezclas para el paciente
			switch ($accion)
			{
				case 1://plan finalizado y/o suspendido
								$i=0;
								$query =" SELECT a.*
													FROM  hc_mezclas_recetadas a,
																hc_evoluciones d
													WHERE a.evolucion_id=d.evolucion_id AND d.ingreso=".$this->ingreso." AND
																a.sw_estado='1'
													ORDER BY a.fecha DESC";


								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
									if (!$resultado)
									{
										$this->error = "Error al tratar de realizar la consulta.<br>";
										$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
										return false;
									}
								while (!$resultado->EOF && $resultado->RecordCount())
								{
									$query = "SELECT c.codigo_producto, c.descripcion, c.presentacion, c.formfarmnombre, c.concentracion, c.unidescripcion, c.empresa_id,
																	e.sw_pos,
																	e.justificacion_no_pos_id,
																	e.cantidad,
																	e.medicamento_id,
																	e.centro_utilidad,
																	e.bodega
														FROM  nombre_medicamento c,
																	hc_evoluciones d,
																	hc_mezclas_recetadas_medicamentos e
														WHERE '".$resultado->fields['mezcla_recetada_id']."'=e.mezcla_recetada_id AND
																	c.codigo_producto=e.medicamento_id AND
																	c.empresa_id=e.empresa_id AND
																	".$resultado->fields['evolucion_id']."=d.evolucion_id AND d.ingreso=".$this->ingreso."  ";

									$query2 = "SELECT b.justificacion_no_pos_id,
																	a.*
														FROM ($query) AS a LEFT JOIN
																	hc_justificaciones_no_posxxx b
														USING(empresa_id, codigo_producto, justificacion_no_pos_id)";

									$cont=0;
									$resultMedicamentos=$this->Verifica_Conexion($query2,$dbconn);
										if (!$resultMedicamentos)
										{
											$this->error = "Error al tratar de realizar la consulta.<br>";
											$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
											//revisar este query
											return false;
										}
										while ($data = $resultMedicamentos->FetchRow()) {
											$vecPlanMedicamentos[$cont]=$data;
											$cont++;
										}//End While
									$mezcla[$i][0]=$resultado->GetRowAssoc($toUpper=false);
									$mezcla[$i][1]=$vecPlanMedicamentos;
									unset($vecPlanMedicamentos);
									$vecPlanMedicamentos=array();
									$resultado->MoveNext();
									$i++;
								}
									$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								return $mezcla;
				break;
				default://plan vigente (2)
								$i=0;
								$query =" SELECT a.*
													FROM  hc_mezclas_recetadas a,
																hc_evoluciones d
													WHERE a.evolucion_id=d.evolucion_id AND d.ingreso=".$this->ingreso." AND
																(a.sw_estado='2' OR a.sw_estado='0')
													ORDER BY a.fecha DESC";

								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
									if (!$resultado)
									{
										$this->error = "Error al tratar de realizar la consulta.<br>";
										$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
										//revisar este query
										return false;
									}
								while (!$resultado->EOF && $resultado->RecordCount())
								{
									$query = "SELECT c.codigo_producto, c.descripcion, c.presentacion, c.formfarmnombre, c.concentracion, c.unidescripcion, c.empresa_id,
																	e.sw_pos,
																	e.justificacion_no_pos_id,
																	e.cantidad,
																	e.medicamento_id,
																	e.centro_utilidad,
																	e.bodega
														FROM  nombre_medicamento c,
																	hc_evoluciones d,
																	hc_mezclas_recetadas_medicamentos e
														WHERE e.mezcla_recetada_id='".$resultado->fields['mezcla_recetada_id']."' AND
																	c.codigo_producto=e.medicamento_id AND
																	c.empresa_id=e.empresa_id AND
																	".$resultado->fields['evolucion_id']."=d.evolucion_id AND d.ingreso=".$this->ingreso."  ";


									 $query2 = "SELECT b.justificacion_no_pos_id,
																	a.*
														FROM ($query) AS a LEFT JOIN
																	hc_justificaciones_no_posxxx b
														USING(empresa_id, codigo_producto, justificacion_no_pos_id)";

									$cont=0;
									$resultMedicamentos=$this->Verifica_Conexion($query2,$dbconn);
										if (!$resultMedicamentos)
										{
											$this->error = "Error al tratar de realizar la consulta.<br>";
											$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
											//revisar este query
											return false;
										}
										while ($data = $resultMedicamentos->FetchRow()) {
											$vecPlanMedicamentos[$cont]=$data;
											$cont++;
										}//End While
									$mezcla[$i][0]=$resultado->GetRowAssoc($toUpper=false);
									$mezcla[$i][1]=$vecPlanMedicamentos;
									unset($vecPlanMedicamentos);
									$vecPlanMedicamentos=array();
									$resultado->MoveNext();
									$i++;
								}
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								return $mezcla;
				break;
			}
		}

		/*
		* function GetNombMedicamentos($CodiMedicamento)
		* $CodiMedicamento es el codigo del medicamento a buscar
		* Se busca en la tabla inventarios y medicamentos para obtener el nombre + concentración
		* retorna el nombre del medicamento
		*/
		function GetNombMedicamentos($CodiMedicamento)
		{
			//---------------- obtengo el nombre del medicamento ----------------
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$query="SELECT descripcion, presentacion, formfarmnombre, concentracion
							FROM nombre_medicamento
							WHERE empresa_id='".$this->empresa."' AND codigo_producto='".$CodiMedicamento."'";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado)
				{
					$this->error = "Error al tratar de realizar la consulta.<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				if (empty($resultado->fields['concentracion']))
				{
					return ($resultado->fields['descripcion']." ".$resultado->fields['formfarmnombre']." ".$resultado->fields['presentacion']);
				}
				else  return ($resultado->fields['descripcion']." ".$resultado->fields['concentracion']." ".$resultado->fields['formfarmnombre']." ".$resultado->fields['presentacion']);
		}//End function

		/*
		* function GetFormFarmaceutica($CodiFormFarmaceutica)
		* $CodiFormFarmaceutica es el codigo de la forma farmaceutica a buscar
		* Se busca en la tabla formas_farmaceuticas  para obtener el nombre
		* retorna el nombre de la forma farmaceutica
		*/
		function GetFormFarmaceutica($CodiFormFarmaceutica)
		{
					//---------------- obtengo la forma farmaceutica ----------------
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$query = "SELECT descripcion
								FROM formas_farmaceuticas
								WHERE forma_farmaceutica = '".$CodiFormFarmaceutica."'";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado)
				{
					$this->error = "Error al tratar de realizar la consulta.<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				else
				{
					$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
					return ($resultado->fields['descripcion']);
				}
		}//End function



		/*
		* function GetViaAdministracion($CodiViaAdministracion)
		* $CodiViaAdministracion es el codigo de la via de administracion del medicamento
		* Se busca en la tabla hc_vias_administracion para obtener el nombre
		* retorna el nombre de la via
		*/
		function GetViaAdministracion($CodiViaAdministracion)
		{
			//---------------- obtengo la via de administracion ----------------
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$query = "SELECT nombre
								FROM hc_vias_administracion
								WHERE via_administracion_id = '".$CodiViaAdministracion."' ORDER BY via_administracion_id";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado)
				{
					$this->error = "Error al tratar de realizar la consulta.<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				else
				{
					$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
					return ($resultado->fields['nombre']);
				}
		}//End function


		/*
		* function ObtenerViasAdministracion()
		* retorna un vector con las vias de administracion de la tabla hc_tipo_vias_administracion
		*/
		function ObtenerViasAdministracion()
		{
			list($dbconn) = GetDBconn();
			$query = "SELECT via_administracion_id, nombre
								FROM hc_vias_administracion
								ORDER BY via_administracion_id";
			$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado)
				{
					$this->error = "Error al tratar de realizar la consulta.<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				else
				{
					$i=0;
					while ($data = $resultado->FetchNextObject($toUpper=false))
					{
						$Vias[$i][0] = $data->via_administracion_id;
						$Vias[$i][1] = $data->nombre;
						$i++;
					}
				}
			return $Vias;
		}//End function


		/*
		* function Verifica_Conexion($query)
		* $query es el query que se quiere verificar
		* Se ejecuta el query y si existe algun error => se retorna falso de los contrario se devuelve el obj resultado
		* retorna el resultado del query
		*/
		function Verifica_Conexion($query,$dbconn)
		{
			$resultado = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				return false;
			}
			return $resultado;
		}//End function


//****************************************************************************
//MEDICAMENTOS REALIZADOS POR CLAUDIA ZUÑIGA
//****************************************************************************


	function Insertar_Varios_Diagnosticos()
  {
		$pfj=$this->frmPrefijo;
		if ($_SESSION['MODIFICANDO'.$pfj]!=1)
		{
				foreach($_REQUEST['opD'.$pfj] as $index=>$codigo)
				{
					$arreglo=explode(",",$codigo);
					$_SESSION['DIAGNOSTICOS'.$pfj][$arreglo[0]]= $arreglo[1];
				}
		}
		else
		{
				foreach($_REQUEST['opD'.$pfj] as $index=>$codigo)
				{
					$arreglo=explode(",",$codigo);
					$_SESSION['DIAGNOSTICOSM'.$pfj][$arreglo[0]]= $arreglo[1];
				}
		}
}

//cor - clzc-jea - ads
function Busqueda_Avanzada_Diagnosticos()
{
		$pfj=$this->frmPrefijo;

		list($dbconn) = GetDBconn();
    $codigo       = STRTOUPPER ($_REQUEST['codigo'.$pfj]);
		$diagnostico  =STRTOUPPER($_REQUEST['diagnostico'.$pfj]);

		$busqueda1 = '';
		$busqueda2 = '';

		if ($codigo != '')
		{
			$busqueda1 =" WHERE diagnostico_id LIKE '$codigo%'";
		}

		if (($diagnostico != '') AND ($codigo != ''))
		{
			$busqueda2 ="AND diagnostico_nombre LIKE '%$diagnostico%'";
		}


		if(empty($_REQUEST['conteo'.$pfj]))
		{
			$query = "SELECT count(*)
						FROM diagnosticos
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
		{
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'.$pfj];
      if($Of > $this->conteo)
			{
				$Of=0;
				$_REQUEST['Of'.$pfj]=0;
				$_REQUEST['paso1'.$pfj]=1;
			}
		}
			    $query = "
						SELECT diagnostico_id, diagnostico_nombre
						FROM diagnosticos
						$busqueda1 $busqueda2 order by diagnostico_id
						LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		//$this->conteo=$resulta->RecordCount();
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

   	if($this->conteo==='0')
		  {       $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			        return false;
		  }
		$resulta->Close();
		return $var;
}

//clzc - si - *
function Busqueda_Avanzada_Medicamentos()
{

		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$opcion      = ($_REQUEST['criterio1'.$pfj]);
		$producto =STRTOUPPER($_REQUEST['producto'.$pfj]);
		$principio_activo =STRTOUPPER($_REQUEST['principio_activo'.$pfj]);

		$busqueda1  = '';
		$busqueda2  = '';
		$dpto = '';
		$espe = '';
		$declaracion = '';
		$condicion = '';

		//condicion para el reenvio de un medicamento
      $reenvio='';
			if ($_REQUEST['codigo_producto'.$pfj]!='')
			{
        $reenvio = " AND a.codigo_producto = '".$_REQUEST['codigo_producto'.$pfj]."'";
			}

		//fin de reenvio

		if ($producto != '')
		{
			  $busqueda1 =" AND a.descripcion LIKE '%$producto%'";
		}

		if ($principio_activo != '')
		{
				$busqueda2 ="AND c.descripcion LIKE '%$principio_activo%'";
		}

		if($opcion == '002')
			{
				$declaracion = ", inv_solicitud_frecuencia as m ";
				$condicion   = "AND a.codigo_producto = m.codigo_producto";
				if ($this->departamento != '' )
					{
						$dpto = "AND m.departamento = '".$this->departamento."'";
					}
				if ($this->especialidad != '' )
					{
						$espe = "AND m.especialidad = '".$this->especialidad."'";
					}
//TUBE QUE COMENTARIAR ESTE IF - EN LA REVISION EL IF SIGUIENTE
//ESTABA COMENTADO, PERO COMO EN CEXTERNA NO ENTONCES OPTE POR DESCOMENTARLO
				if ($dpto == '' AND $espe == '')
					{
						return false;
					}
			}


		if(empty($_REQUEST['conteo'.$pfj]))
			{
          if ($this->bodega == '')
					{
					  $query = "SELECT count(*)
            FROM inventarios_productos as a, medicamentos as b,
						inv_med_cod_principios_activos as c, inv_med_cod_forma_farmacologica as d $declaracion
						where a.codigo_producto = b.codigo_medicamento
						AND b.cod_principio_activo = c.cod_principio_activo
						AND b.cod_forma_farmacologica = d.cod_forma_farmacologica AND a.estado = '1'
            $reenvio $condicion $dpto $espe $busqueda1 $busqueda2";
					}
					else
					{
					  $query = "SELECT count(*)
            FROM inventarios_productos as a left join
						hc_bodegas_consultas as e on(e.bodega_unico = '".$this->bodega."') left join existencias_bodegas as f
						on(e.empresa_id=f.empresa_id and e.centro_utilidad=f.centro_utilidad and
						e.bodega=f.bodega and a.codigo_producto=f.codigo_producto), medicamentos as b,
						inv_med_cod_principios_activos as c, inv_med_cod_forma_farmacologica as d $declaracion
						where a.codigo_producto = b.codigo_medicamento
						AND b.cod_principio_activo = c.cod_principio_activo
						AND b.cod_forma_farmacologica = d.cod_forma_farmacologica AND a.estado = '1'
            $reenvio $condicion $dpto $espe $busqueda1 $busqueda2";
					}

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
		{
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'.$pfj];
      if($Of > $this->conteo)
			{
				$Of=0;
				$_REQUEST['Of'.$pfj]=0;
				$_REQUEST['paso1'.$pfj]=1;
			}
		}
          if ($this->bodega == '')
					{
						$query = "
            select case when b.sw_pos = 1 then 'POS' else 'NO POS' end as item,
						a.codigo_producto, a.descripcion as producto, c.descripcion as principio_activo,
						d.descripcion as forma, d.unidad_dosificacion, b.concentracion_forma_farmacologica, b.unidad_medida_medicamento_id,
						b.factor_conversion, b.factor_equivalente_mg, d.cod_forma_farmacologica FROM inventarios_productos as a, medicamentos as b,
						inv_med_cod_principios_activos as c, inv_med_cod_forma_farmacologica as d $declaracion
						where a.codigo_producto = b.codigo_medicamento
						AND b.cod_principio_activo = c.cod_principio_activo
						AND b.cod_forma_farmacologica = d.cod_forma_farmacologica AND a.estado = '1'
            $reenvio $condicion $dpto $espe $busqueda1 $busqueda2 order by a.codigo_producto
						LIMIT ".$this->limit." OFFSET $Of;";
					}
					else
					{
					   $query = "
            select case when b.sw_pos = 1 then 'POS' else 'NO POS' end as item,
						a.codigo_producto, a.descripcion as producto, c.descripcion as principio_activo,
						d.descripcion as forma, f.existencia, b.concentracion_forma_farmacologica, b.unidad_medida_medicamento_id,	b.factor_conversion, b.factor_equivalente_mg, d.unidad_dosificacion FROM inventarios_productos as a left join
						hc_bodegas_consultas as e on(e.bodega_unico='".$this->bodega."') left join existencias_bodegas as f
						on(e.empresa_id=f.empresa_id and e.centro_utilidad=f.centro_utilidad and
						e.bodega=f.bodega and a.codigo_producto=f.codigo_producto), medicamentos as b,
						inv_med_cod_principios_activos as c, inv_med_cod_forma_farmacologica as d $declaracion
						where a.codigo_producto = b.codigo_medicamento
						AND b.cod_principio_activo = c.cod_principio_activo
						AND b.cod_forma_farmacologica = d.cod_forma_farmacologica AND a.estado = '1'
            $reenvio $condicion $dpto $espe $busqueda1 $busqueda2 order by a.codigo_producto
						LIMIT ".$this->limit." OFFSET $Of;";
					}

		$resulta = $dbconn->Execute($query);

		//$this->conteo=$resulta->RecordCount();
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

   	if($this->conteo==='0')
		  {       $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			        return false;
		  }
		$resulta->Close();
		return $var;
}


     //clzc - si - *
     function Insertar_Medicamentos($no_pos_paciente)
     {
		//inserta un 1 si es pos o si es no pos y selecciono el check
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
	     $dbconn->BeginTrans();

          $_REQUEST['no_pos_paciente'.$pfj] = $no_pos_paciente;

          if ($_REQUEST['dosis'.$pfj] == '' OR $_REQUEST['cantidad'.$pfj] == '' OR $_REQUEST['via_administracion'.$pfj] == -1 OR
              $_REQUEST['unidad_dosis'.$pfj] == -1 OR empty($_REQUEST['unidad_dosis'.$pfj]))
          {
               if($_REQUEST['via_administracion'.$pfj] == '-1')
               {
               	$this->frmError["via_administracion"]=1;
               }
          	if($_REQUEST['cantidad'.$pfj] == '')
               {
               	$this->frmError["cantidad"]=1;
               }

			if($_REQUEST['dosis'.$pfj] == '')
			{
               	$this->frmError["dosis"]=1;
               }

               if(($_REQUEST['unidad_dosis'.$pfj] == '-1') OR (empty($_REQUEST['unidad_dosis'.$pfj])))
               {
               	$this->frmError["unidad_dosis"]=1;
               }

               $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
               return false;
          }


          if($_REQUEST['dosis'.$pfj] == '')
          {
                    $_REQUEST['dosis'.$pfj]='NULL';
          }
          else
          {
               if (is_numeric($_REQUEST['dosis'.$pfj])==0)
               {
                    $this->frmError["dosis"]=1;
                    $this->frmError["MensajeError"]="DOSIS INVALIDA, DIGITE SOLO NUMEROS.";
                    return false;
               }
          }

          //controlando la insercion de la via
          $via = $_REQUEST['via_administracion'.$pfj];
          if($via == '')
          {
               $via = 'NULL';
     	}
          else
          {
        		$via ="'$via'";
          }
          //fin del control


          //OBLIGATORIEDAD DE LA FRECUENCIA
          if($_REQUEST['opcion'.$pfj]=='')
          {
               $this->frmError["frecuencia"]=1;
               $this->frmError["MensajeError"]="SELECCIONE UNA OPCION DE FRECUENCIA PARA LA FORMULACION.";
               return false;
          }
          //fin de obligatoriedad

          if (empty($_REQUEST['opcion'.$pfj]))
          {
               $_REQUEST['opcion'.$pfj] = 0;
          }

      	if($_REQUEST['sw_ambulatorio'.$pfj]){$sw_ambulatorio=1;}else{$sw_ambulatorio=0;}
	 	if( ($_REQUEST['solucion'.$pfj]!=-1) AND ($_REQUEST['solucion'.$pfj] != ''))
          {$solucion="'".$_REQUEST['solucion'.$pfj]."'";}else
          {$solucion='NULL';}
          if($_REQUEST['solucionUnidad'.$pfj]!=-1 AND ($_REQUEST['solucionUnidad'.$pfj] != ''))
          {$solucionUnidad="'".$_REQUEST['solucionUnidad'.$pfj]."'";}
          else{$solucionUnidad='NULL';}
          $query="INSERT INTO hc_medicamentos_recetados_hosp
                         (codigo_producto, evolucion_id, ingreso ,cantidad, observacion, sw_paciente_no_pos, via_administracion_id,
                          dosis, unidad_dosificacion, tipo_opcion_posologia_id,sw_ambulatorio,solucion_id,cantidad_id)
                  VALUES ('".$_REQUEST['codigo_producto'.$pfj]."', ".$this->evolucion.",
                          ".$this->ingreso.",
                          ".$_REQUEST['cantidad'.$pfj].", '".$_REQUEST['observacion'.$pfj]."',
                          '".$no_pos_paciente."', ".$via.",
                          ".$_REQUEST['dosis'.$pfj].",'".$_REQUEST['unidad_dosis'.$pfj]."',
                          ".$_REQUEST['opcion'.$pfj].",'$sw_ambulatorio',$solucion,$solucionUnidad)";
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al insertar en hc_medicamentos_recetados_hosp";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $this->frmError["MensajeError"]="EL MEDICAMENTO YA HA SIDO FORMULADO EN ESTA EVOLUCION.";
               $dbconn->RollbackTrans();
               //caso especial que retorne true porque si ya existe elmedicamento no debe volver
               //a la forma de llenado si no a la forma principal
               return true;
          }
		else
          {
			//esto se hace por si retorna error despues de haber convertido a dosis en null para la insercion
               if ($_REQUEST['dosis'.$pfj]=='NULL')
               {
                         $_REQUEST['dosis'.$pfj]='';
               }

               if ($_REQUEST['opcion'.$pfj] == '1')
               {
                    if (($_REQUEST['periocidad'.$pfj]=='-1') OR ($_REQUEST['tiempo'.$pfj]=='-1'))
                    {
                         $this->frmError["opcion1"]=1;
                         $this->frmError["MensajeError"]="PARA OPCION 1 DE FRECUENCIA DEBE SELECIONAR UNA OPCION.";
                         return false;
                    }
                    else
                    {
                         $query="INSERT INTO hc_posologia_horario_op1_hosp
                                        (codigo_producto, evolucion_id, periocidad_id, tiempo)
                                 VALUES ('".$_REQUEST['codigo_producto'.$pfj]."', ".$this->evolucion.",
                                         ".$_REQUEST['periocidad'.$pfj].", '".$_REQUEST['tiempo'.$pfj]."')";
                         $resulta=$dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al insertar en hc_posologia_horario_op1_hosp";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 1.x";
                              $dbconn->RollbackTrans();
                              //caso especial que retorne true porque si ya existe elmedicamento no debe volver
                              //a la forma de llenado si no a la forma principal
                              return true;
                         }
                    }
               }
               if ($_REQUEST['opcion'.$pfj] == '2')
               {
                    if ($_REQUEST['duracion'.$pfj]=='-1')
                    {
          			$this->frmError["opcion2"]=1;
                         $this->frmError["MensajeError"]="PARA OPCION 2 DE FRECUENCIA DEBE SELECIONAR UNA OPCION.";
                         return false;
                    }
                    else
                    {
                         $query="INSERT INTO hc_posologia_horario_op2_hosp
                                             (codigo_producto, evolucion_id, duracion_id)
                                        VALUES ('".$_REQUEST['codigo_producto'.$pfj]."', ".$this->evolucion.",
                                                '".$_REQUEST['duracion'.$pfj]."')";
                         $resulta=$dbconn->Execute($query);

                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al insertar en hc_posologia_horario_op2_hosp";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 2.";
                              $dbconn->RollbackTrans();
                              //caso especial que retorne true porque si ya existe elmedicamento no debe volver
                              //a la forma de llenado si no a la forma principal
                              return true;
                         }
                    }
               }
               if ($_REQUEST['opcion'.$pfj] == '3')
               {
                    if (empty($_REQUEST['momento'.$pfj]))
                    {
                  		$this->frmError["opcion3"]=1;
                         $this->frmError["MensajeError"]="PARA OPCION 3 DE FRECUENCIA DEBE DILIGENCIAR LOS CAMPOS.";
                         return false;
                    }
               	else
                    {
                         if ((!empty($_REQUEST['desayuno'.$pfj])) OR	(!empty($_REQUEST['almuerzo'.$pfj])) OR (!empty($_REQUEST['cena'.$pfj])))
                         {
                                   $query="INSERT INTO hc_posologia_horario_op3_hosp
                                                  (codigo_producto, evolucion_id, sw_estado_momento,
                                                  sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena)
                                                  VALUES ('".$_REQUEST['codigo_producto'.$pfj]."', ".$this->evolucion.",
                                                  '".$_REQUEST['momento'.$pfj]."', '".$_REQUEST['desayuno'.$pfj]."',
                                                  '".$_REQUEST['almuerzo'.$pfj]."', '".$_REQUEST['cena'.$pfj]."')";
                                                  $resulta=$dbconn->Execute($query);

                                   if ($dbconn->ErrorNo() != 0)
                                   {
                                        $this->error = "Error al insertar en hc_posologia_horario_op3_hosp";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 3.";
                                        $dbconn->RollbackTrans();
                                        //caso especial que retorne true porque si ya existe elmedicamento no debe volver
                                        //a la forma de llenado si no a la forma principal
                                        return true;
                                   }
                         }
                         else
                         {
                                   $this->frmError["MensajeError"]="PARA OPCION 3 DE FRECUENCIA DEBE ESCOGER UNA COMIDA.";
                                   return false;
                         }
                    }
               }
               if ($_REQUEST['opcion'.$pfj] == '4')
               {
                    if (empty($_REQUEST['opH'.$pfj]))
                    {
		               $this->frmError["opcion4"]=1;
                         $this->frmError["MensajeError"]="PARA OPCION 4 DE FRECUENCIA DEBE SELECCIONAR UNA HORA ESPECIFICA.";
                         return false;
                    }
                    else
                    {
                         foreach($_REQUEST['opH'.$pfj] as $index=>$codigo)
                         {
                              $arreglo=explode(",",$codigo);
                              $query="INSERT INTO hc_posologia_horario_op4_hosp
                                                  (codigo_producto, evolucion_id, hora_especifica)
                                                  VALUES ('".$_REQUEST['codigo_producto'.$pfj]."',
                                                       ".$this->evolucion.", '".$arreglo[0]."')";
                              $resulta=$dbconn->Execute($query);

                              if ($dbconn->ErrorNo() != 0)
                              {
                                        $this->error = "Error al insertar en hc_posologia_horario_op4_hosp";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA HORA ESPECIFICA.";
                                        $dbconn->RollbackTrans();
                                        //caso especial que retorne true porque si ya existe elmedicamento no debe volver
                                        //a la forma de llenado si no a la forma principal
                                        return true;
                              }
                         }
                    }
               }
               if ($_REQUEST['opcion'.$pfj] == '5')
               {
                    if (empty($_REQUEST['frecuencia_suministro'.$pfj]))
                    {
                    	$this->frmError["opcion5"]=1;
                         $this->frmError["MensajeError"]="PARA OPCION 5 DE FRECUENCIA DEBE ESCRIBIR EN LA FRECUENCIA DE SUMINISTRO.";
                         return false;
                    }
                    else
                    {
                         $query="INSERT INTO hc_posologia_horario_op5_hosp
                                        (codigo_producto, evolucion_id, frecuencia_suministro)
                                        VALUES ('".$_REQUEST['codigo_producto'.$pfj]."', ".$this->evolucion.",
                                        '".$_REQUEST['frecuencia_suministro'.$pfj]."')";
                         $resulta=$dbconn->Execute($query);

                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al insertar en hc_posologia_horario_op5_hosp";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 5.";
                              $dbconn->RollbackTrans();
                              //caso especial que retorne true porque si ya existe elmedicamento no debe volver
                              //a la forma de llenado si no a la forma principal
                              return true;
                         }
                    }
               }
          }
          //CLAUDIA - OK VERIFICACION ESPECIAL PARA HOSPITALIZACION
          //SE VERIFICA QUE EL MEDICAMENTO QUE ESTABA EN ESTADO '0' O '8' CAMBIE A ESTADO 9
          //DESPUES DEL REENVIO

          $query="SELECT a.codigo_producto, a.evolucion_id
                    FROM hc_medicamentos_recetados_hosp a,
                    hc_evoluciones b, ingresos c
                    where a.codigo_producto = '".$_REQUEST['codigo_producto'.$pfj]."'
                    AND b.ingreso = c.ingreso
                    AND c.ingreso = ".$this->ingreso."
                    AND b.evolucion_id = a.evolucion_id and a.sw_estado IN ('0','8')";
     	$result6 = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_plantillas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$vector_c=$result6->GetRowAssoc($ToUpper = false);
		}

		if($vector_c[codigo_producto]!='')
		{
			 $query= "UPDATE hc_medicamentos_recetados_hosp SET sw_estado= '9'
				     WHERE codigo_producto =  '".$vector_c['codigo_producto']."'
					AND evolucion_id = ".$vector_c['evolucion_id']."";

			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al activar el medicamento en hc_medicamentos_recetados_hosp";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return true;
			}
		}
		//fin de la actualizacion
		$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
          $dbconn->CommitTrans();
          return true;
	}


     //esta funcion se ejecuta cuando el medico despues de solicitar el medicamento va a
     //justificarlo para asegurarnos de que los datos minimos para mas adelante insertar
     //el medicamento existen.
     //*
     function Verificacion_Previa_Insertar_Medicamentos()
     {
          $pfj=$this->frmPrefijo;

          if ($_REQUEST['cantidad'.$pfj] == '' OR $_REQUEST['via_administracion'.$pfj] == -1 OR
          $_REQUEST['unidad_dosis'.$pfj] == -1 OR empty($_REQUEST['unidad_dosis'.$pfj]) OR
          $_REQUEST['dosis'.$pfj] == '' )
          {
               if($_REQUEST['via_administracion'.$pfj] == '-1')
               {
                         $this->frmError["via_administracion"]=1;
               }
               if($_REQUEST['cantidad'.$pfj] == '')
               {
                         $this->frmError["cantidad"]=1;
               }

               if($_REQUEST['dosis'.$pfj] == '')
               {
                         $this->frmError["dosis"]=1;
               }

               if(($_REQUEST['unidad_dosis'.$pfj] == '-1') OR (empty($_REQUEST['unidad_dosis'.$pfj])))
               {
                         $this->frmError["unidad_dosis"]=1;
               }

               $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
               return false;
          }

          if($_REQUEST['dosis'.$pfj] != '')
          {
                    if (is_numeric($_REQUEST['dosis'.$pfj])==0)
                    {
                         $this->frmError["dosis"]=1;
                         $this->frmError["MensajeError"]="DOSIS INVALIDA, DIGITE SOLO NUMEROS.";
                         return false;
                    }
          }

          if (empty($_REQUEST['opcion'.$pfj]))
          {
                    $_REQUEST['opcion'.$pfj] = 0;
          }
          if ($_SESSION['SPIA'.$pfj]==1)
          {
               $_SESSION['MEDICAMENTOS'.$pfj]['posologia_bd']= $_REQUEST['opcion_posol'.$pfj];
               $_SESSION['MEDICAMENTOS'.$pfj]['producto']=$_REQUEST['producto'.$pfj];
               $_SESSION['MEDICAMENTOS'.$pfj]['principio_activo']=$_REQUEST['principio_activo'.$pfj];
               $_SESSION['MEDICAMENTOS'.$pfj]['concentracion_forma_farmacologica']=$_REQUEST['concentracion_forma_farmacologica'.$pfj];
               $_SESSION['MEDICAMENTOS'.$pfj]['unidad_medida_medicamento_id']=$_REQUEST['unidad_medida_medicamento_id'.$pfj];
               $_SESSION['MEDICAMENTOS'.$pfj]['forma']=$_REQUEST['forma'.$pfj];
          }
          $_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']=$_REQUEST['codigo_producto'.$pfj];
          $_SESSION['MEDICAMENTOS'.$pfj]['cantidad']=$_REQUEST['cantidad'.$pfj];
          $_SESSION['MEDICAMENTOS'.$pfj]['observacion']=$_REQUEST['observacion'.$pfj];
          $_SESSION['MEDICAMENTOS'.$pfj]['via_administracion_id']=$_REQUEST['via_administracion'.$pfj];
          $_SESSION['MEDICAMENTOS'.$pfj]['dosis']=$_REQUEST['dosis'.$pfj];
          $_SESSION['MEDICAMENTOS'.$pfj]['unidad_dosificacion']=$_REQUEST['unidad_dosis'.$pfj];
          $_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id']=$_REQUEST['opcion'.$pfj];
          $_SESSION['MEDICAMENTOS'.$pfj]['sw_ambulatorio']=$_REQUEST['sw_ambulatorio'.$pfj];
          $_SESSION['MEDICAMENTOS'.$pfj]['solucion']=$_REQUEST['solucion'.$pfj];
          $_SESSION['MEDICAMENTOS'.$pfj]['solucionUnidad']=$_REQUEST['solucionUnidad'.$pfj];

          //sw_paciente_no_pos y evolucion_id no se metio en session

          if ($_REQUEST['opcion'.$pfj] == '1')
          {
               if (($_REQUEST['periocidad'.$pfj]=='-1') OR ($_REQUEST['tiempo'.$pfj]=='-1'))
               {
                    $this->frmError["opcion1"]=1;
                    $this->frmError["MensajeError"]="PARA OPCION 1 DE FRECUENCIA DEBE SELECIONAR UNA OPCION.";
                    return false;
               }
               else
               {
                    $_SESSION['MEDICAMENTOS'.$pfj]['periocidad_id']=$_REQUEST['periocidad'.$pfj];
                    $_SESSION['MEDICAMENTOS'.$pfj]['tiempo']=$_REQUEST['tiempo'.$pfj];
               }
          }
          if ($_REQUEST['opcion'.$pfj] == '2')
          {
               if ($_REQUEST['duracion'.$pfj]=='-1')
                    {
                         $this->frmError["opcion2"]=1;
                         $this->frmError["MensajeError"]="PARA OPCION 2 DE FRECUENCIA DEBE SELECIONAR UNA OPCION.";
                         return false;
                    }
               else
                    {
                         $_SESSION['MEDICAMENTOS'.$pfj]['duracion_id']=$_REQUEST['duracion'.$pfj];
                    }
          }
	     if ($_REQUEST['opcion'.$pfj] == '3')
          {
               if (empty($_REQUEST['momento'.$pfj]))
               {
                         $this->frmError["opcion3"]=1;
                         $this->frmError["MensajeError"]="PARA OPCION 3 DE FRECUENCIA DEBE DILIGENCIAR LOS CAMPOS.";
                         return false;
               }
               else
               {
                    if ((!empty($_REQUEST['desayuno'.$pfj])) OR	(!empty($_REQUEST['almuerzo'.$pfj])) OR (!empty($_REQUEST['cena'.$pfj])))
                    {
                              $_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_momento']=$_REQUEST['momento'.$pfj];
                              $_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_desayuno']=$_REQUEST['desayuno'.$pfj];
                              $_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_almuerzo']=$_REQUEST['almuerzo'.$pfj];
                              $_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_cena']=$_REQUEST['cena'.$pfj];
                    }
                    else
                    {
                              $this->frmError["MensajeError"]="PARA OPCION 3 DE FRECUENCIA DEBE ESCOGER UNA COMIDA.";
                              return false;
                    }
               }
          }
          if ($_REQUEST['opcion'.$pfj] == '4')
          {
               if (empty($_REQUEST['opH'.$pfj]))
               {
               	$this->frmError["opcion4"]=1;
                    $this->frmError["MensajeError"]="PARA OPCION 4 DE FRECUENCIA DEBE SELECCIONAR UNA HORA ESPECIFICA.";
                    return false;
               }
               else
          	{	
                    $i= 0;
                    foreach($_REQUEST['opH'.$pfj] as $index=>$codigo)
                    {
                         $arreglo=explode(",",$codigo);
                         $_SESSION['POSOLOGIA4'.$pfj]['hora_especifica'][$i]=$arreglo[0];
                         $i++;
                    }
                    $_SESSION['POSOLOGIA4'.$pfj]['cantidad_hora_especifica'] = $i;
               }
          }
          if ($_REQUEST['opcion'.$pfj] == '5')
          {
               if (empty($_REQUEST['frecuencia_suministro'.$pfj]))
               {
                         $this->frmError["MensajeError"]="PARA OPCION 5 DE FRECUENCIA DEBE ESCRIBIR EN LA FRECUENCIA DE SUMINISTRO.";
                         return false;
               }
               else
               {
                    $_SESSION['MEDICAMENTOS'.$pfj]['frecuencia_suministro']=$_REQUEST['frecuencia_suministro'.$pfj];
	          }
          }

		//cargo si existe una justificacion existente
		$justificacion_existente =$this->Consulta_Justificacion_Almacenada($_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']);
		if ($justificacion_existente)
		{
               $_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_hosp']	=$justificacion_existente[0][hc_justificaciones_no_pos_hosp];
               $_SESSION['JUSTIFICACION'.$pfj]['duracion_tratamiento']			=$justificacion_existente[0][duracion];
               $_SESSION['JUSTIFICACION'.$pfj]['dosis_dia']			=$justificacion_existente[0][dosis_dia];
               $_SESSION['JUSTIFICACION'.$pfj]['justificacion_solicitud']	=$justificacion_existente[0][justificacion];
               $_SESSION['JUSTIFICACION'.$pfj]['ventajas_medicamento']			=$justificacion_existente[0][ventajas_medicamento];
               $_SESSION['JUSTIFICACION'.$pfj]['ventajas_tratamiento']			=$justificacion_existente[0][ventajas_tratamiento];
               $_SESSION['JUSTIFICACION'.$pfj]['precauciones']							=$justificacion_existente[0][precauciones];
               $_SESSION['JUSTIFICACION'.$pfj]['controles_evaluacion_efectividad']=$justificacion_existente[0][controles_evaluacion_efectividad];
               $_SESSION['JUSTIFICACION'.$pfj]['tiempo_respuesta_esperado']=$justificacion_existente[0][tiempo_respuesta_esperado];
               $_SESSION['JUSTIFICACION'.$pfj]['sw_riesgo_inminente']			=$justificacion_existente[0][sw_riesgo_inminente];
               $_SESSION['JUSTIFICACION'.$pfj]['riesgo_inminente']					=$justificacion_existente[0][riesgo_inminente];
               $_SESSION['JUSTIFICACION'.$pfj]['sw_agotadas_posibilidades_existentes']=$justificacion_existente[0][sw_agotadas_posibilidades_existentes] ;
               $_SESSION['JUSTIFICACION'.$pfj]['sw_homologo_pos']					=$justificacion_existente[0][sw_homologo_pos] ;
               $_SESSION['JUSTIFICACION'.$pfj]['sw_comercializacion_pais']	=$justificacion_existente[0][sw_comercializacion_pais];
               $_SESSION['JUSTIFICACION'.$pfj]['descripcion_caso_clinico']	=$justificacion_existente[0][descripcion_caso_clinico];
               $_SESSION['JUSTIFICACION'.$pfj]['sw_existe_alternativa_pos']=$justificacion_existente[0][sw_existe_alternativa_pos];
               if($justificacion_existente[0][sw_existe_alternativa_pos]=='1')
               {
                    $alternativas_pos =$this->Consulta_Alternativas_Pos($_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_hosp']);
                    for ($j=1;$j<3;$j++)
                    {
                              $_SESSION['JUSTIFICACION'.$pfj]['medicamento_pos'.$j]						=$alternativas_pos[$j-1][medicamento_pos];
                              $_SESSION['JUSTIFICACION'.$pfj]['principio_activo_pos'.$j]			=$alternativas_pos[$j-1][principio_activo_pos];
                              $_SESSION['JUSTIFICACION'.$pfj]['dosis_dia_pos'.$j]							=$alternativas_pos[$j-1][dosis_dia_pos];
                              $_SESSION['JUSTIFICACION'.$pfj]['duracion_tratamiento_pos'.$j]	=$alternativas_pos[$j-1][duracion_tratamiento_pos];
                              $_SESSION['JUSTIFICACION'.$pfj]['sw_no_mejoria'.$j]							=$alternativas_pos[$j-1][sw_no_mejoria];
                              $_SESSION['JUSTIFICACION'.$pfj]['sw_reaccion_secundaria'.$j]		=$alternativas_pos[$j-1][sw_reaccion_secundaria];
                              $_SESSION['JUSTIFICACION'.$pfj]['reaccion_secundaria'.$j]				=$alternativas_pos[$j-1][reaccion_secundaria];
                              $_SESSION['JUSTIFICACION'.$pfj]['sw_contraindicacion'.$j]				=$alternativas_pos[$j-1][sw_contraindicacion];
                              $_SESSION['JUSTIFICACION'.$pfj]['contraindicacion'.$j]					=$alternativas_pos[$j-1][contraindicacion];
                              $_SESSION['JUSTIFICACION'.$pfj]['otras'.$j]											=$alternativas_pos[$j-1][otras];
                    }
               }
		}
		else
		{
			//cargo la plantilla del medicamento que se va a justificar
			$plantilla =$this->Consulta_Plantillas_Justificacion($_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']);
			if ($plantilla)
			{
					$_SESSION['JUSTIFICACION'.$pfj]['justificacion_solicitud']	=$plantilla[0][justificacion];
					$_SESSION['JUSTIFICACION'.$pfj]['ventajas_medicamento']			=$plantilla[0][ventajas_medicamento];
					$_SESSION['JUSTIFICACION'.$pfj]['ventajas_tratamiento']			=$plantilla[0][ventajas_tratamiento];
					$_SESSION['JUSTIFICACION'.$pfj]['precauciones']							=$plantilla[0][precauciones];
					$_SESSION['JUSTIFICACION'.$pfj]['controles_evaluacion_efectividad']=$plantilla[0][controles_evaluacion_efectividad];
					$_SESSION['JUSTIFICACION'.$pfj]['tiempo_respuesta_esperado']=$plantilla[0][tiempo_respuesta_esperado];
					$_SESSION['JUSTIFICACION'.$pfj]['sw_riesgo_inminente']			=$plantilla[0][sw_riesgo_inminente];
					$_SESSION['JUSTIFICACION'.$pfj]['riesgo_inminente']					=$plantilla[0][riesgo_inminente];
					$_SESSION['JUSTIFICACION'.$pfj]['sw_agotadas_posibilidades_existentes']=$plantilla[0][sw_agotadas_posibilidades_existentes] ;
					$_SESSION['JUSTIFICACION'.$pfj]['sw_homologo_pos']					=$plantilla[0][sw_homologo_pos] ;
					$_SESSION['JUSTIFICACION'.$pfj]['sw_comercializacion_pais']	=$plantilla[0][sw_comercializacion_pais];
			}
		}

		//cargo la sesion de diagnosticos de ingreso
		if(empty($_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_hosp']))
		{
			if (empty($_SESSION['DIAGNOSTICOS'.$pfj]))
			{
				$diag =$this->Diagnosticos_de_Ingreso();
				if ($diag)
				{
					for($j=0;$j<sizeof($diag);$j++)
					{
						$_SESSION['DIAGNOSTICOS'.$pfj][$diag[$j][diagnostico_id]]= $diag[$j][diagnostico_nombre];
					}
				}
			}
		}
		else
		{
			if (empty($_SESSION['DIAGNOSTICOS'.$pfj]))
			{
				$diag =$this->Consulta_Diagnosticos_Justificacion($_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_hosp']);
				if ($diag)
				{
					for($j=0;$j<sizeof($diag);$j++)
					{
						$_SESSION['DIAGNOSTICOS'.$pfj][$diag[$j][diagnostico_id]]= $diag[$j][diagnostico_nombre];
					}
				}
			}

		}
		return true;
	}


	//clzc - si - *
     function Insertar_Justificacion_No_Pos()
     {
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		if ($_REQUEST['duracion_tratamiento'.$pfj] == '' OR (empty($_SESSION['DIAGNOSTICOS'.$pfj])))
          {
               if($_REQUEST['duracion_tratamiento'.$pfj] == '')
               {
                         $this->frmError["duracion_tratamiento"]=1;
               }

               if((empty($_SESSION['DIAGNOSTICOS'.$pfj])))
               {
                         $this->frmError["diagnostico_id"] = 1;
               }

               $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
               return false;
          }

          if($_SESSION['MEDICAMENTOS'.$pfj]['dosis']=='')
          {
               $_SESSION['MEDICAMENTOS'.$pfj]['dosis']='NULL';
          }

          //controlando la insercion de la via
          $via = $_SESSION['MEDICAMENTOS'.$pfj]['via_administracion_id'];
          if($via == '')
          {
               $via = 'NULL';
          }
          else
          {
          	$via ="'$via'";
          }
			//fin del control

          //.....................................
          //PROCESO PARA LA INSERCCION DEL MEDICAMENTO NO POS COMO TAL
          //.....................................
          $no_pos_paciente = '0';
          if ($_SESSION['SPIA'.$pfj]==1)
          {     
               if($_SESSION['MEDICAMENTOS'.$pfj]['sw_ambulatorio']){$sw_ambulatorio=1;}else{$sw_ambulatorio=0;}
               
               if(($_SESSION['MEDICAMENTOS'.$pfj]['solucion']!=-1) AND ($_SESSION['MEDICAMENTOS'.$pfj]['solucion'] != ''))
               {$solucion="'".$_SESSION['MEDICAMENTOS'.$pfj]['solucion']."'";}else{$solucion='NULL';}
               
               if(($_SESSION['MEDICAMENTOS'.$pfj]['solucionUnidad']!=-1) AND ($_SESSION['MEDICAMENTOS'.$pfj]['solucionUnidad'] != ''))
               {$solucionUnidad="'".$_SESSION['MEDICAMENTOS'.$pfj]['solucionUnidad']."'";}else{$solucionUnidad='NULL';}

                    $query="UPDATE hc_medicamentos_recetados_hosp SET
                    cantidad = ".$_SESSION['MEDICAMENTOS'.$pfj]['cantidad'].",
                    observacion = '".$_SESSION['MEDICAMENTOS'.$pfj]['observacion']."',
                    sw_paciente_no_pos = '".$no_pos_paciente."',
                    via_administracion_id = ".$via.",
                    dosis =  ".$_SESSION['MEDICAMENTOS'.$pfj]['dosis'].",
                    unidad_dosificacion = '".$_SESSION['MEDICAMENTOS'.$pfj]['unidad_dosificacion']."',
                    tipo_opcion_posologia_id =  ".$_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id'].",
                    sw_ambulatorio = '".$sw_ambulatorio."',
                    solucion_id=$solucion,
                    cantidad_id=$solucionUnidad
                    WHERE	codigo_producto = '".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."' AND evolucion_id = ".$this->evolucion."";
          }
          else
          {     
               if($_SESSION['MEDICAMENTOS'.$pfj]['sw_ambulatorio']){$sw_ambulatorio=1;}else{$sw_ambulatorio=0;}
               if(($_SESSION['MEDICAMENTOS'.$pfj]['solucion']!=-1) AND ($_SESSION['MEDICAMENTOS'.$pfj]['solucion'] != ''))
               {$solucion="'".$_SESSION['MEDICAMENTOS'.$pfj]['solucion']."'";}else{$solucion='NULL';}
               
               if(($_SESSION['MEDICAMENTOS'.$pfj]['solucionUnidad']!=-1) AND ($_SESSION['MEDICAMENTOS'.$pfj]['solucionUnidad'] != ''))
               {$solucionUnidad="'".$_SESSION['MEDICAMENTOS'.$pfj]['solucionUnidad']."'";}else{$solucionUnidad='NULL';}

               $query="INSERT INTO hc_medicamentos_recetados_hosp
               (codigo_producto, evolucion_id, ingreso, cantidad, observacion, sw_paciente_no_pos, via_administracion_id,
               dosis, unidad_dosificacion, tipo_opcion_posologia_id,sw_ambulatorio,solucion_id,cantidad_id)
               VALUES ('".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."',	".$this->evolucion.",
                         ".$this->ingreso.",
                         ".$_SESSION['MEDICAMENTOS'.$pfj]['cantidad'].",
                         '".$_SESSION['MEDICAMENTOS'.$pfj]['observacion']."',
                         '".$no_pos_paciente."', ".$via.",
                         ".$_SESSION['MEDICAMENTOS'.$pfj]['dosis'].",
                         '".$_SESSION['MEDICAMENTOS'.$pfj]['unidad_dosificacion']."',
                         ".$_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id'].",'$sw_ambulatorio',
                         $solucion,$solucionUnidad)";
          }

          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
                    $this->error = "Error al insertar en hc_medicamentos_recetados_hosp";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->frmError["MensajeError"]="EL MEDICAMENTO YA HA SIDO FORMULADO EN ESTA EVOLUCION.";
                    $dbconn->RollbackTrans();
                    return false;
          }
          else
          {
			//proceso que solo se ejecuta si es una modificacion
               $query= '';
               if ($_SESSION['SPIA'.$pfj]==1)
               {
                         if ($_SESSION['MEDICAMENTOS'.$pfj]['posologia_bd'] == 1)
                         {
                                             $query ="DELETE FROM hc_posologia_horario_op1_hosp
                                             WHERE codigo_producto = '".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."' AND evolucion_id = ".$this->evolucion."";
                         }
                         if ($_SESSION['MEDICAMENTOS'.$pfj]['posologia_bd'] == 2)
                         {
                                             $query ="DELETE FROM hc_posologia_horario_op2_hosp
                                             WHERE codigo_producto = '".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."' AND evolucion_id = ".$this->evolucion."";
                         }
                         if ($_SESSION['MEDICAMENTOS'.$pfj]['posologia_bd'] == 3)
                         {
                                             $query ="DELETE FROM hc_posologia_horario_op3_hosp
                                             WHERE codigo_producto = '".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."' AND evolucion_id = ".$this->evolucion."";
                         }
                         if ($_SESSION['MEDICAMENTOS'.$pfj]['posologia_bd'] == 4)
                         {
                                             $query ="DELETE FROM hc_posologia_horario_op4_hosp
                                             WHERE codigo_producto = '".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."' AND evolucion_id = ".$this->evolucion."";
                         }
                         if ($_SESSION['MEDICAMENTOS'.$pfj]['posologia_bd'] == 5)
                         {
                                             $query ="DELETE FROM hc_posologia_horario_op5_hosp
                                             WHERE codigo_producto = '".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."' AND evolucion_id = ".$this->evolucion."";
                         }

                         $resulta=$dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR";
                              $dbconn->RollbackTrans();
                              return false;
                         }
               }
		//fin del proceo de borrado

               if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id'] == '1')
               {
                    $query="INSERT INTO hc_posologia_horario_op1_hosp
                                                  (codigo_producto, evolucion_id, periocidad_id, tiempo)
                                                  VALUES ('".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."',
                                                  ".$this->evolucion.",	".$_SESSION['MEDICAMENTOS'.$pfj]['periocidad_id'].",
                                                  '".$_SESSION['MEDICAMENTOS'.$pfj]['tiempo']."')";
                    $resulta=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                    $this->error = "Error al insertar en hc_posologia_horario_op1_hosp";
                                   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                   $this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 1.y";
                                   $dbconn->RollbackTrans();
                                   return false;
                    }
               }

               if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id'] == '2')
               {
                         $query="INSERT INTO hc_posologia_horario_op2_hosp
                                             (codigo_producto, evolucion_id, duracion_id)
                                             VALUES ('".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."',
                                             ".$this->evolucion.",	'".$_SESSION['MEDICAMENTOS'.$pfj]['duracion_id']."')";
                                             $resulta=$dbconn->Execute($query);

                              if ($dbconn->ErrorNo() != 0)
                              {
                                   $this->error = "Error al insertar en hc_posologia_horario_op2_hosp";
                                   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                   $this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 2.";
                                   $dbconn->RollbackTrans();
                                   return false;
                              }
               }
               if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id'] == '3')
               {
                    $query="INSERT INTO hc_posologia_horario_op3_hosp
                                   (codigo_producto, evolucion_id, sw_estado_momento,
                                   sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena)
                                   VALUES ('".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."',
                                        ".$this->evolucion.",
                                   '".$_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_momento']."',
                                   '".$_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_desayuno']."',
                                   '".$_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_almuerzo']."',
                                   '".$_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_cena']."')";
                                   $resulta=$dbconn->Execute($query);

                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al insertar en hc_posologia_horario_op3_hosp";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 3.";
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
               if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id'] == '4')
               {
                    for($i=0;$i<$_SESSION['POSOLOGIA4'.$pfj]['cantidad_hora_especifica'];$i++)
                    {
                         $query="INSERT INTO hc_posologia_horario_op4_hosp
                                             (codigo_producto, evolucion_id, hora_especifica)
                                             VALUES ('".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."',
                                                  ".$this->evolucion.", '".$_SESSION['POSOLOGIA4'.$pfj]['hora_especifica'][$i]."')";
                         $resulta=$dbconn->Execute($query);

                         if ($dbconn->ErrorNo() != 0)
                              {
                                        $this->error = "Error al insertar en hc_posologia_horario_op4_hosp";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA HORA ESPECIFICA.";
                                        $dbconn->RollbackTrans();
                                        return false;
                              }
                    }

               }
               if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id'] == '5')
               {
                    $query="INSERT INTO hc_posologia_horario_op5_hosp
                                   (codigo_producto, evolucion_id, frecuencia_suministro)
                                   VALUES ('".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."',
                                   ".$this->evolucion.",
                                   '".$_SESSION['MEDICAMENTOS'.$pfj]['frecuencia_suministro']."')";
                    $resulta=$dbconn->Execute($query);

                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al insertar en hc_posologia_horario_op5_hosp";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 5.";
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
          }
          
          //....................................
          //fin del proceso de inserccion del medicamento
          //.....................................
          
          //ojo este dato no estan llegando
          $sw_existe_alternativa_pos = 1;
		if (empty($_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_hosp']))
		{
			//realiza el id manual de la tabla
               $query1="SELECT nextval('hc_justificaciones_no_pos_hos_hc_justificaciones_no_pos_hos_seq')";

               $result=$dbconn->Execute($query1);
               $hc_justificaciones_no_pos_hosp=$result->fields[0];
			//fin de la operacion

			 $query=	"INSERT INTO hc_justificaciones_no_pos_hosp (hc_justificaciones_no_pos_hosp,
							evolucion_id, codigo_producto, usuario_id_autoriza, duracion, dosis_dia,
							justificacion, ventajas_medicamento, ventajas_tratamiento, precauciones,
							controles_evaluacion_efectividad,	tiempo_respuesta_esperado, riesgo_inminente,
							sw_riesgo_inminente, sw_agotadas_posibilidades_existentes, sw_comercializacion_pais,
							sw_homologo_pos, descripcion_caso_clinico,
							sw_existe_alternativa_pos)
							VALUES (".$hc_justificaciones_no_pos_hosp.", ".$this->evolucion.",
							'".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."',
							".$this->usuario_id.",
							'".$_REQUEST['duracion_tratamiento'.$pfj]."',
							'".$_REQUEST['dosis_dia'.$pfj]."',
							'".$_REQUEST['justificacion_solicitud'.$pfj]."',
							'".$_REQUEST['ventajas_medicamento'.$pfj]."',
							'".$_REQUEST['ventajas_tratamiento'.$pfj]."',
							'".$_REQUEST['precauciones'.$pfj]."',
							'".$_REQUEST['controles_evaluacion_efectividad'.$pfj]."',
							'".$_REQUEST['tiempo_respuesta_esperado'.$pfj]."',
							'".$_REQUEST['riesgo_inminente'.$pfj]."',
							'".$_REQUEST['sw_riesgo_inminente'.$pfj]."',
							'".$_REQUEST['sw_agotadas_posibilidades_existentes'.$pfj]."',
							'".$_REQUEST['sw_comercializacion_pais'.$pfj]."',
							'".$_REQUEST['sw_homologo_pos'.$pfj]."',
							'".$_REQUEST['descripcion_caso_clinico'.$pfj]."',
							'".$sw_existe_alternativa_pos."')";
		}
		else
		{
				$query=	"UPDATE hc_justificaciones_no_pos_hosp SET usuario_id_autoriza =".$this->usuario_id.",
							duracion = '".$_REQUEST['duracion_tratamiento'.$pfj]."',
							dosis_dia = 		'".$_REQUEST['dosis_dia'.$pfj]."',
							justificacion = '".$_REQUEST['justificacion_solicitud'.$pfj]."',
							ventajas_medicamento = '".$_REQUEST['ventajas_medicamento'.$pfj]."',
							ventajas_tratamiento = '".$_REQUEST['ventajas_tratamiento'.$pfj]."',
							precauciones = '".$_REQUEST['precauciones'.$pfj]."',
							controles_evaluacion_efectividad = '".$_REQUEST['controles_evaluacion_efectividad'.$pfj]."',
							tiempo_respuesta_esperado = '".$_REQUEST['tiempo_respuesta_esperado'.$pfj]."',
							riesgo_inminente = '".$_REQUEST['riesgo_inminente'.$pfj]."',
							sw_riesgo_inminente = '".$_REQUEST['sw_riesgo_inminente'.$pfj]."',
							sw_agotadas_posibilidades_existentes = '".$_REQUEST['sw_agotadas_posibilidades_existentes'.$pfj]."',
							sw_comercializacion_pais = '".$_REQUEST['sw_comercializacion_pais'.$pfj]."',
							sw_homologo_pos = '".$_REQUEST['sw_homologo_pos'.$pfj]."',
							descripcion_caso_clinico = '".$_REQUEST['descripcion_caso_clinico'.$pfj]."',
							sw_existe_alternativa_pos = '".$sw_existe_alternativa_pos."' WHERE
							hc_justificaciones_no_pos_hosp = ".$_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_hosp']."";
		}
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al insertar en hc_justificaciones_no_pos_hosp";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $this->frmError["MensajeError"]="NO HA SIDO POSIBLE GUARDAR LA JUSTIFICACION";
               $dbconn->RollbackTrans();
               return false;
          }
		else
          {
               if (!empty($_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_hosp']))
               {
                         $hc_justificaciones_no_pos_hosp =$_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_hosp'];
                         $query=	"DELETE FROM hc_justificaciones_no_pos_hosp_respuestas_pos
                                        WHERE hc_justificaciones_no_pos_hosp = ".$_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_hosp']."";
                    $resulta=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al insertar en hc_justificaciones_no_pos_hosp";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $this->frmError["MensajeError"]="NO HA SIDO POSIBLE GUARDAR LA JUSTIFICACION2";
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
     
               for ($j=1;$j<3;$j++)
               {
                    if(($_REQUEST['medicamento_pos'.$j.$pfj]) != '' OR ($_REQUEST['principio_activo_pos'.$j.$pfj] != ''))
                    {
                                   $query=	"INSERT INTO hc_justificaciones_no_pos_hosp_respuestas_pos
                                                  (hc_justificaciones_no_pos_hosp, medicamento_pos, principio_activo,
                                                  dosis_dia_pos, duracion_pos, sw_no_mejoria, sw_reaccion_secundaria,
                                                  reaccion_secundaria, sw_contraindicacion, contraindicacion, otras)
                                                  VALUES (".$hc_justificaciones_no_pos_hosp.",
                                                  '".$_REQUEST['medicamento_pos'.$j.$pfj]."',
                                                  '".$_REQUEST['principio_activo_pos'.$j.$pfj]."',
                                                  '".$_REQUEST['dosis_dia_pos'.$j.$pfj]."',
                                                  '".$_REQUEST['duracion_tratamiento_pos'.$j.$pfj]."',
                                                  '".$_REQUEST['sw_no_mejoria'.$j.$pfj]."',
                                                  '".$_REQUEST['sw_reaccion_secundaria'.$j.$pfj]."',
                                                  '".$_REQUEST['reaccion_secundaria'.$j.$pfj]."',
                                                  '".$_REQUEST['sw_contraindicacion'.$j.$pfj]."',
                                                  '".$_REQUEST['contraindicacion'.$j.$pfj]."',
                                                  '".$_REQUEST['otras'.$j.$pfj]."')";
                              $resulta=$dbconn->Execute($query);
                              if ($dbconn->ErrorNo() != 0)
                              {
                                   $this->error = "Error al insertar en hc_justificaciones_no_pos_hosp";
                                   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                   $this->frmError["MensajeError"]="NO HA SIDO POSIBLE GUARDAR LA JUSTIFICACION3";
                                   $dbconn->RollbackTrans();
                                   return false;
                              }
                    }
               }
               if (!empty($_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_hosp']))
               {
                    $query=	"DELETE FROM hc_justificaciones_no_pos_hosp_diagnostico
                                   WHERE hc_justificaciones_no_pos_hosp = ".$_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_hosp']."";
                    $resulta=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al insertar en hc_justificaciones_no_pos_hosp";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $this->frmError["MensajeError"]="NO HA SIDO POSIBLE GUARDAR LA JUSTIFICACION";
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }

               foreach ($_SESSION['DIAGNOSTICOS'.$pfj] as $k=>$v)
               {
                              $query="INSERT INTO hc_justificaciones_no_pos_hosp_diagnostico
                              (hc_justificaciones_no_pos_hosp, diagnostico_id)
                              VALUES (".$hc_justificaciones_no_pos_hosp.",'".$k."')";
                              $resulta=$dbconn->Execute($query);

                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al insertar en hc_justificaciones_no_pos_hosp_diagnostico";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LOS DIAGNOSTICOS";
                              $dbconn->RollbackTrans();
                              return false;
                         }
               }
     	}

          //CLAUDIA - OK VERIFICACION ESPECIAL PARA HOSPITALIZACION
          //SE VERIFICA QUE EL MEDICAMENTO QUE ESTABA EN ESTADO 0 CAMBIE A ESTADO 9
          //DESPUES DEL REENVIO
     
     	$query="SELECT a.codigo_producto, a.evolucion_id
                  FROM hc_medicamentos_recetados_hosp a,
	                  hc_evoluciones b, ingresos c
                  where a.codigo_producto = '".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."'
                  AND b.ingreso = c.ingreso
                  AND c.ingreso = ".$this->ingreso."
                  AND b.evolucion_id = a.evolucion_id and a.sw_estado = 0";
               $result6 = $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al consultar hc_justificaciones_no_pos_plantillas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               else
               {
                    $vector_c=$result6->GetRowAssoc($ToUpper = false);
               }
     
               if ($vector_c[codigo_producto]!='')
               {
                    $query= "UPDATE hc_medicamentos_recetados_hosp SET  sw_estado= '9'
                              WHERE codigo_producto =  '".$vector_c['codigo_producto']."'
                                        and evolucion_id = ".$vector_c['evolucion_id']."";
     
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al activar el medicamento en hc_medicamentos_recetados_hosp";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return true;
                    }
               }
	     //fin de la actualizacion
          $this->frmError["MensajeError"]="JUSTIFICACION GUARDADA SATISFACTORIAMENTE";
          $dbconn->CommitTrans();
          unset($_SESSION['SPIA'.$pfj]);
          //CLAUDIA - OK
          return true;
     }


     function Diagnosticos_de_Ingreso()
     {
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "select b.diagnostico_id, b.diagnostico_nombre from hc_diagnosticos_ingreso as a,
		 				diagnosticos as b where evolucion_id = ".$this->evolucion." AND a.tipo_diagnostico_id = b.diagnostico_id";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar los diagnosticos asignados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ 
          	$i=0;
			while (!$result->EOF)
			{
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
			}
		}
		$result->Close();
     	return $vector;
	}

     function ConsultaGeneralModificacionMedicamento($codigo_producto)
     {
		 $pfj=$this->frmPrefijo;
		 list($dbconnect) = GetDBconn();
		 $query= "select a.evolucion_id, a.codigo_producto, a.sw_paciente_no_pos, case when k.sw_pos = 1 then 'POS'
		 else 'NO POS' end as item, a.cantidad, a.dosis, a.via_administracion_id, m.nombre as via,
		 a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id, h.descripcion as producto,
		 c.descripcion as principio_activo, h.contenido_unidad_venta, l.descripcion, n.descripcion as forma,
		 n.unidad_dosificacion as unidad_dosificacion_forma, k.concentracion_forma_farmacologica,a.sw_ambulatorio,
           a.solucion_id,a.cantidad_id,k.unidad_medida_medicamento_id from hc_medicamentos_recetados_hosp as a left join
		 hc_vias_administracion as m on (a.via_administracion_id = m.via_administracion_id),
		 inv_med_cod_principios_activos as c, inventarios_productos as h, medicamentos as k, unidades as l,
		 inv_med_cod_forma_farmacologica as n where a.evolucion_id = ".$this->evolucion." and
		 k.cod_principio_activo = c.cod_principio_activo and h.codigo_producto = k.codigo_medicamento
		 and a.codigo_producto = h.codigo_producto and h.codigo_producto = a.codigo_producto and
		 h.unidad_id = l.unidad_id and k.cod_forma_farmacologica = n.cod_forma_farmacologica and
		 a.codigo_producto = '$codigo_producto' ";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar el medicamento";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ 
          	$i=0;
			while (!$result->EOF)
			{
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
			}
		}
		$result->Close();
     	return $vector;
	}


     //clzc - si
     function Consulta_Solicitud_Medicamentos()
     {
		$pfj=$this->frmPrefijo;
     	list($dbconnect) = GetDBconn();

		//tipo de usuario
		if(($this->tipo_profesional=='1') OR ($this->tipo_profesional=='2')){
			$_SESSION['PROFESIONAL'.$pfj]=1;
		}else{
			$_SESSION['PROFESIONAL'.$pfj]=3;
		}
		//fin del tipo de usuario

		//query igual que el de cexterna pero se altero uniendo profesionales para hospitalizacion
	     $query= "SELECT a.sw_estado,k.sw_uso_controlado,
		 CASE WHEN k.sw_pos = 1 THEN 'POS'
		 ELSE 'NO POS' END as item, a.codigo_producto, a.sw_paciente_no_pos, a.cantidad,
		 a.dosis, m.nombre as via, a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id,
		 h.descripcion as producto, c.descripcion as principio_activo, h.contenido_unidad_venta,
		 l.descripcion, a.evolucion_id,a.sw_ambulatorio,ter.nombre_tercero, n.fecha
		 FROM hc_medicamentos_recetados_hosp as a
		 LEFT JOIN hc_vias_administracion as m ON (a.via_administracion_id = m.via_administracion_id),
		 inv_med_cod_principios_activos as c, inventarios_productos as h, medicamentos as k,
		 unidades as l,hc_evoluciones n
		 LEFT JOIN profesionales_usuarios pusu ON (pusu.usuario_id=n.usuario_id)
		 LEFT JOIN terceros ter ON (pusu.tipo_tercero_id=ter.tipo_id_tercero AND pusu.tercero_id=ter.tercero_id)
		 WHERE n.ingreso = ".$this->ingreso."
		 AND a.evolucion_id = n.evolucion_id AND a.sw_estado = '1' and
		 k.cod_principio_activo = c.cod_principio_activo AND
		 h.codigo_producto = k.codigo_medicamento and
		 a.codigo_producto = h.codigo_producto and
		 h.codigo_producto = a.codigo_producto and h.unidad_id = l.unidad_id $concat
		 ORDER BY k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto, a.evolucion_id";
    
		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
		}
		else
		{ 
          	$i=0;
			while (!$result->EOF)
			{
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
			}
		}
     	return $vector;
	}

     //*
     function Consulta_Solicitud_Medicamentos_Posologia($codigo_producto, $tipo_posologia, $evolucion_id)
     {
     	$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query == '';
		if ($tipo_posologia == 1)
		{
				$query= "select periocidad_id, tiempo from hc_posologia_horario_op1_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
		}
		if ($tipo_posologia == 2)
		{
				$query= "select a.duracion_id, b.descripcion from hc_posologia_horario_op2_hosp as a, hc_horario as b where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto' and a.duracion_id = b.duracion_id";
		}
		if ($tipo_posologia == 3)
		{
    		$query= "select sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena from hc_posologia_horario_op3_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
		}
		if ($tipo_posologia == 4)
		{
    		$query= "select hora_especifica from hc_posologia_horario_op4_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
		}
		if ($tipo_posologia == 5)
		{
    		$query= "select frecuencia_suministro from hc_posologia_horario_op5_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
		}

		if ($query!='')
		{
               $result = $dbconnect->Execute($query);
               if ($dbconnect->ErrorNo() != 0)
               {
                    $this->error = "Error al buscar en la consulta de medicamentos recetados";
                    $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                    return false;
               }
               else
               {
                    if ($tipo_posologia != 4)
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
     	return $vector;
	}


     //clzc - si - *
     function Eliminar_Medicamento_Solicitada($codigo_producto, $opcion_posologia)
     {
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		$query= '';
		if ($opcion_posologia == 1)
		{
						$query ="DELETE FROM hc_posologia_horario_op1_hosp
						WHERE codigo_producto = '$codigo_producto' AND evolucion_id = ".$this->evolucion."";
		}
		if ($opcion_posologia == 2)
		{
						$query ="DELETE FROM hc_posologia_horario_op2_hosp
						WHERE codigo_producto = '$codigo_producto' AND evolucion_id = ".$this->evolucion."";
		}
		if ($opcion_posologia == 3)
		{
						$query ="DELETE FROM hc_posologia_horario_op3_hosp
						WHERE codigo_producto = '$codigo_producto' AND evolucion_id = ".$this->evolucion."";
		}
		if ($opcion_posologia == 4)
		{
						$query ="DELETE FROM hc_posologia_horario_op4_hosp
						WHERE codigo_producto = '$codigo_producto' AND evolucion_id = ".$this->evolucion."";
		}
		if ($opcion_posologia == 5)
		{
						$query ="DELETE FROM hc_posologia_horario_op5_hosp
						WHERE codigo_producto = '$codigo_producto' AND evolucion_id = ".$this->evolucion."";
		}

		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR";
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
               $query="DELETE FROM hc_medicamentos_recetados_hosp
                         WHERE codigo_producto = '".$codigo_producto."' AND evolucion_id = ".$this->evolucion."";
               $resulta=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR";
                    $dbconn->RollbackTrans();
                    return false;
               }
		}
          $dbconn->CommitTrans();
          $this->frmError["MensajeError"]="MEDICAMENTO ELIMINADO.";
          return true;
	}	


     //*
     function Modificacion_Justificacion_Medicamentos_No_Pos($hc_justificaciones_no_pos_hosp)
     {
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
     	$dbconn->BeginTrans();

		if ($_REQUEST['duracion_tratamiento'.$pfj] == '' OR (empty($_SESSION['DIAGNOSTICOSM'.$pfj])))
          {
               if($_REQUEST['duracion_tratamiento'.$pfj] == '')
               {
                         $this->frmError["duracion_tratamiento"]=1;
               }

               if((empty($_SESSION['DIAGNOSTICOSM'.$pfj])))
               {
                         $this->frmError["diagnostico_id"] = 1;
               }

               $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
               return false;
          }

		//ojo este dato no estan llegando
		$sw_existe_alternativa_pos = 1;
	   	$query=	"UPDATE hc_justificaciones_no_pos_hosp SET
                         usuario_id_autoriza = ".$this->usuario_id.",
                         duracion = '".$_REQUEST['duracion_tratamiento'.$pfj]."',
                         dosis_dia = '".$_REQUEST['dosis_dia'.$pfj]."',
                         justificacion = '".$_REQUEST['justificacion_solicitud'.$pfj]."',
                         ventajas_medicamento = '".$_REQUEST['ventajas_medicamento'.$pfj]."',
                         ventajas_tratamiento = '".$_REQUEST['ventajas_tratamiento'.$pfj]."',
                         precauciones = '".$_REQUEST['precauciones'.$pfj]."',
                         controles_evaluacion_efectividad = '".$_REQUEST['controles_evaluacion_efectividad'.$pfj]."',
                         tiempo_respuesta_esperado = '".$_REQUEST['tiempo_respuesta_esperado'.$pfj]."',
                         riesgo_inminente = '".$_REQUEST['riesgo_inminente'.$pfj]."',
                         sw_riesgo_inminente = '".$_REQUEST['sw_riesgo_inminente'.$pfj]."',
                         sw_agotadas_posibilidades_existentes = '".$_REQUEST['sw_agotadas_posibilidades_existentes'.$pfj]."',
                         sw_comercializacion_pais = '".$_REQUEST['sw_comercializacion_pais'.$pfj]."',
                         sw_homologo_pos = '".$_REQUEST['sw_homologo_pos'.$pfj]."',
                         descripcion_caso_clinico = '".$_REQUEST['descripcion_caso_clinico'.$pfj]."',
                         sw_existe_alternativa_pos = '".$sw_existe_alternativa_pos."' WHERE
                         hc_justificaciones_no_pos_hosp = ".$hc_justificaciones_no_pos_hosp."";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al actualizar en hc_justificaciones_no_pos_hosp";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $this->frmError["MensajeError"]="NO HA SIDO POSIBLE MODIFICAR LA JUSTIFICACION";
               $dbconn->RollbackTrans();
               return false;
          }
		else
          {
                    $query = "DELETE FROM hc_justificaciones_no_pos_hosp_respuestas_pos
                                        WHERE hc_justificaciones_no_pos_hosp = ".$hc_justificaciones_no_pos_hosp."";
               $resulta=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al borrar los medicamentos no pos previos en hc_justificaciones_no_pos_hosp_respuestas_pos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
               else
               {
                    for ($j=1;$j<3;$j++)
                    {
                              if(($_REQUEST['medicamento_pos'.$j.$pfj]) != '' OR ($_REQUEST['principio_activo_pos'.$j.$pfj] != ''))
                              {
                                        $query=	"INSERT INTO hc_justificaciones_no_pos_hosp_respuestas_pos
                                                       (hc_justificaciones_no_pos_hosp, medicamento_pos, principio_activo,
                                                       dosis_dia_pos, duracion_pos, sw_no_mejoria, sw_reaccion_secundaria,
                                                       reaccion_secundaria, sw_contraindicacion, contraindicacion, otras)
                                                       VALUES (".$hc_justificaciones_no_pos_hosp.",
                                                       '".$_REQUEST['medicamento_pos'.$j.$pfj]."',
                                                       '".$_REQUEST['principio_activo_pos'.$j.$pfj]."',
                                                       '".$_REQUEST['dosis_dia_pos'.$j.$pfj]."',
                                                       '".$_REQUEST['duracion_tratamiento_pos'.$j.$pfj]."',
                                                       '".$_REQUEST['sw_no_mejoria'.$j.$pfj]."',
                                                       '".$_REQUEST['sw_reaccion_secundaria'.$j.$pfj]."',
                                                       '".$_REQUEST['reaccion_secundaria'.$j.$pfj]."',
                                                       '".$_REQUEST['sw_contraindicacion'.$j.$pfj]."',
                                                       '".$_REQUEST['contraindicacion'.$j.$pfj]."',
                                                       '".$_REQUEST['otras'.$j.$pfj]."')";
                                   $resulta=$dbconn->Execute($query);
                                   if ($dbconn->ErrorNo() != 0)
                                   {
                                        $this->error = "Error al insertar en hc_justificaciones_no_pos_hosp_respuestas_pos";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $this->frmError["MensajeError"]="NO HA SIDO POSIBLE GUARDAR ALTERNATIVAS POS PREVIOS";
                                        $dbconn->RollbackTrans();
                                        return false;
                                   }
                              }
                    }
               }
                    $query = "DELETE FROM hc_justificaciones_no_pos_hosp_diagnostico
                                        WHERE hc_justificaciones_no_pos_hosp = ".$hc_justificaciones_no_pos_hosp."";
               $resulta=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al borrar los diagnosticos de hc_justificaciones_no_pos_hosp_diagnostico";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
               else
               {
                    foreach ($_SESSION['DIAGNOSTICOSM'.$pfj] as $k=>$v)
                    {
                              $query="INSERT INTO hc_justificaciones_no_pos_hosp_diagnostico
                                             (hc_justificaciones_no_pos_hosp, diagnostico_id)
                                             VALUES (".$hc_justificaciones_no_pos_hosp.",'".$k."')";
                         $resulta=$dbconn->Execute($query);

                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al actualizar en hc_justificaciones_no_pos_hosp_diagnostico";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $this->frmError["MensajeError"]="ERROR EN LA ACTUALIZACION DE LOS DIAGNOSTICOS";
                              $dbconn->RollbackTrans();
                              return false;
                         }
                    }
               }
          }
          $this->frmError["MensajeError"]="MODIFICACIONES DE LA JUSTIFICACION GUARDADAS SATISFACTORIAMENTE";
          $dbconn->CommitTrans();
          return true;
	}



     //clzc - si - *
     function Modificar_Medicamento_Solicitado($codigo_producto, $opcion_posol)
     {
		//inserta un 1 si es pos o si es no pos y selecciono el check
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();

		if ($_REQUEST['cantidad'.$pfj] == '' OR $_REQUEST['via_administracion'.$pfj] == -1 OR
               $_REQUEST['unidad_dosis'.$pfj] == -1 OR empty($_REQUEST['unidad_dosis'.$pfj]))
		{
			if($_REQUEST['via_administracion'.$pfj] == '-1')
			{
               	$this->frmError["via_administracion"]=1;
			}
               if($_REQUEST['cantidad'.$pfj] == '')
			{
		     	$this->frmError["cantidad"]=1;
          	}

			if(($_REQUEST['unidad_dosis'.$pfj] == '-1') OR (empty($_REQUEST['unidad_dosis'.$pfj])))
          	{
               	$this->frmError["unidad_dosis"]=1;
               }
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
			return false;
		}

		if($_REQUEST['dosis'.$pfj] == '')
		{
               $_REQUEST['dosis'.$pfj]='NULL';
		}
		else
		{
               if (is_numeric($_REQUEST['dosis'.$pfj])==0)
               {
                    $this->frmError["dosis"]=1;
                    $this->frmError["MensajeError"]="DOSIS INVALIDA, DIGITE SOLO NUMEROS.";
                    return false;
               }
		}

		//controlando la insercion de la via
          $via = $_REQUEST['via_administracion'.$pfj];
          if($via == '')
          {
               $via = 'NULL';
     	}
          else
          {
        		$via ="'$via'";
          }
          //fin del control

		//OBLIGATORIEDAD DE LA FRECUENCIA
          if($_REQUEST['opcion'.$pfj]=='')
          {
               $this->frmError["frecuencia"]=1;
               $this->frmError["MensajeError"]="SELECCIONE UNA OPCION DE FRECUENCIA PARA LA FORMULACION.";
               return false;
          }
          //fin de obligatoriedad

		if ($_REQUEST['opcion'.$pfj]=='')
		{
			$_REQUEST['opcion'.$pfj] = 0;
		}
		  
          if($_REQUEST['sw_ambulatorio'.$pfj]){$sw_ambulatorio=1;}else{$sw_ambulatorio=0;}
      	if($_REQUEST['solucion'.$pfj]!=-1){$solucion="'".$_REQUEST['solucion'.$pfj]."'";}else{$solucion='NULL';}
      	if($_REQUEST['solucionUnidad'.$pfj]!=-1){$solucionUnidad="'".$_REQUEST['solucionUnidad'.$pfj]."'";}else{$solucionUnidad='NULL';}

	 	 $query="UPDATE hc_medicamentos_recetados_hosp SET
                         cantidad = ".$_REQUEST['cantidad'.$pfj].",
                         observacion = '".$_REQUEST['observacion'.$pfj]."',
                         sw_paciente_no_pos = '".$_REQUEST['no_pos_paciente'.$pfj]."',
                         via_administracion_id = ".$via.",
                         dosis = ".$_REQUEST['dosis'.$pfj].",
                         unidad_dosificacion = '".$_REQUEST['unidad_dosis'.$pfj]."',
                         tipo_opcion_posologia_id = ".$_REQUEST['opcion'.$pfj].",
                         sw_ambulatorio = '".$sw_ambulatorio."',
                         solucion_id=$solucion,
                         cantidad_id=$solucionUnidad
                    WHERE
                         codigo_producto = '$codigo_producto' AND evolucion_id = ".$this->evolucion."";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
          {
                    $this->error = "Error al actualizar en hc_medicamentos_recetados_hosp";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->frmError["MensajeError"]="NO SE PUDO ALMACENAR LAS MODIFICACIONES.";
                    $dbconn->RollbackTrans();
                         //caso especial que retorne true porque si ya existe elmedicamento no debe volver
                         //a la forma de llenado si no a la forma principal
                    return true;
          }
		else
          {
               $query= '';
               if ($opcion_posol == 1)
               {
                                   $query_posologia ="DELETE FROM hc_posologia_horario_op1_hosp
                                   WHERE codigo_producto = '".$codigo_producto."' AND evolucion_id = ".$this->evolucion."";
               }
               if ($opcion_posol == 2)
               {
                                   $query_posologia ="DELETE FROM hc_posologia_horario_op2_hosp
                                   WHERE codigo_producto = '".$codigo_producto."' AND evolucion_id = ".$this->evolucion."";
               }
               if ($opcion_posol == 3)
               {
                                   $query_posologia ="DELETE FROM hc_posologia_horario_op3_hosp
                                   WHERE codigo_producto = '".$codigo_producto."' AND evolucion_id = ".$this->evolucion."";
               }
               if ($opcion_posol == 4)
               {
                                   $query_posologia ="DELETE FROM hc_posologia_horario_op4_hosp
                                   WHERE codigo_producto = '".$codigo_producto."' AND evolucion_id = ".$this->evolucion."";
               }
               if ($opcion_posol == 5)
               {
                                   $query_posologia ="DELETE FROM hc_posologia_horario_op5_hosp
                                   WHERE codigo_producto = '".$codigo_producto."' AND evolucion_id = ".$this->evolucion."";
               }

               if ($query_posologia== '')
               {
                    $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR";
                    return false;
               }
               else
               {
                    /*aqui va lo que saque a un lado*/
                    if ($_REQUEST['opcion'.$pfj] == '1')
                    {
                         if (($_REQUEST['periocidad'.$pfj]=='-1') OR ($_REQUEST['tiempo'.$pfj]=='-1'))
                         {
                              $this->frmError["opcion1"]=1;
                              $this->frmError["MensajeError"]="PARA OPCION 1 DE FRECUENCIA DEBE SELECIONAR UNA OPCION.";
                              return false;
                         }
                         else
                         {
                              $resulta=$dbconn->Execute($query_posologia);
                              if ($dbconn->ErrorNo() != 0)
                              {
                                   $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR";
                                   $dbconn->RollbackTrans();
                                   return false;
                              }
                              else
                              {
                                   $query="INSERT INTO hc_posologia_horario_op1_hosp
                                                       (codigo_producto, evolucion_id, periocidad_id, tiempo)
                                                       VALUES ('".$codigo_producto."', ".$this->evolucion.",
                                                       ".$_REQUEST['periocidad'.$pfj].", '".$_REQUEST['tiempo'.$pfj]."')";
                                   $resulta=$dbconn->Execute($query);
                                   if ($dbconn->ErrorNo() != 0)
                                   {
                                        $this->error = "Error al insertar en hc_posologia_horario_op1_hosp";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 1.z";
                                        $dbconn->RollbackTrans();
                                        //caso especial que retorne true porque si ya existe elmedicamento no debe volver
                                        //a la forma de llenado si no a la forma principal
                                        return true;
                                   }
                              }
                         }
                    }
                    if ($_REQUEST['opcion'.$pfj] == '2')
                    {
                         if ($_REQUEST['duracion'.$pfj]=='-1')
                         {
                              $this->frmError["opcion2"]=1;
                              $this->frmError["MensajeError"]="PARA OPCION 2 DE FRECUENCIA DEBE SELECIONAR UNA OPCION.";
                              return false;
                         }
                         else
                         {
                              $resulta=$dbconn->Execute($query_posologia);
                              if ($dbconn->ErrorNo() != 0)
                              {
                                   $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR";
                                   $dbconn->RollbackTrans();
                                   return false;
                              }
                              else
                              {
                                   $query="INSERT INTO hc_posologia_horario_op2_hosp
                                                       (codigo_producto, evolucion_id, duracion_id)
                                                       VALUES ('".$codigo_producto."', ".$this->evolucion.",
                                                       '".$_REQUEST['duracion'.$pfj]."')";
                                                       $resulta=$dbconn->Execute($query);

                                   if ($dbconn->ErrorNo() != 0)
                                   {
                                        $this->error = "Error al insertar en hc_posologia_horario_op2_hosp";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 2.";
                                        $dbconn->RollbackTrans();
                                        //caso especial que retorne true porque si ya existe elmedicamento no debe volver
                                        //a la forma de llenado si no a la forma principal
                                        return true;
                                   }
                              }
                         }
                    }

                    if ($_REQUEST['opcion'.$pfj] == '3')
                    {
                         if (empty($_REQUEST['momento'.$pfj]))
                         {
                              $this->frmError["opcion3"]=1;
                              $this->frmError["MensajeError"]="PARA OPCION 3 DE FRECUENCIA DEBE DILIGENCIAR LOS CAMPOS.";
                              return false;
                         }
                         else
                         {
                              if ((!empty($_REQUEST['desayuno'.$pfj])) OR	(!empty($_REQUEST['almuerzo'.$pfj])) OR (!empty($_REQUEST['cena'.$pfj])))
                              {
                                   $resulta=$dbconn->Execute($query_posologia);
                                   if ($dbconn->ErrorNo() != 0)
                                   {
                                        $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR";
                                        $dbconn->RollbackTrans();
                                        return false;
                                   }
                                   else
                                   {
                                        $query="INSERT INTO hc_posologia_horario_op3_hosp
                                                       (codigo_producto, evolucion_id, sw_estado_momento,
                                                       sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena)
                                                       VALUES ('".$codigo_producto."', ".$this->evolucion.",
                                                       '".$_REQUEST['momento'.$pfj]."', '".$_REQUEST['desayuno'.$pfj]."',
                                                       '".$_REQUEST['almuerzo'.$pfj]."', '".$_REQUEST['cena'.$pfj]."')";
                                                       $resulta=$dbconn->Execute($query);

                                        if ($dbconn->ErrorNo() != 0)
                                        {
                                             $this->error = "Error al insertar en hc_posologia_horario_op3_hosp";
                                             $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                             $this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 3.";
                                             $dbconn->RollbackTrans();
                                             //caso especial que retorne true porque si ya existe elmedicamento no debe volver
                                             //a la forma de llenado si no a la forma principal
                                             return true;
                                        }
                                   }
                              }
                              else
                              {
                                   $this->frmError["MensajeError"]="PARA OPCION 3 DE FRECUENCIA DEBE ESCOGER UNA COMIDA.";
                                   return false;
                              }
                         }
                    }
                    if ($_REQUEST['opcion'.$pfj] == '4')
                    {
                         if (empty($_REQUEST['opH'.$pfj]))
                         {
                              $this->frmError["opcion4"]=1;
                              $this->frmError["MensajeError"]="PARA OPCION 4 DE FRECUENCIA DEBE SELECCIONAR UNA HORA ESPECIFICA.";
                              return false;
                         }
                         else
                         {
                              $resulta=$dbconn->Execute($query_posologia);
                              if ($dbconn->ErrorNo() != 0)
                              {
                                   $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR";
                                   $dbconn->RollbackTrans();
                                   return false;
                              }
                              else
                              {
                                   foreach($_REQUEST['opH'.$pfj] as $index=>$codigo)
                                   {
                                        $arreglo=explode(",",$codigo);
                                        $query="INSERT INTO hc_posologia_horario_op4_hosp
                                                            (codigo_producto, evolucion_id, hora_especifica)
                                                            VALUES ('".$codigo_producto."',
                                                            ".$this->evolucion.", '".$arreglo[0]."')";
                                        $resulta=$dbconn->Execute($query);

                                        if ($dbconn->ErrorNo() != 0)
                                             {
                                                       $this->error = "Error al insertar en hc_posologia_horario_op4_hosp";
                                                       $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                       $this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA HORA ESPECIFICA.";
                                                       $dbconn->RollbackTrans();
                                                       //caso especial que retorne true porque si ya existe elmedicamento no debe volver
                                                       //a la forma de llenado si no a la forma principal
                                                       return true;
                                             }
                                        }
                                   }
                              }
                         }
                    if ($_REQUEST['opcion'.$pfj] == '5')
                    {
                         if (empty($_REQUEST['frecuencia_suministro'.$pfj]))
                         {
                              $this->frmError["opcion5"]=1;
                              $this->frmError["MensajeError"]="PARA OPCION 5 DE FRECUENCIA DEBE ESCRIBIR EN LA FRECUENCIA DE SUMINISTRO.";
                              return false;
                         }
                         else
                         {
                              $resulta=$dbconn->Execute($query_posologia);
                              if ($dbconn->ErrorNo() != 0)
                              {
                                   $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR";
                                   $dbconn->RollbackTrans();
                                   return false;
                              }
                              else
                              {
                                   $query="INSERT INTO hc_posologia_horario_op5_hosp
                                                  (codigo_producto, evolucion_id, frecuencia_suministro)
                                                  VALUES ('".$codigo_producto."', ".$this->evolucion.",
                                                  '".$_REQUEST['frecuencia_suministro'.$pfj]."')";
                                   $resulta=$dbconn->Execute($query);

                                   if ($dbconn->ErrorNo() != 0)
                                   {
                                        $this->error = "Error al insertar en hc_posologia_horario_op5_hosp";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 5.";
                                        $dbconn->RollbackTrans();
                                        //caso especial que retorne true porque si ya existe elmedicamento no debe volver
                                        //a la forma de llenado si no a la forma principal
                                        return true;
                                   }
                              }
                         }
                    }
               }
          }
          $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
          $dbconn->CommitTrans();
          return true;
	}

     function Unidades_Dosificacion()
     {
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "select unidad_dosificacion from hc_unidades_dosificacion";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar los diagnosticos asignados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ 
          	$i=0;
			while (!$result->EOF)
			{
				$vector[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
			}
		}
		$result->Close();
     	return $vector;
	}


     //cor - clzc - spqx - *
     function Medicamentos_Frecuentes_Diagnostico()
	{
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
    		if ($this->bodega === '')
          {
               $query = "select distinct case when k.sw_pos = 1 then 'POS'
               else 'NO POS' end as item,a.codigo_producto, h.descripcion as producto,
               c.descripcion as principio_activo, d.descripcion as forma
               FROM medicamentos_diagnosticos_frecuentes as a, hc_diagnosticos_ingreso as b,
               medicamentos as k, inventarios_productos as h, inv_med_cod_principios_activos
               as c, inv_med_cod_forma_farmacologica as d where b.evolucion_id = '".$this->evolucion."'
               and b.tipo_diagnostico_id = a.diagnostico_id
               AND a.codigo_producto = h.codigo_producto
               and h.codigo_producto = k.codigo_medicamento
               AND k.cod_principio_activo = c.cod_principio_activo
               AND k.cod_forma_farmacologica = d.cod_forma_farmacologica AND h.estado = '1'
	          order by a.codigo_producto";
          }
     	else
          {
               $query = "
                         select distinct case when k.sw_pos = 1 then 'POS'
                         else 'NO POS' end as item, a.codigo_producto, h.descripcion as producto,
                         c.descripcion as principio_activo, d.descripcion as forma,
                         f.existencia
                         FROM medicamentos_diagnosticos_frecuentes as a, hc_diagnosticos_ingreso as b,
                         inventarios_productos as h left join hc_bodegas_consultas as e on(e.bodega_unico='".$this->bodega."')
                         left join existencias_bodegas as f
                         on(e.empresa_id=f.empresa_id and e.centro_utilidad=f.centro_utilidad
                         and						e.bodega=f.bodega
                         and h.codigo_producto=f.codigo_producto), medicamentos as k,
                         inv_med_cod_principios_activos as c, inv_med_cod_forma_farmacologica as d
                         where b.evolucion_id = '".$this->evolucion."'
                         and b.tipo_diagnostico_id = a.diagnostico_id
			          AND a.codigo_producto = h.codigo_producto
					and h.codigo_producto = k.codigo_medicamento
					AND k.cod_principio_activo = c.cod_principio_activo
                         AND k.cod_forma_farmacologica = d.cod_forma_farmacologica AND h.estado = '1'
                         order by a.codigo_producto";
          }
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la tabla qx_tipo_equipo_fijo";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
          	$i=0;
			while (!$result->EOF)
			{
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
			}
		}
		$result->Close();
     	return $vector;
	}

	//DARLING
	/**
	* Separa la fecha del formato timestamp
	* @access private
	* @return string
	* @param date fecha
	*/
     function FechaStamp($fecha)
     {
          if($fecha){
               $fech = strtok ($fecha,"-");
               for($l=0;$l<3;$l++)
               {
                    $date[$l]=$fech;
                    $fech = strtok ("-");
               }
               return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
          }
	}	


	/**
	* Separa la hora del formato timestamp
	* @access private
	* @return string
	* @param date hora
	*/
	function HoraStamp($hora)
	{
          $hor = strtok ($hora," ");
          for($l=0;$l<4;$l++)
          {
               $time[$l]=$hor;
               $hor = strtok (":");
          }

          $x = explode (".",$time[3]);
          return  $time[1].":".$time[2].":".$x[0];
	}


     //cor - clzc - spqx - *
     function tipo_via_administracion($codigo_producto)
     {
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "select b.via_administracion_id, b.nombre,c.tipo_via_id
		 FROM inv_medicamentos_vias_administracion as a, hc_vias_administracion as b,hc_vias_administracion_tipos c
		 WHERE a.codigo_medicamento = '".$codigo_producto."' and
		 a.via_administracion_id = b.via_administracion_id  AND
           b.tipo_via_id=c.tipo_via_id
           order by b.via_administracion_id";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la tabla hc_vias_administracion";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ 
          	$i=0;
			while (!$result->EOF)
			{
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
			}
		}
     	$result->Close();
     	return $vector;
	}


     //cor - clzc - ptce - *
     function GetunidadesViaAdministracion($via_administracion)
     {
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "SELECT unidad_dosificacion FROM
		hc_unidades_dosificacion_vias_administracion
		WHERE via_administracion_id = '$via_administracion'";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en las unidades de dosificacion";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ 
          	$i=0;
			while (!$result->EOF)
			{
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
			}
		}
		$result->Close();
     	return $vector;
	}


     //cor - clzc - spqx - *
     function Cargar_Periocidad()
     {
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "select periocidad_id from hc_periocidad order by periocidad_indice_orden";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la tabla periocidad_id";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ 
          	$i=0;
			while (!$result->EOF)
			{
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
			}
		}
		$result->Close();
     	return $vector;
	}


     //cor - clzc - spqx - *
     function horario()
     {
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
          $query= "select duracion_id, descripcion from hc_horario order by duracion_id";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la tabla hc_horarion";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ 
          	$i=0;
			while (!$result->EOF)
			{
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
			}
		}
		$result->Close();
     	return $vector;
	}



     //cor - clzc - spqx - *
     function Consulta_Datos_Justificacion($codigo_producto, $evolucion)
     {
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "select a.hc_justificaciones_no_pos_hosp, a.evolucion_id, n.descripcion as forma,
		k.concentracion_forma_farmacologica, k.unidad_medida_medicamento_id, a.codigo_producto,
		a.usuario_id_autoriza, a.duracion, a.dosis_dia, a.justificacion, a.ventajas_medicamento,
		a.ventajas_tratamiento, a.precauciones, a.controles_evaluacion_efectividad,
		a.tiempo_respuesta_esperado, a.riesgo_inminente, a.sw_riesgo_inminente,
		a.sw_agotadas_posibilidades_existentes, a.sw_comercializacion_pais, a.sw_homologo_pos,
		a.descripcion_caso_clinico, a.sw_existe_alternativa_pos from hc_justificaciones_no_pos_hosp as a,
		medicamentos as k, inv_med_cod_forma_farmacologica as n where
		a.codigo_producto = '".$codigo_producto."' and a.evolucion_id = ".$evolucion." and
		a.codigo_producto = k.codigo_medicamento and k.cod_forma_farmacologica = n.cod_forma_farmacologica";
		/*echo $query= "select a.hc_justificaciones_no_pos_hosp, a.evolucion_id,
		a.codigo_producto, a.usuario_id_autoriza, a.duracion, a.dosis_dia,
		a.justificacion, a.ventajas_medicamento, a.ventajas_tratamiento,
		a.precauciones, a.controles_evaluacion_efectividad,
		a.tiempo_respuesta_esperado, a.riesgo_inminente, a.sw_riesgo_inminente,
		a.sw_agotadas_posibilidades_existentes, a.sw_comercializacion_pais,
		a.sw_homologo_pos, a.descripcion_caso_clinico, a.sw_existe_alternativa_pos
		from hc_justificaciones_no_pos_hosp as a
 		where (a.codigo_producto = '".$codigo_producto."' and
	 	a.evolucion_id = ".$this->evolucion.")";*/

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar la justificacion";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ 
          	$i=0;
			while (!$result->EOF)
			{
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
			}
		}
		$result->Close();
     	return $vector;
	}


     function Consulta_Diagnosticos_Justificacion($hc_justificaciones_no_pos_hosp)
     {
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();

		$query= "select a.hc_justificaciones_no_pos_hosp, a.diagnostico_id,
		b.diagnostico_nombre from hc_justificaciones_no_pos_hosp_diagnostico as a,
		diagnosticos as b where a.diagnostico_id = b.diagnostico_id and
		a.hc_justificaciones_no_pos_hosp = ".$hc_justificaciones_no_pos_hosp."";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_hosp_diagnostico";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ 
          	$i=0;
			while (!$result->EOF)
			{
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
			}
		}
		if ($_SESSION['SPIA'.$pfj] != 1)
		{
			for($j=0;$j<sizeof($vector);$j++)
			{
				$_SESSION['DIAGNOSTICOSM'.$pfj][$vector[$j][diagnostico_id]]= $vector[$j][diagnostico_nombre];
			}
		}
		$result->Close();
		return $vector;
	}


     function Consulta_Alternativas_Pos($hc_justificaciones_no_pos_hosp)
     {
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
          $query= "select a.alternativa_pos_id, a.medicamento_pos,
		a.principio_activo, a.dosis_dia_pos, a.duracion_pos,
		a.sw_no_mejoria, a.sw_reaccion_secundaria, a.reaccion_secundaria,
		a.sw_contraindicacion, a.contraindicacion,
		a.otras, a.hc_justificaciones_no_pos_hosp
		from hc_justificaciones_no_pos_hosp_respuestas_pos as a
 		where (a.hc_justificaciones_no_pos_hosp = ".$hc_justificaciones_no_pos_hosp.")";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_hosp_respuestas_pos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ 
          	$i=0;
			while (!$result->EOF)
			{
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
			}
		}
     	$result->Close();
	  	return $vector;
	}


     //*
     function Consulta_Plantillas_Justificacion($codigo_medicamento)
     {
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
          $query= "select codigo_medicamento, justificacion, ventajas_medicamento,
			ventajas_tratamiento, precauciones, controles_evaluacion_efectividad,
			tiempo_respuesta_esperado, riesgo_inminente, sw_riesgo_inminente,
			sw_agotadas_posibilidades_existentes, sw_comercializacion_pais,
			sw_homologo_pos from hc_justificaciones_no_pos_plantillas
			where codigo_medicamento = '".$codigo_medicamento."'";
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al consultar hc_justificaciones_no_pos_plantillas";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
          	$i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
		$result->Close();
     	return $vector;
	}


     //*
     function Consulta_Justificacion_Almacenada($codigo_medicamento)
     {
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
          $query= "select
		hc_justificaciones_no_pos_hosp, evolucion_id, codigo_producto, usuario_id_autoriza,
		duracion, dosis_dia, justificacion, ventajas_medicamento, ventajas_tratamiento,
		precauciones, controles_evaluacion_efectividad, tiempo_respuesta_esperado,
		riesgo_inminente, sw_riesgo_inminente, sw_agotadas_posibilidades_existentes,
		sw_comercializacion_pais, sw_homologo_pos, descripcion_caso_clinico,
		sw_existe_alternativa_pos from hc_justificaciones_no_pos_hosp
		where codigo_producto = '".$codigo_medicamento."' and evolucion_id = ".$this->evolucion."";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_plantillas";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ 
          	$i=0;
			while (!$result->EOF)
			{
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
			}
		}
		$result->Close();
     	return $vector;
	}


     //*
     //como el codigo del producto en inventario_productos es unico el resultado del query es un solo item
     function Unidad_Venta($codigo_producto)
     {
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();

          $query="select a.codigo_producto, a.contenido_unidad_venta, b.descripcion from
               inventarios_productos as a, unidades as b where a.codigo_producto = '".$codigo_producto."'
               and a.unidad_id = b.unidad_id";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar la tabla de unidades";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		$a=$result->GetRowAssoc($ToUpper = false);
		$result->Close();
		return $a;
	}


     //clzc-ptce
     function Verificacion_Existe_Medicamento($codigo_producto)
     {
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();

		//QUERY DISEÑADO ESPECIAL PARA MEDICAMENTOS EN HOSPITALIZACION
          $query="SELECT a.codigo_producto, a.evolucion_id, a.sw_estado FROM hc_medicamentos_recetados_hosp a,
               hc_evoluciones b, ingresos c
               where a.codigo_producto = '".$codigo_producto."'
               AND b.ingreso = c.ingreso
               AND c.ingreso = ".$this->ingreso."
               AND b.evolucion_id = a.evolucion_id";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar la tabla hc_medicamentos_recetados_hosp";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
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
		$result->Close();
		return $vector;
	}

     //FUNCIONES PROPIAS DE HOSPITALIZACION DE CLAUDIA
     function Consulta_Solicitud_Medicamentos_Historial($codigo_producto)
     {
          $pfj=$this->frmPrefijo;
          list($dbconnect) = GetDBconn();
          $query= "select o.nombre, n.fecha, o.tipo_profesional,  a.sw_estado, k.sw_uso_controlado,
          case when k.sw_pos = 1 then 'POS' else 'NO POS' end as item,
          a.codigo_producto, a.sw_paciente_no_pos, a.cantidad, a.dosis, m.nombre as via,
          a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id, h.descripcion
          as producto, c.descripcion as principio_activo, h.contenido_unidad_venta,
          l.descripcion, a.evolucion_id from hc_medicamentos_recetados_hosp as a left join
          hc_vias_administracion as m on (a.via_administracion_id = m.via_administracion_id),
          inv_med_cod_principios_activos as c, inventarios_productos as h, medicamentos as k,
          unidades as l,

          hc_evoluciones n, profesionales o, profesionales_usuarios p

          where n.ingreso = ".$this->ingreso."
          and a.evolucion_id = n.evolucion_id and

          n.usuario_id = p.usuario_id and
          p.tipo_tercero_id = o.tipo_id_tercero and
          p.tercero_id = o.tercero_id 
          and (a.sw_estado = '9' or a.sw_estado = '1')
          and a.codigo_producto = '".$codigo_producto."' and

          k.cod_principio_activo = c.cod_principio_activo and
          h.codigo_producto = k.codigo_medicamento and
          a.codigo_producto = h.codigo_producto and
          h.codigo_producto = a.codigo_producto and h.unidad_id = l.unidad_id
          order by a.evolucion_id, k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
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
		$result->Close();
          return $vector;
	}

     //clzc
     function Finalizar_Medicamento()
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          //INSERTANDO NOTA PARA LA FINALIZACION
     	$query="INSERT INTO hc_notas_suministro_medicamentos
                    (codigo_producto, evolucion_id, observacion, tipo_observacion,
                    usuario_id_nota, fecha_registro_nota)
                    VALUES
                    (
                         '".$_REQUEST['codigo_producto'.$pfj]."',
                         ".$_REQUEST['evolucion_id'.$pfj].",
                         'Finalizacion del Medicamento',
                         '3', ".UserGetUID().", now())";
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al insertar en hc_justificaciones_no_pos_hosp_respuestas_pos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $this->frmError["MensajeError"]="NO HA SIDO POSIBLE GENERAR NOTA";
               $dbconn->RollbackTrans();
               return false;
          }
          //FIN DE LA INSERCION
          else
          {
               $query= "UPDATE hc_medicamentos_recetados_hosp SET  sw_estado= '0'
                                   WHERE codigo_producto =  '".$_REQUEST['codigo_producto'.$pfj]."'
                                   and evolucion_id = '".$_REQUEST['evolucion_id'.$pfj]."'";

               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al actualizar la observacion en hc_os_solicitudes_apoyod";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
          $dbconn->CommitTrans();
          return true;
	}

     //clzc
     function Activar_Medicamento_Medico()
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $query= "UPDATE hc_medicamentos_recetados_hosp SET  sw_estado= '1'
                   WHERE codigo_producto =  '".$_REQUEST['codigo_producto'.$pfj]."'
                   AND evolucion_id = '".$_REQUEST['evolucion_id'.$pfj]."'";

          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al activar el medicamento en hc_medicamentos_recetados_hosp";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          return true;
	}

     //clzc
     function Insertar_Suspension_Medicamento()
     {
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
		if ($_REQUEST['nota_suspension_medicamento'.$pfj]=='')
		{
			$this->frmError["nota"]=1;
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
			return false;
		}

		$query= "UPDATE hc_medicamentos_recetados_hosp SET
                         sw_estado = '".$_REQUEST['tipo_nota'.$pfj]."'
	               WHERE codigo_producto = '".$_REQUEST['codigo_producto'.$pfj]."'
                         and evolucion_id = ".$_REQUEST['evolucion_id'.$pfj]."";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al actualizar la observacion en hc_cargos_directos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
               $query=	 "INSERT INTO hc_notas_suministro_medicamentos
                                        (codigo_producto, evolucion_id, observacion, tipo_observacion,
                                        usuario_id_nota, fecha_registro_nota)
                                   VALUES
                                   (
                                   '".$_REQUEST['codigo_producto'.$pfj]."',
                                   ".$_REQUEST['evolucion_id'.$pfj].",
                                   '".$_REQUEST['nota_suspension_medicamento'.$pfj]."',
                                   '".$_REQUEST['tipo_nota'.$pfj]."', ".UserGetUID().", now())";
               $resulta=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al insertar en hc_justificaciones_no_pos_hosp_respuestas_pos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->frmError["MensajeError"]="NO HA SIDO POSIBLE GENERAR NOTA";
                    $dbconn->RollbackTrans();
                    return false;
               }
		}
		$_REQUEST = '';
		$dbconn->CommitTrans();
		return true;
	}


     function Consultar_Notas_Suministro($codigo_producto)
     {
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "select g.nombre as nombre_usuario, b.sw_estado, b.unidad_dosificacion, e.nombre, a.hc_nota_suministro_id,
		a.codigo_producto, a.evolucion_id, a.observacion, a.tipo_observacion, a.usuario_id_nota,
		a.fecha_registro_nota, z.descripcion as producto from hc_notas_suministro_medicamentos a
		left join profesionales_usuarios f on (a.usuario_id_nota = f.usuario_id) left join
		profesionales e on (f.tipo_tercero_id = e.tipo_id_tercero and f.tercero_id = e.tercero_id)
		left join system_usuarios g on (a.usuario_id_nota = g.usuario_id),
		hc_medicamentos_recetados_hosp b
          left join inventarios_productos z on (b.codigo_producto = z.codigo_producto), hc_evoluciones c, ingresos d
		where a.codigo_producto = '".$codigo_producto."' and a.evolucion_id = b.evolucion_id and
		a.codigo_producto = b.codigo_producto and a.evolucion_id = c.evolucion_id and
		c.ingreso = d.ingreso and d.ingreso = ".$this->ingreso." order by a.hc_nota_suministro_id";


	//and a.evolucion_id = ".$evolucion_id."
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar el medicamento";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ 
          	$i=0;
			while (!$result->EOF)
			{
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
			}
		}
		$result->Close();
          return $vector;
	}
     
     
     /*provisionalmente*/
     function Consulta_Solicitud_Medicamentos_Finalizados_y_Suspendidos()
     {
          $pfj=$this->frmPrefijo;
          list($dbconnect) = GetDBconn();

          $query= "select a.sw_estado,
                    k.sw_uso_controlado, case when k.sw_pos = 1 then 'POS'
                    else 'NO POS' end as item, a.codigo_producto, a.sw_paciente_no_pos, a.cantidad,
                    a.dosis, m.nombre as via, a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id,
                    h.descripcion as producto, c.descripcion as principio_activo, h.contenido_unidad_venta,
                    l.descripcion, a.evolucion_id from hc_medicamentos_recetados_hosp as a left join
                    hc_vias_administracion as m on (a.via_administracion_id = m.via_administracion_id),
                    inv_med_cod_principios_activos as c, inventarios_productos as h, medicamentos as k,
                    unidades as l,
          
                    hc_evoluciones n
          
                    where n.ingreso = ".$this->ingreso."
                    and a.evolucion_id = n.evolucion_id and
          
          
	               (a.sw_estado = '0' or a.sw_estado = '2' or a.sw_estado = '8')  and
          
          
                    k.cod_principio_activo = c.cod_principio_activo and
                    h.codigo_producto = k.codigo_medicamento and
                    a.codigo_producto = h.codigo_producto and
                    h.codigo_producto = a.codigo_producto and h.unidad_id = l.unidad_id
                    order by a.sw_estado, k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto, a.evolucion_id";
          
          $result = $dbconnect->Execute($query);
     
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
               $i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          $result->Close();
          return $vector;
	}
     /*provisionalmente*/
     
     
     function Consulta_Solicitud_Medicamentos_Finalizados()
     {
          $pfj=$this->frmPrefijo;
          list($dbconnect) = GetDBconn();

          $query="SELECT a.sw_estado,
                    k.sw_uso_controlado, case when k.sw_pos = 1 then 'POS'
                    else 'NO POS' end as item, a.codigo_producto, a.sw_paciente_no_pos, a.cantidad,
                    a.dosis, m.nombre as via, a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id,
                    h.descripcion as producto, c.descripcion as principio_activo, h.contenido_unidad_venta,
                    l.descripcion, a.evolucion_id, n.fecha, ter.nombre_tercero, notas.fecha_registro_nota as fecha_registro,
                    1 as finalizado
                    
                    FROM hc_medicamentos_recetados_hosp as a
                    JOIN hc_notas_suministro_medicamentos as notas on(notas.codigo_producto = a.codigo_producto and notas.evolucion_id = a.evolucion_id)
				left join hc_vias_administracion as m on (a.via_administracion_id = m.via_administracion_id),
                    inv_med_cod_principios_activos as c, inventarios_productos as h, medicamentos as k,
                    unidades as l,
          
                    hc_evoluciones n
                    LEFT JOIN profesionales_usuarios pusu ON (pusu.usuario_id=n.usuario_id)
                    LEFT JOIN terceros ter ON (pusu.tipo_tercero_id=ter.tipo_id_tercero AND pusu.tercero_id=ter.tercero_id)

          
                    where n.ingreso = ".$this->ingreso."
                    AND a.evolucion_id = n.evolucion_id 
                    AND (a.sw_estado = '0' OR a.sw_estado = '9' OR a.sw_estado = '1' OR a.sw_estado = '2')
                    AND notas.tipo_observacion = '3'
                    AND k.cod_principio_activo = c.cod_principio_activo
                    AND h.codigo_producto = k.codigo_medicamento
                    AND a.codigo_producto = h.codigo_producto
                    AND h.codigo_producto = a.codigo_producto 
                    AND h.unidad_id = l.unidad_id
                    
                    order by a.sw_estado, k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto, a.evolucion_id, finalizado;";
          
          $result = $dbconnect->Execute($query);
     
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
               $i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          $result->Close();
          return $vector;
	}


     function Consulta_Solicitud_Medicamentos_Suspendidos()
     {
          $pfj=$this->frmPrefijo;
          list($dbconnect) = GetDBconn();

          $query= "SELECT a.sw_estado,
                    k.sw_uso_controlado, case when k.sw_pos = 1 then 'POS'
                    else 'NO POS' end as item, a.codigo_producto, a.sw_paciente_no_pos, a.cantidad,
                    a.dosis, m.nombre as via, a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id,
                    h.descripcion as producto, c.descripcion as principio_activo, h.contenido_unidad_venta,
                    l.descripcion, a.evolucion_id, n.fecha, ter.nombre_tercero, notas.fecha_registro_nota as fecha_registro,
                    1 as finalizado
                    
                    FROM hc_medicamentos_recetados_hosp as a
                    JOIN hc_notas_suministro_medicamentos as notas on(notas.codigo_producto = a.codigo_producto and notas.evolucion_id = a.evolucion_id)
                    left join hc_vias_administracion as m on (a.via_administracion_id = m.via_administracion_id),
                    inv_med_cod_principios_activos as c, inventarios_productos as h, medicamentos as k,
                    unidades as l,
          
                    hc_evoluciones n
                    LEFT JOIN profesionales_usuarios pusu ON (pusu.usuario_id=n.usuario_id)
                    LEFT JOIN terceros ter ON (pusu.tipo_tercero_id=ter.tipo_id_tercero AND pusu.tercero_id=ter.tercero_id)

          
                    where n.ingreso = ".$this->ingreso."
                    and a.evolucion_id = n.evolucion_id 
                    and (a.sw_estado = '2' or a.sw_estado = '1' or a.sw_estado = '0' or a.sw_estado = '9')
                    AND notas.tipo_observacion = '2'
                    and k.cod_principio_activo = c.cod_principio_activo and
                    h.codigo_producto = k.codigo_medicamento 
                    and a.codigo_producto = h.codigo_producto 
                    and h.codigo_producto = a.codigo_producto 
                    and h.unidad_id = l.unidad_id
                    order by a.sw_estado, k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto, a.evolucion_id, finalizado";
          
          $result = $dbconnect->Execute($query);
     
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
               $i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          $result->Close();
          return $vector;
	}

     function verificar($codigo)
     {
          $pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
          $query="SELECT codigo_producto
                  FROM hc_medicamentos_recetados_hosp
                  WHERE codigo_producto = '$codigo'
                  AND ingreso = ".$this->ingreso."
                  AND sw_estado = '1';";
     	$resulta=$dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al insertar en hc_control_suministro_medicamentos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->frmError["MensajeError"]="NO HA SIDO POSIBLE GENERAR EL CONTROL DEL SUMINISTRO";
			$dbconn->RollbackTrans();
			return false;
		}
          list($codigo) = $resulta->FetchRow();
          return $codigo;
     }
     
     
     function Insertar_Control_Suministro()
     {
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
		if($_REQUEST['cantidad_suministrada'.$pfj] == '' ){
               $this->frmError["cantidad_suministrada"]=1;
               $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
               return false;
		}
		
          if($_REQUEST['cantidad_suministrada'.$pfj] != ''){
			if (is_numeric($_REQUEST['cantidad_suministrada'.$pfj])==0){
				$this->frmError["cantidad_suministrada"]=1;
				$this->frmError["MensajeError"]="CANTIDAD INVALIDA, DIGITE SOLO NUMEROS.";
				return false;
			}
		}
		
          if($_REQUEST['cantidad_suministrada'.$pfj] > ($_SESSION['CABECERA_CONTROL'.$pfj][cantidad] - $_REQUEST['total_suministro']))	{
			$this->frmError["cantidad_suministrada"]=1;
			$this->frmError["MensajeError"]="LA CANTIDAD SUMINISTRADA NO PUEDE SER MAYOR A LA CANTIDAD DE DOSIS FORMULADA.";
			return false;
		}
		
          $fechaHora = $_REQUEST['selectHora'.$pfj].":".$_REQUEST['selectMinutos'.$pfj];

		$query=	 "INSERT INTO hc_control_suministro_medicamentos
							(codigo_producto, evolucion_id, usuario_id_control, fecha_realizado,
							fecha_registro_control, cantidad_suministrada, observacion)
                              VALUES
							(
								'".$_REQUEST['codigo_producto'.$pfj]."',".$_REQUEST['evolucion_id'.$pfj].",
								".UserGetUID().", '".$fechaHora."',
								now(), ".$_REQUEST['cantidad_suministrada'.$pfj].",
								'".$_REQUEST['observacion_suministro'.$pfj]."')";
		
          $resulta=$dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al insertar en hc_control_suministro_medicamentos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->frmError["MensajeError"]="NO HA SIDO POSIBLE GENERAR EL CONTROL DEL SUMINISTRO";
			$dbconn->RollbackTrans();
			return false;
		}
		
          if(($_REQUEST['total_suministro']+$_REQUEST['cantidad_suministrada'.$pfj])==$_SESSION['CABECERA_CONTROL'.$pfj][cantidad]){
			$query=	 "INSERT INTO hc_notas_suministro_medicamentos
                                       (codigo_producto, evolucion_id, observacion, tipo_observacion,
                                        usuario_id_nota, fecha_registro_nota)
                                   VALUES
                                        (
                                             '".$_REQUEST['codigo_producto'.$pfj]."',
                                             ".$_REQUEST['evolucion_id'.$pfj].",
                                             'Finalizacion del Medicamento',
                                             '3', ".UserGetUID().", now())";
               $resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al insertar en hc_justificaciones_no_pos_hosp_respuestas_pos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]="NO HA SIDO POSIBLE GENERAR NOTA";
				$dbconn->RollbackTrans();
				return false;
			}else{
				$query= "UPDATE hc_medicamentos_recetados_hosp SET  sw_estado= '0'
						WHERE codigo_producto =  '".$_REQUEST['codigo_producto'.$pfj]."'
						AND evolucion_id = '".$_REQUEST['evolucion_id'.$pfj]."'";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al actualizar la observacion en hc_os_solicitudes_apoyod";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
		}
		$dbconn->CommitTrans();
		$_REQUEST = '';
		$this->frmError["MensajeError"]="CONTROL DEL SUMINISTRO GENERADO SATISFACTORIAMENTE";
		return true;
	}

     function Consultar_Control_Suministro($codigo_producto, $evolucion_id)
     {
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		//trae todo lo del igreso
			$query1= "select a.hc_control_suministro_id, a.codigo_producto,
			a.evolucion_id, a.usuario_id_control, a.fecha_realizado,
			a.fecha_registro_control, a.cantidad_suministrada, a.observacion,
			e.nombre, g.nombre as nombre_usuario

			from hc_control_suministro_medicamentos a left join profesionales_usuarios f on
			(a.usuario_id_control = f.usuario_id) left join profesionales e on
			(f.tipo_tercero_id = e.tipo_id_tercero and f.tercero_id = e.tercero_id )
			left join system_usuarios g on (a.usuario_id_control = g.usuario_id ),
			hc_medicamentos_recetados_hosp b, hc_evoluciones c, ingresos d

			where a.codigo_producto = '".$codigo_producto."'
			and a.evolucion_id = b.evolucion_id and a.codigo_producto = b.codigo_producto
			and a.evolucion_id = c.evolucion_id and	 c.ingreso = d.ingreso and
			d.ingreso =  ".$this->ingreso."
			order by a.hc_control_suministro_id";


			//trae todo lo de la evolucion en especial
		  $query= "select a.hc_control_suministro_id, a.codigo_producto,
		                  a.evolucion_id,	a.usuario_id_control, a.fecha_realizado,
					   a.fecha_registro_control,	a.cantidad_suministrada, a.observacion,
                            e.nombre, g.nombre as nombre_usuario

			from hc_control_suministro_medicamentos a 
               left join profesionales_usuarios f on (a.usuario_id_control = f.usuario_id) 
               left join profesionales e on (f.tipo_tercero_id = e.tipo_id_tercero and f.tercero_id = e.tercero_id)
			left join system_usuarios g on (a.usuario_id_control = g.usuario_id),
			hc_medicamentos_recetados_hosp b

			where a.codigo_producto = '".$codigo_producto."'
			and a.evolucion_id = '".$evolucion_id."'
               and a.evolucion_id = b.evolucion_id
               and a.codigo_producto = b.codigo_producto
			order by a.hc_control_suministro_id desc";

     //and a.evolucion_id = ".$evolucion_id."
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al consultar el medicamento";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { $i=0;
               while (!$result->EOF)
               {
               $vector[$i]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
               $i++;
               }
          }
          $result->Close();
          return $vector;
	}

     //insumos claudia
     function Busqueda_Avanzada_Insumos()
     {
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();

          $opcion         = $_REQUEST['criterio1'.$pfj];
          $codigo_insumo  = STRTOUPPER ($_REQUEST['codigo_insumo'.$pfj]);
		$insumo         = STRTOUPPER($_REQUEST['insumo'.$pfj]);

		$filtroTipoCargo = '';
		$busqueda1 = '';
		$busqueda2 = '';

		if($opcion != '-1' && !empty($opcion) && $opcion != '-2')
          {
               $filtroTipoCargo=" AND a.grupo_tipo_cargo = '$opcion'";
          }

          if ($codigo_insumo != '')
          {
               $busqueda1 =" AND b.codigo_producto LIKE '$codigo_insumo%'";
          }

          if ($insumo != '')
          {
               $busqueda2 ="AND b.descripcion LIKE '%$insumo%'";
          }

          if($opcion == '-2')
          {

               $dpto = '';
               $espe = '';
               if ($this->departamento != '' )
                    {
                         $dpto = "AND a.departamento = '".$this->departamento."'";
                    }
               if ($this->especialidad != '' )
                    {
                         $espe = "AND a.especialidad = '".$this->especialidad."'";
                    }
               if ($dpto == '' AND $espe == '')
                    {
                         return false;
                    }
          }


		if(empty($_REQUEST['conteo'.$pfj]))
		{
          	if($opcion == '-2')
          	{
                    $query= "SELECT count(*)
                    		FROM apoyod_solicitud_frecuencia a, cups b,
                              apoyod_tipos c
						WHERE a.cargo = b.cargo
						AND b.grupo_tipo_cargo = c.apoyod_tipo_id
						$dpto $espe $busqueda1 $busqueda2";
          	}
		  	else
          	{
			    $query = "SELECT count(*)
					from	inv_grupos_inventarios a,	inventarios_productos b
					where a.sw_insumos = '1' and  a.grupo_id = b.grupo_id
      				$filtroTipoCargo	$busqueda1 $busqueda2";
          	}

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
		{
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'.$pfj];
               if($Of > $this->conteo)
			{
				$Of=0;
				$_REQUEST['Of'.$pfj]=0;
				$_REQUEST['paso1'.$pfj]=1;
			}
		}
          if($opcion == '-2')
          {
               $query= "SELECT DISTINCT a.cargo, b.descripcion, c.apoyod_tipo_id,
                    c.descripcion as tipo
                    FROM apoyod_solicitud_frecuencia a, cups b,
                    apoyod_tipos c
                    WHERE a.cargo = b.cargo
                    AND b.grupo_tipo_cargo = c.apoyod_tipo_id
                    $dpto $espe $busqueda1 $busqueda2
                    order by c.descripcion, a.cargo
                    LIMIT ".$this->limit." OFFSET $Of;";
          }
          else
          {
               $query = "
                         select b.codigo_producto, b.descripcion, a.grupo_id
                         from	inv_grupos_inventarios a,	inventarios_productos b
                         where a.sw_insumos = '1' and  a.grupo_id = b.grupo_id
		               $filtroTipoCargo	$busqueda1 $busqueda2 order by b.codigo_producto
                         LIMIT ".$this->limit." OFFSET $Of;";
          }

		$resulta = $dbconn->Execute($query);
		//$this->conteo=$resulta->RecordCount();
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

     	if($this->conteo==='0')
          {
               $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
               return false;
          }
     	$resulta->Close();
     	return $var;
	}

     function Insertar_Varios_Insumos()
     {
		 $pfj=$this->frmPrefijo;
		 list($dbconn) = GetDBconn();
		 $dbconn->BeginTrans();

		 //se copia este codigo para editarlo y crear la funcion de insumos.

		 
//      foreach($_REQUEST['op'.$pfj] as $index=>$codigo)
// 		    {
//          //realiza el id manual de la tabla
// 		     $query1="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
//       	 $result=$dbconn->Execute($query1);
// 		     $hc_os_solicitud_id=$result->fields[0];
//          //fin de la operacion
// 
// 				 $arreglo=explode(",",$codigo);
// 
// 				 $query2="INSERT INTO hc_os_solicitudes
// 										(hc_os_solicitud_id, evolucion_id, cargo, os_tipo_solicitud_id, plan_id)
// 										VALUES
//                    		($hc_os_solicitud_id,".$this->evolucion.",
// 										   '".$arreglo[0]."', '".ModuloGetVar('','','TipoSolicitudApoyod')."',
// 											  ".$this->plan_id.")";
// 
// 				 $resulta=$dbconn->Execute($query2);
// 
// 				 if ($dbconn->ErrorNo() != 0)
// 						 {
// 							$this->error = "Error al insertar en hc_os_solicitudes";
// 							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 							$dbconn->RollbackTrans();
// 							return false;
// 						 }
// 					else
// 					{
//         		$query3="INSERT INTO hc_os_solicitudes_apoyod
// 						(hc_os_solicitud_id, apoyod_tipo_id)
// 		  			 VALUES  ($hc_os_solicitud_id, '".$arreglo[1]."');";
// 
//          		$resulta1=$dbconn->Execute($query3);
// 				 		if ($dbconn->ErrorNo() != 0)
// 		      		{
// 			     			$this->error = "Error al insertar en hc_os_solicitudes_apoyod";
// 			    			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 								$dbconn->RollbackTrans();
// 			     			return false;
// 		      		}
//          		else
// 		      		{
// 				   			$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
// 			    		}
// 					}
// 		    }
          $dbconn->CommitTrans();
          return true;
	}

     //funciones para la impresion claudia
     function Imprimir_Justificacion_nopos()
     {
     	$pfj=$this->frmPrefijo;
        	$var=$this->Reporte_Justificacion_nopos();
        	if (!IncludeFile("classes/reports/reports.class.php"))
        	{
               $this->error = "No se pudo inicializar la Clase de Reportes";
               $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
               return false;
          }
          $classReport = new reports;
          $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pdf');
          $reporte=$classReport->PrintReport($tipo_reporte='pdf',$tipo_modulo='system',$modulo='reportes',$reporte_name='justificacion_nopos_med',$var,$impresora,$orientacion='P',$unidades='mm',$formato='letter',$html=1);

          /*
          $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pdf');
          $reporte=$classReport->PrintReport($tipo_reporte='pdf',$tipo_modulo='app',$modulo='Os_Listas_Trabajo_Apoyod',$reporte_name='examenes',$var,$impresora='starPC16',$orientacion='P',$unidades='mm',$formato='letter',$html=1);
          */

          if(!$reporte)
          {
               $this->error = $classReport->GetError();
               $this->mensajeDeError = $classReport->MensajeDeError();
               UNSET($classReport);
               return false;
          }

          $resultado=$classReport->GetExecResultado();
          UNSET($classReport);
          //aqui se coloca la funcion a la que debe retornar.
          //$this->BuscarOrden();
          $_REQUEST['consultar_just'.$pfj]=1;
          $this->Consultar_Justificacion_Medicamentos_No_Pos();
     	return true;
    	}


     function Reporte_Justificacion_nopos()
     {
     	$pfj=$this->frmPrefijo;
     	$codigo_producto= $_SESSION['MEDICAMENTOSM'.$pfj][codigo_producto];
    		$evolucion = $_SESSION['MEDICAMENTOSM'.$pfj][evolucion];

		list($dbconnect) = GetDBconn();
		$query= "select	c.descripcion as principio_activo, b.contenido_unidad_venta,
		b.descripcion as producto, p.unidad_dosificacion, q.nombre as via, p.cantidad, p.observacion,
		p.dosis, a.hc_justificaciones_no_pos_hosp, a.evolucion_id, n.descripcion as forma,
		k.concentracion_forma_farmacologica, k.unidad_medida_medicamento_id, a.codigo_producto,
		a.usuario_id_autoriza, a.duracion, a.dosis_dia, a.justificacion, a.ventajas_medicamento,
		a.ventajas_tratamiento, a.precauciones, a.controles_evaluacion_efectividad,
		a.tiempo_respuesta_esperado, a.riesgo_inminente, a.sw_riesgo_inminente,
		a.sw_agotadas_posibilidades_existentes, a.sw_comercializacion_pais, a.sw_homologo_pos,
		a.descripcion_caso_clinico, a.sw_existe_alternativa_pos

		from hc_justificaciones_no_pos_hosp as a,	medicamentos as k,
		inv_med_cod_forma_farmacologica as n, hc_medicamentos_recetados_hosp p
		left join hc_vias_administracion q on (p.via_administracion_id = q.via_administracion_id),
		inventarios_productos	as b, inv_med_cod_principios_activos as c

		where	a.codigo_producto = '".$codigo_producto."' and a.evolucion_id = ".$evolucion." and
		a.codigo_producto = k.codigo_medicamento and k.cod_forma_farmacologica =
		n.cod_forma_farmacologica and a.codigo_producto = p.codigo_producto and
		a.evolucion_id = p.evolucion_id
		and a.codigo_producto = b.codigo_producto and k.cod_principio_activo = c.cod_principio_activo
		and b.codigo_producto = k.codigo_medicamento ";
		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar la justificacion";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
			  $vector_justificacion[]=$result->GetRowAssoc($ToUpper = false);
			  $result->MoveNext();
			}
		}
		$result->Close();

		//consulta de los diagnosticos de la justificacion
		$query= "select a.hc_justificaciones_no_pos_hosp, a.diagnostico_id,
		b.diagnostico_nombre from hc_justificaciones_no_pos_hosp_diagnostico as a,
		diagnosticos as b where a.diagnostico_id = b.diagnostico_id and
		a.hc_justificaciones_no_pos_hosp = ".$vector_justificacion[0][hc_justificaciones_no_pos_hosp]."";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_hosp_diagnostico";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
			  $vector_diagnostico[]=$result->GetRowAssoc($ToUpper = false);
			  $result->MoveNext();
			}
		}
          $vector_justificacion[diagnosticos]= $vector_diagnostico;
          $result->Close();
		//fin de los diagnosticos

     //CONSULTA DE LAS ALTERNATIVAS
	     $query= "select a.alternativa_pos_id, a.medicamento_pos,
               a.principio_activo, a.dosis_dia_pos, a.duracion_pos,
               a.sw_no_mejoria, a.sw_reaccion_secundaria, a.reaccion_secundaria,
               a.sw_contraindicacion, a.contraindicacion,
               a.otras, a.hc_justificaciones_no_pos_hosp
               from hc_justificaciones_no_pos_hosp_respuestas_pos as a
               where (a.hc_justificaciones_no_pos_hosp = ".$vector_justificacion[0][hc_justificaciones_no_pos_hosp].")";
     
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al consultar hc_justificaciones_no_pos_hosp_respuestas_pos";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          {
               while (!$result->EOF)
               {
                    $vector_alternativas[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
          }
          $vector_justificacion[alternativas]= $vector_alternativas;
          $result->Close();
          //FIN DE LAS ALTERNATIVAS

          //OBTENER DATOS DEL PACIENTE
  		$query= "select e.tipo_id_tercero, e.id, e.razon_social,
                    a.fecha, b.tipo_id_paciente, b.paciente_id,
                    btrim(c.primer_nombre||' '||c.segundo_nombre||' '||
                    c.primer_apellido||' '||c.segundo_apellido,'') as nombre
                                   from hc_evoluciones a, ingresos b, pacientes c,
                    departamentos d, empresas e
                    where a.evolucion_id = ".$vector_justificacion[0][evolucion_id]." and
                    a.ingreso = b.ingreso and
                    b.tipo_id_paciente = c.tipo_id_paciente  and b.paciente_id = c.paciente_id
                    and b.departamento = d.departamento and d.empresa_id = e.empresa_id";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_hosp_respuestas_pos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
                    $paciente[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
			}
		}
		$vector_justificacion[paciente]= $paciente;
		$result->Close();
		//FIN DE DATOS DEL PACIENTE

          //IncludeLib("reportes/examenes");
          //GenerarExamen($a);
          //si esta variable de session esta en 1 es por que saldra al otro lado
          //$_SESSION['LISTA']['APOYOD']['SW']=$_REQUEST['resultado_id'];
          //$this->FormaMetodoBuscar($_SESSION['VECTOR DE BUSQUEDA']);
          //return true;
	     return $vector_justificacion;
	}

     
     function Consulta_Todos_Medicamentos()
     {
          $pfj=$this->frmPrefijo;
	     list($dbconnect) = GetDBconn();

          $query= "SELECT a.sw_estado,
                    a.codigo_producto,
                    h.descripcion as producto,
                    a.evolucion_id, a.unidad_dosificacion
                    FROM hc_medicamentos_recetados_hosp as a,
                    inventarios_productos as h,
                    hc_evoluciones n
                    WHERE n.ingreso = ".$this->ingreso."
                    AND a.evolucion_id = n.evolucion_id 
                    AND a.codigo_producto = h.codigo_producto
                    ORDER BY a.codigo_producto, a.evolucion_id ASC";
     
          $result = $dbconnect->Execute($query);

          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          {
               $i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          return $vector;
     }

     function tiposSoluciones()
     {
          list($dbconnect) = GetDBconn();
          $query= "SELECT solucion_id,descripcion
	          FROM hc_medicamentos_soluciones";
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0){
                    $this->error = "Error al buscar en la consulta de medicamentos recetados";
                    $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                    return false;
          }else{
               while(!$result->EOF){
                    $vector[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
          }
          return $vector;
     }

     function tiposUnidadesSoluciones()
     {
          list($dbconnect) = GetDBconn();
          $query= "SELECT cantidad_id,cantidad,unidad_id FROM hc_medicamentos_soluciones_cantidades";
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0){
                    $this->error = "Error al buscar en la consulta de medicamentos recetados";
                    $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                    return false;
          }else{
               while(!$result->EOF){
                    $vector[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
          }
          return $vector;
     }
  
     /**
     * Consulta de los medicamentos de la canasta de Cirugia.
     */     
     function ConsultaCanastaMedica()
     {
          list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          $query= "SELECT A.*, 
                          (select nombre from system_usuarios where usuario_id = A.usuario_ordeno) as us_orden,
                          (select nombre from system_usuarios where usuario_id = A.usuario_suministro) as us_suministro,
                          B.descripcion
                   FROM estacion_enfermeria_qx_iym_suministrados AS A,
                        inventarios_productos AS B
                   WHERE ingreso=".$this->ingreso."
                   AND A.codigo_producto = B.codigo_producto
                   ORDER BY A.fecha_registro DESC;";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resulta = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else
          {
               while($data = $resulta->FetchRow())
               {
                    $medicamentos[] = $data;
               }
          }
          return $medicamentos;
     }
     
}//End class
?>
