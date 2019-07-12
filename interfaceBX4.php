<?php

$directorio  = '/desarrollo/INTERFACE';
$separador   = "\\";
global $dbconn;
$datos=array();
$Flag="/tmp/FlagIBX4";//Este archivo es un flag, si existe no se ejecuta el proceso, si no se ejecuta
if(!file_exists($Flag))
{
	$file = fopen($Flag,'w');
	fclose($file);
	setDebug("Preparando para proceso de interface ".date('Y-m-d H:i:s'));
	if(file_exists($directorio) && (is_dir($directorio)))
	{
		setDebug("Directorio $directorio encontrado");
		$handle=opendir($directorio);
		readdir($handle);//omite el .
		readdir($handle);//omite el ..
		$HayArchivos=false;
		while ($archivo = readdir($handle)) 
		{
			$file = $directorio."/$archivo";
			if (is_file($file) && is_writable($file))
			{
				setDebug("***************Revisando el archivo $file ***************");
				$HayArchivos=true;
				$contenido= @implode('', @file($file));
				$datos=explode($separador,$contenido);
				unset($contenido);
				setDebug("Subiendo el archivo $file");
				if(RealizarInterfaceBX4(&$datos))
				{
					setDebug("Archivo $file subido satisfactoriamente");
					if(!unlink($file))
					{
						setDebug("WARNING : El archivo $file no se eliminó, por favor ELIMINAR");
					}
					else
						setDebug("El archivo $file se eliminó correctamente");
				}
				else
				{
					$mensaje = $dbconn->ErrorMsg();
					setDebug("ERROR : El arhivo $file no fue subido satistaftoriamente revise su contenido");
					if(!copy($file,$directorio."/AUDITORIA/$archivo"))
						setDebug("El archivo $file no se pudo copiar a la carpeta AUDITORIA");
					else
						setDebug("Pasando el archivo $file a la carpeta AUDITORIA");
					if(!unlink($file))
						setDebug("WARNING : El archivo $file no se eliminó, por favor ELIMINAR");
					else
						setDebug("El archivo $file se eliminó correctamente");
				}
			}
			else
			{
				if(!is_dir($file))
				setDebug("ERROR : El archivo $file no se pudo cargar, revise los permisos");
			}
		}
		if(!$HayArchivos)
			setDebug("No se encontraron archivos para procesar");
		closedir($handle);
		setDebug("Cerrando proceso de interface ".date('Y-m-d H:i:s')."\n\n");
	}
	else
	{
		setDebug("ERROR : el directorio $directorio no fue encontrado no se pudo realizar el proceso");
	}
	unlink($Flag);
}


