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
  
$sql  = "SELECT c.relname, n.nspname "; 
$sql .= "FROM  pg_catalog.pg_class c, "; 
$sql .= "      pg_catalog.pg_user u,  ";
$sql .= "      pg_catalog.pg_namespace n  ";
$sql .= "WHERE	c.relowner=u.usesysid  ";
$sql .= "AND   c.relnamespace=n.oid  ";
$sql .= "AND   c.relkind = 'S'  ";
//$sql .= "AND   n.nspname  IN ('public','cg_conf','cg_mov_01') ";
$sql .= "AND   n.nspname  = 'public' ";
$sql .= "ORDER BY 2,1 ";  

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
  $texto .= "'{$datos_fila['nspname']}.{$datos_fila['relname']}',\n";
 }
 $nombre_archivo="slony/SECUENCIAS.sql";
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