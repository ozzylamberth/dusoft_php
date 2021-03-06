

CREATE TABLE GruposFamiliaresPorMedico
(
    tipo_id_medico character varying(3) NOT NULL,
    medico_id character varying(32) NOT NULL,
    usuario_id integer NOT NULL,
    eps_afiliacion_id integer NOT NULL,
    tipo_id_cotizante character varying(3) NOT NULL,
    cotizante_id character varying(32) NOT NULL,
    fecha_registro date NOT NULL,
    usuario_registro integer NOT NULL
);


ALTER TABLE ONLY GruposFamiliaresPorMedico ADD CONSTRAINT GruposFamiliaresPorMedico_pkey PRIMARY KEY (eps_afiliacion_id,tipo_id_cotizante,cotizante_id);
ALTER TABLE ONLY GruposFamiliaresPorMedico ADD CONSTRAINT GruposFamiliaresPorMedico_medico_id_fkey FOREIGN KEY (tipo_id_medico,medico_id) REFERENCES profesionales(tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY GruposFamiliaresPorMedico ADD CONSTRAINT GruposFamiliaresPorMedico_cotizante_id_fkey FOREIGN KEY (eps_afiliacion_id,tipo_id_cotizante,cotizante_id) REFERENCES eps_afiliados_cotizantes(eps_afiliacion_id,afiliado_tipo_id, afiliado_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY GruposFamiliaresPorMedico ADD CONSTRAINT GruposFamiliaresPorMedico_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY GruposFamiliaresPorMedico ADD CONSTRAINT GruposFamiliaresPorMedico_usuario_registro_fkey FOREIGN KEY (usuario_registro) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

