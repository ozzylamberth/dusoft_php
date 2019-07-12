<?php

function pg_timefmt($format, $where = PG_TIME)
{
 ini_set($where, $format);
}

function is_date($valor){
	if (!isset($valor))
		return false;
		
	if (!ereg("^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$", $valor))
		return false;
	else{
		$aux = explode("/", $valor);
		if ($aux[0] < 1 || $aux[0] > 31) return false;
		if ($aux[1] < 1 || $aux[1] > 12) return false;
		
		return true;
	}
}

function procesar_entrada(){
	for($i = 1; $i < func_num_args(); $i ++)
		if (func_get_arg(0) == "POST"){
			if (!isset($_POST[func_get_arg($i)]))
				$_POST[func_get_arg($i)] = "";
		} else {
			if (!isset($_GET[func_get_arg($i)]))
				$_GET[func_get_arg($i)] = "";
		}
}

function get_value($variable, $tipo)
{
	if (empty($variable)) 
		$variable = "";	
	
	switch($tipo){
		case "N": if (is_numeric($variable))
					return $variable;
				  else
				  	return 0;
					
		case "C": $variable = str_replace("'", "", $variable);
				  $variable = htmlspecialchars($variable);
				  return $variable;
				  
		case "D": if (is_date($variable)) 
					return $variable;
				  else
				  	return ""; 
	}
} 

function build_where()
{
    $where = "";

    for($i = 0; $i < func_num_args(); $i ++) {
        $campo = func_get_arg($i);
        $valor = func_get_arg(++ $i);
        $tipo = func_get_arg(++ $i);
        if ($valor) {
            if ($where)
                $where = $where . " AND ";
            if ($tipo == "C")
                $where = $where . "UPPER(" . $campo . ") LIKE '%" . strtoupper($valor) . "%'";
            else
                $where = $where . $campo . "=" . $valor;
        } 
    } 
    return $where;
}

function build_beetwen($campo, $valor1, $valor2, $tipo)
{
    $query = "";

    if ($tipo == "C") {
        if ($valor1)
            $query = $campo . " >= '" . $valor1 . "'";

        if ($valor2) {
            if ($query) $query .= " AND ";
            $query .= $campo . " <= '" . $valor2 . "'";
        } 
    } else {
        if ($valor1)
            $query = $campo . " >= " . $valor1;

        if ($valor2) {
            if ($query) $query .= " AND ";
            $query .= $campo . " <= " . $valor2;
        } 
    } 

    return $query;
} 

function paginar_consulta($consulta, $pagina){
 	$limit = "LIMIT ".$GLOBALS["records_per_page"]."";
 	$cantidad = "OFFSET ".($pagina - 1) * $GLOBALS["records_per_page"]."";
	return $consulta." ".$limit." ".$cantidad;
}

function formatdate($datestring)
{
	if ($datestring) {
        $chunks = explode("-", $datestring);
        $formatted = "$chunks[0]-$chunks[1]-$chunks[2]";
        
    } else {
		$formatted = "";
	} 
    return $formatted;
}


function querystring_changeval($key, $value)
{
    $querystring = "";
    $found = false;

    reset($_GET);
    while (list($clave, $valor) = each($_GET)) {
        if ($querystring)
            $querystring .= "&";

        if ($clave == $key) {
            $querystring .= $clave . "=" . $value;
            $found = true;
        } else
            $querystring .= $clave . "=" . $valor;
    } 

    if (!$found) {
        if ($querystring)
            $querystring .= "&" . $key . "=" . $value;
        else
            $querystring .= $key . "=" . $value;
    } 
    return $querystring;
} 

function setOrientation($orientation)
{
    echo "<a href='" . $_SERVER['PHP_SELF'] . "?" . querystring_changeval("orientation", ($orientation == "1")?"2":"1") . "'>";
    echo "<img src=" . ($orientation == "1"?"'imagenes/up.gif'":"'imagenes/down.gif'") . " width='12' height='12' align='absmiddle' border=0></a>";
} 
// Escribe los números de paginas
function set_numpages($num_records, $pagina)
{
    $num_pages = (int)($num_records / $GLOBALS["records_per_page"]);

    if ($num_records % $GLOBALS["records_per_page"] > 0 && $num_pages > 0)
        $num_pages++;

    for($i = 1; $i <= $num_pages; $i++) {
        if ($i > 1) echo "&nbsp;-&nbsp;";
        echo "<a href='" . $_SERVER["PHP_SELF"] . "?" . querystring_changeval("pagina", $i) . "'>";
        if ($i == $pagina || (!$pagina && $i == 1))
            echo "<b>" . $i . "</b></a>";
        else
            echo $i . "</a>";
    } 

   
}
function set_numpages1($num_records, $pagina)
{
    $num_pages = (int)($num_records / $GLOBALS["records_per_page"]);
    
    $anterior = $pagina - 1;
  	$posterior = $pagina + 1;
    if ($pagina>1)
    echo "<a href='" . $_SERVER["PHP_SELF"] . "?" . querystring_changeval("pagina", $anterior) . "'>&laquo;</a> ";
  else
    echo "<b>&laquo;</b> ";
    
    for ($i=1; $i<$pagina; $i++)
    echo "<a href='" . $_SERVER["PHP_SELF"] . "?" . querystring_changeval("pagina", $i) . "'>$i</a> ";
  	echo "<b>$pagina</b> ";
  for ($i=$pagina+1; $i<=$num_pages+1; $i++)
    echo "<a href='" . $_SERVER["PHP_SELF"] . "?" . querystring_changeval("pagina", $i) . "'>$i</a> ";
  if ($pagina<$num_pages+1)
   	echo "<a href='" . $_SERVER["PHP_SELF"] . "?" . querystring_changeval("pagina", $posterior) . "'>&raquo;</a>";
  else
    echo "<b>&raquo;</b>";

}



function existe_valor($tabla, $campo, $valor, $type)
{
    $existe = false;

    if ($type == "N")
        $sql = "SELECT " . $campo . " FROM " . $tabla . " WHERE " . $campo . " = " . $valor;

    if ($type == "C")
        $sql = "SELECT " . $campo . " FROM " . $tabla . " WHERE UPPER(" . $campo . ") = UPPER('" . $valor . "')";

    $result = execute_query($dbconn, $sql);
    if (fetch_object($result))
        $existe = true;
    else
        $existe = false;

    free_result($result);
    return $existe;
} 

function calcular_importe($cantidad, $precio, $dto)
{
    $precio = $precio - ($dto * $precio / 100);
    $resultado = $precio * $cantidad;
    return $resultado;
} 

function leer_empresa()
{
    $result = execute_query($dbconn, "SELECT * FROM CONFIGURACION");
    $row = fetch_object($result);
    $empresa = array("empresa" => $row->EMPRESA,
        "cif" => $row->CIF,
        "dircompleta" => $row->DIRCOMPLETA,
        "dirimagen" => $row->LOGO,
        "telefono" => $row->TELEFONO);
    free_result($result);
    return $empresa;
} 

function rellenar_ceros($codigo, $longitud){
	return str_repeat("0", $longitud - strlen($codigo)).$codigo;		
}
?>
