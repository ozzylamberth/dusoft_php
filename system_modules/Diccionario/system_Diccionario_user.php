
<?php

/**
* Modulo de Diccionario (PHP).
*
* Modulo para el manejo de los tarifarios
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* system_Diccionario_user.php
*
//*
**/

class system_Diccionario_user extends classModulo
{
	function system_Diccionario_user()
	{
		return true;
	}

	function main()//Principal
	{
	//1 activa
	//0 inactiva
		$_SESSION['editar']=0;//variable que inhabilita las ediciones
		UNSET($_SESSION['Diccionario']['tabla']);
		UNSET($_SESSION['Diccionario']['campo']);
		list($dbconn)=GetDbConn();
		$shemas=$this->GetEsquemasDB();
		$this->AbrirTablas();
		FOREACH($shemas AS $K => $V)
		{
		    $tablas=$this->GetTablasDB($V[0]);
			$this->FormaTablas($V[0],$tablas);
		}
		$this->CerrarTablas();
		return true;
	}

	function ListCamposTabla()//Lista los campos de una tabla
	{
		if(empty($_REQUEST['NombreEsquema']) OR empty($_REQUEST['NombreTabla']))
		{
			$this->main();
			return true;
		}
		$tabla=$this->GetTablasDB($_REQUEST['NombreEsquema'],$_REQUEST['NombreTabla']);
		$campos=$this->GetCamposTablaDB($_REQUEST['NombreEsquema'],$_REQUEST['NombreTabla']);
		$forkey=$this->GetFK($_REQUEST['NombreEsquema'],$_REQUEST['NombreTabla']);
		$refforkey=$this->GetTablasReferenciadas($_REQUEST['NombreEsquema'],$_REQUEST['NombreTabla']);
		$this->FormaCampos($_REQUEST['NombreEsquema'],$tabla,$campos,$forkey,$refforkey);
		return true;
	}

	function ListCamposTablaEdicion()//Lista los campos de una tabla, para modificarlos
	{
		if(empty($_REQUEST['NombreEsquema']) OR empty($_REQUEST['NombreTabla']))
		{
			$this->main();
			return true;
		}
		$tabla=$this->GetTablasDB($_REQUEST['NombreEsquema'],$_REQUEST['NombreTabla']);
		$campos=$this->GetCamposTablaDB($_REQUEST['NombreEsquema'],$_REQUEST['NombreTabla']);
		$this->FormaCamposEdicion($_REQUEST['NombreEsquema'],$tabla,$campos);
		return true;
	}

	function GetEsquemasDB()//Busca los esquemas de la BD
	{
		list($dbconn)=GetDBConn();
		$sql = "SELECT pn.nspname,
				pu.usename AS nspowner
				FROM pg_catalog.pg_namespace pn, pg_catalog.pg_user pu
				WHERE pn.nspowner = pu.usesysid
				AND nspname NOT LIKE 'pg_%'
				ORDER BY nspname";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$shemas=$result->GetRows();
		$result->Close();
		return $shemas;
	}

	function GetTablasDB($schema,$tabla='')//Busca las tablas de un esquema
	{
		list($dbconn)=GetDBConn();
		if(empty($tabla))
		{
			$filtro="AND c.relname NOT LIKE 'pg_%'";
		}
		else
		{
			$filtro="AND c.relname = '$tabla'";
		}
		$sql = "SELECT
				c.relname AS tabla,
				u.usename AS propietario,
				pg_catalog.obj_description(c.oid, 'pg_class') AS comentario,
				c.relnatts AS nucampos,
				c.reltuples AS nufilasaprox
				FROM pg_catalog.pg_class c
					LEFT JOIN pg_catalog.pg_user u ON u.usesysid = c.relowner
					LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
				WHERE c.relkind = 'r'
				$filtro
				AND n.nspname = '$schema' ORDER BY c.relname";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$tablas=$result->GetRows();
		$result->Close();
		return $tablas;
	}

