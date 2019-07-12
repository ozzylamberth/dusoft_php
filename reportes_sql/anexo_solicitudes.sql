SELECT  CU.sede,
        HS.hc_os_solicitud_id AS solicitud,
        CU.paciente_documento_id,
        CU.paciente_identificacion,
        CU.nombres_paciente||' '||CU.apellidos_paciente AS paciente,
        CU.fecha_nacimiento,
        CU.edad::text||' A' AS paciente_edad,
        CU.paciente_sexo,
        CU.paciente_identificacion AS paciente_historia,
        CASE WHEN CU.tarjeta_profesional <> '' THEN 'TP'
             ELSE ' ' END AS medico_id_registro,
        CU.tarjeta_profesional AS medico_registro,
        CU.medico_id_documento,
        CU.medico_documento,
        '-' AS medico_abreviatura,
        CU.medico,
        CU.medico_especialidad,
        'JORNADA - EL PRADO' AS CCostos_Solicitante,
        CU.entidad,
        '1' AS Entregas,
        '1' AS Entrega,
        '1' AS Item,
        CU.centro_costo AS CCostosSolicitado,
        '-' AS ResponsableSolicitado,
        CP.cargo AS producto,
        CP.descripcion,
        OT.descripcion AS tipo_producto,
        HS.cantidad,
        '-' AS unidad,
        '-' AS dosis_texto,
        'SOLICITUDES' AS informe_titulo,	
        'Caducidad tres (3) días calendario para MEDICAMENTOS-toda alteraci¢n o enmendadura anula la fórmula.' AS Informe_Pie,	
        ' ' AS CLIENTE_PRIMERAPELLIDO	,
        ' ' AS CLIENTE_SEGUNDOAPELLIDO	, 
        ' ' AS CLIENTE_PRIMERNOMBRE,
        ' ' AS CLIENTE_SEGUNDONOMBRE,	 
        CU.direccion_residencia_paciente,
        CU.telefono_residencia_paciente,
        CU.ciudad,
        CU.dpto,
        CU.diagnostico_egreso_nombre,
        CU.diagnostico_egr_cod,
        'Servicios electivos' AS ServicioSolicitado,
        '10' AS Dia_R
FROM    agenda_citas_asignadas AA,
        os_cruce_citas CC,
        os_maestro OS,
        os_ordenes_servicios OO,
        hc_os_solicitudes HS,
        os_tipos_solicitudes OT,
        cups CP,
        (
          SELECT  EM.razon_social AS sede,
                  HE.evolucion_id AS autorizacion,
                  TO_CHAR(HE.fecha,'DD/MM/YYYY HH:MI') AS fecha,
                  PA.tipo_id_paciente AS paciente_documento_id,
                  PA.paciente_id AS paciente_identificacion,
                  PA.primer_nombre||' '||PA.segundo_nombre AS nombres_paciente,
                  PA.primer_apellido||' '||PA.segundo_apellido AS apellidos_paciente,
                  TO_CHAR(PA.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento,
                  edad(PA.fecha_nacimiento) AS edad,
                  PA.sexo_id AS paciente_sexo,
                  PA.residencia_direccion AS direccion_residencia_paciente,
                  PA.residencia_telefono AS telefono_residencia_paciente,
                  PA.tipo_mpio_id AS ciudad,
                  PA.tipo_dpto_id AS dpto,
                  PR.tarjeta_profesional,
                  PR.tipo_id_tercero AS medico_id_documento,
                  PR.tercero_id AS medico_documento,
                  PR.nombre AS medico, 
                  ES.descripcion AS medico_especialidad,
                  PL.plan_descripcion AS entidad,
                  DE.descripcion AS centro_costo,
                  CASE WHEN DG.diagnostico_nombre IS NULL THEN 'SIN DIAGNOSTICO'
                       ELSE DG.diagnostico_nombre END AS diagnostico_egreso_nombre,
                  CASE WHEN DG.diagnostico_id IS NULL THEN '-'
                       ELSE DG.diagnostico_id END AS diagnostico_egr_cod,
                  CU.numerodecuenta
        FROM    cuentas CU,
                planes PL,
                ingresos IG,
                pacientes PA,
                profesionales_usuarios PU,
                profesionales PR,
                profesionales_especialidades PE,
                especialidades ES,
                departamentos DE,
                empresas EM,
                hc_evoluciones HE LEFT JOIN
                (
                  SELECT  DE.evolucion_id,
                          DG.diagnostico_nombre,
                          DG.diagnostico_id
                  FROM    hc_diagnosticos_egreso DE,
                          diagnosticos DG,
                          hc_evoluciones HE
                  WHERE   DE.sw_principal = '1'
                  AND     DE.tipo_diagnostico_id = DG.diagnostico_id
                  AND     HE.evolucion_id = DE.evolucion_id 
                ) AS DG
                ON(DG.evolucion_id = HE.evolucion_id)
        WHERE   CU.plan_id = PL.plan_id
        AND     CU.ingreso = IG.ingreso
        AND     IG.tipo_id_paciente = PA.tipo_id_paciente
        AND     IG.paciente_id = PA.paciente_id
        AND     IG.ingreso = HE.ingreso
        AND     HE.departamento = DE.departamento
        AND     DE.empresa_id = EM.empresa_id
        AND     HE.usuario_id = PU.usuario_id
        AND     PU.tipo_tercero_id = PR.tipo_id_tercero
        AND		  PU.tercero_id = PR.tercero_id
        AND     PR.tipo_id_tercero = PE.tipo_id_tercero
        AND	    PR.tercero_id = PE.tercero_id
        AND     PE.especialidad = ES.especialidad
        AND     IG.fecha_ingreso::date >= _1
        AND     IG.fecha_ingreso::date <= _2
        ) CU
WHERE   AA.agenda_cita_asignada_id = CC.agenda_cita_asignada_id
AND     CC.numero_orden_id = OS.numero_orden_id
AND     OS.numerodecuenta = CU.numerodecuenta
AND     OS.orden_servicio_id = OO.orden_servicio_id
AND     OS.hc_os_solicitud_id = HS.hc_os_solicitud_id
AND     HS.os_tipo_solicitud_id = OT.os_tipo_solicitud_id
AND     HS.cargo = CP.cargo
ORDER BY CU.fecha