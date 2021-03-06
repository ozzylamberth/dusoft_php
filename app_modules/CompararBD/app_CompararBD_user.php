<?php
/**
 * $Id: app_CompararBD_user.php,v 1.5 2006/01/09 18:49:35 ehudes Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

 /**
  */
class app_CompararBD_user  extends classModulo
{
	/**
	 * Conexion a la base de datos 1
	 *
	 * @var object
	 */
	var $dbconn1;
	
	/**
	 * Conexion a la base de datos 2
	 *
	 * @var object
	 */
	var $dbconn2;
	
	/**
	 * Error
	 *
	 * @var string
	 */
	 var $error;
	 
	/**
	 * Mensaje de error
	 *
	 * @var string
	 */
	var $mensajeDeError;
	 
	
	/**
	 * Arreglo con las tablas que est?n en db1 y no est?n en db2
	 *
	 * @var array
	 */
	var $TablasNoDB1;

	/**
	 * Arreglo con las tablas que est?n en db1 y no est?n en db2
	 *
	 * @var array
	 */
	var $TablasNoDB2;

	/**
	 * Arreglo con las diferencias de las tablas que est?n en db1 y en db2
	 * 
	 * @var array
	 */
	var $TablasDiferencias;

	/**
	 * Arregon con los objetos de la base de datos que se deben comparar
	 * Array('campos','indices','triggers','restricciones') 
	 *
	 * @var array
	 */
	var $ComparaTablas;
	
	/**
	 * Funciones que estan en db1 y no est?n en db2
	 *
	 */
	var $Funciones1=array();
	
	/**
	 * Funciones que estan en db2 y no est?n en db1
	 *
	 * @var array
	 */
	var $Funciones2=array();
	
	/**
	 * Funciones que estan en db1 y est?n en db2 y tienen diferencias
	 *
	 * @var array
	 */
	var $Funciones3=array();
	
	/**
	 * Vistas que est?n en db1 y no est?n en db2
	 *
	 * @var array
	 */
	var $Vistas1;
	
	/**
	 * Vistas que est?n en db2 y no est?n en db1
	 *
	 * @var array
	 */
	var $Vistas2;
	
	/**
	 * Secuencias que est?n en db1 y no est?n en db2
	 *
	 * @var array
	 */
	var $Secuencias1;
	
	/**
	 * Vistas que est?n en db2 y no est?n en db1
	 *
	 * @var array
	 */
	var $Secuencias2;

	
	/**
	 * Constructor
	 */
	function app_CompararBD_user()
	{
		$this->error="";
		$this->mensajeDeError="";
	}//Fin constructor

	/**
	 * Carga las conexiones a las dos bases de datos
	 */
	function Conexiones()
	{
		if (!$this->GetConnDB1())
		{
			return false;
		}

		if (!$this->GetConnDB2())
		{
			return false;
		}
		return true;
	}//Fin Conexiones
	
	/**
	 * Compara las bases de datos de acuerdo al parametro vector $comparar
	 * este vector debe tener la siguiente estructura
	 * Array(
			tablas=>1,campos=>1,restricciones=>1,indices=>1,triggers=1,
			funciones=>1,vistas=>1,secuencias=>1
		)
	 * y al menos debe contener un campo de los anteriores
	 *
	 * @param array comparar
	 * @return boolean
	 */
	function CompararBasesDeDatos($comparar)
	{
		if(!$this->Conexiones())
			return false;
		if(!empty($comparar['campos']))
			$this->CompararTablas['campos']='campos';
		if(!empty($comparar['restricciones']))
			$this->CompararTablas['restricciones']='restricciones';
		if(!empty($comparar['indices']))
			$this->CompararTablas['indices']='indices';
		if(!empty($comparar['triggers']))
			$this->CompararTablas['triggers']='triggers';
		if(!$this->CompararTablas($comparar['tablas']))
			return false;
		if(!empty($comparar['funciones']))
		{
			if(!$this->CompararFunciones())
				return false;
		}
		if(!empty($comparar['vistas']))
		{
			if(!$this->CompararVistas())
				return false;
		}
		if(!empty($comparar['secuencias']))
		{
			if(!$this->CompararSecuencias())
				return false;
		}
		return true;
	}//Fin CompararBasesDeDatos

	/**
	 * Devuelve los datos del ConfigDb
	 */
	function GetDatosConexionActual()
	{
		global $ConfigDB;
		return $ConfigDB;
	}//Fin GetDatosConexionActual

