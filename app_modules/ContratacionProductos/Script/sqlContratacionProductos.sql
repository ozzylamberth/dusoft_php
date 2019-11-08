
------/***************************** MODULO DE CONTRATACION  DE PRODUCTOS***********************************************/

----/********TABLAS   DEL MODULO ******/
CREATE TABLE userpermisos_contratacion_productos
(
    empresa_id character(2) NOT NULL,
    usuario_id integer NOT NULL,
    sw_activo character(1) DEFAULT '1'::bpchar NOT NULL
);
COMMENT ON TABLE userpermisos_contratacion_productos  IS 'Tabla donde se almacena los usuarios que tienen permiso para  acceder al modulo de contratacion';
COMMENT ON COLUMN userpermisos_contratacion_productos.empresa_id IS 'Id de la  Empresa';
COMMENT ON COLUMN userpermisos_contratacion_productos.usuario_id IS 'Id  del Usuario';
COMMENT ON COLUMN userpermisos_contratacion_productos.sw_activo IS 'Estado que indica si esta activo o no';


ALTER TABLE userpermisos_contratacion_productos ADD CONSTRAINT  userpermisos_contratacion_productos_pkey PRIMARY KEY (empresa_id, usuario_id);
ALTER TABLE ONLY  userpermisos_contratacion_productos ADD CONSTRAINT userpermisos_contratacion_productos_empresa_id_fkey FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id);
ALTER TABLE ONLY  userpermisos_contratacion_productos ADD CONSTRAINT  userpermisos_contratacion_productos_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id);


CREATE TABLE contratacion_produc_proveedor(
        contratacion_prod_id        SERIAL NOT NULL,
        empresa_id                CHARACTER(2) not null,
        No_Contrato                 CHARACTER VARYING(32) NOT NULL,
        Descripcion                CHARACTER VARYING(250) NOT NULL,
        Fecha_Inicio                    DATE  NOT NULL,
        Fecha_Vencimiento            DATE   NOT NULL,
        tipo_id_tercero                 CHARACTER VARYING(3) NOT NULL,
        tercero_id                      CHARACTER VARYING(32) NOT NULL,
        Condiciones_entrega             CHARACTER VARYING(100) NOT NULL,
        usuario_id                INTEGER NOT NULL,
        fecha_registro                TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
        observaciones                   TEXT, 
        estado                        CHARACTER(1) DEFAULT '1'::bpchar NOT NULL);

