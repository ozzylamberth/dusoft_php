/*  TABLA DONDE SE REGISTRAN LOS MOVIMIENTOS */

CREATE TABLE rotacion_producto_x_empresa
(
  empresa_id CHARACTER(2) NOT NULL,
  codigo_producto CHARACTER VARYING(50) NOT NULL,
  centro_utilidad CHARACTER(2) NOT NULL,
  bodega CHARACTER VARYING(2) NOT NULL,
  fecha DATE NOT NULL,
  cantidad_ingreso 	NUMERIC(9,2),
  cantidad_egreso NUMERIC(9,2),
  cantidad_inicial NUMERIC(9,2)
);

ALTER TABLE rotacion_producto_x_empresa ADD PRIMARY KEY(empresa_id,codigo_producto,centro_utilidad,bodega,fecha);
ALTER TABLE rotacion_producto_x_empresa ADD FOREIGN KEY (empresa_id, codigo_producto) 
REFERENCES inventarios(empresa_id, codigo_producto) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE rotacion_producto_x_empresa ADD FOREIGN KEY (empresa_id, centro_utilidad, bodega) 
REFERENCES bodegas(empresa_id, centro_utilidad, bodega) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE rotacion_producto_x_empresa IS 'Tabla donde se guarda la rotacion diaria de los productos de las bodegas';
COMMENT ON COLUMN rotacion_producto_x_empresa.empresa_id IS '(PK - FK) Identificadoe de la empresa';
COMMENT ON COLUMN rotacion_producto_x_empresa.codigo_producto IS '(PK - FK) Identificador del prodiucto';
COMMENT ON COLUMN rotacion_producto_x_empresa.centro_utilidad IS '(PK - FK) Centro de utilidda';
COMMENT ON COLUMN rotacion_producto_x_empresa.bodega IS '(PK - FK) bodega de movimiento';
COMMENT ON COLUMN rotacion_producto_x_empresa.fecha IS '(PK - FK) Fecha de la rotacion';
COMMENT ON COLUMN rotacion_producto_x_empresa.cantidad_ingreso IS 'Cantidad de ingreso en la fecha';
COMMENT ON COLUMN rotacion_producto_x_empresa.cantidad_egreso IS 'Cantidad de egreso en la fecha';
COMMENT ON COLUMN rotacion_producto_x_empresa.cantidad_inicial IS 'Cantidad de inicial en la fecha';

GRANT ALL ON TABLE rotacion_producto_x_empresa TO siis;

CREATE TABLE rotacion_producto_x_empresa_lf
(
  empresa_id CHARACTER(2) NOT NULL,
  codigo_producto CHARACTER VARYING(50) NOT NULL,
  centro_utilidad CHARACTER(2) NOT NULL,
  bodega CHARACTER VARYING(2) NOT NULL,
  lote CHARACTER VARYING(255) NOT NULL,
  fecha_vencimiento DATE NOT NULL,
  fecha DATE NOT NULL,
  cantidad_ingreso 	NUMERIC(9,2),
  cantidad_egreso NUMERIC(9,2),
  cantidad_inicial NUMERIC(9,2)
);

ALTER TABLE rotacion_producto_x_empresa_lf ADD PRIMARY KEY(empresa_id,codigo_producto,centro_utilidad,bodega,lote,fecha_vencimiento,fecha);
ALTER TABLE rotacion_producto_x_empresa_lf ADD FOREIGN KEY (empresa_id, codigo_producto) 
REFERENCES inventarios(empresa_id, codigo_producto) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE rotacion_producto_x_empresa_lf ADD FOREIGN KEY (empresa_id, centro_utilidad, bodega) 
REFERENCES bodegas(empresa_id, centro_utilidad, bodega) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE rotacion_producto_x_empresa_lf IS 'Tabla donde se guarda la rotacion diaria de los productos de las bodegas por lote y fecha de vencimiento';
COMMENT ON COLUMN rotacion_producto_x_empresa_lf.empresa_id IS '(PK - FK) Identificadoe de la empresa';
COMMENT ON COLUMN rotacion_producto_x_empresa_lf.codigo_producto IS '(PK - FK) Identificador del prodiucto';
COMMENT ON COLUMN rotacion_producto_x_empresa_lf.centro_utilidad IS '(PK - FK) Centro de utilidda';
COMMENT ON COLUMN rotacion_producto_x_empresa_lf.bodega IS '(PK - FK) bodega de movimiento';
COMMENT ON COLUMN rotacion_producto_x_empresa_lf.lote IS '(PK) Lote del producto';
COMMENT ON COLUMN rotacion_producto_x_empresa_lf.fecha_vencimiento IS '(PK) Fecha de vencimiento del producto';
COMMENT ON COLUMN rotacion_producto_x_empresa_lf.fecha IS '(PK - FK) Fecha de la rotacion';
COMMENT ON COLUMN rotacion_producto_x_empresa_lf.cantidad_ingreso IS 'Cantidad de ingreso en la fecha';
COMMENT ON COLUMN rotacion_producto_x_empresa_lf.cantidad_egreso IS 'Cantidad de egreso en la fecha';
COMMENT ON COLUMN rotacion_producto_x_empresa_lf.cantidad_inicial IS 'Cantidad de inicial en la fecha';
GRANT ALL ON TABLE rotacion_producto_x_empresa_lf TO siis;

