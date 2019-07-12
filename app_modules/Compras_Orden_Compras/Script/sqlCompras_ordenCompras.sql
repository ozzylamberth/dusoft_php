-----/***************************** MODULO DE CONTRATACION  DE PRODUCTOS***********************************************/


----/********TABLAS QUE NECESITAN ESTAR LLENAR PARA QUE EL MODULO FUNCIONE****/

empresas
bodegas
centros_utilidad
informacion_preorden
informacion_preorden_detalle
tipo_id_terceros
terceros
terceros_proveedores
tipo_mpios
tipo_dptos
inv_grupos_inventarios
inv_clases_inventarios
inv_laboratorios
inv_moleculas
inv_subclases_inventarios
inventarios_productos
inventarios
unidades
inv_condiciones_compra





-----/******* TABLA PARA DAR PERMISO AL USUARIO PARA TRABAJAR SOBRE EL MODULO*****/

userpermisos_compras

----/********TABLAS   DEL MODULO ******/
ALTER TABLE compras_ordenes_pedidos  ADD empresa_id_pedido   CHARACTER(2) NULL;
ALTER TABLE compras_ordenes_pedidos  ADD sw_unificada   CHARACTER(2) DEFAULT '0' NULL; 


COMMENT ON COLUMN compras_ordenes_pedidos.empresa_id_pedido IS 'Empresa que realiza el Pedido';
COMMENT ON COLUMN compras_ordenes_pedidos.sw_unificada IS '0->No Unificada,1->Unificada ';



ALTER TABLE compras_ordenes_pedidos_detalle  ADD preorden_detalle_id integer null ;
ALTER TABLE compras_ordenes_pedidos_detalle ADD valor_unitario  numeric(9,2)  null;
ALTER TABLE compras_ordenes_pedidos_detalle  ADD FOREIGN KEY (preorden_detalle_id) REFERENCES informacion_preorden_detalle(preorden_detalle_id);
ALTER TABLE compras_ordenes_pedidos_detalle  ADD item_id serial not null ;  ///ojo


COMMENT ON COLUMN compras_ordenes_pedidos_detalle.preorden_detalle_id IS 'Id de la Pre Orden';
COMMENT ON COLUMN compras_ordenes_pedidos_detalle.valor_unitario IS 'valor unitario  ';


CREATE TABLE Condiciones_Orden_Compra(
            CondicionOC_id        SERIAL NOT NULL,
            empresa_id                CHARACTER(2) not null,
            orden_pedido_id              integer  NOT NULL,
            condicion                    TEXT, 
            usuario_id                INTEGER NOT NULL,
            fecha_registro                TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()
       );

