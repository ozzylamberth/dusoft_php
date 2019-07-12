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
  		if($pagina-10 <= 0){
			$corte_inicio = 1;
		}
		else{
			$corte_inicio = $pagina - 10;
		}
		
		if($pagina+10 > $num_pages+1){
			$corte = $num_pages+1;
		}
		else{
			$corte = $pagina + 10;
		}
  	
  	$paginacion ="<p align=center>";
  	
    if ($pagina>1){
    $paginacion .= "<a href='" . $_SERVER["PHP_SELF"] . "?" . querystring_changeval("pagina", 1) . "'>&laquo;Primero</a> ";
    $paginacion .= "<a href='" . $_SERVER["PHP_SELF"] . "?" . querystring_changeval("pagina", $anterior) . "'>&nbsp;&nbsp;&laquo; Anterior</a> ";
    }
  else{
    $paginacion .= "<b>&laquo;Primero</b> ";
    $paginacion .= "<b>&nbsp;&nbsp;&laquo;Anterior</b> ";
    }

    for ($i=$corte_inicio; $i<$pagina; $i++)
    $paginacion .= "<a href='" . $_SERVER["PHP_SELF"] . "?" . querystring_changeval("pagina", $i) . "'>$i</a> ";
  	$paginacion .= "<b>$pagina</b> ";
  for ($i=$pagina+1; $i<=$corte; $i++)
    $paginacion .= "<a href='" . $_SERVER["PHP_SELF"] . "?" . querystring_changeval("pagina", $i) . "'>$i</a> ";
    
  if ($pagina<$num_pages+1){
   	$paginacion .= "<a href='" . $_SERVER["PHP_SELF"] . "?" . querystring_changeval("pagina", $posterior) . "'> Siguiente&raquo;</a>";
   	$paginacion .= "<a href='" . $_SERVER["PHP_SELF"] . "?" . querystring_changeval("pagina", $num_pages+1) . "'>&nbsp;&nbsp;Ultimo&raquo;</a>";
   	}
  else{
    $paginacion .= "<b>Siguiente&raquo; </b>";
    $paginacion .= "<b>&nbsp;&nbsp;Ultimo&raquo;</b>";
    }
	$paginacion .= "</p>";
echo $paginacion;
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

function PerfilOpcionUsuario($usuario_id, $cod_menu, $proceso, $dbh)
		{
		  
		
		$query_perfil = "SELECT a.*
					FROM usuarios_menu_prosi a
					WHERE a.cod_menu = ".$cod_menu."
					AND a.usuario_id = ".$usuario_id."";
			
			$result_perfil=execute_query($dbh, $query_perfil);
			
			$row = fetch_object($result_perfil);
        	
        	if($proceso == "insert"){
        	 
				if($row->sw_insert == '0'){
					$permiso = false;
					
				}
				else{
					$permiso = true;
					
				}
			}
			else if($proceso == "update"){
				if($row->sw_update == '0'){
					$permiso = false;
				}
				else{
					$permiso = true;
				}
			}
			else if($proceso == "delete"){
				if($row->sw_delete == '0'){
					$permiso = false;
				}
				else{
					$permiso = true;
				}
			}
			else if($proceso == "select"){
				if($row->sw_select == '0'){
					$permiso = false;
				}
				else{
					$permiso = true;
				}
			}
			else if($proceso == "print"){
				if($row->sw_print == '0'){
					$permiso = false;
				}
				else{
					$permiso = true;
				}
				
			}

    		free_result($result_perfil);
    		return $permiso;
		}
		
function suma_fechas($fecha,$ndias)
	{
		if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
		list($ano,$mes,$dia)=split("/", $fecha);
		if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
		list($ano,$mes,$dia)=split("-",$fecha);
		$nueva = mktime(0,0,0, $mes,$dia,$ano) + $ndias * 24 * 60 * 60;
		$nuevafecha=date("Y-m-d",$nueva);
		return $nuevafecha;
	}		
?>
