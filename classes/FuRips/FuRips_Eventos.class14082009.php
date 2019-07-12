<?
/**
 * $Id: FuRips_Eventos.class.php,v 1.2 2009/05/14 21:40:32 hugo Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS-CLASSES
 * 
 * 1- ARCHIVO DE ACCIDENTES O EVENTOS CATASTROFICOS Y TERRORISTAS.
 */

/**
 * Extiende de la superclase Csv para generar el archivo Rips ForeCat resolucion 2056 - 2003
 * 1- ARCHIVO DE ACCIDENTES O EVENTOS CATASTROFICOS Y TERRORISTAS.
 *
 * @author    Ehudes Garcia <efgarcia@ipsoft-sa.com>
 * @version   $Revision: 1.2 $
 * @package   IPSOFT-SIIS-CLASSES
 */

class FuRips_Eventos extends FuRips
{
    /**
     * Naturaleza cel evento del accidente o evento catastrofio/terrorista
     *
     * @var string
     */
	var $naturalezaEvento;
    
    /**
     * Directorio donde se crean los archivos del rips soat
     *
     * @var string
     */
    var $directorio;
	
	/**
	 * Constructor
	 */
	function FuRips_Eventos($envio_id,$rutaRips,$sgsss)
	{
		//$this->RipsSoat($nombre,$envio_id);
		$this->FuRips($nombre,$envio_id);
		$this->directorio = $rutaRips;
		//$this->setNombre("AA");
		$this->setNombre("FURIPS1".$sgsss.date('dmY'));
		$this->cargarDatos();
		$metodosFormatos = array(
				"formatoColumna1",//No. Radicado Anterior (10)
				"formatoColumna2",//RG - Se debe diligenciar 1 en los casos de Respuesta a Pago Parcial y 0 para Glosa Total, si la reclamación es nueva se debe dejar nulo.(1)
				"formatoColumna3",//Número factura  o  Número de cuenta de cobro. (20)
				"formatoColumna4",//Número consecutivo de la reclamación. (12)
				"formatoColumna5",//Código de habilitación IPS. (12)
				"formatoColumna6",//Primer apellido victima(20)
				"formatoColumna7",//Segundo apellido victima (30)
				"formatoColumna8",//Primer nombre victima (20)
				"formatoColumna9",//Segundo nombre victima (30)
				"formatoColumna10",//Tipo documento victima (2)
				"formatoColumna11",//Número documento victima (16)
				"formatoColumna12",//Fecha de nacimiento (DD/MM/AAAA)(10)
				"formatoColumna13",//Sexo (1)
				"formatoColumna14",//Dirección residencia victima (40)
				"formatoColumna15",//Código departamento ubicación victima (2)
				"formatoColumna16",//Código municipio ubicación victima (3)
				"formatoColumna17",//Teléfono victima (10)
				"formatoColumna18",//Condición del accidentado (1)
				"formatoColumna19",//Naturaleza evento (2)
				"formatoColumna20",//Descripción de otro evento (25)
				"formatoColumna21",//Dirección ocurrencia (40)
				"formatoColumna22",//Fecha ocurrencia (DD/MM/AAAA)(10)
				"formatoColumna23",//Hora de ocurrencia HH:MM (5)
				"formatoColumna24",//Código departamento (2)
				"formatoColumna25",//Código municipio (3)
				"formatoColumna26",//Zona (1)
				//"formatoColumna27",//Descripción  breve de la ocurrencia (255)
				"formatoColumna28",//Estado de aseguramiento
				"formatoColumna29",//Marca (15)
				"formatoColumna30",//Placa(6)
				"formatoColumna31",//Tipo de vehiculo. (1)
				"formatoColumna32",//Código de la aseguradora. (20)
				"formatoColumna33",//Número de póliza SOAT(20)
				"formatoColumna34",//Fecha de inicio de vigencia de la póliza  (DD/MM/AAAA)(10)
				"formatoColumna35",//Fecha final de vigencia de la póliza  (DD/MM/AAAA)(10)
				"formatoColumna36",//Intervención autoridad (1)
				"formatoColumna37",//Cobro excedente póliza(1)
				"formatoColumna38",//Placa del segundo vehículo involucrado(6)
				"formatoColumna39",//Tipo de documento de identidad del  propietario del segundo vehículo involucrado(2)
				"formatoColumna40",//Número documento  propietario del segundo vehículo involucrado (16)
				"formatoColumna41",//Placa del tercer vehículo involucrado (6)
				"formatoColumna42",//Tipo de documento de identidad del  propietario del tercer vehículo involucrado (2)
				"formatoColumna43",//Número documento  propietario del tercer vehículo involucrado (16)
				"formatoColumna44",//Tipo de documento de identidad del  propietario (2)
				"formatoColumna45",//Número documento  propietario (16)
				"formatoColumna46",//Primer apellido propietario o razón social en caso de empresa. (20)
				"formatoColumna47",//Segundo apellido propietario (30)
				"formatoColumna48",//Primer nombre propietario (20)
				"formatoColumna49",//Segundo nombre propietario(30)
				"formatoColumna50",//Dirección residencia propietario(40)
				"formatoColumna51",//Teléfono del propietario (10)
				"formatoColumna52",//Código departamento de residencia del propietario(2)
				"formatoColumna53",//Código municipio de residencia del propietario(3)
				"formatoColumna54",//Primer apellido conductor (20)
				"formatoColumna55",//Segundo apellido conductor(30)
				"formatoColumna56",//Primer nombre conductor(20)
				"formatoColumna57",//Segundo nombre conductor(30)
				"formatoColumna58",//Tipo documento conductor(2)
				"formatoColumna59",//Número documento conductor(16)
				"formatoColumna60",//Dirección residencia conductor(40)
				"formatoColumna61",//Código departamento de residencia del conductor(2)
				"formatoColumna62",//Código municipio de residencia del conductor(3)
				"formatoColumna63",//Teléfono de residencia del conductor(10)
				"formatoColumna64",//Tipo de Referencia (1)
				"formatoColumna65",//Fecha de Remisión - DD/MM/AAAA
				"formatoColumna66",//Hora de salida HH:MM
				"formatoColumna67",//Código de habilitación IPS remitente (12)
				"formatoColumna68",//Profesional que remite (60)
				"formatoColumna69",//Cargo persona que remite (30)
				"formatoColumna70",//Fecha de Aceptación DD/MM/AAAA
				"formatoColumna71",//Hora de ingreso  HH:MM
				"formatoColumna72",//Código de habilitación IPS receptor (12)
				"formatoColumna73",//Profesional que recibe (60)
				"formatoColumna74",//Cargo persona que recibe (30)
				"formatoColumna75",//Número de placa (6)
				"formatoColumna76",//Transportes de victimas desde (20)
				"formatoColumna77",//Transporte victimas hasta (20)
				"formatoColumna78",//Tipo de servicio (1)
				"formatoColumna79",//Zona donde  recoge victima (1)
				"formatoColumna80",//Fecha de ingreso (10) DD/MM/AAAA
				"formatoColumna81",//Hora de ingreso (5) HH:MM
				"formatoColumna82",//Fecha de egreso DD/MM/AAAA
				"formatoColumna83",//Hora de egreso (5) HH:MM
				"formatoColumna84",//Código diagnostico principal de ingreso (4)
				"formatoColumna85",//Otro Código Diagnóstico de ingreso (4)
				"formatoColumna86",//Otro Código Diagnóstico de ingreso (4)
				"formatoColumna87",//Código diagnostico  principal de egreso (4)
				"formatoColumna88",//Otro Código Diagnóstico de egreso (4)
				"formatoColumna89",//Otro Código Diagnóstico de egreso (4)
				"formatoColumna90",//Primer apellido medico o profesional de la salud (20) 
				"formatoColumna91",//Segundo apellido medico o profesional de la salud (30)
				"formatoColumna92",//Primer nombre medico o profesional de la salud (20)
				"formatoColumna93",//Segundo nombre medico o profesional de la salud (30)
				"formatoColumna94",//Tipo documento medico o profesional de la salud (2)
				"formatoColumna95",//Número documento medico o profesional de la salud (16)
				"formatoColumna96",//Número de registro medico (16)
				"formatoColumna97",//Total facturado amparo gastos medico quirúrgicos (15)
				"formatoColumna98",//Total reclamado amparo gastos medico quirúrgicos (15)
				"formatoColumna99",//Total facturado amparo gastos de transporte y movilización de la victima (15)
				"formatoColumna100",//Total reclamado amparo gastos de transporte y movilización de la victima (15)
				"formatoColumna101"//Total Folios 3
			);
		$this->setMetodosFormatos($metodosFormatos);
	}//Fin Constructor
	