ALTER TABLE Condiciones_Orden_Compra ADD PRIMARY KEY (CondicionOC_id);
ALTER TABLE Condiciones_Orden_Compra ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE Condiciones_Orden_Compra ADD FOREIGN KEY (usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE Condiciones_Orden_Compra ADD FOREIGN KEY (orden_pedido_id) REFERENCES compras_ordenes_pedidos(orden_pedido_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON COLUMN Condiciones_Orden_Compra.CondicionOC_id IS 'Id condicion ';
COMMENT ON COLUMN Condiciones_Orden_Compra.empresa_id IS 'Id Empresa  ';
COMMENT ON COLUMN Condiciones_Orden_Compra.orden_pedido_id IS 'Id Orden pedido';
COMMENT ON COLUMN Condiciones_Orden_Compra.condicion IS 'Condiciones  ';
COMMENT ON COLUMN Condiciones_Orden_Compra.usuario_id IS 'Id Usuario Registra';
COMMENT ON COLUMN Condiciones_Orden_Compra.fecha_registro IS 'Fecha de registro  ';

CREATE TABLE Productos_pendientes_OrdenPedido(
        Prod_Pend_OP_id        SERIAL NOT NULL,
        empresa_id                CHARACTER(2) not null,
        codigo_proveedor_id              integer  NOT NULL,
		observacion                    TEXT, 
        usuario_id                INTEGER NOT NULL,
        fecha_registro                TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
		sw_asignado                   CHARACTER(1) not null
       );

ALTER TABLE Productos_pendientes_OrdenPedido ADD PRIMARY KEY (Prod_Pend_OP_id);
ALTER TABLE Productos_pendientes_OrdenPedido ADD FOREIGN KEY (codigo_proveedor_id) REFERENCES terceros_proveedores(codigo_proveedor_id)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE Productos_pendientes_OrdenPedido ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE Productos_pendientes_OrdenPedido ADD FOREIGN KEY (usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON COLUMN Productos_pendientes_OrdenPedido.Prod_Pend_OP_id IS 'Id (PK) ';
COMMENT ON COLUMN Productos_pendientes_OrdenPedido.empresa_id IS 'Id Empresa  ';
COMMENT ON COLUMN Productos_pendientes_OrdenPedido.codigo_proveedor_id IS 'Id del proveedor';
COMMENT ON COLUMN Productos_pendientes_OrdenPedido.observacion IS 'Observaciones ';
COMMENT ON COLUMN Productos_pendientes_OrdenPedido.usuario_id IS 'Id Usuario Registra';
COMMENT ON COLUMN Productos_pendientes_OrdenPedido.fecha_registro IS 'Fecha de registro  ';
COMMENT ON COLUMN Productos_pendientes_OrdenPedido.sw_asignado IS '0->No Asignada, 1->Asignada (Orden de compra) ';


CREATE TABLE Productos_pendientes_OrdenPedido_d(
        Prod_Pend_OP_id_d       SERIAL NOT NULL,
        Prod_Pend_OP_id        integer NOT NULL,
        empresa_id                CHARACTER(2) not null,
        codigo_producto               CHARACTER VARYING(60) NOT NULL,
        numero_unidades      	numeric(9) not null,
        valor                   numeric(15,2) not null, 
        porc_iva                numeric(5,3) not null,
        fecha_registro                TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()
		    );
ALTER TABLE Productos_pendientes_OrdenPedido_d ADD PRIMARY KEY (Prod_Pend_OP_id_d);
ALTER TABLE Productos_pendientes_OrdenPedido_d ADD FOREIGN KEY (codigo_producto) REFERENCES inventarios_productos(codigo_producto)
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE Productos_pendientes_OrdenPedido_d ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)
ON UPDATE CASCADE ON DELETE RESTRICT;



COMMENT ON COLUMN Productos_pendientes_OrdenPedido_d.Prod_Pend_OP_id_d IS 'Id (PK) ';
COMMENT ON COLUMN Productos_pendientes_OrdenPedido_d.Prod_Pend_OP_id IS 'Id (FK) ';
COMMENT ON COLUMN Productos_pendientes_OrdenPedido_d.empresa_id IS 'Id Empresa  ';
COMMENT ON COLUMN Productos_pendientes_OrdenPedido_d.codigo_producto IS 'Id del producto';
COMMENT ON COLUMN Productos_pendientes_OrdenPedido_d.numero_unidades IS 'unidades solicitadas ';
COMMENT ON COLUMN Productos_pendientes_OrdenPedido_d.valor IS 'valor';
COMMENT ON COLUMN Productos_pendientes_OrdenPedido_d.fecha_registro IS 'Fecha de registro  ';
COMMENT ON COLUMN Productos_pendientes_OrdenPedido_d.porc_iva IS 'Porcentaje-iva';



create table tmp_Unificadas_Orden_Pedidos(
                                  item_id   SERIAL NOT NULL, 
                                  orden_pedido_id integer NOT NULL,
                                  codigo_proveedor_id  integer NOT NULL,
                                  codigo_producto      CHARACTER VARYING(60) NOT NULL,
                                  numero_unidades numeric(9) not null,
                                  valor 	numeric(15,2) null,
                                  porc_iva numeric(5,3) null,
                                  numero_unidades_recibidas numeric(9)  null,
                                  preorden_detalle_id 	integer null,
                                  valor_unitario  numeric(9,2) null,
                                  usuario_id  integer not null,
                                  fecha_registro   TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW());
                                  
ALTER TABLE tmp_Unificadas_Orden_Pedidos ADD PRIMARY KEY (item_id);
ALTER TABLE tmp_Unificadas_Orden_Pedidos  ADD FOREIGN KEY (usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON COLUMN tmp_Unificadas_Orden_Pedidos.item_id IS 'Id (PK) ';
COMMENT ON COLUMN tmp_Unificadas_Orden_Pedidos.orden_pedido_id IS 'Id orden de pedido ';
COMMENT ON COLUMN tmp_Unificadas_Orden_Pedidos.codigo_proveedor_id IS 'Id  proveedor ';
COMMENT ON COLUMN tmp_Unificadas_Orden_Pedidos.codigo_producto IS 'Id del producto';
COMMENT ON COLUMN tmp_Unificadas_Orden_Pedidos.numero_unidades IS 'unidades solicitadas ';
COMMENT ON COLUMN tmp_Unificadas_Orden_Pedidos.valor IS 'valor';
COMMENT ON COLUMN tmp_Unificadas_Orden_Pedidos.porc_iva IS 'Porcentaje-iva';
COMMENT ON COLUMN tmp_Unificadas_Orden_Pedidos.numero_unidades_recibidas IS 'Unidades recibidas';
COMMENT ON COLUMN tmp_Unificadas_Orden_Pedidos.valor_unitario IS 'valor Unitario';
COMMENT ON COLUMN tmp_Unificadas_Orden_Pedidos.fecha_registro IS 'Fecha de registro  ';






