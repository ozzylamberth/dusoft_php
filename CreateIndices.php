<?
$VISTA='HTML';
$_ROOT = '';

include $_ROOT . 'includes/enviroment.inc.php';
//GetVariableEntorno();
CrearScriptSlonyInit();

/**
 */
function CrearScriptSlonyInit()
{
	list($dbconn)=GetDBConn();
	$sql="SELECT 
		c.nspname,
		a.relname,
		d.rolname,
		a.relowner,
		b.oid
	FROM
		pg_catalog.pg_class a LEFT JOIN pg_catalog.pg_constraint b
		ON (b.conrelid = a.oid  AND b.contype='p'),
		pg_catalog.pg_namespace c,
		pg_roles d
	WHERE
		a.relnamespace = c.oid AND
		c.nspname IN ('public','cg_conf','cg_mov_01') AND
		a.relkind='r' AND
		b.oid ISNULL AND
		d.oid = a.relowner 
		AND d.rolname='admin'
	ORDER BY 1,2";
	
	GLOBAL $ADODB_FETCH_MODE;
	//Se modifica de acuerdo a la necesidad,la variable $ADODB_FETCH_MODE
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	//Se ejectua la sentecia sql
	$resultado = $dbconn->Execute($sql);
	//Se modifica nuevamente la variable $ADODB_FETCH_MODE
	$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
	//Control de error
	if ($dbconn->ErrorNo() != 0)
	{
		echo "Error en la consulta<br>";
		echo $sql.$dbconn->ErrorMsg()."<br>";
		echo  __FILE__."<br>";
		echo  __LINE__."<br>";                                
		return false;
	}
	$texto="";
	while ($datos_fila = $resultado->FetchRow()) 
	{
		$texto .= "ALTER TABLE \"{$datos_fila['nspname']}\".\"{$datos_fila['relname']}\"  ADD COLUMN indice_automatico serial NOT NULL;\n";
		$texto2 .= "ALTER TABLE \"{$datos_fila['nspname']}\".\"{$datos_fila['relname']}\"  ADD PRIMARY KEY (indice_automatico);\n";
	}
	$nombre_archivo="slony/CreateIndices.sql";
	if (!$gestor = fopen($nombre_archivo, 'w+')) {
         echo "No se puede abrir el archivo ($nombre_archivo)";
         exit;
    }

    // Escribir $contenido a nuestro arcivo abierto.
	 if (fwrite($gestor,$texto) === FALSE) {
        echo "No se puede escribir al archivo ($nombre_archivo)";
        exit;
    }
	
	 if (fwrite($gestor,$texto2) === FALSE) {
        echo "No se puede escribir al archivo ($nombre_archivo)";
        exit;
    }
	 
 
	 fclose($gestor);
	 echo $nombre_archivo;
	 echo "<br>OK";
}

?>