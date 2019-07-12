<?php

$dbconn3 = pg_connect("host=10.0.2.246 port=5432 dbname=dusoft user=siis password=.123mauro*");

function insertarMolecula($dbconn3)
{

    $sql = "SELECT * FROM inv_clases_inventarios_fo";

    $result = pg_query($sql);

    while ($row = pg_fetch_array($result))
    {
        
        
        $sql = "INSERT INTO inv_clases_inventarios VALUES ('{$row["grupo_id"]}','{$row["clase_id"]}', '{$row["descripcion"]}', '{$row["laboratorio_id"]}',
                '{$row["sw_tipo_empresa"]}') ";


       pg_query($sql);


        if (pg_last_error($dbconn3) != ""){
            
            echo "<p style = 'color: red;'>Error   # " . pg_last_error(). " ". print_r($row) . "</p></br>";
        } else {
           echo "insertado ". print_r($row); 
        }
        
        //echo print_r($row). "</br>";
    }
}

insertarMolecula($dbconn3);