/*     */

CREATE OR REPLACE FUNCTION actualizar_existencias() RETURNS TRIGGER AS $$
DECLARE
  Total NUMERIC;
  DATOS RECORD;
  DATOS1 RECORD;
  INGRESO_E NUMERIC(9,2);
  EGRESO_E NUMERIC(9,2);
  CODIGO_P CHARACTER VARYING(50);
  EMPRESA CHARACTER(2);
  BODEGA_I CHARACTER VARYING(2);
  CENTRO CHARACTER(2);
  INICIAL_E NUMERIC(9,2);
BEGIN
  IF TG_OP='INSERT' OR TG_OP='UPDATE' THEN
    Total:=(SELECT CASE WHEN SUM(existencia) IS NULL THEN 0 ELSE SUM(existencia) END FROM existencias_bodegas WHERE empresa_id=NEW.empresa_id AND codigo_producto=NEW.codigo_producto);
    UPDATE inventarios SET existencia=Total 
    WHERE empresa_id=NEW.empresa_id 
    AND codigo_producto=NEW.codigo_producto;
  END IF;  
  IF TG_OP = 'INSERT' THEN
    CODIGO_P := NEW.codigo_producto;
    EMPRESA :=  NEW.empresa_id;
    INGRESO_E := NEW.existencia;
    BODEGA_I := NEW.bodega;
    CENTRO := NEW.centro_utilidad;
    EGRESO_E := 0;
  ELSE
    CODIGO_P := OLD.codigo_producto;
    EMPRESA :=  OLD.empresa_id;
    BODEGA_I := OLD.bodega;
    CENTRO := OLD.centro_utilidad;
    IF NEW.existencia > OLD.existencia THEN
      INGRESO_E := NEW.existencia - OLD.existencia;
      EGRESO_E := 0;
    ELSE
      EGRESO_E :=  OLD.existencia - NEW.existencia;
      INGRESO_E := 0;
    END IF;
  END IF;
  
  SELECT * INTO DATOS
  FROM rotacion_producto_x_empresa
  WHERE  empresa_id = EMPRESA
  AND    codigo_producto = CODIGO_P
  AND    bodega = BODEGA_I
  AND    centro_utilidad = CENTRO
  AND    fecha = NOW()::date;
  
  SELECT * INTO DATOS1
  FROM rotacion_producto_x_empresa
  WHERE  empresa_id = EMPRESA
  AND    codigo_producto = CODIGO_P
  AND    bodega = BODEGA_I
  AND    centro_utilidad = CENTRO
  AND    fecha = NOW()::date-1;
  
  INICIAL_E := 0;
  IF DATOS1.fecha IS NOT NULL THEN
    INICIAL_E := DATOS1.cantidad_inicial + DATOS1.cantidad_ingreso - DATOS1.cantidad_egreso;
    IF INICIAL_E < 0 THEN
      INICIAL_E := 0;
    END IF;
  END IF;
  
  IF DATOS.fecha IS NOT NULL THEN
    UPDATE rotacion_producto_x_empresa
    SET    cantidad_ingreso = cantidad_ingreso + INGRESO_E,
           cantidad_egreso = cantidad_egreso + EGRESO_E,
           cantidad_inicial = INICIAL_E
    WHERE  empresa_id = DATOS.empresa_id
    AND    codigo_producto = DATOS.codigo_producto
    AND    bodega = DATOS.bodega
    AND    centro_utilidad = DATOS.centro_utilidad
    AND    fecha = DATOS.fecha;    
  ELSE
    INSERT INTO rotacion_producto_x_empresa
    (
      empresa_id ,
      codigo_producto,
      bodega,
      centro_utilidad,
      fecha ,
      cantidad_ingreso ,
      cantidad_egreso,
      cantidad_inicial
    )
    VALUES
    (
      EMPRESA,
      CODIGO_P,
      BODEGA_I,
      CENTRO,
      NOW()::date,
      INGRESO_E,
      EGRESO_E,
      INICIAL_E
    );
  END IF;
  RETURN NEW; 
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;


