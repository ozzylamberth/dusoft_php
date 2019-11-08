<?php
/****************************************************************************************************
* Web Service ws_producto.php
*
* Fecha: 06-V-2013
* Autor: Steven H. Gamboa
* 
* Descripcion:  Contiene 99999 Funciones para ingreso y actualizacion de Productos, en la base de datos de Cosmitet.
*
* 1. insertar_grupo: Permite ingresar el grupo en la tabla inv_grupos_inventarios.
*
* 2. insertar_clasesagrupo: Permite ingresar una o varias clases al grupo asociado, en tabla inv_clases_inventarios.
*
* 3. insertar_subclaseaclase: Permite asociar una o mas subclases a la clase, en la tabla inv_subclases_inventarios.
*
* 4. insertar_productoinsumo: Permite ingresar la informacion de todo el producto, tabla inventarios_productos.
*
* 5. modificar_grupo: Permite actualizar la descripcion del grupo en la tabla inv_grupos_inventarios.
*
* 6. borrar_registro: Permite la eliminacion de un grupo. Puede ser usada para borrar otros elementos.
*
* 7. borrar_clase: Permite la eliminacion de la clase, no debe tener asociados ni productos ni subclases.
*
* 8. borrar_subclase: Permite la eliminacion de la clase, no debe tener asociados productos.
*
****************************************************************************************************/


function registrar_logs($codigo, $grupo_id, $clase_id, $subclase_id, $mensaje,$tipo='0') {

    require_once("conexionproductopg.php");

    $codigo = (empty($codigo)) ? 'NULL' : $codigo;
    $grupo_id = (empty($grupo_id)) ? 'NULL' : $grupo_id;
    $mensaje=pg_escape_string($mensaje);
    $query = "INSERT INTO logs_productos_ws (codigo, grupo_id, clase_id, subclase_id, mensaje, tipo)
             VALUES ('{$codigo}', '{$grupo_id}', '{$clase_id}','{$subclase_id}', '{$mensaje}', '{$tipo}' ); ";
    

    $result = pg_query($conexionn, $query);

    if ($result) {
        $continuar = true;
        $msj = "LOG Registrado";
    } else {
        $continuar = false;
        $msj = "Se ha generado un error insertando en logs_productos_ws ( " . pg_last_error($conexionn) . " ) ";
    }

    return array("msj" => $msj, "continuar" => $continuar);
}

 
registrar_logs("4asas",44558,55589,55599, "HOla mundo",'1');

?>