ALTER TABLE contratacion_produc_proveedor ADD PRIMARY KEY (contratacion_prod_id);
ALTER TABLE contratacion_produc_proveedor ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)
ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE contratacion_produc_proveedor ADD FOREIGN KEY (usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE contratacion_produc_proveedor ADD FOREIGN KEY (tipo_id_tercero, tercero_id) REFERENCES terceros(tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE contratacion_produc_proveedor  ADD 	codigo_proveedor_id INTEGER NOT NULL;
ALTER TABLE contratacion_produc_proveedor ADD FOREIGN KEY (codigo_proveedor_id) REFERENCES terceros_proveedores(codigo_proveedor_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE contratacion_produc_proveedor ADD  UNIQUE (no_contrato);

COMMENT ON TABLE contratacion_produc_proveedor  IS 'Tabla donde se almacena toda la informacion de los contratos de los productos con los diferentes proveedores';

COMMENT ON COLUMN contratacion_produc_proveedor.contratacion_prod_id IS 'Identificador del contrato';
COMMENT ON COLUMN contratacion_produc_proveedor.empresa_id IS 'id de la Empresa';
COMMENT ON COLUMN contratacion_produc_proveedor.No_Contrato IS 'Numero del contrato';
COMMENT ON COLUMN contratacion_produc_proveedor.Descripcion IS 'Descripcion del Contrato';
COMMENT ON COLUMN contratacion_produc_proveedor.Fecha_Inicio IS 'Fecha Inicial del contrato';
COMMENT ON COLUMN contratacion_produc_proveedor. Fecha_Vencimiento IS 'Fecha de Vencimiento del contrato';
COMMENT ON COLUMN contratacion_produc_proveedor. tipo_id_tercero IS 'Tipo de Identificacion del Proveedor';
COMMENT ON COLUMN contratacion_produc_proveedor.tercero_id  IS 'Numero de Identificacion del Proveedor';
COMMENT ON COLUMN contratacion_produc_proveedor. Condiciones_entrega IS 'Descripcion de las Condiciones del Tiempo de Entrega';
COMMENT ON COLUMN contratacion_produc_proveedor.usuario_id IS 'id del usuario que registrar';
COMMENT ON COLUMN contratacion_produc_proveedor. fecha_registro IS 'Fecha registro del contrato';
COMMENT ON COLUMN contratacion_produc_proveedor.observaciones IS 'Observaciones del contrato';
COMMENT ON COLUMN contratacion_produc_proveedor.estado IS 'Estado del contrato 1=>activo 0=>inactivo';
COMMENT ON COLUMN contratacion_produc_proveedor.codigo_proveedor_id IS 'Codigo del proveedor';


CREATE TABLE contratacion_produc_prov_detalle(
        contrato_produc_prov_det_id              SERIAL NOT NULL,
        empresa_id                               CHARACTER(2) not null,
        contratacion_prod_id                     integer Not null,     
        codigo_producto                          CHARACTER VARYING(30) NOT NULL,
        Precio                                   Numeric(15,2) not null,
        Valor_pactado                            Numeric(15,2),
        Valor_porcentaje                         Numeric(9,2),
        Valor_total_pactado                      Numeric(16,2) not null,
        usuario_id                               INTEGER NOT NULL,
        fecha_registro                           TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()
        );

ALTER TABLE contratacion_produc_prov_detalle ADD PRIMARY KEY (contrato_produc_prov_det_id );
ALTER TABLE contratacion_produc_prov_detalle ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE contratacion_produc_prov_detalle ADD FOREIGN KEY (contratacion_prod_id) REFERENCES contratacion_produc_proveedor(contratacion_prod_id)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE contratacion_produc_prov_detalle ADD FOREIGN KEY (codigo_producto) REFERENCES inventarios_productos(codigo_producto)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE contratacion_produc_prov_detalle ADD FOREIGN KEY (usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

CREATE TABLE tmp_contratacion_detalle(
        empresa_id                               CHARACTER(2) not null,
        contratacion_prod_id                     integer Not null,     
        codigo_producto                          CHARACTER VARYING(30) NOT NULL,
        Precio                                   Numeric(15,2) not null,
        Valor_pactado                            Numeric(15,2),
        Valor_porcentaje                         Numeric(9,2),
        Valor_total_pactado                      Numeric(16,2) not null,
        usuario_id                               INTEGER NOT NULL
        );

ALTER TABLE tmp_contratacion_detalle ADD PRIMARY KEY (empresa_id,contratacion_prod_id,codigo_producto);
ALTER TABLE tmp_contratacion_detalle ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE contratacion_produc_prov_detalle ADD FOREIGN KEY (codigo_producto) REFERENCES inventarios_productos(codigo_producto)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE contratacion_produc_prov_detalle ADD FOREIGN KEY (usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;



CREATE TABLE ESTADOS_CONTRATO(
        
        Estado_contrato_id          SERIAL NOT NULL,
        contratacion_prod_id         integer Not null,     
        empresa_id                   CHARACTER(2) not null,
        estado_actual                CHARACTER VARYING(1) NOT NULL, 
        usuario_id                   INTEGER NOT NULL,
        fecha_registro                TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
        observaciones                   TEXT Not null);

 
ALTER TABLE ESTADOS_CONTRATO ADD PRIMARY KEY (Estado_contrato_id);
ALTER TABLE ESTADOS_CONTRATO ADD FOREIGN KEY (contratacion_prod_id) REFERENCES contratacion_produc_proveedor(contratacion_prod_id)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ESTADOS_CONTRATO ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ESTADOS_CONTRATO ADD FOREIGN KEY (usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON COLUMN ESTADOS_CONTRATO.Estado_contrato_id IS 'Id Estados del contrato';
COMMENT ON COLUMN ESTADOS_CONTRATO.contratacion_prod_id IS '(FK) contratacion_produc_proveedor';
COMMENT ON COLUMN ESTADOS_CONTRATO.empresa_id IS 'Id de la  Empresa';
COMMENT ON COLUMN ESTADOS_CONTRATO.estado_actual IS 'Estado al que cambia el contrato';
COMMENT ON COLUMN ESTADOS_CONTRATO.usuario_id IS 'id del usuario que registrar';
COMMENT ON COLUMN ESTADOS_CONTRATO.fecha_registro IS 'Fecha registro';
COMMENT ON COLUMN ESTADOS_CONTRATO.observaciones IS 'observacion por el cambio de estado';

CREATE TABLE Contrato_prod_detalle_envios(
 
        Contrato_prod_detalle_envio_id           SERIAL NOT NULL,
        empresa_id                               CHARACTER(2) not null,
        contratacion_prod_id                     integer Not null, 
        tipo_producto_id                          CHARACTER VARYING(1) NOT NULL,
        Dias_envio                                Numeric(6) not null,
        usuario_id                                INTEGER NOT NULL,
        fecha_registro                            TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()
      );
ALTER TABLE Contrato_prod_detalle_envios ADD PRIMARY KEY (Contrato_prod_detalle_envio_id);
ALTER TABLE Contrato_prod_detalle_envios ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE  Contrato_prod_detalle_envios ADD FOREIGN KEY (contratacion_prod_id) REFERENCES contratacion_produc_proveedor(contratacion_prod_id)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE Contrato_prod_detalle_envios ADD FOREIGN KEY (tipo_producto_id) REFERENCES inv_tipo_producto(tipo_producto_id)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE Contrato_prod_detalle_envios ADD FOREIGN KEY (usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON COLUMN Contrato_prod_detalle_envios.Contrato_prod_detalle_envio_id IS '(PK) id  Contrato_prod_detalle_envios';
COMMENT ON COLUMN Contrato_prod_detalle_envios.contratacion_prod_id IS '(FK) contratacion_produc_proveedor';
COMMENT ON COLUMN Contrato_prod_detalle_envios.empresa_id IS 'Id de la  Empresa';
COMMENT ON COLUMN Contrato_prod_detalle_envios.tipo_producto_id IS '(FK)inv_tipo_producto ';
COMMENT ON COLUMN Contrato_prod_detalle_envios.Dias_envio IS 'numero de dias para el envio del producto';
COMMENT ON COLUMN Contrato_prod_detalle_envios.usuario_id IS 'id del usuario que registrar';
COMMENT ON COLUMN Contrato_prod_detalle_envios.fecha_registro IS 'Fecha registro';



CREATE TABLE contrato_proveed_politicas_vencimientos (
              
        Contrato_proveed_PolitVen_id           SERIAL NOT NULL,
        empresa_id                             CHARACTER(2) not null,
        tipo_id_tercero                        CHARACTER VARYING(3) NOT NULL,
        tercero_id                             CHARACTER VARYING(32) NOT NULL,
        contratacion_prod_id                     integer Not null,     
        tipo_producto_id                       CHARACTER VARYING(1) NOT NULL,
        politica_descripcion                   TEXT Not null,
        usuario_id                             INTEGER NOT NULL,
        fecha_registro                         TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()
        );


ALTER TABLE contrato_proveed_politicas_vencimientos ADD PRIMARY KEY (Contrato_proveed_PolitVen_id);
ALTER TABLE  contrato_proveed_politicas_vencimientos  ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE contrato_proveed_politicas_vencimientos  ADD FOREIGN KEY (tipo_id_tercero, tercero_id) REFERENCES terceros(tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE contrato_proveed_politicas_vencimientos ADD FOREIGN KEY (tipo_producto_id) REFERENCES inv_tipo_producto(tipo_producto_id)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE contrato_proveed_politicas_vencimientos ADD FOREIGN KEY (usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE  contrato_proveed_politicas_vencimientos ADD FOREIGN KEY (contratacion_prod_id) REFERENCES contratacion_produc_proveedor(contratacion_prod_id)
ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON COLUMN contrato_proveed_politicas_vencimientos.Contrato_proveed_PolitVen_id IS '(PK) id  contrato_proveed_politicas_vencimientos';
COMMENT ON COLUMN contrato_proveed_politicas_vencimientos.tipo_id_tercero IS '(FK) terceros';
COMMENT ON COLUMN contrato_proveed_politicas_vencimientos.tercero_id IS '(FK) terceros';
COMMENT ON COLUMN contrato_proveed_politicas_vencimientos.empresa_id IS 'Id de la  Empresa';
COMMENT ON COLUMN contrato_proveed_politicas_vencimientos.contratacion_prod_id IS 'numero de contrato';
COMMENT ON COLUMN contrato_proveed_politicas_vencimientos.tipo_producto_id IS '(FK)inv_tipo_producto ';
COMMENT ON COLUMN contrato_proveed_politicas_vencimientos.politica_descripcion IS 'Politicas de vencimiento';
COMMENT ON COLUMN contrato_proveed_politicas_vencimientos.usuario_id IS 'id del usuario que registrar';
COMMENT ON COLUMN contrato_proveed_politicas_vencimientos.fecha_registro IS 'Fecha registro';


Create table ARCHIVO
(
		codigo_archivo        SERIAL NOT NULL,
		empresa_id             CHARACTER(2) not null,
		tipo_id_tercero        CHARACTER VARYING(3) NOT NULL,
		tercero_id             CHARACTER VARYING(32) NOT NULL,
		archivo_nombre         character varying(255),
		archivo_peso           character varying(50),
		archivo_tipo           character varying(20),
		archivo_bytea          Bytea,
		usuario_id             INTEGER NOT NULL,
		fecha_registro         TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    primary key (codigo_archivo)
)   Without Oids;

ALTER TABLE ARCHIVO ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ARCHIVO ADD FOREIGN KEY (usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ARCHIVO ADD FOREIGN KEY (tipo_id_tercero, tercero_id) REFERENCES terceros(tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ARCHIVO ADD  UNIQUE (archivo_nombre);

COMMENT ON TABLE ARCHIVO  IS 'Tabla donde se almacenan las cartas de los proveedores';




/* EN CASO DE QUE SE COLOQUE LAS CONTRATOS ASOCIADOS A LA BODEGA*/

	CREATE TABLE Bodegas_Farmacia_Asoc_Formulas(
              Asociacion_id           SERIAL NOT NULL,
              farmacia_id            CHARACTER(2) not null,
              centro_utilidad        CHARACTER(2) not null,
              bodega                 CHARACTER(2) not null,
              plan_id       	        integer  not null,
              usuario_id                INTEGER NOT NULL,
              fecha_registro                TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()

       );
     
ALTER TABLE Bodegas_Farmacia_Asoc_Formulas ADD PRIMARY KEY (Asociacion_id);
ALTER TABLE Bodegas_Farmacia_Asoc_Formulas ADD FOREIGN KEY (farmacia_id) REFERENCES empresas(empresa_id)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE Bodegas_Farmacia_Asoc_Formulas ADD FOREIGN KEY (usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE Bodegas_Farmacia_Asoc_Formulas ADD FOREIGN KEY  (plan_id) REFERENCES planes(plan_id)


