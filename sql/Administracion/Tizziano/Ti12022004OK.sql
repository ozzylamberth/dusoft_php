ALTER TABLE "hc_evolucion_descripcion" ADD COLUMN "sw_epicrisis" "char";
UPDATE "hc_evolucion_descripcion" SET "sw_epicrisis"= '0'; 
ALTER TABLE "hc_evolucion_descripcion" ALTER COLUMN "sw_epicrisis" SET NOT NULL;
ALTER TABLE "hc_evolucion_descripcion" ALTER COLUMN "sw_epicrisis" SET DEFAULT '0';

CREATE TABLE hc_diagnosticos_medicos (
    diagnosticos_medicos_id serial NOT NULL,
    descripcion text NOT NULL,
    evolucion_id integer NOT NULL,
    ingreso integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    sw_estado "char" DEFAULT '0'::"char" NOT NULL
);


ALTER TABLE hc_diagnosticos_medicos
    ADD PRIMARY KEY (diagnosticos_medicos_id);

ALTER TABLE hc_diagnosticos_medicos
    ADD FOREIGN KEY (evolucion_id) REFERENCES hc_evoluciones(evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE hc_diagnosticos_medicos
    ADD FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE hc_diagnosticos_medicos
    ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE hc_diagnosticos_medicos IS 'Descripciones adicionales de los diagnosticos medicos';

COMMENT ON COLUMN hc_diagnosticos_medicos.diagnosticos_medicos_id IS 'Llave de la tabla';

COMMENT ON COLUMN hc_diagnosticos_medicos.descripcion IS 'Argumentos de los diagnosticos';

COMMENT ON COLUMN hc_diagnosticos_medicos.evolucion_id IS 'Identificador de la Evolucion';

COMMENT ON COLUMN hc_diagnosticos_medicos.ingreso IS 'Indentificador del Ingreso';

COMMENT ON COLUMN hc_diagnosticos_medicos.usuario_id IS 'Identificador del Usuario';

COMMENT ON COLUMN hc_diagnosticos_medicos.fecha_registro IS 'Indicador de fecha de adicion de nuevo registro';

COMMENT ON COLUMN hc_diagnosticos_medicos.sw_estado IS 'Switche de estado del diagnostico';


INSERT INTO system_hc_submodulos VALUES ('DiagnosticosMedicos', 'Diagnosticos Medicos', 1.00, '', '1', NULL, NULL, NULL, NULL, '0');


INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'DiagnosticosMedicos', 22, 0, '1', '1', '0', '0');

