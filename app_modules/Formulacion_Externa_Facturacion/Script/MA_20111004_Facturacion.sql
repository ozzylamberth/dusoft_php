  ALTER TABLE public.userpermisos_formulacion_externa_facturacion
  ADD COLUMN sw_auditoria char NOT NULL DEFAULT 0;

COMMENT ON COLUMN public.userpermisos_formulacion_externa_facturacion.sw_auditoria
  IS 'Define si el usuario que Ingresa al Modulo, puede Auditar los Cortes de las Farmacias';

ALTER TABLE public.ff_cortes_detalle
  ADD COLUMN empresa_factura char(2);

COMMENT ON COLUMN public.ff_cortes_detalle.empresa_factura
  IS 'Empresa que Genera la Factura de las formulas';

  
  ALTER TABLE public.ff_cortes_detalle
  ADD COLUMN prefijo varchar(4);

COMMENT ON COLUMN public.ff_cortes_detalle.prefijo
  IS 'Prefijo de la factura Fiscal';

  ALTER TABLE public.ff_cortes_detalle
  ADD COLUMN factura_fiscal integer;

COMMENT ON COLUMN public.ff_cortes_detalle.factura_fiscal
  IS 'Numero de la factura';

  ALTER TABLE public.ff_cortes_detalle
  ADD CONSTRAINT foreign_key05
  FOREIGN KEY (empresa_factura, prefijo, factura_fiscal)
    REFERENCES public.fac_facturas(empresa_id, prefijo, factura_fiscal)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;

	
CREATE VIEW public.cortes_sin_facturar
(
corte_general_id,
empresa_id,
centro_utilidad,
lapso,
descripcion,
estado_corte_lapso,
tipo_pais_id,
tipo_dpto_id,
tipo_mpio_id,
auditado,
no_auditado,
faltan_facturar
)
AS
SELECT
a.corte_general_id,
a.empresa_id,
a.centro_utilidad,
c.lapso,
b.descripcion,
c.estado as estado_corte_lapso,
b.tipo_pais_id,
b.tipo_dpto_id,
b.tipo_mpio_id,
round(d.auditado) as auditado,
round(d.no_auditado) as no_auditado,
round(e.cantidad) as faltan_facturar
FROM
ff_cortes_generales as a
JOIN centros_utilidad as b ON (a.empresa_id = b.empresa_id)
AND (a.centro_utilidad = b.centro_utilidad)
JOIN ff_cortes_mensual as c ON (a.corte_general_id = c.corte_general_id)
AND (a.empresa_id = c.empresa_id)
AND (a.centro_utilidad = c.centro_utilidad)
LEFT JOIN (
				SELECT 
				x.empresa_id, 	
				x.centro_utilidad, 	
				x.lapso,
				AVG(y.cantidad) as auditado,
				AVG(z.cantidad) as no_auditado
				FROM
					ff_cortes AS x
					LEFT JOIN (
										SELECT
											empresa_id,
											centro_utilidad,
											lapso,
											count(*) as cantidad
										FROM
											ff_cortes
										WHERE TRUE
										AND corte_auditado = '1'
										GROUP BY 1,2,3
										)as y ON (x.empresa_id = y.empresa_id)
										AND (x.centro_utilidad = y.centro_utilidad)
										AND (x.lapso = y.lapso)
							LEFT JOIN (
										SELECT
											empresa_id,
											centro_utilidad,
											lapso,
											count(*) as cantidad
										FROM
											ff_cortes
										WHERE TRUE
											AND corte_auditado = '0'
											GROUP BY 1,2,3
											)as z ON (x.empresa_id = z.empresa_id)
				AND (x.centro_utilidad = z.centro_utilidad)
				AND (x.lapso = z.lapso)
				GROUP BY 1,2,3
				)as d ON (c.empresa_id = d.empresa_id)
				AND (c.centro_utilidad = d.centro_utilidad)
				AND (c.lapso = d.lapso)
LEFT JOIN (
				SELECT
				empresa_id,
				centro_utilidad,
				lapso,
				count(*) as cantidad
				FROM 
				ff_cortes_detalle
				WHERE TRUE
				AND empresa_factura IS NULL
				GROUP BY 1,2,3
			) as e ON (d.empresa_id = e.empresa_id)
			AND (d.centro_utilidad = e.centro_utilidad)
			AND (d.lapso = e.lapso)
WHERE TRUE;

ALTER TABLE public.cortes_sin_facturar
  OWNER TO "admin";

  
  ALTER TABLE public.userpermisos_formulacion_externa_facturacion
  DROP CONSTRAINT userpermisos_formulacion_externa_facturacion_pkey;

ALTER TABLE public.userpermisos_formulacion_externa_facturacion
  ADD CONSTRAINT userpermisos_formulacion_externa_facturacion_pkey
  PRIMARY KEY (empresa_id, usuario_id, centro_utilidad)
    WITH (FILLFACTOR = 100);