	/**
	 * Crea la conexion a la base de datos 1
	 */
	function GetConnDB1()
	{
		$this->dbconn1=ADONewConnection('postgres');
		if (!($this->dbconn1->Connect($_POST['HOST1'], $_POST['UserBD1'], $_POST['Paswd1'],$_POST['BD1'])))
		{
			$this->error = "Error en la conexi?n";
			$this->mensajeDeError = "No se pudo crear la conexi?n a la base de datos {$_POST['BD1']}";
			return false;
		}
		return true;
	}//Fin GetConnDB1

	/**
	 * Crea la conexion a la base de datos 2
	 */
	function GetConnDB2()
	{
		$this->dbconn2=ADONewConnection('postgres');
		if (!($this->dbconn2->Connect($_POST['HOST2'], $_POST['UserBD2'], $_POST['Paswd2'],$_POST['BD2'])))
		{
			$this->error = "Error en la conexion";
			$this->mensajeDeError = "No se pudo crear la conexion a la base de datos {$_POST['BD2']}";
			return false;
		}
		return true;
	}//Fin GetConnDB2
	
	/**
	 * Carga en un vector las tablas correspondientes a la base de datos
	 * 
	 * @param object $db
	 * @return array
	 */
	function CargarTablas($dbconn)
	{
		$sql = "
			SELECT
				tablename 
			FROM
				pg_catalog.pg_tables
			WHERE
				schemaname='public'
			ORDER BY
				tablename";
		$resultado = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error en la consulta";
			$this->mensajeDeError = "Error DB : " . $conndb1->ErrorMsg();
			return false;
		}
		while ($a = $resultado->FetchRow())
		{
			$Tablas[]=$a[0];
		}
		return $Tablas;
	}//Fin Cargar Tablas
	
	/**
	 * Carga en un vector los indices de una o todas las tabas de la bd
	 *
	 * @param object dbconn
	 * @param string tabla
	 * @return array
	 */
	function CargarIndices($dbconn,$tabla=null)
	{
		//sql para consultar los indices de las tablas de la bd
		if(!empty($tabla))
		{
			$where="c.relname = '$tabla' AND \n";
		}
		else
			$where="";
		$sql_indices="
			SELECT 
				c.relname,
				--c2.relname AS indname, 
				--i.indisprimary, 
				--i.indisunique, 
				--i.indisclustered, 
				pg_catalog.pg_get_indexdef(i.indexrelid, 0, true) AS inddef 
			FROM 
				pg_catalog.pg_class c, 
				pg_catalog.pg_class c2, 
				pg_catalog.pg_index i,
				pg_catalog.pg_namespace pn
			WHERE 
				$where 
				c.relnamespace=pn.oid AND
				pn.nspname='public' AND
				pg_catalog.pg_table_is_visible(c.oid) AND 
				c.oid = i.indrelid AND 
				i.indexrelid = c2.oid
			ORDER BY
				c2.relname";
		GLOBAL $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($sql_indices);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error en la consulta";
			$this->mensajeDeError = "Error DB : " . $conndb1->ErrorMsg();
			return false;
		}
		while ($a = $resultado->FetchRow())
		{
			$Indices[$a['relname']][]=$a['inddef'];
		}
		return $Indices;
	}//Fin CargarIndices
	
	/**
	 * Carga en un vector los triggers de una o todas las tabas de la bd
	 *
	 * @param object dbconn
	 * @param string tabla
	 * @return array
	 */
	function CargarTriggers($dbconn,$tabla=null)
	{
		//sql para consultar los triggers de las tablas de la base de datos
		if(!empty($tablas))
		{
			$sql_triggers = "
			SELECT 
				'$tabla' as relname,
				--t.tgname, 
				pg_catalog.pg_get_triggerdef(t.oid) AS tgdef
			FROM pg_catalog.pg_trigger t
			WHERE t.tgrelid = 
			(
				SELECT oid 
				FROM pg_catalog.pg_class 
				WHERE relname='$tabla'
				AND relnamespace=
				(
					SELECT oid 
					FROM pg_catalog.pg_namespace 
					WHERE nspname='public'
				)
			)
			AND 
			(
				NOT tgisconstraint OR NOT EXISTS
				(
					SELECT 1 
					FROM pg_catalog.pg_depend d JOIN pg_catalog.pg_constraint c
						ON (d.refclassid = c.tableoid AND d.refobjid = c.oid)
					WHERE d.classid = t.tableoid AND d.objid = t.oid AND d.deptype = 'i' AND c.contype = 'f'
				)
			)";
		}
		else
		{
			$sql_triggers="
			SELECT
				pc.relname,
				--t.tgname,
				pg_catalog.pg_get_triggerdef(t.oid) AS tgdef 
			FROM 
				pg_catalog.pg_trigger t,
				pg_catalog.pg_class pc,
				pg_catalog.pg_namespace pn
			WHERE 
				t.tgrelid=pc.oid AND
				pc.relnamespace=pn.oid AND
				pn.nspname='public' AND
				(
					NOT tgisconstraint OR NOT EXISTS 
					(
						SELECT 1 
						FROM 
							pg_catalog.pg_depend d JOIN pg_catalog.pg_constraint c ON 
							(d.refclassid = c.tableoid AND d.refobjid = c.oid) 
						WHERE 
							d.classid = t.tableoid AND 
							d.objid = t.oid AND 
							d.deptype = 'i' AND 
							c.contype = 'f'
					)
				)";
		}
		GLOBAL $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($sql_triggers);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error en la consulta";
			$this->mensajeDeError = "Error DB : " . $conndb1->ErrorMsg();
			return false;
		}
		while ($a = $resultado->FetchRow())
		{
			$Triggers[$a['relname']][]=$a['tgdef'];
		}
		return $Triggers;
	}//Fin CargarTriggers
	
	/**
	 * Carga las restricciones de todas las tablas o de una tabla
	 *
	 * @param object dbconn
	 * @param string tabla
	 * @return array
	 */
	function CargarRestricciones($dbconn,$tabla=null)
	{
		//sql para consultar los constraints de las tablas de la bd.
		if(!empty($tabla))
		{
			$sql_restricciones = "
			SELECT
				'$tabla' as relname,
				--pc.conname,
				pg_catalog.pg_get_constraintdef(pc.oid, true) AS consrc,
				--pc.contype,
				CASE WHEN pc.contype='u' OR pc.contype='p' THEN (
					SELECT
						indisclustered
					FROM
						pg_catalog.pg_depend pd,
						pg_catalog.pg_class pl,
						pg_catalog.pg_index pi
					WHERE
						pd.refclassid=pc.tableoid 
						AND pd.refobjid=pc.oid
						AND pd.objid=pl.oid
						AND pl.oid=pi.indexrelid
				) ELSE
					NULL
				END AS indisclustered
			FROM
				pg_catalog.pg_constraint pc
			WHERE
				pc.conrelid = (SELECT oid FROM pg_catalog.pg_class WHERE relname='{$tabla}'
					AND relnamespace = (SELECT oid FROM pg_catalog.pg_namespace
					WHERE nspname='public'))
			ORDER BY
				1";
		}
		else
		{
			$sql_restricciones="
			SELECT
				pcl.relname,
				--pc.conname,
				pg_catalog.pg_get_constraintdef(pc.oid, true) AS consrc,
				--pc.contype,
				CASE WHEN pc.contype='u' OR pc.contype='p' THEN
				(
					SELECT
						indisclustered
					FROM
						pg_catalog.pg_depend pd,
						pg_catalog.pg_class pl,
						pg_catalog.pg_index pi
					WHERE
						pd.refclassid=pc.tableoid 
						AND pd.refobjid=pc.oid
						AND pd.objid=pl.oid
						AND pl.oid=pi.indexrelid
				)
				ELSE
					NULL
				END AS indisclustered
			FROM
				pg_catalog.pg_constraint pc,
				pg_catalog.pg_class pcl,
				pg_catalog.pg_namespace pn
			WHERE
				pc.conrelid = pcl.oid AND
				pcl.relnamespace = pn.oid AND
				pn.nspname='public'
			ORDER BY
				1,2";
		}
		GLOBAL $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($sql_restricciones);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error en la consulta";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while ($a = $resultado->FetchRow())
		{
			$Restricciones[$a['relname']][]=$a['consrc'];
		}
		return $Restricciones;
	}//Fin CargarRestricciones
	
	/**
	 * Carga en un vector las columnas de una tabla
	 * 
	 * @param object dbconn
	 * @param string tabla
	 * @return array
	 */
	function CargarColumnas($dbconn,$tabla)
	{
		$sql="
				SELECT
					a.attname||' '||
					pg_catalog.format_type(a.atttypid, a.atttypmod) as campo
				FROM
					pg_catalog.pg_attribute a LEFT JOIN pg_catalog.pg_attrdef adef
					ON a.attrelid=adef.adrelid
					AND a.attnum=adef.adnum
					LEFT JOIN pg_catalog.pg_type t ON a.atttypid=t.oid
				WHERE 
					a.attrelid = (SELECT oid FROM pg_catalog.pg_class WHERE relname='{$tabla}'
						AND relnamespace = (SELECT oid FROM pg_catalog.pg_namespace WHERE
						nspname = 'public'))
					AND a.attnum > 0 AND NOT a.attisdropped
				ORDER BY a.attnum";
		$Columnas = $dbconn->GetCol($sql);
		return $Columnas;
	}//Fin CargarColumnas
	
	/**
	 * Compara las tablas de las bases de datos
	 *
	 * @param bool compararTablas
	 * @param array comparar
	 */
	function CompararTablas($compararTablas=false,$comparar='')
	{
		if(empty($comparar))
		{
			$comparar=$this->CompararTablas;
		}
		else
		{
			$this->CompararTablas=$comparar;
		}
		$TablasDb1=$this->CargarTablas($this->dbconn1);
		$TablasDb2=$this->CargarTablas($this->dbconn2);
		if(!empty($compararTablas))
		{
			
			$this->TablasNoDB2=array_diff($TablasDb1,$TablasDb2);//Tablas que est?n en db1 pero no est?n en db2
			$this->TablasNoDB1=array_diff($TablasDb2,$TablasDb1);//Tablas que est?n en db2 pero no est?n en db1
		}
		$CompararObjetos=false;
		if(isset($comparar['indices']))
		{
			$IndicesDb1=$this->CargarIndices($this->dbconn1);
			$IndicesDb2=$this->CargarIndices($this->dbconn2);
			$CodigoIndices=
				'if(!empty($IndicesDb1[$Tabla]))
					$IndicesTbl1=$IndicesDb1[$Tabla];
				else
					$IndicesTbl1=array();
				if(!empty($IndicesDb2[$Tabla]))
					$IndicesTbl2=$IndicesDb2[$Tabla];
				else
					$IndicesTbl2=array();
				$tmp=array_diff($IndicesTbl1,$IndicesTbl2);
				if(sizeof($tmp)>0)
				{
					$this->TablasDiferencias[$Tabla]['."'".'indices'."'".']['."'".'db1'."'".']=$tmp;
				}
				$tmp1=array_diff($IndicesTbl2,$IndicesTbl1);
				if(sizeof($tmp1)>0)
				{
					$this->TablasDiferencias[$Tabla]['."'".'indices'."'".']['."'".'db2'."'".']=$tmp1;
				}
				unset($IndicesDb1[$Tabla]);
				unset($IndicesDb2[$Tabla]);';
				$CompararObjetos=true;
		}
		if(isset($comparar['triggers']))
		{
			$TriggersDb1=$this->CargarTriggers($this->dbconn1);
			$TriggersDb2=$this->CargarTriggers($this->dbconn2);
			$CodigoTriggers=
				'if(!empty($TriggersDb1[$Tabla]))
					$TriggersTbl1=$TriggersDb1[$Tabla];
				else
					$TriggersTbl1=array();
				if(!empty($TriggersDb2[$Tabla]))
					$TriggersTbl2=$TriggersDb2[$Tabla];
				else
					$TriggersTbl2=array();
				$tmp=array_diff($TriggersTbl1,$TriggersTbl2);
				if(sizeof($tmp)>0)
				{
					$this->TablasDiferencias[$Tabla]['."'".'triggers'."'".']['."'".'db1'."'".']=$tmp;
				}
				$tmp1=array_diff($TriggersTbl2,$TriggersTbl1);
				if(sizeof($tmp1))
				{
					$this->TablasDiferencias[$Tabla]['."'".'triggers'."'".']['."'".'db2'."'".']=$tmp1;
				}
				unset($TriggersDb1[$Tabla]);
				unset($TriggersDb2[$Tabla]);';
				$CompararObjetos=true;
		}
		if(isset($comparar['restricciones']))
		{
			$RestriccionesDb1=$this->CargarRestricciones($this->dbconn1);
			$RestriccionesDb2=$this->CargarRestricciones($this->dbconn2);
			$CodigoRestricciones=
				'if(!empty($RestriccionesDb1[$Tabla]))
					$RestriccionesTbl1=$RestriccionesDb1[$Tabla];
				else
					$RestriccionesTbl1=array();
				if(!empty($RestriccionesDb2[$Tabla]))
					$RestriccionesTbl2=$RestriccionesDb2[$Tabla];
				else
					$RestriccionesTbl2=array();
				$tmp=array_diff($RestriccionesTbl1,$RestriccionesTbl2);
				if(sizeof($tmp)>0)
				{
					$this->TablasDiferencias[$Tabla]['."'".'restricciones'."'".']['."'".'db1'."'".']=$tmp;
				}
				$tmp1=array_diff($RestriccionesTbl2,$RestriccionesTbl1);
				if(sizeof($tmp1)>0)
				{
					$this->TablasDiferencias[$Tabla]['."'".'restricciones'."'".']['."'".'db2'."'".']=$tmp1;
				}
				unset($RestriccionesDb1[$Tabla]);
				unset($RestriccionesDb2[$Tabla]);';
				$CompararObjetos=true;
		}
		if(isset($comparar['campos']))
		{
				$CodigoCampos='
				$CamposTbl1=$this->CargarColumnas($this->dbconn1,$Tabla);
				$CamposTbl2=$this->CargarColumnas($this->dbconn2,$Tabla);
				$tmp=array_diff($CamposTbl1,$CamposTbl2);
				if(sizeof($tmp)>0)
				{
					$this->TablasDiferencias[$Tabla]['."'".'campos'."'".']['."'".'db1'."'".']=$tmp;
				}
				$tmp1=array_diff($CamposTbl2,$CamposTbl1);
				if(sizeof($tmp1)>0)
				{
					$this->TablasDiferencias[$Tabla]['."'".'campos'."'".']['."'".'db2'."'".']=$tmp1;
				}';
				$CompararObjetos=true;
		}
		if($CompararObjetos)//Columnas
		{
			$TablasDB1DB2=array_intersect($TablasDb1,$TablasDb2);
			unset($TablasDb1);
			unset($TablasDb2);
			$Codigo=
			'foreach($TablasDB1DB2 as $Key=>$Tabla)
			{';
				$Codigo .= $CodigoCampos.$CodigoIndices.$CodigoTriggers.$CodigoRestricciones;
				$Codigo .= 'unset($TablasDB1DB2[$key]);
			}';
			//echo "<pre>$Codigo</pre>";
			eval($Codigo);
		}
		return true;
	}//Fin CompararTablas
	
	/**
	 * Carga en un vector las funciones correspondientes a la base de datos
	 * de la conexion
	 * 
	 * @param object dbconn
	 * @return array
	 */
	function CargarFunciones($dbconn)
	{
		$sql = "
		SELECT
			DISTINCT p.proname,
			p.prosrc,
			pl.lanname,
			pt.typname,
			pg_catalog.oidvectortypes(p.proargtypes) AS arguments
		FROM 
			pg_catalog.pg_proc p
			LEFT JOIN pg_catalog.pg_namespace n ON n.oid = p.pronamespace,
			pg_catalog.pg_type pt,
			pg_catalog.pg_language pl
		WHERE 
			p.prorettype <> 'pg_catalog.cstring'::pg_catalog.regtype AND 
			p.proargtypes[0] <> 'pg_catalog.cstring'::pg_catalog.regtype AND NOT 
			p.proisagg AND n.nspname = 'public' AND
			p.prolang=pl.oid 	AND
			p.prorettype=pt.oid
		ORDER BY 
			p.proname;";
		GLOBAL $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$rsFunciones=$dbconn->Execute($sql); 
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo()!=0)
		{
			$this->error=2;
			$this->msjError=MsgOut("ERROR QUERY : " . $dbconn->ErrorMsg());
			return false;
		} 
		while($row=$rsFunciones->FetchRow())
		{
			$Funciones[$row['proname']."(".$row['arguments'].")"]=$row;
		}
		$rsFunciones->Close();
		return $Funciones;
	}//Fin CargarFunciones
  
	/**
	 * Carga en un vector las vistas que tiene la base de datos de la conexion
	 *
	 * @param object dbconn
	 * @return array
	 */
	function CargarVistas($dbconn)
	{
		$sql = "
				SELECT viewname 
				FROM pg_catalog.pg_views
				WHERE schemaname='public' 
				ORDER BY viewname";
		$Objetos=$dbconn->GetCol($sql);
		if($dbconn->ErrorNo()!=0)
		{
			$this->error=2;
			$this->mensajeDejError=MsgOut("ERROR QUERY : " . $dbconn->ErrorMsg());
			return false;
		}
		return $Objetos;
	}//Fin CargarVistas

	/**
	 * Carga en un vector las secuencias que tiene la base de datos de la conexion
	 *
	 * @param object dbconn
	 * @return array
	 */
	function CargarSecuencias($dbconn)
	{
		$sql = "
			SELECT 
				c.relname 
			FROM 
				pg_catalog.pg_class c, 
				pg_catalog.pg_user u, 
				pg_catalog.pg_namespace n
			WHERE 
				c.relowner=u.usesysid AND 
				c.relnamespace=n.oid AND 
				c.relkind = 'S' AND 
				n.nspname='public' 
			ORDER BY relname";
		$Objetos=$dbconn->GetCol($sql);        
		if($dbconn->ErrorNo()!=0)
		{
			$this->error=2;
			$this->msjError=MsgOut("ERROR QUERY : " . $dbconn->ErrorMsg());
			return false;
		}
		return $Objetos;
	}//Fin CargarSecuencias
	
	/**
	 * Compara las funciones
	 *
	 * @return bool
	 */
	function CompararFunciones()
	{
		$Funcionesdb1=$this->CargarFunciones($this->dbconn1);
		$Funcionesdb2=$this->CargarFunciones($this->dbconn2);
		
		$funciones1=array_keys($Funcionesdb1);
		$funciones2=array_keys($Funcionesdb2);
		$this->Funciones1=array_diff($funciones1,$funciones2);//Funciones que estan en db1 y no est?n en db2
		$this->Funciones2=array_diff($funciones2,$funciones1);//Funciones que estan en db2 y no est?n en db1
		$funciones3=array_intersect($funciones1,$funciones2);//Funciones que estan en db2 y est?n en db1 pero son diferentes
		unset($funciones1);
		unset($funciones2);
		foreach($funciones3 as $key)
		{
			$str= "";
			$contenido1=$Funcionesdb1[$key]['prosrc'];
			$contenido2=$Funcionesdb2[$key]['prosrc'];
			$contenido1=ereg_replace (' +', ' ', trim($contenido1));
			$contenido1=ereg_replace("[\r\t\n]","",$contenido1);
			$contenido2=ereg_replace (' +', ' ', trim($contenido2));
			$contenido2=ereg_replace("[\r\t\n]","",$contenido2);
			if($val=strcmp($contenido1,$contenido2))
			{
				$str .= " contenido";
			}
			if($Funcionesdb1[$key]['lanname']!=$Funcionesdb2[$key]['lanname'])
			{
				$str .=" lenguaje";
			}
			if($Funcionesdb1[$key]['typname']!=$Funcionesdb2[$key]['typname'])
			{
				$str .=" return";
			}
			if(!empty($str))
				$this->Funciones3[]=$key." <label class=\"label_error\">Diferente en ".$str."</label>";
			unset($Funcionesdb2[$key]);
			unset($Funcionesdb1[$key]);
		}
		return true;
	}//Fin CompararFunciones
	
	/**
	 * Compara las vista de dos bases de datos
	 */
	function CompararVistas()
	{
		$Vistas1=$this->CargarVistas($this->dbconn1);
		$Vistas2=$this->CargarVistas($this->dbconn2);
		$this->Vistas1=array_diff($Vistas1,$Vistas2);
		$this->Vistas2=array_diff($Vistas2,$Vistas1);
		return true;
	}//Fin CompararVistas
	
	/**
	 * Compara las secuencias de dos bases de datos
	 */
	function CompararSecuencias()
	{
		$Secuencias1=$this->CargarSecuencias($this->dbconn1);
		$Secuencias2=$this->CargarSecuencias($this->dbconn2);
		$this->Secuencias1=array_diff($Secuencias1,$Secuencias2);
		$this->Secuencias2=array_diff($Secuencias2,$Secuencias1);
		return true;
	}//Fin CompararVistas
}//end of class
?>