	function GetCamposTablaDB($shema,$tabla)//Busca los campos de una tabla
	{
		list($dbconn)=GetDBConn();
		$sql = "SELECT
				a.attname AS \"NOMBRE CAMPO\",
				pg_catalog.format_type(a.atttypid, a.atttypmod) AS \"TIPO CAMPO\",
				case when a.attnotnull='t' then 'NOT NULL'
					else '' end AS \"NO NULO\",
				case when a.atthasdef='t' then adef.adsrc
					else '' end AS \"DEFAULT\",
				case when col_description(a.attrelid, a.attnum) is null then ''
					else col_description(a.attrelid, a.attnum) end AS \"COMENTARIO\"
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
						WHERE nspname = '$shema'
						)
					)
				AND a.attnum > 0
				AND NOT a.attisdropped
				ORDER BY a.attnum";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$campos=$result->GetRows();
		$result->Close();
		return $campos;
	}

	function ReturnSQLCommentTabla($shema,$tabla,$comment='')//Modifica el comentario de la tabla
	{
		$comment=trim($comment);
		$sql = "COMMENT ON TABLE \"$shema\".\"$tabla\" IS ";
		if ($comment == '')
		{
			$sql .= 'NULL; ';
		}
		else
		{
			$sql .= "'$comment'; ";
		}
		return $sql;
	}

	function ReturnSQLCommentColumn($shema,$tabla,$column,$comment='')//Modifica el comentario del campo
	{
		$comment=trim($comment);
		$sql = "COMMENT ON COLUMN \"$shema\".\"$tabla\".\"$column\" IS ";
		if ($comment == '')
		{
			$sql .= 'NULL; ';
		}
		else
		{
			$sql .= "'$comment'; ";
		}
		return $sql;
	}

	function GrabarComentario()//Función que guarda los comentarios
	{
		$tabla=$_SESSION['Diccionario']['tabla'];//tabla con sus atributos
		$campos=$_SESSION['Diccionario']['campo'];//campos con sus atributos
		UNSET($_SESSION['Diccionario']['tabla']);
		UNSET($_SESSION['Diccionario']['campo']);
		list($dbconn)=GetDBConn();
		$dbconn->BeginTrans();
		$sqlcom=$this->ReturnSQLCommentTabla($_POST['shema'],$tabla[0][0],$_POST['tablacomen']);
		$result=$dbconn->Execute($sqlcom);
		if($dbconn->ErrorNo() != 0)
		{
			$dbconn->RollBackTrans();
		}
		$i=0;
		$ciclo=sizeof($campos);
		while($i<$ciclo)
		{
			$sql.=$this->ReturnSQLCommentColumn($_POST['shema'],$tabla[0][0],$campos[$i][0],$_POST['comentario'.$campos[$i][0]]);
			$i++;
		}
		$result=$dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$dbconn->RollBackTrans();
		}
		$dbconn->CommitTrans();
		$this->main();
		return true;
	}

	function BuscarTablas()//Busca las tablas según una coincidencia
	{
		list($dbconn)=GetDBConn();
		$sql = "SELECT b.nspname AS shema,
				a.relname AS tabla,
				c.usename,
				pg_catalog.obj_description(a.oid, 'pg_class') AS comentario
				FROM pg_catalog.pg_class a, pg_catalog.pg_namespace b, pg_catalog.pg_user c
				WHERE a.relkind = 'r'
				AND a.relnamespace=b.oid
				AND a.relname LIKE '%$_POST[NomTablaDic]%'
				AND a.relowner=c.usesysid
				AND a.relname NOT LIKE 'pg_%'
				AND a.relnamespace=b.oid
				order by b.nspname, a.relname;";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$tablas=$result->GetRows();
		$result->Close();
		$this->AbrirTablas();
		$this->FormaTablasBusqueda($tablas,$_POST[NomTablaDic]);
		$this->CerrarTablas();
		return true;
	}

	function BuscarCampos()//Busca los campos según una coincidencia
	{
		list($dbconn)=GetDBConn();
		$sql = "SELECT c.nspname,
				b.relname,
				a.attname,
				pg_catalog.format_type(a.atttypid, a.atttypmod) AS type,
				col_description(a.attrelid, a.attnum) AS coment
				FROM pg_catalog.pg_attribute a, pg_catalog.pg_class b, pg_catalog.pg_namespace c
				WHERE a.attrelid=b.oid
				AND b.relname NOT LIKE 'pg_%'
				AND a.attnum>0
				AND b.relkind='r'
				AND a.attisdropped='f'
				AND a.attname LIKE '%$_POST[NomCampoDic]%'
				AND b.relnamespace=c.oid
				order by c.nspname, b.relname;";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$campos=$result->GetRows();
		$result->Close();
		$this->AbrirTablas();
		$this->FormaCamposBusqueda($campos,$_POST[NomCampoDic]);
		$this->CerrarTablas();
		return true;
	}

	function BuscarComentario()//Busca los comentarios según una coincidencia
	{
		list($dbconn)=GetDBConn();
		$sql = "SELECT c.nspname,
				b.relname,
				a.attname,
				pg_catalog.format_type(a.atttypid, a.atttypmod) AS type,
				col_description(a.attrelid, a.attnum) AS coment
				FROM pg_catalog.pg_attribute a, pg_catalog.pg_class b, pg_catalog.pg_namespace c
				WHERE a.attrelid=b.oid
				AND b.relname NOT LIKE 'pg_%'
				AND a.attnum>0
				AND b.relkind='r'
				AND a.attisdropped='f'
				AND col_description(a.attrelid, a.attnum)
				LIKE '%$_POST[NomComenDic]%'
				AND b.relnamespace=c.oid
				order by c.nspname, b.relname;";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$coment=$result->GetRows();
		$result->Close();
		$this->AbrirTablas();
		$this->FormaComentBusqueda($coment,$_POST[NomComenDic]);
		$this->CerrarTablas();
		return true;
	}

	function GetTablasReferenciadas($shema,$tabla)
	{
		list($dbconn)=GetDBConn();
		$sql = "SELECT b.relname
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
						WHERE nspname='$shema'
						)
				)
				AND b.oid=a.conrelid";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$rfk=$result->GetRows();
		$result->Close();
		return $rfk;
	}

	function GetFK($shema,$tabla)//Busca las llaves foraneas de una tabla
	{
		list($dbconn)=GetDBConn();
		$sql = "SELECT conname,
				pg_catalog.pg_get_constraintdef(oid) as consrc
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
						WHERE nspname='$shema'
						)
					)
				ORDER BY conname";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$fk=$result->GetRows();
		$result->Close();
		return $fk;
	}

	function ImprimirComments()//Función que coloca el comentario a los campos comunes a varias tablas
	{
		list($dbconn)=GetDBConn();
		$sql="select 'COMMENT ON COLUMN \"' || A.nspname || '\".\"' || A.relname || '\".\"' || A.attname || '\" IS \'$_POST[NomComen]\';'
					FROM (
						SELECT
						c.nspname, b.relname, a.attname
						FROM pg_catalog.pg_attribute a, pg_catalog.pg_class b, pg_catalog.pg_namespace c
						WHERE a.attrelid=b.oid
						and b.relname NOT LIKE 'pg_%'
						and a.attnum>0
						and b.relkind='r'
						and a.attisdropped='f'
						and a.attname LIKE '%$_POST[NomCampo]%'
						and b.relnamespace=c.oid
						order by c.nspname,b.relname
					) AS A;";
		$result=$dbconn->Execute($sql);
		if($result->EOF)
		{
            $this->salida = "No se econtro el campo $_POST[NomCampo].";
		}
		else
		{
			$comments=$result->GetRows();
			foreach($comments AS $k => $v)
			{
				$this->salida .= "$v[0] <br>";
			}
		}
		return true;
	}

}//fin de la clase
?>
