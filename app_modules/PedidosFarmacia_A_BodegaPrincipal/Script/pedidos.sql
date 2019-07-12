
CREATE TABLE userpermisos_Pedidos_Farmacia_a_BPrincipal
(
    empresa_id character(2) NOT NULL,
    centro_utilidad 	 character(2) NOT NULL,
    usuario_id integer NOT NULL
  
);

ALTER TABLE userpermisos_Pedidos_Farmacia_a_BPrincipal add PRIMARY KEY (empresa_id, centro_utilidad, usuario_id);
ALTER TABLE userpermisos_Pedidos_Farmacia_a_BPrincipal add FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY  userpermisos_Pedidos_Farmacia_a_BPrincipal add FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON COLUMN userpermisos_Pedidos_Farmacia_a_BPrincipal.empresa_id IS 'Id de la Farmacia';
COMMENT ON COLUMN userpermisos_Pedidos_Farmacia_a_BPrincipal.centro_utilidad IS 'centro de utilidad de la empresa';
COMMENT ON COLUMN userpermisos_Pedidos_Farmacia_a_BPrincipal.usuario_id IS 'Id  del Usuario';

CREATE TABLE Solicitud_Pro_A_Bod_prpal_tmp(
        Soli_A_Bod_prpal_tmp_id          CHARACTER VARYING(30) NOT NULL,
        farmacia_id 	                 CHARACTER(2) 	NOT NULL,
        centro_utilidad                  character(2) NOT NULL,
        bodega     	                     character(2) NOT NULL,
        codigo_producto                  CHARACTER VARYING(30) NOT NULL,
        cantidad_Solic                   numeric(14)not null,  
		usuario_id                       INTEGER NOT NULL,
        Tipo_producto                    character varying(1) not null
             );

ALTER TABLE Solicitud_Pro_A_Bod_prpal_tmp  ADD PRIMARY KEY (Soli_A_Bod_prpal_tmp_id);
ALTER TABLE Solicitud_Pro_A_Bod_prpal_tmp  ADD  UNIQUE (codigo_producto);
COMMENT ON TABLE Solicitud_Pro_A_Bod_prpal_tmp  IS 'Tabla temporal para la solicitud de productos a bodega principal';
COMMENT ON COLUMN Solicitud_Pro_A_Bod_prpal_tmp.Soli_A_Bod_prpal_tmp_id IS '(PK)';
COMMENT ON COLUMN Solicitud_Pro_A_Bod_prpal_tmp.centro_utilidad IS 'centro de utilidad de la farmacia';
COMMENT ON COLUMN Solicitud_Pro_A_Bod_prpal_tmp.bodega IS 'Id  de la bodega de la farmacia';
COMMENT ON COLUMN Solicitud_Pro_A_Bod_prpal_tmp.farmacia_id IS 'Id de la Farmacia';
COMMENT ON COLUMN Solicitud_Pro_A_Bod_prpal_tmp.codigo_producto IS 'producto a solicitar';
COMMENT ON COLUMN Solicitud_Pro_A_Bod_prpal_tmp.cantidad_Solic IS 'cantidad a solcitar';
COMMENT ON COLUMN Solicitud_Pro_A_Bod_prpal_tmp.Tipo_producto IS 'tipo de producto ';


CREATE TABLE Solicitud_Productos_A_Bodega_principal(
			Solicitud_Prod_A_Bod_ppal_id     SERIAL NOT NULL,
			farmacia_id 	                CHARACTER(2) 	NOT NULL,
			centro_utilidad                character(2) NOT NULL,
			bodega     	            character(2) NOT NULL,
			observacion                   TEXT, 
			usuario_id                       INTEGER NOT NULL,
			fecha_registro   	     TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
			empresa_destino              character(2) NOT NULL,
			sw_despachado     character(1) default '0' NULL
    );

ALTER TABLE Solicitud_Productos_A_Bodega_principal  ADD SW_despacho character(1) DEFAULT '0'::bpchar ;
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal.SW_despacho IS '0=>pendiente por despachar 1=>despachado';
ALTER TABLE Solicitud_Productos_A_Bodega_principal  ADD PRIMARY KEY (Solicitud_Prod_A_Bod_ppal_id);
ALTER TABLE Solicitud_Productos_A_Bodega_principal  ADD FOREIGN KEY (farmacia_id, centro_utilidad, bodega) REFERENCES bodegas(empresa_id, centro_utilidad, bodega) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE Solicitud_Productos_A_Bodega_principal  ADD FOREIGN KEY(usuario_id) 
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON TABLE Solicitud_Productos_A_Bodega_principal  IS 'Tabla donde se genera la solicitud de productos a bodega principal';
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal.Solicitud_Prod_A_Bod_ppal_id IS '(PK)';
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal.farmacia_id IS 'Id de la Farmacia';
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal.centro_utilidad IS 'centro de utilidad de la farmacia';
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal.bodega IS 'Id  de la bodega de la farmacia';
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal.observacion IS 'observacion sobre el documento de pedido';
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal.usuario_id IS 'usuario que registra';
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal.fecha_registro IS 'fecha de registro';
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal.empresa_destino IS 'Empresa (bodega principal) ';


