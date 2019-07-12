<?php

function open_database()
{
    $GLOBALS["dbh"] = pg_connect("host=127.0.0.1 port=5432 dbname=SIIS user=admin password=coc70a")
      or die ("Nao consegui conectar ao PostGres --> " . pg_last_error($conn));
} 

function close_database()
{
    pg_close($GLOBALS["dbh"]);
} 

function get_next_val($tabla, $campo)
{
    $query = "SELECT MAX(CAST(" . $campo . " AS INT)) AS MAXIMO FROM " . $tabla;
    $result = execute_query($GLOBALS["dbh"], $query);

    $row = pg_fetch_row($result);

    $maximo = $row[0];
    free_result($result);

    return $maximo + 1;
}

function execute_query($dbh, $sql)
{
	/**** Esto hay que cambiarlo, quizas por una funcion que genere las consultas */
	/*$sql = str_replace("''", "NULL", $sql);*/
	
	$sql = $sql;

	return pg_query($dbh, $sql);
}

function fetch_object($result)
{
	return pg_fetch_object($result);
}

function free_result($result)
{
	pg_free_result($result);
}
$records_per_page = 25;

function existe_valor($tabla, $campo, $valor, $type)
{
    $existe = false;

    if ($type == "N")
        $sql = "SELECT " . $campo . " FROM " . $tabla . " WHERE " . $campo . " = " . $valor;

    if ($type == "C")
        $sql = "SELECT " . $campo . " FROM " . $tabla . " WHERE UPPER(" . $campo . ") = UPPER('" . $valor . "')";

    $result = execute_query($GLOBALS["dbh"], $sql);
    if (fetch_object($result))
        $existe = true;
    else
        $existe = false;

    free_result($result);
    return $existe;
}
?>
