<?php
// Postgres80.class.php  27/01/2005
// ----------------------------------------------------------------------
// Copyright (C) 2005 IPSOFT SA
// www.ipsoft-sa.com
// ----------------------------------------------------------------------
// Autor: Alexander Giraldo
// Proposito del Archivo: Clase para extraer datos del motor y realizar el
// mantenimiento de la estructura.
// ----------------------------------------------------------------------


class Postgres80
{

    function Postgres80()
    {
			return true;
    }

    function GetKernelVersion()
    {
			global     $SIIS_VERSION;
			return $SIIS_VERSION;
    }

  //METODOS DE LA CLASE
  //TABLAS DE LA BASE DE DATOS X
  //*********************************************************
  function TablasBD()
  {
  	list($dbconn)=GetDBConn();
  	$query = "SELECT	c.relname AS tabla,
              				u.usename AS propietario,
                      n.nspname AS schema,
             					pg_catalog.obj_description(c.oid, 'pg_class') AS comentario,
              				c.relnatts AS numcampos,
              				c.reltuples AS numfilasaprox
    					FROM pg_catalog.pg_class c
              	LEFT JOIN pg_catalog.pg_user u ON u.usesysid = c.relowner
              	LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
    					WHERE n.nspname='public' AND c.relkind = 'r'
							ORDER BY c.relname";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $this->fileError = __FILE__;
      $this->lineError = __LINE__;
			return false;
		}
    if(!$resulta->EOF)
     {
	  	 while(!$resulta->EOF)
	  	  {
		 	  	 $datos[]=$resulta->GetRowAssoc($ToUpper = false);
			  	 $resulta->MoveNext();
	  	  }
     }
    $_SESSION['mantenimiento']['schema']='public';
    $resulta->Close();
    return $datos;
  }

  //CAMPOS DE LA TABLA X
  //*********************************************************
  function CamposTablaBD($schema,$tabla)
  {
  	if (empty($schema))
    		$schema='public';

  	list($dbconn)=GetDBconn();
  	$query = "SELECT
                a.attname AS NOMBRE_CAMPO,
                pg_catalog.format_type(a.atttypid, a.atttypmod) AS TIPO_CAMPO,
                case when a.attnotnull='t' then 'NOT NULL'
                  else '' end AS NO_NULO,
                case when a.atthasdef='t' then adef.adsrc
                  else '' end AS DEFAULT,
                case when col_description(a.attrelid, a.attnum) is null then ''
                  else col_description(a.attrelid, a.attnum) end AS COMENTARIO
              FROM pg_catalog.pg_attribute a
                  LEFT JOIN pg_catalog.pg_attrdef adef
                  ON a.attrelid=adef.adrelid
                AND a.attnum=adef.adnum
              WHERE a.attrelid =
                  (
                    SELECT oid
                    FROM pg_catalog.pg_class
                    WHERE relname='$tabla'
                     AND relnamespace =
                      (
                        SELECT oid
                        FROM pg_catalog.pg_namespace
                        WHERE nspname = '$schema'
                      )
                  )
                  AND a.attnum > 0
                  AND NOT a.attisdropped
              ORDER BY a.attnum";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $this->fileError = __FILE__;
      $this->lineError = __LINE__;
			return false;
		}
    if(!$resulta->EOF)
     {
	  	 while(!$resulta->EOF)
	  	  {
		 	  	 $datos[]=$resulta->GetRowAssoc($ToUpper = false);
			  	 $resulta->MoveNext();
	  	  }
     }
		$resulta->Close();
    return $datos;
	}

  function LlavesForaneasTablasBD($op,$schema,$tabla)
  {
  	if (empty($schema))
    		$schema='public';

		if ($op=='pk')
    	$key ='p';

    if($op=='fk')
			$key='f';

  	list($dbconn)=GetDBconn();
  	$query = "SELECT pg_catalog.pg_get_constraintdef(oid) as consrc
				FROM pg_catalog.pg_constraint
				WHERE contype ='$key'
				AND conrelid=
					(
					SELECT oid
					FROM pg_catalog.pg_class
					WHERE relname='$tabla'
					AND relnamespace=
					    (
						SELECT oid
						FROM pg_catalog.pg_namespace
						WHERE nspname='$schema'
						)
					)
				ORDER BY conname";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error en la consulta.";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $this->fileError = __FILE__;
      $this->lineError = __LINE__;
			return false;
		}
    if(!$resulta->EOF)
    {
      while(!$resulta->EOF)
        {
          $datos[]=$resulta->GetRowAssoc($ToUpper = false);
          $resulta->MoveNext();
        }
    }

    $key='';
    return $datos;
	}

  function TablasReferenciadasBD($schema,$tabla)
  {
  	if (empty($schema))
    		$schema='public';

  	list($dbconn)=GetDBconn();
 		$query = "SELECT b.relname AS table_ref
				FROM pg_catalog.pg_constraint a, pg_catalog.pg_class b
				WHERE
				a.contype ='f'
				AND a.confrelid=
				(
					SELECT oid
					FROM pg_catalog.pg_class
					WHERE relname='$tabla'
					AND relnamespace =
						(
              SELECT oid
              FROM pg_catalog.pg_namespace
              WHERE nspname='$schema'
						)
				)
				AND b.oid=a.conrelid";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $this->fileError = __FILE__;
      $this->lineError = __LINE__;
			return false;
		}
    if(!$resulta->EOF)
     {
	  	 while(!$resulta->EOF)
	  	  {
		 	  	 $datos[]=$resulta->GetRowAssoc($ToUpper = false);
			  	 $resulta->MoveNext();
	  	  }
     }
    return $datos;
	}

	//CLAUSULA SELECT
  function Seleccion($sql)
  {
		list($dbconn)=GetDBconn();
		$resulta = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $this->fileError = __FILE__;
      $this->lineError = __LINE__;
			return false;
		}
    if(!$resulta->EOF)
     {
	  	 while(!$resulta->EOF)
	  	  {
		 	  	 $datos[]=$resulta->GetRowAssoc($ToUpper = false);
			  	 $resulta->MoveNext();
	  	  }
     }
    return $datos;
	}

  //selección de los artibutos de la tabla
  function SelCampos($schema,$tabla)
	{
  if (empty($schema))
      $schema='public';

	 list($dbconn)=GetDBconn();
   $sql="SELECT
          a.attname AS NOMBRE_CAMPO
         FROM pg_catalog.pg_attribute a
            LEFT JOIN pg_catalog.pg_attrdef adef
            ON a.attrelid=adef.adrelid
          AND a.attnum=adef.adnum
         WHERE a.attrelid =
            (
              SELECT oid
              FROM pg_catalog.pg_class
              WHERE relname='$tabla'
              AND relnamespace =
                (
                  SELECT oid
                  FROM pg_catalog.pg_namespace
                  WHERE nspname = '$schema'
                )
            )
            AND a.attnum > 0
            AND NOT a.attisdropped
         ORDER BY a.attnum";
		$resulta = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $this->fileError = __FILE__;
      $this->lineError = __LINE__;
			return false;
		}
    if(!$resulta->EOF)
     {
	  	 while(!$resulta->EOF)
	  	  {
		 	  	 $datos[]=$resulta->GetRowAssoc($ToUpper = false);
			  	 $resulta->MoveNext();
	  	  }
     }
    return $datos;
	}

  //función extraer campo de la referencia
  function GetCamposRef($schema,$tabla)
  {
  	if (empty($schema))
    		$schema='public';

	  $sql = "SELECT pg_catalog.pg_get_constraintdef(oid) as consrc
						FROM pg_catalog.pg_constraint
						WHERE contype ='f'
            AND conrelid=
              (
                SELECT oid
                FROM pg_catalog.pg_class
                WHERE relname='$tabla'
                AND relnamespace=
                (
                  SELECT oid
                  FROM pg_catalog.pg_namespace
                  WHERE nspname='$schema'
                )
              )";
		$field = $this->selectField($sql);
		$i=0;
    while ($i<sizeof($field))
		{
      //$params = split('[(.*?)]', $field);
      preg_match('/REFERENCES\s*.*[(](.*?)[)]/i', $field[$i], $params);
      $campos[] = explode(",", $params[1]);
      $i++;
    }
		$i=0;
    while ($i<sizeof($field))
		{
      //$params = split('[(.*?)]', $field);
      preg_match('/FOREIGN\s*KEY\s*[(](.*?)[)]\s*REFERENCES\s*(.*?)[(](.*?)[)]/i', $field[$i], $params);
      $campos1[] = explode(",", $params[1]);
      $campos2[] = explode(",", $params[3]);
      $tablaref[] = explode(",", $params[2]);
      $i++;
    }

  $c=0;
  for ($i=0; $i<sizeof($campos1); $i++)
    {
      for ($j=0; $j<sizeof($campos1[$i]); $j++)
      {
      		$tmp=strcmp(trim($campos1[$i][$j]),trim($campos2[$i][$j]));
					if($tmp!=0)
          {
			      $camposref1[$c] = trim($campos1[$i][$j]);
            $c++;
          }
      }
    }

	$c=0;
  for ($i=0; $i<sizeof($campos2); $i++)
    {
    for ($j=0; $j<sizeof($campos2[$i]); $j++)
      {
			  $camposrefarray = trim($campos2[$i][$j]);
        $camposref2[$c] = trim($campos2[$i][$j]);
        $c++;
			}
    }

    for($i=0; $i<sizeof($campos); $i++)
    {
      $c=chr(65+$i);
      for($j=0; $j<sizeof($campos[$i]); $j++)
      {
				if ($concat=='')
					$concat=$c.'.'.$campos[$i][$j];
				else
					$concat=$concat.', '.$c.'.'.$campos[$i][$j];
      }
    }

    //cadena separada por comas, para armar las consultas
    $_SESSION['mantenimiento']['seleccion']=$concat;
    //variable que almacena las referencias a campos de otra tabla con distinto nombre
    $_SESSION['mantenimiento']['refdisnombre1']=$camposref1;
    $_SESSION['mantenimiento']['refdisnombre2']=$camposref2;
    $_SESSION['mantenimiento']['camposrefarray']=$campos2;
    $_SESSION['mantenimiento']['tablaref']=$tablaref;
    return $campos;
  }

