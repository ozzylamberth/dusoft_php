---------------------------------------hc_tipos_accion_preventiva--------------------------------------------------------
CREATE TABLE hc_tipos_accion_preventiva (
    tipo_accion_id serial NOT NULL,
    nombre character varying(200) NOT NULL
);
ALTER TABLE ONLY hc_tipos_accion_preventiva
    ADD PRIMARY KEY (tipo_accion_id);
COMMENT ON TABLE hc_tipos_accion_preventiva IS 'Maestro de Accion Preventiva';
COMMENT ON COLUMN hc_tipos_accion_preventiva.tipo_accion_id IS 'PK Identificador de la accion preventiva';
COMMENT ON COLUMN hc_tipos_accion_preventiva.nombre IS 'Descripcion de la accion preventiva';

---------------------------------------hc_accion_preventiva-------------------------------------------------------------
CREATE TABLE hc_accion_preventiva (
    hc_accion_preventiva_id serial NOT NULL,
    evolucion_id integer NOT NULL,
    tipo_accion_id integer NOT NULL,
    sw_accion_preventiva character(1) NOT NULL,
    descripcion text
);
ALTER TABLE ONLY hc_accion_preventiva
    ADD PRIMARY KEY (hc_accion_preventiva_id);
ALTER TABLE ONLY hc_accion_preventiva
    ADD FOREIGN KEY (evolucion_id) REFERENCES hc_evoluciones(evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY hc_accion_preventiva
    ADD FOREIGN KEY (tipo_accion_id) REFERENCES hc_tipos_accion_preventiva(tipo_accion_id) ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON TABLE hc_accion_preventiva IS 'Tabla donde se guardan las acciones preventivas de la atencion';
COMMENT ON COLUMN hc_accion_preventiva.hc_accion_preventiva_id IS 'PK Serial para identificar los diferentes sistemas a revisar';
COMMENT ON COLUMN hc_accion_preventiva.evolucion_id IS 'FK de evolucion para determinar de que plantilla y a que evolucion pertencen';
COMMENT ON COLUMN hc_accion_preventiva.tipo_accion_id IS 'FK de la tabla tipo_sistemas';
COMMENT ON COLUMN hc_accion_preventiva.sw_accion_preventiva IS 'switche que identifica si la accion preventiva existe o no';
COMMENT ON COLUMN hc_accion_preventiva.descripcion IS 'descripcion de la accion preventiva';

------------------------------------hc_tipos_cuadrantes_dientes-----------------------------------------------------
CREATE TABLE "public"."hc_tipos_cuadrantes_dientes" (
  "hc_tipo_cuadrante_id" SERIAL,
  "descripcion" VARCHAR(20) NOT NULL,
  "indice_orden" SMALLINT DEFAULT 0,
  "sw_mostrar" CHAR(1) DEFAULT '0'::bpchar NOT NULL,
  CONSTRAINT "hc_tipos_cuadrantes_dientes_pkey" PRIMARY KEY("hc_tipo_cuadrante_id")
) ;

COMMENT ON TABLE "public"."hc_tipos_cuadrantes_dientes" IS 'relaciona los diferentes cuadrantes que puede tener un diente';
COMMENT ON COLUMN "public"."hc_tipos_cuadrantes_dientes"."hc_tipo_cuadrante_id" IS 'Identificador de los diferentes cuadrantes de los dientes';
COMMENT ON COLUMN "public"."hc_tipos_cuadrantes_dientes"."descripcion" IS 'descripcion del cuadrante de los dientes';
COMMENT ON COLUMN "public"."hc_tipos_cuadrantes_dientes"."indice_orden" IS 'ndice con el que se muestra los cuadrantes en pantalla';
COMMENT ON COLUMN "public"."hc_tipos_cuadrantes_dientes"."sw_mostrar" IS 'switche para identificar si el cuadrante se muestra o no';

------------------------------------hc_tipos_ubicaciones_dientes------------------------------------------
CREATE TABLE "public"."hc_tipos_ubicaciones_dientes" (
  "hc_tipo_ubicacion_diente_id" VARCHAR(2) NOT NULL,
  "indice_orden" SMALLINT DEFAULT 0,
  CONSTRAINT "hc_tipos_ubicaciones_dientes_pkey" PRIMARY KEY("hc_tipo_ubicacion_diente_id")
);

COMMENT ON TABLE "public"."hc_tipos_ubicaciones_dientes" IS 'relaciona los diferentes dientes que existen en la boca';
COMMENT ON COLUMN "public"."hc_tipos_ubicaciones_dientes"."hc_tipo_ubicacion_diente_id" IS 'descripcion del diente';
COMMENT ON COLUMN "public"."hc_tipos_ubicaciones_dientes"."indice_orden" IS 'indice con el que se muestra los dientes en pantalla';

-----------------------------------hc_tipos_problemas_dientes----------------------------------------------
CREATE TABLE "public"."hc_tipos_problemas_dientes" (
  "hc_tipo_problema_diente_id" SERIAL,
  "descripcion" VARCHAR(40) DEFAULT ''::character varying NOT NULL,
  "cargo" VARCHAR(10),
  "sw_presupuesto" CHAR(1) DEFAULT '0'::bpchar NOT NULL,
  "indice_orden" SMALLINT DEFAULT 0,
  "sw_cariado" CHAR(1) DEFAULT '0'::bpchar NOT NULL,
  "sw_obturado" CHAR(1) DEFAULT '0'::bpchar NOT NULL,
  "sw_perdidos" CHAR(1) DEFAULT '0'::bpchar NOT NULL,
  "sw_sanos" CHAR(1) DEFAULT '0'::bpchar NOT NULL,
  "sw_diente_completo" CHAR(1) DEFAULT '0'::bpchar NOT NULL,
  CONSTRAINT "hc_tipos_problemas_dientes_pkey" PRIMARY KEY("hc_tipo_problema_diente_id"),
  FOREIGN KEY ("cargo")
    REFERENCES "public"."cups"("cargo")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

COMMENT ON TABLE "public"."hc_tipos_problemas_dientes" IS 'relaciona los diferentes problemas que pueden tener los dientes';
COMMENT ON COLUMN "public"."hc_tipos_problemas_dientes"."hc_tipo_problema_diente_id" IS 'identifica los posibles problemas que pueden tener los dientes';
COMMENT ON COLUMN "public"."hc_tipos_problemas_dientes"."descripcion" IS 'descripcion del problema del diente';
COMMENT ON COLUMN "public"."hc_tipos_problemas_dientes"."cargo" IS 'identifica el cargo que identifica el problema';
COMMENT ON COLUMN "public"."hc_tipos_problemas_dientes"."sw_presupuesto" IS 'switche para identificar si se debe generar el presupuesto con este item o no';
COMMENT ON COLUMN "public"."hc_tipos_problemas_dientes"."indice_orden" IS 'indice con el que se muestra el problema en pantalla';
COMMENT ON COLUMN "public"."hc_tipos_problemas_dientes"."sw_cariado" IS 'Sirve para identificar cual problema identifica los dientes cariados';
COMMENT ON COLUMN "public"."hc_tipos_problemas_dientes"."sw_obturado" IS 'Sirve para identificar los dientes obturados';
COMMENT ON COLUMN "public"."hc_tipos_problemas_dientes"."sw_perdidos" IS 'Sirve para identificar el tipo de dientes perdido';
COMMENT ON COLUMN "public"."hc_tipos_problemas_dientes"."sw_sanos" IS 'Sirve para identificar los dientes sanos';
COMMENT ON COLUMN "public"."hc_tipos_problemas_dientes"."sw_diente_completo" IS 'Sirve para identificar si el problema es en todo el diente o no 1 = es todo y 0 = es todo';

-------------------------------------hc_odontogramas_primera_vez--------------------------------------------------------
CREATE TABLE "public"."hc_odontogramas_primera_vez" (
  "hc_odontograma_primera_vez_id" SERIAL,
  "evolucion_id" INTEGER NOT NULL,
  "sw_activo" CHAR(1) DEFAULT '1'::bpchar NOT NULL,
  "paciente_id" VARCHAR(32) NOT NULL,
  "tipo_id_paciente" VARCHAR(3) NOT NULL,
  CONSTRAINT "hc_odontogramas_primera_vez_pkey" PRIMARY KEY("hc_odontograma_primera_vez_id"),
  FOREIGN KEY ("evolucion_id")
    REFERENCES "public"."hc_evoluciones"("evolucion_id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY ("paciente_id", "tipo_id_paciente")
    REFERENCES "public"."pacientes"("paciente_id", "tipo_id_paciente")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

COMMENT ON TABLE "public"."hc_odontogramas_primera_vez" IS 'listado de los diferentes odontogramas de primera vez que se ha realizado el paciente.';
COMMENT ON COLUMN "public"."hc_odontogramas_primera_vez"."hc_odontograma_primera_vez_id" IS 'concecutivo de identificacion de los diferentes odontogramas';
COMMENT ON COLUMN "public"."hc_odontogramas_primera_vez"."evolucion_id" IS 'identificacion de la atencion del paciente';
COMMENT ON COLUMN "public"."hc_odontogramas_primera_vez"."sw_activo" IS 'switche para identificar si el odontograma esta activo o no 1=activo y 0=inactivo';
COMMENT ON COLUMN "public"."hc_odontogramas_primera_vez"."paciente_id" IS 'identificacion del paciente que se le realizo el odontograma';
COMMENT ON COLUMN "public"."hc_odontogramas_primera_vez"."tipo_id_paciente" IS 'tipo de identificacion del paciente al que se realizo el odontogrrama';

------------------------------hc_tipos_productos_dientes----------------------------------
CREATE TABLE "public"."hc_tipos_productos_dientes" (
  "hc_tipo_producto_diente_id" SERIAL,
  "descripcion" VARCHAR(20) NOT NULL,
  "indice_orden" SMALLINT DEFAULT 0,
  CONSTRAINT "hc_tipos_productos_dientes_pkey" PRIMARY KEY("hc_tipo_producto_diente_id")
);

COMMENT ON TABLE "public"."hc_tipos_productos_dientes" IS 'producto que se utiliza para realizar la reparacion del diente';
COMMENT ON COLUMN "public"."hc_tipos_productos_dientes"."hc_tipo_producto_diente_id" IS 'codigo que identifica el producto con el cual se realiza la atencion del paciente';
COMMENT ON COLUMN "public"."hc_tipos_productos_dientes"."descripcion" IS 'describe el tipo de producto con el que se va ha atender al paciente';
COMMENT ON COLUMN "public"."hc_tipos_productos_dientes"."indice_orden" IS 'Sirve para mostrar los productos en pantalla';

--------------------------------hc_odontogramas_primera_vez_detalle----------------------------------------------------
CREATE TABLE "public"."hc_odontogramas_primera_vez_detalle" (
  "hc_odontograma_primera_vez_detalle_id" SERIAL NOT NULL,
  "hc_odontograma_primera_vez_id" INTEGER NOT NULL,
  "hc_tipo_cuadrante_id" INTEGER,
  "hc_tipo_ubicacion_diente_id" VARCHAR(2) NOT NULL,
  "hc_tipo_problema_diente_id" INTEGER NOT NULL,
  "hc_tipo_producto_diente_id" INTEGER,
  CONSTRAINT "hc_odontogramas_primera_vez_detalle_pkey" PRIMARY KEY("hc_odontograma_primera_vez_detalle_id"),
  FOREIGN KEY ("hc_odontograma_primera_vez_id")
    REFERENCES "public"."hc_odontogramas_primera_vez"("hc_odontograma_primera_vez_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY ("hc_tipo_cuadrante_id")
    REFERENCES "public"."hc_tipos_cuadrantes_dientes"("hc_tipo_cuadrante_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY ("hc_tipo_ubicacion_diente_id")
    REFERENCES "public"."hc_tipos_ubicaciones_dientes"("hc_tipo_ubicacion_diente_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY ("hc_tipo_problema_diente_id")
    REFERENCES "public"."hc_tipos_problemas_dientes"("hc_tipo_problema_diente_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY ("hc_tipo_producto_diente_id")
    REFERENCES "public"."hc_tipos_productos_dientes"("hc_tipo_producto_diente_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

COMMENT ON TABLE "public"."hc_odontogramas_primera_vez_detalle" IS 'detalle del tratamiento de primera vez';
COMMENT ON COLUMN "public"."hc_odontogramas_primera_vez_detalle"."hc_odontograma_primera_vez_detalle_id" IS 'consecutivo para identificar el detalle del odontograma';
COMMENT ON COLUMN "public"."hc_odontogramas_primera_vez_detalle"."hc_odontograma_primera_vez_id" IS 'consecutivo para identificar el odontograma';
COMMENT ON COLUMN "public"."hc_odontogramas_primera_vez_detalle"."hc_tipo_cuadrante_id" IS 'identificacion del cuadrante donde se encuentra el diente';
COMMENT ON COLUMN "public"."hc_odontogramas_primera_vez_detalle"."hc_tipo_ubicacion_diente_id" IS 'identificacion de la ubicacion del diente';
COMMENT ON COLUMN "public"."hc_odontogramas_primera_vez_detalle"."hc_tipo_problema_diente_id" IS 'identificacion del problema del diente';
COMMENT ON COLUMN "public"."hc_odontogramas_primera_vez_detalle"."hc_tipo_producto_diente_id" IS 'codigo que identifica el producto con el cual se realiza la atencion del paciente';

----------------------------------hc_odontograma_primera_vez_descripcion--------------------
CREATE TABLE "public"."hc_odontogramas_primera_vez_descripcion" (
  "hc_odontograma_primera_vez_id" INTEGER NOT NULL,
  "descripcion" TEXT NOT NULL,
  PRIMARY KEY("hc_odontograma_primera_vez_id"),
  FOREIGN KEY ("hc_odontograma_primera_vez_id")
    REFERENCES "public"."hc_odontogramas_primera_vez"("hc_odontograma_primera_vez_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

COMMENT ON COLUMN "public"."hc_odontogramas_primera_vez_descripcion"."hc_odontograma_primera_vez_id" IS 'consecutivo para identificar el odontograma';
COMMENT ON COLUMN "public"."hc_odontogramas_primera_vez_descripcion"."descripcion" IS 'descripcion del odontograma';

-------------------------------hc_indice_ipb_oleary----------------------------------------
CREATE TABLE "public"."hc_indice_ipb_oleary" (
  "hc_indice_ipb_oleary_id" SERIAL NOT NULL,
  "evolucion_id" INTEGER NOT NULL,
  PRIMARY KEY("hc_indice_ipb_oleary_id"),
  FOREIGN KEY ("evolucion_id")
    REFERENCES "public"."hc_evoluciones"("evolucion_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

COMMENT ON TABLE "public"."hc_indice_ipb_oleary" IS 'listado de los diferentes indices que le tomen al paciente';
COMMENT ON COLUMN "public"."hc_indice_ipb_oleary"."hc_indice_ipb_oleary_id" IS 'consecutivo para identificar los indice de ipb oleary';
COMMENT ON COLUMN "public"."hc_indice_ipb_oleary"."evolucion_id" IS 'consecutivo para identificar la atencion del paciente';

-------------------------------hc_tipos_cuadrantes_dientes_oleary---------------------------
CREATE TABLE "public"."hc_tipos_cuadrantes_dientes_oleary" (
  "hc_tipo_cuadrante_diente_oleary_id" INTEGER NOT NULL,
  PRIMARY KEY("hc_tipo_cuadrante_diente_oleary_id"),
  FOREIGN KEY ("hc_tipo_cuadrante_diente_oleary_id")
    REFERENCES "public"."hc_tipos_cuadrantes_dientes"("hc_tipo_cuadrante_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

COMMENT ON TABLE "public"."hc_tipos_cuadrantes_dientes_oleary" IS 'relaciona los diferentes cuadrantes que puede tener un diente para el indice oleary';
COMMENT ON COLUMN "public"."hc_tipos_cuadrantes_dientes_oleary"."hc_tipo_cuadrante_diente_oleary_id" IS 'Identificador de los diferentes cuadrantes de los dientes para el indice de oleary';

------------------------------hc_indice_ipb_oleary_detalle-----------------------------------
CREATE TABLE "public"."hc_indice_ipb_oleary_detalle" (
  "hc_indice_ipb_oleary_detalle_id" SERIAL NOT NULL,
  "hc_indice_ipb_oleary_id" INTEGER NOT NULL,
  "hc_tipo_cuadrante_diente_oleary_id" INTEGER NOT NULL,
  "hc_tipo_ubicacion_diente_id" VARCHAR(2) NOT NULL,
  PRIMARY KEY("hc_indice_ipb_oleary_detalle_id"),
  FOREIGN KEY ("hc_indice_ipb_oleary_id")
    REFERENCES "public"."hc_indice_ipb_oleary"("hc_indice_ipb_oleary_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY ("hc_tipo_cuadrante_diente_oleary_id")
    REFERENCES "public"."hc_tipos_cuadrantes_dientes_oleary"("hc_tipo_cuadrante_diente_oleary_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY ("hc_tipo_ubicacion_diente_id")
    REFERENCES "public"."hc_tipos_ubicaciones_dientes"("hc_tipo_ubicacion_diente_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

COMMENT ON TABLE "public"."hc_indice_ipb_oleary_detalle" IS 'detalle del listado de los diferentes indices de ipb oleary';
COMMENT ON COLUMN "public"."hc_indice_ipb_oleary_detalle"."hc_indice_ipb_oleary_detalle_id" IS 'consecutivo para identificar el detalla del indice ipb oleary';
COMMENT ON COLUMN "public"."hc_indice_ipb_oleary_detalle"."hc_indice_ipb_oleary_id" IS 'consecutivo para identificar los indices de ipb oleary';
COMMENT ON COLUMN "public"."hc_indice_ipb_oleary_detalle"."hc_tipo_cuadrante_diente_oleary_id" IS 'identificacion del cuadrante donde se encuentra el diente';
COMMENT ON COLUMN "public"."hc_indice_ipb_oleary_detalle"."hc_tipo_ubicacion_diente_id" IS 'identificacion de la ubicacion del diente';

------------------------------------hc_odontogramas_tratamientos---------------------------
CREATE TABLE "public"."hc_odontogramas_tratamientos" (
  "hc_odontograma_tratamiento_id" SERIAL,
  "paciente_id" VARCHAR(32) NOT NULL,
  "tipo_id_paciente" VARCHAR(3) NOT NULL,
  "sw_activo" CHAR(1) DEFAULT '1'::bpchar NOT NULL,
  CONSTRAINT "hc_odontogramas_tratamientos_pkey" PRIMARY KEY("hc_odontograma_tratamiento_id"),
  FOREIGN KEY ("paciente_id", "tipo_id_paciente")
    REFERENCES "public"."pacientes"("paciente_id", "tipo_id_paciente")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

COMMENT ON TABLE "public"."hc_odontogramas_tratamientos" IS 'listado de los diferentes tratamientos que se realizan al paciente';
COMMENT ON COLUMN "public"."hc_odontogramas_tratamientos"."hc_odontograma_tratamiento_id" IS 'consecutivo para identificar los tratamientos';
COMMENT ON COLUMN "public"."hc_odontogramas_tratamientos"."paciente_id" IS 'identificacion del paciente al cual se le esta realizando el tratamiento';
COMMENT ON COLUMN "public"."hc_odontogramas_tratamientos"."tipo_id_paciente" IS 'tipo de identificacion del paciente al cual se le esta realizando el tratamiento';
COMMENT ON COLUMN "public"."hc_odontogramas_tratamientos"."sw_activo" IS 'identifica si el tratamiento se encuentra activo o no';

-----------------------------------hc_odontogramas_tratamientos_detalle--------------------
CREATE TABLE "public"."hc_odontogramas_tratamientos_detalle" (
  "hc_odontograma_tratamiento detalle_id" SERIAL,
  "hc_odontograma_tratamiento_id" INTEGER NOT NULL,
  "hc_tipo_cuadrante_id" INTEGER,
  "hc_tipo_ubicacion_diente_id" VARCHAR(2),
  "hc_tipo_problema_diente_id" INTEGER,
  "hc_tipo_producto_diente_id" INTEGER,
  "hc_odontograma_primera_vez_detalle_id" INTEGER,
  "evolucion_id" INTEGER NOT NULL,
  CONSTRAINT "hc_odontogramas_tratamientos_detalle_pkey" PRIMARY KEY("hc_odontograma_tratamiento detalle_id"),
  FOREIGN KEY ("hc_odontograma_tratamiento_id")
    REFERENCES "public"."hc_odontogramas_tratamientos"("hc_odontograma_tratamiento_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY ("hc_tipo_cuadrante_id")
    REFERENCES "public"."hc_tipos_cuadrantes_dientes"("hc_tipo_cuadrante_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY ("hc_tipo_ubicacion_diente_id")
    REFERENCES "public"."hc_tipos_ubicaciones_dientes"("hc_tipo_ubicacion_diente_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY ("hc_tipo_problema_diente_id")
    REFERENCES "public"."hc_tipos_problemas_dientes"("hc_tipo_problema_diente_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY ("hc_tipo_producto_diente_id")
    REFERENCES "public"."hc_tipos_productos_dientes"("hc_tipo_producto_diente_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY ("hc_odontograma_primera_vez_detalle_id")
    REFERENCES "public"."hc_odontogramas_primera_vez_detalle"("hc_odontograma_primera_vez_detalle_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY ("evolucion_id")
    REFERENCES "public"."hc_evoluciones"("evolucion_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

COMMENT ON TABLE "public"."hc_odontogramas_tratamientos_detalle" IS 'detalle del listado de los diferentes tratamientos';
COMMENT ON COLUMN "public"."hc_odontogramas_tratamientos_detalle"."hc_odontograma_tratamiento detalle_id" IS 'consecutivo para identificar el detalle del odontograma';
COMMENT ON COLUMN "public"."hc_odontogramas_tratamientos_detalle"."hc_odontograma_tratamiento_id" IS 'consecutivo para identificar el tratamiento';
COMMENT ON COLUMN "public"."hc_odontogramas_tratamientos_detalle"."hc_tipo_cuadrante_id" IS 'identificacion del cuadrante donde se encuentra el diente';
COMMENT ON COLUMN "public"."hc_odontogramas_tratamientos_detalle"."hc_tipo_ubicacion_diente_id" IS 'identificacion de la ubicacion del diente';
COMMENT ON COLUMN "public"."hc_odontogramas_tratamientos_detalle"."hc_tipo_problema_diente_id" IS 'identificacion del problema del diente';
COMMENT ON COLUMN "public"."hc_odontogramas_tratamientos_detalle"."hc_tipo_producto_diente_id" IS 'identificacion del producto con el que se atiende al paciente';
COMMENT ON COLUMN "public"."hc_odontogramas_tratamientos_detalle"."hc_odontograma_primera_vez_detalle_id" IS 'consecutivo para identificar el detalle del odontograma';
COMMENT ON COLUMN "public"."hc_odontogramas_tratamientos_detalle"."evolucion_id" IS 'consecutivo para identificar la atencion del paciente';

-------------------------------hc_odontogramas_tratamientos_descripcion--------------------
CREATE TABLE "public"."hc_odontogramas_tratamientos_descripcion" (
  "hc_odontograma_tratamiento_id" INTEGER NOT NULL,
  "descripcion" TEXT NOT NULL,
  PRIMARY KEY("hc_odontograma_tratamiento_id"),
  FOREIGN KEY ("hc_odontograma_tratamiento_id")
    REFERENCES "public"."hc_odontogramas_tratamientos"("hc_odontograma_tratamiento_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

COMMENT ON TABLE "public"."hc_odontogramas_tratamientos_descripcion" IS 'contiene la descripcion del odontograma de tratamiento';
COMMENT ON COLUMN "public"."hc_odontogramas_tratamientos_descripcion"."hc_odontograma_tratamiento_id" IS 'consecutivo para identificar el odontograma de tratamiento';
COMMENT ON COLUMN "public"."hc_odontogramas_tratamientos_descripcion"."descripcion" IS 'descripcion del odontograma';

-------------------------------hc_os_solicitudes_problemas_dientes--------------------------
CREATE TABLE "public"."hc_os_solicitudes_problemas_dientes" (
  "hc_os_solicitud_id" INTEGER NOT NULL,
  "hc_odontograma_tratamiento detalle_id" INTEGER NOT NULL,
  PRIMARY KEY("hc_os_solicitud_id"),
  FOREIGN KEY ("hc_os_solicitud_id")
    REFERENCES "public"."hc_os_solicitudes"("hc_os_solicitud_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

COMMENT ON TABLE "public"."hc_os_solicitudes_problemas_dientes" IS 'guarda los procedimientos que se solicitan por realizar al paciente sobre la atencion odontologica';
COMMENT ON COLUMN "public"."hc_os_solicitudes_problemas_dientes"."hc_os_solicitud_id" IS 'identificador de la solicitud realizada al paciente';
COMMENT ON COLUMN "public"."hc_os_solicitudes_problemas_dientes"."hc_odontograma_tratamiento detalle_id" IS 'consecutivo para identificar el detalle del odontograma';


-------------------------------------hc_tipos_dibujar_dientes_ipb_oleary-----------------------------------------------
CREATE TABLE "public"."hc_tipos_dibujar_dientes_ipb_oleary" (
  "hc_tipo_cuadrante_diente_oleary_id" INTEGER NOT NULL,
  "hc_tipo_ubicacion_diente_id" VARCHAR(2) NOT NULL,
  PRIMARY KEY("hc_tipo_cuadrante_diente_oleary_id", "hc_tipo_ubicacion_diente_id"),
  FOREIGN KEY ("hc_tipo_cuadrante_diente_oleary_id")
    REFERENCES "public"."hc_tipos_cuadrantes_dientes_oleary"("hc_tipo_cuadrante_diente_oleary_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY ("hc_tipo_ubicacion_diente_id")
    REFERENCES "public"."hc_tipos_ubicaciones_dientes"("hc_tipo_ubicacion_diente_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

COMMENT ON TABLE "public"."hc_tipos_dibujar_dientes_ipb_oleary" IS 'Identifica los dientes por defecto que deben ser dibujados a la hora de entrar en el modulo de ipb oleary';
COMMENT ON COLUMN "public"."hc_tipos_dibujar_dientes_ipb_oleary"."hc_tipo_cuadrante_diente_oleary_id" IS 'Identificador de los diferentes cuadrantes de los dientes para el indice de oleary';
COMMENT ON COLUMN "public"."hc_tipos_dibujar_dientes_ipb_oleary"."hc_tipo_ubicacion_diente_id" IS 'ubicaci� del diente';

-------------------------------------hc_tipos_dibujar_dientes--------------------------------------------------------
CREATE TABLE "public"."hc_tipos_dibujar_dientes" (
  "hc_tipo_cuadrante_id" INTEGER NOT NULL,
  "hc_tipo_ubicacion_diente_id" VARCHAR NOT NULL,
  "hc_tipo_problema_diente_id" INTEGER NOT NULL,
  CONSTRAINT "hc_tipos_dibujar_dientes_pkey" PRIMARY KEY("hc_tipo_cuadrante_id", "hc_tipo_ubicacion_diente_id", "hc_tipo_problema_diente_id"),
  FOREIGN KEY ("hc_tipo_cuadrante_id")
    REFERENCES "public"."hc_tipos_cuadrantes_dientes"("hc_tipo_cuadrante_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY ("hc_tipo_ubicacion_diente_id")
    REFERENCES "public"."hc_tipos_ubicaciones_dientes"("hc_tipo_ubicacion_diente_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY ("hc_tipo_problema_diente_id")
    REFERENCES "public"."hc_tipos_problemas_dientes"("hc_tipo_problema_diente_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

COMMENT ON TABLE "public"."hc_tipos_dibujar_dientes" IS 'Identifica los dientes por defecto que deben ser dibujados a la hora de entrar en el modulo de odontograma';
COMMENT ON COLUMN "public"."hc_tipos_dibujar_dientes"."hc_tipo_cuadrante_id" IS 'Identificador de los diferentes cuadrantes de los dientes';
COMMENT ON COLUMN "public"."hc_tipos_dibujar_dientes"."hc_tipo_ubicacion_diente_id" IS 'ubicaci� del diente';
COMMENT ON COLUMN "public"."hc_tipos_dibujar_dientes"."hc_tipo_problema_diente_id" IS 'identifica los posibles problemas que pueden tener los dientes';

------------------------------pacientes_auditoria-----------------------------------------
CREATE TABLE "public"."pacientes_auditoria" (
  "paciente_auditoria_id" SERIAL,
  "paciente_id" VARCHAR(32) NOT NULL,
  "tipo_id_paciente" VARCHAR(3) NOT NULL,
  "observacion" TEXT NOT NULL,
  "usuario_id" INTEGER NOT NULL,
  "fecha_registro" TIMESTAMP WITHOUT TIME ZONE NOT NULL,
  CONSTRAINT "pacientes_auditoria_pkey" PRIMARY KEY("paciente_auditoria_id"),
  FOREIGN KEY ("paciente_id", "tipo_id_paciente")
    REFERENCES "public"."pacientes"("paciente_id", "tipo_id_paciente")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY ("usuario_id")
    REFERENCES "public"."system_usuarios"("usuario_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

COMMENT ON TABLE "public"."pacientes_auditoria" IS 'Registro de la auditoria que se realiza a la historia clinica de un paciente';
COMMENT ON COLUMN "public"."pacientes_auditoria"."paciente_auditoria_id" IS 'consecutivo que identifica el paciente';
COMMENT ON COLUMN "public"."pacientes_auditoria"."paciente_id" IS 'Numero de identificacion de los pacientes';
COMMENT ON COLUMN "public"."pacientes_auditoria"."tipo_id_paciente" IS 'Tipo de identificacion del paciente';
COMMENT ON COLUMN "public"."pacientes_auditoria"."observacion" IS 'Observacion de la auditoria del paciente';
COMMENT ON COLUMN "public"."pacientes_auditoria"."usuario_id" IS 'identificacion del usuario del sistema que ingresa la auditoria';
COMMENT ON COLUMN "public"."pacientes_auditoria"."fecha_registro" IS 'fecha en el que se hizo el registro de la auditoria';


---------------------------------hc_evoluciones_auditoria----------------------------------
CREATE TABLE "public"."hc_evoluciones_auditoria" (
  "hc_evolucion_auditoria_id" SERIAL,
  "evolucion_id" INTEGER NOT NULL,
  "observacion" TEXT NOT NULL,
  "usuario_id" INTEGER NOT NULL,
  "fecha_registro" TIMESTAMP WITHOUT TIME ZONE NOT NULL,
  CONSTRAINT "hc_evoluciones_auditoria_pkey" PRIMARY KEY("hc_evolucion_auditoria_id"),
  FOREIGN KEY ("evolucion_id")
    REFERENCES "public"."hc_evoluciones"("evolucion_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY ("usuario_id")
    REFERENCES "public"."system_usuarios"("usuario_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

COMMENT ON TABLE "public"."hc_evoluciones_auditoria" IS 'Registro de la auditoria que se realiza a una atencion especifica de un paciente';
COMMENT ON COLUMN "public"."hc_evoluciones_auditoria"."hc_evolucion_auditoria_id" IS 'consecutivo que identifica las diferentes auditorias que se realiza a la atencion';
COMMENT ON COLUMN "public"."hc_evoluciones_auditoria"."evolucion_id" IS 'consecutivo que identifica las diferentes atenciones';
COMMENT ON COLUMN "public"."hc_evoluciones_auditoria"."observacion" IS 'observacion de la auditoria por evolucion';
COMMENT ON COLUMN "public"."hc_evoluciones_auditoria"."usuario_id" IS 'usuario que registra la auditoria de la atencion';
COMMENT ON COLUMN "public"."hc_evoluciones_auditoria"."fecha_registro" IS 'fecha de registro de la auditoria';

