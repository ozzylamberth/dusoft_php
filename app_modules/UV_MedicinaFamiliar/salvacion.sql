
///////////sql para traer los medicos

SELECT
a.nombre_tercero as nombre,
a.tercero_id,
a.tipo_id_tercero
FROM
(
 SELECT
 DISTINCT(d.nombre_tercero),
 b.tercero_id ,
 b.tipo_id_tercero,
 c.estado
 from agenda_turnos as a
    left join profesionales_estado as c
        on (a.profesional_id=c.tercero_id
            and a.tipo_id_profesional=c.tipo_id_tercero
            and c.empresa_id='01'),
    profesionales as b,
    terceros as d
    WHERE
    
    and a.empresa_id='01'
    and a.profesional_id=b.tercero_id
    and a.tipo_id_profesional=d.tipo_id_tercero
    and a.profesional_id=d.tercero_id
    and a.tipo_id_profesional=b.tipo_id_tercero
    
) as a
    where a.estado is null or a.estado=1 order by a.nombre_tercero;