//*********************************************************
 	function selectField($sql)
  {
		// Execute the statement
		//$rs = $this->conn->Execute($sql);
    list($con) = GetDBconn();
    $resulta = $con->Execute($sql);
		if ($con->ErrorNo() != 0)
		{
			$this->error = "Error en la consulta";
			$this->mensajeDeError = "Error DB : " . $con->ErrorMsg();
      $this->fileError = __FILE__;
      $this->lineError = __LINE__;
			return false;
		}
    $i=0;
		while(!$resulta->EOF)
		{
			$datos[$i]=$resulta->fields[0];
			$i++;
			$resulta->MoveNext();
		}
		return $datos;
	}

  //EXTRAER TABLAS DE LA FK O PK
  //función extraer campo de la referencia
  function GetTablasRef($schema,$tabla,$op)
  {
  	list($dbconn)=GetDBconn();
  	if (empty($schema))
    		$schema='public';

		if ($op=='fk')
			$key='f';
    else
			$key='p';

    $sql = "SELECT pg_catalog.pg_get_constraintdef(oid) as consrc
				FROM pg_catalog.pg_constraint
				WHERE contype ='$key'
				AND conrelid=
					(
            SELECT oid
            FROM pg_catalog.pg_class
            WHERE relname='$tabla'
            AND relnamespace=
					  (
              SELECT oid
              FROM pg_catalog.pg_namespace
              WHERE nspname='$schema'
						)
					)";
		$resulta = $dbconn->Execute($sql);

    if(!$resulta->EOF)
     {
	  	 while(!$resulta->EOF)
	  	  {
		 	  	 $datos[]=$resulta->GetRowAssoc($ToUpper = false);
			  	 $resulta->MoveNext();
	  	  }
     }
    if ($op=='fk')
    {
      $i=0;
      while ($i<sizeof($datos))
      {
        preg_match('/REFERENCES\s*(.*)[(]/i', $datos[$i][consrc], $tabla);
        $campos[] = explode(",", $tabla[1]);
        $i++;
      }

      for($i=0; $i<sizeof($campos); $i++)
      {
          $c=chr(65+$i);
        if ($i==sizeof($campos)-1)
          $tablas=$tablas.$campos[$i][0]." ".$c;
        else
          $tablas=$tablas.$campos[$i][0]." ".$c.", ";
      }
      $_SESSION['mantenimiento']['tablasref']=$campos;
      return $tablas;
    }
    elseif ($op=='pk')
    {
      $i=0;
      while ($i<sizeof($datos))
      {
        preg_match('/PRIMARY\s*KEY\s*[(](.*)[)]/i',$datos[$i][consrc], $tabla);
        $campos[] = explode(",", $tabla[1]);
        $i++;
      }

      $k=0;
			for($i=0; $i<sizeof($campos); $i++)
      {
        for($j=0; $j<sizeof($campos[$i]); $j++)
        {
					$campos1[$k]=$campos[$i][$j];
          $k++;
        }
      }
			$_SESSION['mantenimiento']['campospk']=$campos1;
     	return $campos;
		}//fin del if
  }

  function BuscarTablaBD($tabla)
	{
  	list($dbconn)=GetDBConn();
  	$query = "SELECT	u.usename AS propietario,
                      n.nspname AS schema,
             					pg_catalog.obj_description(c.oid, 'pg_class') AS comentario,
              				c.relnatts AS numcampos,
              				c.reltuples AS numfilasaprox
    				FROM pg_catalog.pg_class c
              LEFT JOIN pg_catalog.pg_user u ON u.usesysid = c.relowner
              LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
    				WHERE n.nspname='public' AND c.relkind = 'r' AND c.relname='$tabla'
						ORDER BY c.relname";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $this->fileError = __FILE__;
      $this->lineError = __LINE__;
			return false;
		}
    if(!$resulta->EOF)
     {
	  	 while(!$resulta->EOF)
	  	  {
		 	  	 $datos[]=$resulta->GetRowAssoc($ToUpper = false);
			  	 $resulta->MoveNext();
	  	  }
     }
    $_SESSION['mantenimiento']['schema']='public';
    $resulta->Close();
    return $datos;
  }
  //CONSULTA PARA TRAER LOS TRIGGERS DE UNA TABLA
/*
SELECT t.tgname, t.tgisconstraint, t.tgdeferrable, t.tginitdeferred, t.tgtype,
			t.tgargs, t.tgnargs, t.tgconstrrelid,
			(SELECT relname FROM pg_class c2 WHERE c2.oid=t.tgconstrrelid) AS tgconstrrelname,
			(SELECT proname FROM pg_proc p WHERE t.tgfoid=p.oid) AS tgfname,
			c.relname, NULL AS tgdef
			FROM pg_trigger t, pg_class c
			WHERE t.tgrelid=c.oid
			AND c.relname='cuentas_detalle' and t.tgisconstraint='FALSE'
*/
	//*********************************************************

}//End class.

?>