CREATE OR REPLACE FUNCTION actualizar_existencias_lotes() RETURNS TRIGGER AS $$
DECLARE
  Total NUMERIC;
  DATOS RECORD;
  DATOS1 RECORD;
  INGRESO_E NUMERIC(9,2);
  EGRESO_E NUMERIC(9,2);
  CODIGO_P CHARACTER VARYING(50);
  EMPRESA CHARACTER(2);
  BODEGA_I CHARACTER VARYING(2);
  CENTRO CHARACTER(2);
  INICIAL_E NUMERIC(9,2);
  LOTE_1 CHARACTER VARYING(255);
  FECHA_1 DATE;
BEGIN
  IF TG_OP = 'INSERT' THEN
    CODIGO_P := NEW.codigo_producto;
    EMPRESA :=  NEW.empresa_id;
    INGRESO_E := NEW.existencia_actual;
    BODEGA_I := NEW.bodega;
    CENTRO := NEW.centro_utilidad;
    LOTE_1 := NEW.lote;
    FECHA_1 := NEW.fecha_vencimiento;
    EGRESO_E := 0;
  ELSE
    CODIGO_P := OLD.codigo_producto;
    EMPRESA :=  OLD.empresa_id;
    BODEGA_I := OLD.bodega;
    CENTRO := OLD.centro_utilidad;
    LOTE_1 := OLD.lote;
    FECHA_1 := OLD.fecha_vencimiento;
    IF NEW.existencia_actual > OLD.existencia_actual THEN
      INGRESO_E := NEW.existencia_actual - OLD.existencia_actual;
      EGRESO_E := 0;
    ELSE
      EGRESO_E :=  OLD.existencia_actual - NEW.existencia_actual;
      INGRESO_E := 0;
    END IF;
  END IF;
  
  SELECT * INTO DATOS
  FROM rotacion_producto_x_empresa_lf
  WHERE  empresa_id = EMPRESA
  AND    codigo_producto = CODIGO_P
  AND    bodega = BODEGA_I
  AND    centro_utilidad = CENTRO
  AND    lote = LOTE_1
  AND    fecha_vencimiento = FECHA_1
  AND    fecha = NOW()::date;
  
  SELECT * INTO DATOS1
  FROM rotacion_producto_x_empresa_lf
  WHERE  empresa_id = EMPRESA
  AND    codigo_producto = CODIGO_P
  AND    bodega = BODEGA_I
  AND    centro_utilidad = CENTRO
  AND    lote = LOTE_1
  AND    fecha_vencimiento = FECHA_1
  AND    fecha = NOW()::date-1;
  
  INICIAL_E := 0;
  IF DATOS1.fecha IS NOT NULL THEN
    INICIAL_E := DATOS1.cantidad_inicial + DATOS1.cantidad_ingreso - DATOS1.cantidad_egreso;
    IF INICIAL_E < 0 THEN
      INICIAL_E := 0;
    END IF;
  END IF;
  
  IF DATOS.fecha IS NOT NULL THEN
    UPDATE rotacion_producto_x_empresa_lf
    SET    cantidad_ingreso = cantidad_ingreso + INGRESO_E,
           cantidad_egreso = cantidad_egreso + EGRESO_E,
           cantidad_inicial = cantidad_inicial + INICIAL_E
    WHERE  empresa_id = DATOS.empresa_id
    AND    codigo_producto = DATOS.codigo_producto
    AND    bodega = DATOS.bodega
    AND    centro_utilidad = DATOS.centro_utilidad
    AND    fecha = DATOS.fecha
    AND    lote = DATOS.lote
    AND    fecha_vencimiento = DATOS.fecha_vencimiento;
  ELSE
    INSERT INTO rotacion_producto_x_empresa_lf
    (
      empresa_id ,
      codigo_producto,
      bodega,
      centro_utilidad,
      lote,
      fecha_vencimiento,
      fecha ,
      cantidad_ingreso ,
      cantidad_egreso,
      cantidad_inicial
    )
    VALUES
    (
      EMPRESA,
      CODIGO_P,
      BODEGA_I,
      CENTRO,
      LOTE_1,
      FECHA_1,
      NOW()::date,
      INGRESO_E,
      EGRESO_E,
      INICIAL_E
    );
  END IF;
  RETURN NEW; 
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