CREATE TABLE Solicitud_Productos_A_Bodega_principal_detalle(
			Solicitud_Prod_A_Bod_ppal_det_id     SERIAL NOT NULL,
			Solicitud_Prod_A_Bod_ppal_id      integer NOT NULL,
			farmacia_id 	                CHARACTER(2) 	NOT NULL,
			centro_utilidad                character(2) NOT NULL,
			bodega     	            character(2) NOT NULL,
			codigo_producto            CHARACTER VARYING(30) NOT NULL,
			cantidad_Solic             numeric(14)not null,  
			Tipo_producto              character varying(1) not null,
			usuario_id INTEGER NOT NULL,
			fecha_registro   	     TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
			fecha_vencimiento	date,
			lote	character varying(255),
			sw_pendiente	character(1)  default '0' null
	    );

ALTER TABLE Solicitud_Productos_A_Bodega_principal_detalle  ADD PRIMARY KEY (Solicitud_Prod_A_Bod_ppal_det_id);
ALTER TABLE Solicitud_Productos_A_Bodega_principal_detalle  ADD FOREIGN KEY (farmacia_id, centro_utilidad, bodega) REFERENCES bodegas(empresa_id, centro_utilidad, bodega) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE Solicitud_Productos_A_Bodega_principal_detalle   ADD FOREIGN KEY (Solicitud_Prod_A_Bod_ppal_id) REFERENCES Solicitud_Productos_A_Bodega_principal(Solicitud_Prod_A_Bod_ppal_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE Solicitud_Productos_A_Bodega_principal_detalle   ADD FOREIGN KEY (codigo_producto) REFERENCES inventarios_productos(codigo_producto)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE Solicitud_Productos_A_Bodega_principal_detalle   ADD FOREIGN KEY(usuario_id) 
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON TABLE Solicitud_Productos_A_Bodega_principal_detalle  IS 'Tabla donde se genera el detalle de  la solicitud de productos a bodega principal';
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal_detalle.Solicitud_Prod_A_Bod_ppal_det_id IS '(PK)';
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal_detalle.Solicitud_Prod_A_Bod_ppal_id IS '(FK) Solicitud_Productos_A_Bodega_principal';
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal_detalle.farmacia_id IS 'Id de la Farmacia';
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal_detalle.centro_utilidad IS 'centro de utilidad de la farmacia';
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal_detalle.bodega IS 'Id  de la bodega de la farmacia';
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal_detalle.codigo_producto IS '(FK) inventarios_productos';
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal_detalle.cantidad_Solic IS 'cantidad solicitada';
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal_detalle.Tipo_producto IS 'tipo de producto';
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal_detalle.usuario_id IS 'usuario que registra';
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal_detalle.fecha_registro IS 'fecha de registro';


CREATE TABLE solicitud_Bodega_principal_aux(
			farmacia_id 	                CHARACTER(2) 	NOT NULL,
			centro_utilidad                 character(2) NOT NULL,
			bodega     	                    character(2) NOT NULL,
			empresa_destino                 character(2) NOT NULL,
			centro_destino                  character(2) NOT NULL,
            bogega_destino                  character(2) NOT NULL,
			usuario_id                       INTEGER NOT NULL
			);
			
			
ALTER TABLE solicitud_Bodega_principal_aux  ADD PRIMARY KEY (farmacia_id,centro_utilidad,bodega,empresa_destino,centro_destino,bogega_destino,usuario_id);
ALTER TABLE solicitud_Bodega_principal_aux  ADD FOREIGN KEY (empresa_destino, centro_destino, bogega_destino) REFERENCES bodegas(empresa_id, centro_utilidad, bodega) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE solicitud_Bodega_principal_aux   ADD FOREIGN KEY(usuario_id) 
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE  solicitud_Bodega_principal_aux  IS 'Tabla auxiliar donde se tiene la empresa destino ';
COMMENT ON COLUMN solicitud_Bodega_principal_aux.farmacia_id IS '(PK)';
COMMENT ON COLUMN solicitud_Bodega_principal_aux.centro_utilidad IS '(PK)';
COMMENT ON COLUMN solicitud_Bodega_principal_aux.bodega IS '(PK)';
COMMENT ON COLUMN solicitud_Bodega_principal_aux.empresa_destino IS ' (PK)(FK) Bodega';
COMMENT ON COLUMN solicitud_Bodega_principal_aux.centro_destino IS '(PK)(FK) Bodega';
COMMENT ON COLUMN solicitud_Bodega_principal_aux.bogega_destino IS '(PK) (FK) Bodega';
COMMENT ON COLUMN solicitud_Bodega_principal_aux.usuario_id IS '(PK)(FK) Usuario Registra';


	