	/**
	 * Carga los datos del archivo Csv ejecutando la consulta sql
	 *
	 */
	function cargarDatos()
	{
	//echo "<pre>";
		$sql = "		
			SELECT *
					
			FROM 
				ingresos_soat AS ISO,
				ingresos AS I,
				cuentas AS C,
				fac_facturas_cuentas AS FFC,
				envios_detalle AS ED

			WHERE 
					ISO.ingreso = I.ingreso
	
			
			AND ISO.ingreso = C.ingreso
			AND C.numerodecuenta = FFC.numerodecuenta
			AND FFC.prefijo = ED.prefijo
			AND FFC.factura_fiscal = ED.factura_fiscal
			AND ED.envio_id = ".$this->envio_id;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error en la consulta";
			$this->mensajeDeError = $sql." ".$dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
			$this->fileError = __FILE__;
			$this->lineError = __LINE__;                                
			return false;
		}
		$dat = array();
		while(!$resultado->EOF)
		{
			$dat[] = $resultado->GetRowAssoc($ToUpper = false);
			$resultado->MoveNext();
		}
		$folios = sizeof($dat);

		foreach($dat AS $indice => $valor)
		{
			$sql = "SELECT 	*
				FROM hc_evoluciones 
				WHERE ingreso = ".$valor[ingreso];
	 		$resultado = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error en la consulta";
				$this->mensajeDeError = $sql." ".$dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
				$this->fileError = __FILE__;
				$this->lineError = __LINE__;                                
				return false;
			}
			if($resultado->RecordCount()==0)
			{
				$sql = "SELECT 	*
					FROM 	ingresos_soat iso,
						soat_atencion_medica_furips sam
					WHERE iso.evento = sam.evento
					AND iso.ingreso = ".$valor[ingreso];
				$resultado = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error en la consulta";
					$this->mensajeDeError = $sql." ".$dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
					$this->fileError = __FILE__;
					$this->lineError = __LINE__;                                
					return false;
				}
				if($resultado->RecordCount()==0)
				{
					$this->salida = ThemeAbrirTabla("MENSAJE");
					$this->setErrorRegla("PARA EL PACIENTE IDENTIFICADO CON : \"".$valor['tipo_id_paciente']." ".$valor['paciente_id']."\" DEBE DILIGENCIARCE EL <BR>FORMULARIO UNICO DE RECLAMACIONES DE LAS INSTITUCIONES
                               <BR>PRESTADORAS DE SERVICIOS DE SALUD POR SERVICIOS PRESTADOS A VICTIMAS DE EVENTOS CATASTROFICOS Y ACCIDENTE DE TRANSITO .");
					echo "<BR><BR><center><font size=\"4\" color=\"red\"><b>".$this->errorRegla."</b></size></center>";
					$this->salida .= ThemeCerrarTabla();
					exit;
					//echo $valor[ingreso]."<BR><BR><BR>";
				}
			}
		}

	$sql = "(	
			SELECT 
				NULL AS numero_radicado,
				NULL AS rg,
				FFC.prefijo||FFC.factura_fiscal as numero_factura,
				ED.envio_id AS consecutivo,
				E.codigo_sgsss,
				P.primer_apellido, 
				P.segundo_apellido,
				P.primer_nombre,
				P.segundo_nombre, 
				P.tipo_id_paciente,
				P.paciente_id,
				TO_CHAR(P.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento,
				P.sexo_id,
				P.residencia_direccion,
				P.tipo_dpto_id,
				P.tipo_mpio_id,
				P.residencia_telefono,
				CASE WHEN SE.condicion_accidentado IS NULL THEN '1' ELSE
					SE.condicion_accidentado END AS condicion_accidentado,
				SE.soat_naturaleza_evento_id AS evento,
				NULL AS otro_evento,
				AC.sitio_accidente,
				AC.fecha_accidente,
				AC.fecha_accidente as hora_accidente,
				AC.tipo_dpto_id AS dpto_accidente, 
				AC.tipo_mpio_id AS mpio_accidente,
				AC.zona,
				SE.asegurado,
				H.marca_vehiculo,
				H.placa_vehiculo,
				CASE WHEN SE.tipo_servicio_vehiculo_id = '0' THEN '3'
				     WHEN SE.tipo_servicio_vehiculo_id = '1' THEN '4'
				     WHEN SE.tipo_servicio_vehiculo_id = '2' THEN '5'
				     WHEN SE.tipo_servicio_vehiculo_id = '3' THEN '6'
				     WHEN SE.tipo_servicio_vehiculo_id = '4' THEN '7'
				     WHEN SE.tipo_servicio_vehiculo_id = '5' THEN '8'
				     WHEN SE.tipo_servicio_vehiculo_id = '6' THEN '9'
				     WHEN SE.tipo_servicio_vehiculo_id = '7' THEN '3'
				ELSE NULL END AS tipo_servicio_vehiculo_id,
				'AT'||TS.identificador_at AS identificador,
				H.poliza,
				H.vigencia_desde,
				H.vigencia_hasta,
				SE.intervension_autoridad,
				'0' AS cobro_excedente,
				NULL AS placa_segundo_vehiculo,
				NULL AS tipo_id_segundo,
				NULL AS documento_segundo,
				NULL AS placa_tercer_vehiculo,
				NULL AS tipo_id_tercer,
				NULL AS documento_tercer,
				J.tipo_id_propietario,
				J.propietario_id,
				J.apellidos_propietario as primer_apellido_propietario,
				J.apellidos_propietario as segundo_apellido_propietario,
				J.nombres_propietario as primer_nombre_propietario,
				J.nombres_propietario as segundo_nombre_propietario,
				J.direccion_propietario,
				J.telefono_propietario,
				K.tipo_dpto_id AS dpto_conductor,
				K.tipo_mpio_id AS mpio_conductor,
				K.apellidos_conductor as primer_apellido_conductor,
				K.apellidos_conductor as segundo_apellido_conductor,
				K.nombres_conductor as primer_nombre_conductor,
				K.nombres_conductor as segundo_nombre_conductor,
				K.tipo_id_conductor,
				K.conductor_id,
				K.direccion_conductor,
				J.tipo_dpto_id AS dpto_propietario,
				J.tipo_mpio_id AS mpio_propietario,
				K.telefono_conductor,
				CASE WHEN TRIM(SR.tipo_referencia) = 'R' THEN '1'
             WHEN TRIM(SR.tipo_referencia) = 'OS' THEN '2'
             ELSE NULL END AS tipo_referencia,
				SR.fecha_remision,
				SR.hora AS hora_salida,
				E.codigo_sgsss AS codigo_habilitacion,
				SR.nombre_profesional_remite,
				SR.profesional_remite_cargo,
				SR.fecha_recepcion_remision,
				SR.hora_recepcion_remision,
				TSG.codigo_sgsss AS codigo_sgsss_receptor,
				SR.nombre_profesional_recibe,
				SR.profesional_recibe_cargo,
				SA.placa_ambulancia,
				SA.lugar_desde,
				SA.lugar_hasta,
				SE.tipo_ambulancia_id AS tipo_servicio,
				SAC.zona,
				I.fecha_ingreso,
				I.fecha_ingreso AS hora_ingreso,
				HC.fecha_cierre AS fecha_egreso,
				TO_CHAR(HC.fecha_cierre, 'YYYY-MM-DD HH:MI:SS') AS hora_egreso,
				CASE WHEN (SELECT tipo_diagnostico_id FROM hc_diagnosticos_ingreso WHERE sw_principal = '1' 
				AND evolucion_id = (SELECT MIN(evolucion_id) FROM hc_evoluciones WHERE ingreso=ISO.ingreso AND estado = '0')) IS NOT NULL
					THEN (SELECT tipo_diagnostico_id FROM hc_diagnosticos_ingreso WHERE sw_principal = '1' 
						AND evolucion_id = (SELECT MIN(evolucion_id) FROM hc_evoluciones WHERE ingreso=ISO.ingreso AND estado = '0'))  
				ELSE 'T140' END AS dx_ingreso,
				NULL AS otro_codigo_dx_ingreso1,
				NULL AS otro_codigo_dx_ingreso2,
				CASE WHEN (SELECT tipo_diagnostico_id FROM hc_diagnosticos_egreso WHERE sw_principal = '1' 
				AND evolucion_id = (SELECT MIN(evolucion_id) FROM hc_evoluciones WHERE ingreso=ISO.ingreso AND estado = '0')) IS NOT NULL
				THEN (SELECT tipo_diagnostico_id FROM hc_diagnosticos_ingreso WHERE sw_principal = '1' 
						AND evolucion_id = (SELECT MIN(evolucion_id) FROM hc_evoluciones WHERE ingreso=ISO.ingreso AND estado = '0'))  
				ELSE 'T140' END AS dx_egreso,
				NULL AS otro_codigo_dx_egreso1,
				NULL AS otro_codigo_dx_egreso2,
				PRO.nombre AS primer_apellido, 
				NULL AS segundo_apellido, 
				PRO.nombre AS primer_nombre, 
				NULL AS segundo_nombre, 
				PRO.furips_tipo_id_tercero,
				PRO.furips_tercero_id,
				PRO.tarjeta_profesional,
				C.total_cuenta,
				C.valor_total_empresa,
				0 as total_transporte,
				0 as total_transporte_reclamado,
				".$folios." AS total_folios
			FROM 
				ingresos_soat AS ISO
				LEFT JOIN soat_remision AS SR ON (ISO.evento = SR.evento)
				LEFT JOIN centros_remision CR ON (SR.centro_remision = CR.centro_remision)
				LEFT JOIN terceros_sgsss AS TSG ON (CR.tipo_id_tercero = TSG.tipo_id_tercero
								AND CR.tercero_id = TSG.tercero_id),
				cuentas AS C,
				fac_facturas_cuentas AS FFC,
				envios_detalle AS ED,
				ingresos AS I,
				empresas AS E,
				pacientes AS P,
				soat_eventos SE
				LEFT JOIN soat_vehiculo_propietario AS J ON (SE.evento=J.evento)
				LEFT JOIN soat_vehiculo_conductor AS K ON (SE.evento=K.evento)
				LEFT JOIN soat_ambulancias AS SA ON (SE.evento = SA.evento),
				soat_accidente AS AC,
				soat_polizas AS H,
				terceros AS T LEFT JOIN 
				terceros_soat AS TS ON (T.tipo_id_tercero=TS.tipo_id_tercero
									AND T.tercero_id=TS.tercero_id),
				soat_accidente AS SAC,
				hc_evoluciones AS HC,
				profesionales_usuarios AS PU,
				profesionales AS PRO
						
			WHERE ISO.ingreso = C.ingreso
			AND C.numerodecuenta = FFC.numerodecuenta
			AND FFC.empresa_id = ED.empresa_id
			AND FFC.prefijo = ED.prefijo
			AND FFC.factura_fiscal = ED.factura_fiscal
			AND ISO.ingreso = I.ingreso
			AND I.paciente_id = P.paciente_id
			AND I.tipo_id_paciente = P.tipo_id_paciente	
			AND E.empresa_id = ED.empresa_id
			AND ISO.evento=SE.evento
			AND AC.accidente_id = SE.accidente_id  
			AND SE.poliza=H.poliza
			AND H.tipo_id_tercero=T.tipo_id_tercero
			AND H.tercero_id=T.tercero_id
			AND SE.accidente_id = SAC.accidente_id
			AND HC.ingreso = ISO.ingreso
			AND HC.usuario_id=PU.usuario_id
			AND PU.tipo_tercero_id = PRO.tipo_id_tercero 
			AND PU.tercero_id = PRO.tercero_id    
			
			
			AND ED.envio_id = ".$this->envio_id."
		)
		UNION ALL
		(
			SELECT 
				NULL AS numero_radicado,
				NULL AS rg,
				FFC.prefijo||FFC.factura_fiscal as numero_factura,
				ED.envio_id AS consecutivo,
				E.codigo_sgsss,
				P.primer_apellido, 
				P.segundo_apellido,
				P.primer_nombre,
				P.segundo_nombre, 
				P.tipo_id_paciente,
				P.paciente_id,
				TO_CHAR(P.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento,
				P.sexo_id,
				P.residencia_direccion,
				P.tipo_dpto_id,
				P.tipo_mpio_id,
				P.residencia_telefono,
				CASE WHEN SE.condicion_accidentado IS NULL THEN '1' ELSE
					SE.condicion_accidentado END AS condicion_accidentado,
				SE.soat_naturaleza_evento_id AS evento,
				NULL AS otro_evento,
				AC.sitio_accidente,
				AC.fecha_accidente,
				AC.fecha_accidente as hora_accidente,
				AC.tipo_dpto_id AS dpto_accidente, 
				AC.tipo_mpio_id AS mpio_accidente,
				AC.zona,
				SE.asegurado,
				H.marca_vehiculo,
				H.placa_vehiculo,
				CASE WHEN SE.tipo_servicio_vehiculo_id = '0' THEN '3'
				     WHEN SE.tipo_servicio_vehiculo_id = '1' THEN '4'
				     WHEN SE.tipo_servicio_vehiculo_id = '2' THEN '5'
				     WHEN SE.tipo_servicio_vehiculo_id = '3' THEN '6'
				     WHEN SE.tipo_servicio_vehiculo_id = '4' THEN '7'
				     WHEN SE.tipo_servicio_vehiculo_id = '5' THEN '8'
				     WHEN SE.tipo_servicio_vehiculo_id = '6' THEN '9'
				     WHEN SE.tipo_servicio_vehiculo_id = '7' THEN '3'
				ELSE NULL END AS tipo_servicio_vehiculo_id,
				'AT'||TS.identificador_at AS identificador,
				H.poliza,
				H.vigencia_desde,
				H.vigencia_hasta,
				SE.intervension_autoridad,
				'0' AS cobro_excedente,
				NULL AS placa_segundo_vehiculo,
				NULL AS tipo_id_segundo,
				NULL AS documento_segundo,
				NULL AS placa_tercer_vehiculo,
				NULL AS tipo_id_tercer,
				NULL AS documento_tercer,
				J.tipo_id_propietario,
				J.propietario_id,
				J.apellidos_propietario as primer_apellido_propietario,
				J.apellidos_propietario as segundo_apellido_propietario,
				J.nombres_propietario as primer_nombre_propietario,
				J.nombres_propietario as segundo_nombre_propietario,
				J.direccion_propietario,
				J.telefono_propietario,
				K.tipo_dpto_id AS dpto_conductor,
				K.tipo_mpio_id AS mpio_conductor,
				K.apellidos_conductor as primer_apellido_conductor,
				K.apellidos_conductor as segundo_apellido_conductor,
				K.nombres_conductor as primer_nombre_conductor,
				K.nombres_conductor as segundo_nombre_conductor,
				K.tipo_id_conductor,
				K.conductor_id,
				K.direccion_conductor,
				J.tipo_dpto_id AS dpto_propietario,
				J.tipo_mpio_id AS mpio_propietario,
				K.telefono_conductor,
				CASE WHEN TRIM(SR.tipo_referencia) = 'R' THEN '1'
             WHEN TRIM(SR.tipo_referencia) = 'OS' THEN '2'
             ELSE NULL END AS tipo_referencia,
				SR.fecha_remision,
				SR.hora AS hora_salida,
				E.codigo_sgsss AS codigo_habilitacion,
				SR.nombre_profesional_remite,
				SR.profesional_remite_cargo,
				SR.fecha_recepcion_remision,
				SR.hora_recepcion_remision,
				TSG.codigo_sgsss AS codigo_sgsss_receptor,
				SR.nombre_profesional_recibe,
				SR.profesional_recibe_cargo,
				SA.placa_ambulancia,
				SA.lugar_desde,
				SA.lugar_hasta,
				SE.tipo_ambulancia_id AS tipo_servicio,
				SAC.zona,
				I.fecha_ingreso,
				I.fecha_ingreso AS hora_ingreso,
				SAM.fecha_egreso,
				SAM.hora_egreso,
				SAM.diagnostico_principal_ingreso_id,
				SAM.diagnostico1_ingreso_id,
				SAM.diagnostico2_ingreso_id	,
				SAM.diagnostico_principal_egreso_id,
				SAM.diagnostico1_egreso_id,
				SAM.diagnostico2_egreso_id,
				SAM.primer_apellido, 
				SAM.segundo_apellido, 
				SAM.primer_nombre, 
				SAM.segundo_nombre, 
				SAM.tipo_id_tercero,
				SAM.tercero_id,
				SAM.registro_medico,
				C.total_cuenta,
				C.valor_total_empresa,
				0 as total_transporte,
				0 as total_transporte_reclamado,
				".$folios." AS total_folios
				
			FROM 
				ingresos_soat AS ISO
				LEFT JOIN soat_remision AS SR ON (ISO.evento = SR.evento)
				LEFT JOIN centros_remision CR ON (SR.centro_remision = CR.centro_remision)
				LEFT JOIN terceros_sgsss AS TSG ON (CR.tipo_id_tercero = TSG.tipo_id_tercero
								AND CR.tercero_id = TSG.tercero_id),
				cuentas AS C,
				fac_facturas_cuentas AS FFC,
				envios_detalle AS ED,
				ingresos AS I,
				empresas AS E,
				pacientes AS P,
				soat_eventos SE
				LEFT JOIN soat_vehiculo_propietario AS J ON (SE.evento=J.evento)
				LEFT JOIN soat_vehiculo_conductor AS K ON (SE.evento=K.evento)
				LEFT JOIN soat_ambulancias AS SA ON (SE.evento = SA.evento),
				soat_accidente AS AC,
				soat_polizas AS H,
				terceros AS T LEFT JOIN 
        terceros_soat AS TS ON (T.tipo_id_tercero=TS.tipo_id_tercero
									AND T.tercero_id=TS.tercero_id),
				
				soat_accidente AS SAC,
				soat_atencion_medica_furips AS SAM
						
			WHERE ISO.ingreso = C.ingreso
			AND C.numerodecuenta = FFC.numerodecuenta
			AND FFC.empresa_id = ED.empresa_id
			AND FFC.prefijo = ED.prefijo
			AND FFC.factura_fiscal = ED.factura_fiscal
			AND ISO.ingreso = I.ingreso
			AND I.paciente_id = P.paciente_id
			AND I.tipo_id_paciente = P.tipo_id_paciente	
			AND E.empresa_id = ED.empresa_id
			AND ISO.evento=SE.evento
			AND AC.accidente_id = SE.accidente_id  
			AND SE.poliza=H.poliza
			AND H.tipo_id_tercero=T.tipo_id_tercero
			AND H.tercero_id=T.tercero_id
			AND SE.accidente_id = SAC.accidente_id
			AND SAM.evento = ISO.evento
			AND SAM.ingreso = ISO.evento
		
			AND ED.envio_id = ".$this->envio_id."
		)";
		
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error en la consulta";
			$this->mensajeDeError = $sql." ".$dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
			$this->fileError = __FILE__;
			$this->lineError = __LINE__;                                
			return false;
		}
		$this->arrayContenido = $resultado->GetRows();

	}//Fin cargarDatos
	
	/**
	 * No. Radicado Anterior (12)
	 * @param string str
	 */
	function formatoColumna1($str)
	{
		$valor = $this->_formatoCadena($str,12);
		//if(!$this->regla1($valor))
		//{
		//	$this->setErrorRegla("EL CAMPO \"Código del prestador de servicios de salud\" ES OBLIGATORIO");
		//	return false;
		//}
		return $valor;
	}//Fin formatoColumna1
	
	/**
	 * RG - Se debe diligenciar 1 en los casos de Respuesta a Pago Parcial y 0 para Glosa Total, si la reclamación es nueva se debe dejar nulo (2)
	 * @param string str
	 */
	function formatoColumna2($str)
	{
		$valor = $this->_formatoCadena($str,2);
		//if(!$this->regla1($valor))
		//{
		//	$this->setErrorRegla("EL CAMPO \"Tipo Identificaci�n de la v�ctima\" ES OBLIGATORIO");
		//	return false;
		//}
		//if(!$this->regla2($valor))
		//{
		//	$this->setErrorRegla("EL CAMPO \"Tipo Identificaci�n de la v�ctima\" TIENE UN VALOR DIFERENTE DE (CC,PA,RC,TI,AS,MS,UN)");
		//	return false;
		//}
		return $valor;
	}//Fin formatoColumna2
	
	/**
	 * Número factura  o  Número de cuenta de cobro. (20)
	 * @param string str
	 */
	function formatoColumna3($str)
	{
		$valor = $this->_formatoCadena($str,20);
		if(!$this->regla1($valor))
		{
			echo $this->setErrorRegla("EL CAMPO \"Número factura  o  Número de cuenta de cobro.\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna3
	
	/**
	 * Número consecutivo de la reclamación. (12) 
	 * @param string str
	 */
	function formatoColumna4($str)
	{
		$valor = $this->_formatoCadena($str,12);
		if(!$this->regla1($valor))
		{
			echo $this->setErrorRegla("EL CAMPO \"Número consecutivo de la reclamación\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna4
	
	/**
	 * Código de habilitación IPS (12)
	 * @param string str
	 */
	function formatoColumna5($str)
	{
		$valor = $this->_formatoCadena($str,12);
		return $valor;
	}//Fin formatoColumna5
	
	/**
	 * Primer apellido victima(20)
	 * @param string str
	 */
	function formatoColumna6($str)
	{
		$valor = $this->_formatoCadena($str,20);
		if(!$this->regla1($valor))
		{
			echo $this->setErrorRegla("EL CAMPO \"PRIMER APELLIDO DE LA VICTIMA\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna6
	
	/**
	 * Segundo apellido victima (30)
	 * @param string str
	 */
	function formatoColumna7($str)
	{
		$valor = $this->_formatoCadena($str,30);
		return $valor;
	}//Fin formatoColumna7
	
	/**
	 * Primer nombre victima (20)
	 * @param string edad
	 */
	function formatoColumna8($str)
	{
		//$edad1 = explode(" ",$edad);
		$valor = $this->_formatoCadena($str,20);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EDAD");
			return false;
		}*/
		if(!$this->regla1($valor))
		{
			echo $this->setErrorRegla("EL CAMPO \"PRIMER NOMBRE DE LA VICTIMA\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna8
	
	/**
	 * Segundo nombre victima (30)
	 * @param string str
	 */
	function formatoColumna9($str)
	{
		$valor = $this->_formatoCadena($str,30);
/*		if(!$this->regla3($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Unidad Edad\" TIENE UN VALOR DIFERENTE DE (1,2,3)");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna9
	
	/**
	 * Tipo documento victima (2)
	 * @param string str
	 */
	function formatoColumna10($str)
	{
		$valor = $this->_formatoCadena($str,2);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Sexo\" ES OBLIGATORIO");
			return false;
		}
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Sexo\" TIENE UN VALOR DIFERENTE DE (M,F)");
			return false;
		}*/
		if(!$this->regla1($valor))
		{
			echo $this->setErrorRegla("EL CAMPO \"Tipo documento\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna10
	
	/**
	 * Número documento victima (16)
	 * @param string str
	 */
	function formatoColumna11($str)
	{
		$valor = $this->_formatoCadena($str,16);
		if(!$this->regla1($valor))
		{
			echo $this->setErrorRegla("EL CAMPO \"Numero de documento\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna11
	
	/**
	 * Fecha de nacimiento (DD/MM/AAAA)(10)
	 * @param string str
	 */
	function formatoColumna12($fecha)
	{
		$valor = $this->_formatoFecha($fecha);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"C�digo departamento de residencia de la v�ctima\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna12
	
	/**
	 * Sexo (1)
	 * @param string str
	 */
	function formatoColumna13($str)
	{
		$valor = $this->_formatoCadena($str,1);
		if(!$this->regla1($valor))
		{
			echo $this->setErrorRegla("EL CAMPO \"SEXO\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna13
	
	/**
	 * Dirección residencia victima (40)
	 * @param string str
	 */
	function formatoColumna14($str)
	{
		$valor = $this->_formatoCadena($str,40);
/*		if(!$this->regla1($valor))
		{
			echo $this->setErrorRegla("EL CAMPO \"Direccion residencia de la victima\" ES OBLIGATORIO");
			echo "EL CAMPO \"Direccion residencia de la victima\" ES OBLIGATORIO";
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna14
	
	/**
	 * Código departamento ubicación victima (2)
	 * @param string str
	 */
	function formatoColumna15($str)
	{
		$valor = $this->_formatoCadena($str,2);
		//$this->naturalezaEvento = $valor;
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Naturaleza del Evento\" ES OBLIGATORIO");
			return false;
		}
		if(!$this->regla5($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Naturaleza del Evento\" DEBE TENER UN VALOR ENTRE 01 Y 14");
			return false;
		}*/
		if(!$this->regla1($valor))
		{
			echo $this->setErrorRegla("EL CAMPO \"Código departamento ubicación victima\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna15
	
	/**
	 * Código municipio ubicación victima (3)
	 * @param string str
	 */
	function formatoColumna16($str)
	{
		$valor = $this->_formatoCadena($str,3);
		if(!$this->regla1($valor))
		{
			echo $this->setErrorRegla("EL CAMPO \"Código municipio ubicación victima\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna16
	
	/**
	 * Teléfono victima (10)
	 *  @param date fecha
	 */
	function formatoColumna17($str)
	{
		//$valor = $this->_formatoFecha($fecha);
		$valor = $this->_formatoCadena($str,10);
		if(!$this->regla1($valor))
		{
			echo $this->setErrorRegla("EL CAMPO \"Teléfono victima\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna17
	
	/**
	 * Condición del accidentado (1)
	 * @param date fecha
	 */
	function formatoColumna18($str)
	{
		$valor = $this->_formatoCadena($str,1);
		if(!$this->regla1($valor))
		{
			echo $this->setErrorRegla("EL CAMPO \"Condición del accidentado\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna18
	
	/**
	 * Naturaleza evento (2)
	 * @param string str
	 */
	function formatoColumna19($str)
	{
		$valor = $this->_formatoCadena($str,2);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"C�digo departamento donde ocurrio el evento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna19
	
	/**
	 * Descripción de otro evento (25)
	 * @param string str
	 */
	function formatoColumna20($str)
	{
		$valor = $this->_formatoCadena($str,25);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"C�digo del municipio donde ocurrio el evento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna20
	
	/**
	 * Dirección ocurrencia (40)
	 *  @param string str
	 */
	function formatoColumna21($str)
	{
		$valor = $this->_formatoCadena($str,40);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Zona\" ES OBLIGATORIO");
			return false;
		}
		if(!$this->regla6($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Zona\" TIENE UN VALOR DIFERENTE DE (R y U)");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna21
	
	/**
	 * Fecha ocurrencia (DD/MM/AAAA)(10)
	 *  @param string str
	 */
	function formatoColumna22($fecha)
	{
		$valor = $this->_formatoFecha($fecha);
		//$valor = $this->_formatoCadena($str,255);
/*		if(!$this->regla7($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Informe del evento\" YA QUE LA NATURALEZA DEL EVENTO ES UN ACCIDENTE DE TRANSITO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Hora de ocurrencia HH:MM (5)
	 *  @param string str
	 */
	function formatoColumna23($fecha)
	{
		$valor = $this->_formatoHora($fecha);
		//$valor = $this->_formatoCadena($str,255);
/*		if(!$this->regla7($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Informe del evento\" YA QUE LA NATURALEZA DEL EVENTO ES UN ACCIDENTE DE TRANSITO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Código departamento (2)
	 *  @param string str
	 */
	function formatoColumna24($str)
	{
		$valor = $this->_formatoCadena($str,2);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Código municipio (3)
	 *  @param string str
	 */
	function formatoColumna25($str)
	{
		$valor = $this->_formatoCadena($str,3);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código municipio\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna22
	
	
	/**
	 * Zona (1)
	 *  @param string str
	 */
	function formatoColumna26($str)
	{
		$valor = $this->_formatoCadena($str,1);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Descripción  breve de la ocurrencia (255)
	 *  @param string str
	 */
	function formatoColumna27($str)
	{
		$valor = $this->_formatoCadena($str,255);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Estado de aseguramiento (1)
	 *  @param string str
	 */
	function formatoColumna28($str)
	{
		$valor = $this->_formatoCadena($str,1);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Marca (15)
	 *  @param string str
	 */
	function formatoColumna29($str)
	{
		$valor = $this->_formatoCadena($str,15);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Placa(6)
	 *  @param string str
	 */
	function formatoColumna30($str)
	{
		$valor = $this->_formatoCadena($str,6);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Tipo de vehiculo. (1)
	 *  @param string str
	 */
	function formatoColumna31($str)
	{
		$valor = $this->_formatoCadena($str,1);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Código de la aseguradora. (20)
	 *  @param string str
	 */
	function formatoColumna32($str)
	{
		$valor = $this->_formatoCadena($str,20);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Número de póliza SOAT(20)
	 *  @param string str
	 */
	function formatoColumna33($str)
	{
		$valor = $this->_formatoCadena($str,20);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Fecha de inicio de vigencia de la póliza  (DD/MM/AAAA)(10)
	 *  @param string str
	 */
	function formatoColumna34($str)
	{
		$valor = $this->_formatoFecha($str);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Fecha final de vigencia de la póliza  (DD/MM/AAAA)(10)
	 *  @param string str
	 */
	function formatoColumna35($str)
	{
		$valor = $this->_formatoFecha($str);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Intervención autoridad (1)
	 *  @param string str
	 */
	function formatoColumna36($str)
	{
		$valor = $this->_formatoCadena($str,1);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Cobro excedente póliza(1)
	 *  @param string str
	 */
	function formatoColumna37($str)
	{
		$valor = $this->_formatoCadena($str,1);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Placa del segundo vehículo involucrado(6)
	 *  @param string str
	 */
	function formatoColumna38($str)
	{
		$valor = $this->_formatoCadena($str,6);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Tipo de documento de identidad del  propietario del segundo vehículo involucrado(2)
	 *  @param string str
	 */
	function formatoColumna39($str)
	{
		$valor = $this->_formatoCadena($str,2);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Número documento  propietario del segundo vehículo involucrado (16)
	 *  @param string str
	 */
	function formatoColumna40($str)
	{
		$valor = $this->_formatoCadena($str,16);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Placa del tercer vehículo involucrado (6)
	 *  @param string str
	 */
	function formatoColumna41($str)
	{
		$valor = $this->_formatoCadena($str,6);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Tipo de documento de identidad del  propietario del tercer vehículo involucrado (2)
	 *  @param string str
	 */
	function formatoColumna42($str)
	{
		$valor = $this->_formatoCadena($str,2);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Número documento  propietario del tercer vehículo involucrado (16)
	 *  @param string str
	 */
	function formatoColumna43($str)
	{
		$valor = $this->_formatoCadena($str,16);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Tipo de documento de identidad del  propietario (2)
	 *  @param string str
	 */
	function formatoColumna44($str)
	{
		$valor = $this->_formatoCadena($str,2);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Número documento  propietario (16)
	 *  @param string str
	 */
	function formatoColumna45($str)
	{
		$valor = $this->_formatoCadena($str,16);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Primer apellido propietario o razón social en caso de empresa. (20)
	 *  @param string str
	 */
	function formatoColumna46($str)
	{
		$valor = $this->_formatoCadena($str,20);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Segundo apellido propietario (30)
	 *  @param string str
	 */
	function formatoColumna47($str)
	{
		$valor = $this->_formatoCadena($str,30);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Primer nombre propietario (20)
	 *  @param string str
	 */
	function formatoColumna48($str)
	{
		$valor = $this->_formatoCadena($str,20);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Segundo nombre propietario(30)
	 *  @param string str
	 */
	function formatoColumna49($str)
	{
		$valor = $this->_formatoCadena($str,30);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Dirección residencia propietario(40)
	 *  @param string str
	 */
	function formatoColumna50($str)
	{
		$valor = $this->_formatoCadena($str,40);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Teléfono del propietario (10)
	 *  @param string str
	 */
	function formatoColumna51($str)
	{
		$valor = $this->_formatoCadena($str,10);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Código departamento de residencia del propietario(2)
	 *  @param string str
	 */
	function formatoColumna52($str)
	{
		$valor = $this->_formatoCadena($str,2);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Código municipio de residencia del propietario(3)
	 *  @param string str
	 */
	function formatoColumna53($str)
	{
		$valor = $this->_formatoCadena($str,3);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Primer apellido conductor (20)
	 *  @param string str
	 */
	function formatoColumna54($str)
	{
		$valor = $this->_formatoCadena($str,20);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Segundo apellido conductor(30)
	 *  @param string str
	 */
	function formatoColumna55($str)
	{
		$valor = $this->_formatoCadena($str,30);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	

	/**
	 * Primer nombre conductor(20)
	 *  @param string str
	 */
	function formatoColumna56($str)
	{
		$valor = $this->_formatoCadena($str,20);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Segundo nombre conductor(30)
	 *  @param string str
	 */
	function formatoColumna57($str)
	{
		$valor = $this->_formatoCadena($str,30);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Tipo documento conductor(2)
	 *  @param string str
	 */
	function formatoColumna58($str)
	{
		$valor = $this->_formatoCadena($str,2);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Número documento conductor(16)
	 *  @param string str
	 */
	function formatoColumna59($str)
	{
		$valor = $this->_formatoCadena($str,16);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Dirección residencia conductor(40)
	 *  @param string str
	 */
	function formatoColumna60($str)
	{
		$valor = $this->_formatoCadena($str,40);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Código departamento de residencia del conductor(2)
	 *  @param string str
	 */
	function formatoColumna61($str)
	{
		$valor = $this->_formatoCadena($str,2);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Código municipio de residencia del conductor(3)
	 *  @param string str
	 */
	function formatoColumna62($str)
	{
		$valor = $this->_formatoCadena($str,3);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * Teléfono de residencia del conductor(10)
	 *  @param string str
	 */
	function formatoColumna63($str)
	{
		$valor = $this->_formatoCadena($str,10);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna63
	
	/**
	 * Tipo de Referencia (1)
	 *  @param string str
	 */
	function formatoColumna64($str)
	{
		$valor = $this->_formatoCadena($str,1);
    if($valor)
    {
      if(!$this->regla8($valor,array("1","2")))
      {
        $this->setErrorRegla("EL CAMPO \"Tipo de Referencia\" DEBE TENER UNO DE LOS SIGUIENTES VALORES: 1 , 2 ");
        return false;
      }
		}
		return $valor;
	}
	
	/**
	 * Fecha de Remisión - DD/MM/AAAA
	 *  @param string str
	 */
	function formatoColumna65($f)
	{
		$valor = $this->_formatoFecha($f);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
	
	/**
	 * Hora de salida HH:MM
	 * @param string str
	 */
	function formatoColumna66($h)
	{//echo $h; exit;
		$valor = $this->_formatoCadenaII($h,5);
		return $valor;
	}
	
	/**
	 * Código de habilitación IPS remitente (12)
	 *  @param string str
	 */
	function formatoColumna67($str)
	{
		$valor = $this->_formatoCadena($str,12);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
	
	/**
	 * Profesional que remite (60)
	 *  @param string str
	 */
	function formatoColumna68($str)
	{
		$valor = $this->_formatoCadena($str,60);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
				
	/**
	 * Cargo persona que remite (30)
	 *  @param string str
	 */
	function formatoColumna69($str)
	{
		$valor = $this->_formatoCadena($str,30);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
				
				
	/**
	 * Fecha de Aceptación DD/MM/AAAA
	 *  @param string str
	 */
	function formatoColumna70($fecha)
	{
		$valor = $this->_formatoFecha($fecha);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
				
	/**
	 * Hora de ingreso  HH:MM
	 *  @param string str
	 */
	function formatoColumna71($hora)
	{
		$valor = $this->_formatoCadenaII($hora,5);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
				
	/**
	 * Código de habilitación IPS receptor (12)
	 *  @param string str
	 */
	function formatoColumna72($str)
	{
		$valor = $this->_formatoCadena($str,12);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
				
	/**
	 * Profesional que recibe (60)
	 *  @param string str
	 */
	function formatoColumna73($str)
	{
		$valor = $this->_formatoCadena($str,60);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
				
	/**
	 * Cargo persona que recibe (30)
	 *  @param string str
	 */
	function formatoColumna74($str)
	{
		$valor = $this->_formatoCadena($str,30);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
	
	/**
	 * Número de placa (6)
	 *  @param string str
	 */
	function formatoColumna75($str)
	{
		$valor = $this->_formatoCadena($str,6);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
				
	/**
	 * Transportes de victimas desde (20)
	 *  @param string str
	 */
	function formatoColumna76($str)
	{
		$valor = $this->_formatoCadena($str,20);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
				
	/**
	 * Transporte victimas hasta (20)
	 *  @param string str
	 */
	function formatoColumna77($str)
	{
		$valor = $this->_formatoCadena($str,20);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
				
	/**
	 * Tipo de servicio (1)
	 *  @param string str
	 */
	function formatoColumna78($str)
	{
		$valor = $this->_formatoCadena($str,1);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
				
	/**
	 * Zona donde  recoge victima (1)
	 *  @param string str
	 */
	function formatoColumna79($str)
	{
		$valor = $this->_formatoCadena($str,1);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
	
	/**
	 * Fecha de ingreso (10) DD/MM/AAAA
	 *  @param string str
	 */
	function formatoColumna80($fecha)
	{
		$valor = $this->_formatoFecha($fecha);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
	
	/**
	 * Hora de ingreso (5) HH:MM
	 *  @param string str
	 */
	function formatoColumna81($hora)
	{
		$valor = $this->_formatoHora($hora);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
	
	/**
	 * Fecha de egreso DD/MM/AAAA
	 *  @param string str
	 */
	function formatoColumna82($fecha)
	{
		$valor = $this->_formatoFecha($fecha);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
				
	/**
	 * Hora de egreso (5) HH:MM
	 *  @param string str
	 */
	function formatoColumna83($hora)
	{
		$valor = $this->_formatoHora($hora);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
				
	/**
	 * Código diagnostico principal de ingreso (4)
	 *  @param string str
	 */
	function formatoColumna84($str)
	{
		if(empty($str))
		{
			$str = 'T140';
		}
		$valor = $this->_formatoCadena($str,4);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
				
	/**
	 * Otro Código Diagnóstico de ingreso (4)
	 *  @param string str
	 */
	function formatoColumna85($str)
	{
		$valor = $this->_formatoCadena($str,4);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}

	/**
	 * Otro Código Diagnóstico de ingreso (4)
	 *  @param string str
	 */
	function formatoColumna86($str)
	{
		$valor = $this->_formatoCadena($str,4);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
				
				
	/**
	 * Código diagnostico  principal de egreso (4)
	 *  @param string str
	 */
	function formatoColumna87($str)
	{
		if(empty($str))
		{
			$str = 'T140';
		}
		$valor = $this->_formatoCadena($str,4);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
				
	/**
	 * Otro Código Diagnóstico de egreso (4)
	 *  @param string str
	 */
	function formatoColumna88($str)
	{
		$valor = $this->_formatoCadena($str,4);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
	/**
	 * Otro Código Diagnóstico de egreso (4)
	 *  @param string str
	 */
	function formatoColumna89($str)
	{
		$valor = $this->_formatoCadena($str,4);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
	
	/**
	 * Primer apellido medico o profesional de la salud (20) 
	 *  @param string str
	 */
	function formatoColumna90($str)
	{
		$valor = $this->_formatoCadena($str,20);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
	
	/**
	 * Segundo apellido medico o profesional de la salud (30)
	 *  @param string str
	 */
	function formatoColumna91($str)
	{
		$valor = $this->_formatoCadena($str,30);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
	
	/**
	 * Primer nombre medico o profesional de la salud (20)
	 *  @param string str
	 */
	function formatoColumna92($str)
	{
		$valor = $this->_formatoCadena($str,20);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
	
	/**
	 * Segundo nombre medico o profesional de la salud (30)
	 *  @param string str
	 */
	function formatoColumna93($str)
	{
		$valor = $this->_formatoCadena($str,30);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
	
	/**
	 * Tipo documento medico o profesional de la salud (2)
	 *  @param string str
	 */
	function formatoColumna94($str)
	{
		$valor = $this->_formatoCadena($str,2);
		if(!$this->regla8($valor,array("CC","CE","PA")))
		{
			$this->setErrorRegla("EL CAMPO 93 \"Tipo documento medico o profesional de la salud\" DEBE TENER UNO DE LOS SIGUIENTES VALORES: CC,CE,PA ");
			return false;
		}
		return $valor;
	}
	
	/**
	 * Número documento medico o profesional de la salud (16)
	 *  @param string str
	 */
	function formatoColumna95($str)
	{
		$valor = $this->_formatoCadena($str,16);
/*		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}
	
	/**
	 * Número de registro medico (16)
	 *  @param string str
	 */
	function formatoColumna96($str)
	{
		$valor = $this->_formatoCadena($str,16);
		return $valor;
	}

	/**
	 * Total facturado amparo gastos medico quirúrgicos (15)
	 *  @param string str
	 */
	function formatoColumna97($str)
	{
		$valor = $this->_formatoNumero($str);
		return $valor;
	}

	/**
	 * Total reclamado amparo gastos medico quirúrgicos (15)
	 *  @param string str
	 */
	function formatoColumna98($str)
	{
		$valor = $this->_formatoNumero($str);
		return $valor;
	}

	/**
	 * Total facturado amparo gastos de transporte y movilización de la victima (15)
	 *  @param string str
	 */
	function formatoColumna99($str)
	{
		$valor = $this->_formatoNumero($str);
		return $valor;
	}
	
	/**
	 * Total reclamado amparo gastos de transporte y movilización de la victima (15)
	 *  @param string str
	 */
	function formatoColumna100($str)
	{
		$valor = $this->_formatoCadena($str,15);
		return $valor;
	}
	
	/**
	 * Total Folios 3
	 *  @param string str
	 */
	function formatoColumna101($str)
	{
		$valor = $this->_formatoCadena($str,15);
		return $valor;
	}

	/**
	 * Regla 1
	 * campo obligatorio
	 *
	 * @param mixed val
	 * @return bool
	 */
	function regla1($val)
	{
		return  $this->_reglaCampoObligatorio($val);
	}//regla1
	
	/**
	 * Regla 2
	 * Campo Obligatorio
	 * CC = C�dula de ciudadan�a
	 * CE= c�dual de extrabjeria PA=Pasaporte
	 * RC=registro Civil
	 * TI=Tarjeta de identidad
	 * AS= Adulto sin identificar
	 * MS=Menor sin identificar 
	 * UN= N�mero �nico de identificaci�n 
	 * Cuando la victima tenga como identificaci�n el n�mero de la historia cl�nica 
	 * se debe registrar como tipo de identificaci�n AS o MS
	 *
	 * @param string val
	 * @return bool
	 */
	function regla2($val)
	{
		$valoresValidos = array("CC","PA","RC","TI","AS","MS","UN");
		return $this->_reglaValorEnArreglo($val,$valoresValidos);
	}//Fin regla2
	
	/**
	 * Regla3
	 * 1= a�os
	 * 2= Meses
	 * 3= d�as.
	 *
	 * @param mixed val
	 * @return bool
	 */
	function regla3($val)
	{
		$valoresValidos = array(1,2,3);
		return $this->_reglaValorEnArreglo($val,$valoresValidos);
	}//Fin regla3
	
	/**
	 * Regla4
	 * Campo obligatorio
	 * M= Masculino
	 * F= Femenino
	 *
	 * @param mixed val
	 * @return bool
	 */
	function regla4($val)
	{
		$valoresValidos = array('M','F');
		return $this->_reglaValorEnArreglo($val,$valoresValidos);
	}//Fin regla3
	
	/**
	 * Campo Obligatorio.
	 * 01= Accidente de tr�nsito
	 * 02= Sismo
	 * 03= Maremoto 
	 * 04= Erupci�n volcanica
	 * 05= Deslizamiento Tierra
	 * 06= Inundaci�n
	 * 07= Avalancha
	 * 08= Incendio Natural
	 * 09= Explosi�n terrorista
	 * 10= Incendio Terrorista
	 * 11= combate
	 * 12= Toma Guerrillera
	 * 13= Masacre
	 * 14= Desplazados.
	 *
	 * @param mixed val
	 * @return bool
	 */
	function regla5($val)
	{
		$valoresValidos = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14");
		return $this->_reglaValorEnArreglo($val,$valoresValidos);
	}//Fin regla5
	
	/**
	 * Campo Obligatorio.
	 * R = Rural
	 * U= Urbano
	 *
	 * @param mixed val
	 * @return bool
	 */
	function regla6($val)
	{
		$valoresValidos = array('R','U');
		return $this->_reglaValorEnArreglo($val,$valoresValidos);
	}//Fin regla6
	
	/**
	 * Obligatorio en caso de accidente de transito.
	 *
	 * @param mixed val
	 * @return bool
	 */
	function regla7($val)
	{
		if($this->naturalezaEvento == "01")//01=>Accidente de tr�nsito
		{
			return $this->regla1($val);
		}
		return true;
	}
  /**
  * Campo Obligatorio. Valores definbidos en el arreglo
  *
  * @param mixed $val Valor a evaluar
  * @param array $valoresValidos Arreglo con el que se evaluara
  * @return bool
  */
	function regla8($val,$valoresValidos)
	{
		return $this->_reglaValorEnArreglo($val,$valoresValidos);
	}
}//Fin clase
?>