CREATE TRIGGER actualizar_existencias_lotes AFTER INSERT OR UPDATE ON existencias_bodegas_lote_fv
FOR EACH ROW EXECUTE PROCEDURE actualizar_existencias_lotes();



--/***** PRE ORDEN POR ROTACION DE EMPRESAS************/
CREATE TABLE userpermisos_RotacionGerencia
(
empresa_id character(2) NOT NULL,
centro_utilidad 	 character(2) NOT NULL,
usuario_id integer NOT NULL

);
ALTER TABLE userpermisos_RotacionGerencia
ADD CONSTRAINT userpermisos_RotacionGerencia_pkey PRIMARY KEY (empresa_id, centro_utilidad, usuario_id);
ALTER TABLE ONLY userpermisos_RotacionGerencia
ADD CONSTRAINT userpermisos_RotacionGerencia_empresa_id_fkey FOREIGN KEY (empresa_id, centro_utilidad) REFERENCES centros_utilidad(empresa_id, centro_utilidad);
ALTER TABLE ONLY  userpermisos_RotacionGerencia
ADD CONSTRAINT  userpermisos_RotacionGerencia_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id);



COMMENT ON COLUMN userpermisos_RotacionGerencia.empresa_id IS 'Id Empresa (FK) ';
COMMENT ON COLUMN userpermisos_RotacionGerencia.centro_utilidad IS 'Id centro_utilidad(FK)  ';
COMMENT ON COLUMN userpermisos_RotacionGerencia.usuario_id IS 'Id Usuario Registra';




CREATE TABLE Proveedor_Compras_PreOrden(
			Proveedor_PreOrden_id                     CHARACTER VARYING(50) NOT NULL,
			codigo_proveedor_id                      integer NOT NULL,
			codigo_producto                          CHARACTER VARYING(30) NOT NULL,
			empresa_id                               CHARACTER(2) not null,  
			cantidad                                 numeric(14,0)not null,
			Valor_Unidad                             Numeric(16,2) not null,
			valor_Total_pactado                      Numeric(16,2) not null,
			usuario_id                               INTEGER NOT NULL,
			fecha_registro                           TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
			sw_preOrden                              CHARACTER(1) not null
        );

