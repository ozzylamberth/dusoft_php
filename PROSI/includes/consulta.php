<?php

if ($where) {
    $query .= " WHERE " . $where;
    $query_records .= " WHERE " . $where;
} 


if ($grupo){
	$query .= " GROUP BY " .$grupo;
	$query_records .= " GROUP BY " .$grupo;
}

if ($_GET["orientation"])
    $orientation = $_GET["orientation"];
else
    $orientation = 1;

$query .= " ORDER BY " . $order;

if ($orientation == 1)
    $query = $query . " ASC";
else
    $query = $query . " DESC";


$result = execute_query($dbh, $query_records);
$row = fetch_object($result);
$num_records = $row->numreg;
free_result($result);

/*if ($_GET["imprimir"] == "SI"){

$result = execute_query($dbh, $query);
}
else{*/
if (is_numeric($pagina)){ 
	$query = paginar_consulta($query, $pagina);
	
	$result = execute_query($dbh, $query);
}else{
 	
	$query = paginar_consulta($query, 1);
	$result = execute_query($dbh, $query);
	}
/*}*/

?>