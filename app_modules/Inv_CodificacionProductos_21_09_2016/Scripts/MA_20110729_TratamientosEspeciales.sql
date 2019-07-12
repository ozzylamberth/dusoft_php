CREATE TABLE public.inv_tratamientos_productos (
  tratamiento_id  serial NOT NULL PRIMARY KEY,
  descripcion     varchar(100) NOT NULL,
  usuario_id      integer NOT NULL DEFAULT 2,
  fecha_registro  timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT NOW(),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (usuario_id)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.inv_tratamientos_productos
  OWNER TO siis;

COMMENT ON TABLE public.inv_tratamientos_productos
  IS 'Tabla donde se permite parametrizar los Tipos de tratamientos que pueden clasificar a un producto';

COMMENT ON COLUMN public.inv_tratamientos_productos.tratamiento_id
  IS 'Codigo Secuencial, de los tratamientos que pueden ser asignados a un Producto';

COMMENT ON COLUMN public.inv_tratamientos_productos.descripcion
  IS 'Descripcion del tratamiento';

COMMENT ON COLUMN public.inv_tratamientos_productos.usuario_id
  IS 'Usuario que registra el Tratamiento';

COMMENT ON COLUMN public.inv_tratamientos_productos.fecha_registro
  IS 'Fecha y hora del registro';

  ALTER TABLE public.inventarios_productos
  ADD COLUMN tratamiento_id integer;

COMMENT ON COLUMN public.inventarios_productos.tratamiento_id
  IS 'Permite Asignar a un producto, el tipo de tratamiento, para el que puede ser usado el producto';

  ALTER TABLE public.inventarios_productos
  ADD CONSTRAINT foreign_key01
  FOREIGN KEY (tratamiento_id)
    REFERENCES public.inv_tratamientos_productos(tratamiento_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;
	
	ALTER TABLE public.inventarios_productos
  ADD COLUMN usuario_id integer NOT NULL DEFAULT 2;

COMMENT ON COLUMN public.inventarios_productos.usuario_id
  IS 'Usuario que Ha hecho Consultas sobre la tabla';

  ALTER TABLE public.inventarios_productos
  ADD COLUMN fecha_registro timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT NOW();

COMMENT ON COLUMN public.inventarios_productos.fecha_registro
  IS 'Fecha de creacion/modificacion del registro en el sistema';

  ALTER TABLE public.inventarios_productos
  ADD CONSTRAINT foreign_key02
  FOREIGN KEY (usuario_id)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;

  
ALTER TABLE public.medicamentos
  ADD COLUMN usuario_id integer NOT NULL DEFAULT 2;

COMMENT ON COLUMN public.medicamentos.usuario_id
  IS 'Usuario que hacer Insecion/Modificacion de registros';

  ALTER TABLE public.medicamentos
  ADD COLUMN fecha_registro timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT NOW();

COMMENT ON COLUMN public.medicamentos.fecha_registro
  IS 'Fecha Registro';

  ALTER TABLE public.medicamentos
  ADD CONSTRAINT foreign_key01
  FOREIGN KEY (usuario_id)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;
	
	ALTER TABLE public.inv_laboratorios
  ADD COLUMN usuario_id integer NOT NULL DEFAULT 2;

COMMENT ON COLUMN public.inv_laboratorios.usuario_id
  IS 'Usuario que HAce el Registro del Laboratorio';

  ALTER TABLE public.inv_laboratorios
  ADD COLUMN fecha_registro timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT NOW();

COMMENT ON COLUMN public.inv_laboratorios.fecha_registro
  IS 'fecha de registro de la informacion';

  ALTER TABLE public.inv_laboratorios
  ADD CONSTRAINT foreign_key01
  FOREIGN KEY (usuario_id)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;

	ALTER TABLE public.inv_moleculas
  ADD COLUMN usuario_id integer NOT NULL DEFAULT 2;

COMMENT ON COLUMN public.inv_moleculas.usuario_id
  IS 'usuario que hace el registro de la molecula';

  ALTER TABLE public.inv_moleculas
  ADD COLUMN fecha_registro timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT NOW();

COMMENT ON COLUMN public.inv_moleculas.fecha_registro
  IS 'Fecha de Registro de la molecula';

  ALTER TABLE public.inv_moleculas
  ADD CONSTRAINT foreign_key01
  FOREIGN KEY (usuario_id)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;