ALTER TABLE Proveedor_Compras_PreOrden  ADD PRIMARY KEY (Proveedor_PreOrden_id);
ALTER TABLE Proveedor_Compras_PreOrden ADD FOREIGN KEY (codigo_proveedor_id) REFERENCES terceros_proveedores(codigo_proveedor_id)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE Proveedor_Compras_PreOrden ADD FOREIGN KEY (empresa_id,codigo_producto) REFERENCES inventarios(empresa_id,codigo_producto)
ON UPDATE CASCADE ON DELETE RESTRICT; 
ALTER TABLE Proveedor_Compras_PreOrden  ADD FOREIGN KEY (usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;



COMMENT ON COLUMN Proveedor_Compras_PreOrden.Proveedor_PreOrden_id IS 'PK ';
COMMENT ON COLUMN Proveedor_Compras_PreOrden.codigo_proveedor_id IS '(FK) Terceros_proveedores ';
COMMENT ON COLUMN Proveedor_Compras_PreOrden.codigo_producto IS '(FK) Inventarios ';
COMMENT ON COLUMN Proveedor_Compras_PreOrden.empresa_id IS '(FK)  Empresas ';
COMMENT ON COLUMN Proveedor_Compras_PreOrden.cantidad IS 'cantidad solcitada ';
COMMENT ON COLUMN Proveedor_Compras_PreOrden.Valor_Unidad IS 'Valor Unitario del Producto';
COMMENT ON COLUMN Proveedor_Compras_PreOrden.Valor_Unidad IS 'Valor Total del Producto';
COMMENT ON COLUMN Proveedor_Compras_PreOrden.usuario_id IS 'Id Usuario Registra';
COMMENT ON COLUMN Proveedor_Compras_PreOrden.fecha_registro IS 'Fecha de Registro';
COMMENT ON COLUMN Proveedor_Compras_PreOrden.sw_preOrden IS 'Indica si es pre-orden';


CREATE TABLE  informacion_preorden  -----ROTACION
    (
      preorden_id                            SERIAL NOT NULL,  
      farmacia_id                            CHARACTER(2) not null, 
      observacion                            TEXT,
      usuario_id                             INTEGER NOT NULL,
      fecha_registro                         TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
      sw_preorden                            CHARACTER(2) not null
    );

ALTER TABLE informacion_preorden add FOREIGN KEY (farmacia_id) REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE informacion_preorden add PRIMARY KEY (preorden_id);
ALTER TABLE informacion_preorden add FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
 
 
COMMENT ON COLUMN informacion_preorden.preorden_id IS 'PK ';
COMMENT ON COLUMN informacion_preorden.farmacia_id IS '(FK)  Empresas ';
COMMENT ON COLUMN informacion_preorden.observacion IS 'Observacion al generar la pre-orden';
COMMENT ON COLUMN informacion_preorden.fecha_registro IS 'Fecha de Registro';
COMMENT ON COLUMN Proveedor_Compras_PreOrden.sw_preOrden IS 'Indica si es pre-orden ';
  
 
 CREATE  TABLE informacion_preorden_detalle   
   (
       preorden_detalle_id        SERIAL NOT NULL,
       preorden_id                INTEGER NOT NULL,
       codigo_proveedor_id        INTEGER NOT NULL,
       codigo_producto            CHARACTER VARYING(30) NOT NULL,
       cantidad                   	numeric(14,4)  NOT NULL,
       valor_total_pactado         numeric(14,4) NOT NULL,
       usuario_id                  INTEGER NOT NULL,
       fecha_registro              TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
       sw_unificada 	           character varying(1) DEFAULT '0'::bpchar NOT NULL
	 );
	 
ALTER TABLE informacion_preorden_detalle add FOREIGN KEY (codigo_producto) REFERENCES inventarios_productos(codigo_producto) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE informacion_preorden_detalle add FOREIGN KEY (codigo_proveedor_id) REFERENCES terceros_proveedores(codigo_proveedor_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE informacion_preorden_detalle add PRIMARY KEY (preorden_detalle_id);
ALTER TABLE informacion_preorden_detalle add FOREIGN KEY (preorden_id) REFERENCES informacion_preorden(preorden_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE informacion_preorden_detalle add FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

AlTER TABLE informacion_preorden_detalle ADD valor_unitario numeric(9,2) null;



COMMENT ON COLUMN informacion_preorden_detalle.preorden_detalle_id IS 'PK ';
COMMENT ON COLUMN informacion_preorden_detalle.preorden_id IS '(FK) informacion_preorden ';
COMMENT ON COLUMN informacion_preorden_detalle.codigo_proveedor_id IS '(FK) Codigo del proveedor ';
COMMENT ON COLUMN informacion_preorden_detalle.codigo_producto IS '(FK) inventarios_productos ';
COMMENT ON COLUMN informacion_preorden_detalle.cantidad IS 'cantidad solcitada ';
COMMENT ON COLUMN informacion_preorden_detalle.valor_total_pactado IS 'Valor Total del Producto';
COMMENT ON COLUMN informacion_preorden_detalle.usuario_id IS 'Id Usuario Registra';
COMMENT ON COLUMN informacion_preorden_detalle.fecha_registro IS 'Fecha de Registro';
COMMENT ON COLUMN informacion_preorden_detalle.sw_preOrden IS 'Indica si es pre-orden';



/*  nueva9*/

CREATE  TABLE solicitud_gerencia
   (
       solictud_gerencia_id        SERIAL NOT NULL,
       empresa_id                   CHARACTER(2) not null, 
	   centro_utilidad              CHARACTER(2) not null, 
	   bodega                       CHARACTER(2) not null, 
       codigo_producto              CHARACTER VARYING(30) NOT NULL,
       cantidad                   	numeric(17,2)  NOT NULL,
       usuario_id                  INTEGER NOT NULL,
       fecha_registro              TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
       estado 	                   character varying(1) DEFAULT '0'::bpchar NOT NULL
	 );
ALTER TABLE solicitud_gerencia add PRIMARY KEY (solictud_gerencia_id);
ALTER TABLE solicitud_gerencia add FOREIGN KEY (empresa_id, centro_utilidad, bodega) REFERENCES bodegas(empresa_id, centro_utilidad, bodega) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE solicitud_gerencia add FOREIGN KEY (codigo_producto) REFERENCES inventarios_productos(codigo_producto) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE solicitud_gerencia add FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