function RealizarInterfaceBX4($datos)
{
	//validar que el vector este correcto;
	//------------------------------------
	if(sizeof($datos)!=26)
	{
		setDebug("ERROR: El contenido del archivo no es valido");
		return false;
	}
	global $dbconn;
	list($dbconn) = GetDBconn();
	//----------------------------------------------
	//valida de que exista un tipo_afiliado_id y plan_id en la tabla 
	//planes_rangos
	//-----------------------------------------------
	$sql = "
	SELECT 
		 plan_id
	FROM 
		planes_rangos
	WHERE
	plan_id = $datos[16]
	AND tipo_afiliado_id = '$datos[17]'
	AND rango = '$datos[18]'";
	$rs = $dbconn->Execute($sql);
	if ($dbconn->ErrorNo() != 0)
	{
		setDebug($dbconn->ErrorMsg()." Query: $sql");
		$dbconn->RollbackTrans();
		return false;
	}
	if($rs->EOF)
	{
		setDebug("Buscando tipo_afiliado_id y rango en planes_rangos con plan_id $datos[16]");
		echo $sql = "
			SELECT 
				tipo_afiliado_id,
				rango
			FROM
				planes_rangos
			WHERE
				plan_id = $datos[16]
			LIMIT 1 OFFSET 0;";
			$rs1 = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				setDebug($dbconn->ErrorMsg()." Query: $sql");
				$dbconn->RollbackTrans();
				return false;
			}
			if($rs1->EOF)
			{
				setDebug("No se encontró tipo_afiliado_id y rango en planes_rangos");
				return false;
			}
			list($datos[18],$datos[17])=$rs->FetchRow();
	}
	//------------------------------------
	//Insertar Datos en la BD (en transacciones)
	//------------------------------------
	$query = "SELECT paciente_id FROM pacientes
	WHERE paciente_id='$datos[0]' AND tipo_id_paciente='$datos[1]'";
	$result=$dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0)
	{   
		setDebug($dbconn->ErrorMsg()." Query: $query");
		return false;
	}
	$dbconn->BeginTrans();
	//paciente nuevos
	if($result->EOF)
	{
		$query = "
				INSERT INTO pacientes (
						paciente_id,
						tipo_id_paciente,
						primer_apellido,
						segundo_apellido,
						primer_nombre,
						segundo_nombre,
						fecha_nacimiento,
						residencia_direccion,
						residencia_telefono,
						zona_residencia,
						ocupacion_id,
						fecha_registro,
						sexo_id,
						tipo_pais_id,
						tipo_dpto_id,
						tipo_mpio_id,
						nombre_madre,
						usuario_id)
				VALUES ('$datos[0]','$datos[1]','$datos[2]','$datos[3]','$datos[4]','$datos[5]','$datos[6]','$datos[20]','$datos[21]','$datos[7]','$datos[23]','$datos[24]','$datos[8]','$datos[9]','$datos[10]','$datos[11]','$datos[22]',$datos[12])";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			setDebug($dbconn->ErrorMsg()." Query: $query");
			$dbconn->RollbackTrans();
			return false;
		}
		$query = "INSERT INTO historias_clinicas( tipo_id_paciente,
						paciente_id,
						historia_numero,
						historia_prefijo,
						fecha_creacion)
					VALUES ('$datos[1]','$datos[0]','$datos[25]','','now()')";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
								setDebug($dbconn->ErrorMsg()." Query: $query");
								$dbconn->RollbackTrans();
								return false;
		}
	}
	else
	{        //existe
		$query = "
		UPDATE pacientes SET
			primer_apellido='$datos[2]',
			segundo_apellido='$datos[3]',
			primer_nombre='$datos[4]',
			segundo_nombre='$datos[5]',
			fecha_nacimiento='$datos[6]',
			residencia_direccion='$datos[20]',
			residencia_telefono='$datos[21]',
			zona_residencia='$datos[7]',
			ocupacion_id='$datos[23]',
			sexo_id='$datos[8]',
			tipo_pais_id='$datos[9]',
			tipo_dpto_id='$datos[10]',
			tipo_mpio_id='$datos[11]',
			nombre_madre='$datos[22]',
			fecha_registro='$datos[24]',
			usuario_id=$datos[12]
		WHERE paciente_id='$datos[0]' AND tipo_id_paciente='$datos[1]'";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			setDebug($dbconn->ErrorMsg()." Query: $query");
			$dbconn->RollbackTrans();
			return false;
		}
		$query = "UPDATE historias_clinicas SET historia_prefijo='$prefijo',
						historia_numero='$hc'
					WHERE paciente_id='$datos[0]' AND tipo_id_paciente='$datos[1]'";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			setDebug($dbconn->ErrorMsg()." Query: $query");
			$dbconn->RollbackTrans();
			return false;
		}
	}
	//Bloqueo de la tabla ingresos
	$query="SELECT MAX(ingreso) FROM ingresos;";
	$result=$dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0)
	{
		setDebug($dbconn->ErrorMsg()." Query: $query");
		$dbconn->RollbackTrans();
		return false;
	}
	$IngresoId=$result->fields[0]+1;
	$sql = "LOCK TABLE ingresos IN SHARE ROW EXCLUSIVE MODE;";
	$result=$dbconn->Execute($sql);
	if ($dbconn->ErrorNo() != 0)
	{
		setDebug($dbconn->ErrorMsg()." Query: $sql");
		$dbconn->RollbackTrans();
		return false;
	}
	$dbconn->Execute($query);
	$query = "
		INSERT INTO ingresos (ingreso,
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
		VALUES($IngresoId,
		'$datos[1]',
		'$datos[0]',
		'$datos[24]',
		'$datos[13]',
		'$datos[14]',
		'',
		'021501',
		'1',
		'$datos[24]',
		$datos[12],
		'021501',
		NULL,
		NULL)";
	$dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0)
	{
		setDebug($dbconn->ErrorMsg()." Query: $query");
		$dbconn->RollbackTrans();
		return false;
	}
	$query = "
		INSERT INTO cuentas ( numerodecuenta,
			empresa_id,
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
		VALUES(nextval('cuentas_numerodecuenta_seq'),
		'01',
		'01',
		$IngresoId,
		".$datos[16].",
		1,
		".$datos[12].",
		'now()','".$datos[17]."','".$datos[18]."',NULL,NULL,".$datos[19].")";
	$dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0)
	{
		setDebug($dbconn->ErrorMsg()." Query: $query");
		$dbconn->RollbackTrans();
		return false;
	}
	$sqls="
		INSERT into pacientes_urgencias(
			ingreso,
			estacion_id,
			triage_id,
			paciente_urgencia_consultorio_id)
		VALUES($IngresoId,'URG1',NULL,NULL)";
	$result = $dbconn->Execute($sqls);
	if ($dbconn->ErrorNo() != 0)
	{
		setDebug($dbconn->ErrorMsg()." Query: $sqls");
		$dbconn->RollbackTrans();
		return false;
	}
	$dbconn->CommitTrans();
	$query="SELECT setval('ingresos_ingreso_seq',$IngresoId)";
	$result = $dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0)
	{
		setDebug($dbconn->ErrorMsg()." Query: $query");
		$dbconn->RollbackTrans();
		return false;
	}
	return true;
	//------------------------------------
}

function setDebug($log)
{
	error_log($log."\n", 3, GetVarConfigAplication('DirCache')."/DebugIBX4.log");
}
?>