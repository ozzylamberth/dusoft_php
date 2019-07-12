<?php

/**
 * $Id: app_Mantenimiento_user.php,v 1.2 2005/06/02 19:06:55 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_Mantenimiento_user extends classModulo
{
	var $uno;//para los errores
  var $limit;
	var $conteo;
	var $vercolumna;
/**
* Esta función retorna los datos de concernientes a la version del Módulo
* @access private
*/

	function GetVersion()
	{
		$informacion=array(
		'version'=>'1',
		'subversion'=>'0',
		'revision'=>'0',
		'fecha'=>'01/03/2005',
		'autor'=>'Carlos A. Henao',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
	}

  function app_Mantenimiento_user()
	{
 		$this->limit=GetLimitBrowser();
		return true;
	}

	function main()
	{
		$this->PrincipalMantenimiento();
		return true;
	}

  //FUNCIONES DE LA BARRA
	function CalcularNumeroPasos($conteo)//Función de las barras
	{
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	function CalcularBarra($paso)//Función de las barras
	{
		$barra=floor($paso/10)*10;
		if(($paso%10)==0)
		{
			$barra=$barra-10;
		}
		return $barra;
	}

	function CalcularOffset($paso)//Función de las barras
	{
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}

	function UsuariosMantenimiento()//Función de permisos
	{
		list($dbconn) = GetDBconn();
	  $usuario=UserGetUID();
		$query = "SELECT D.empresa_id,
									B.razon_social AS descripcion1,	D.centro_utilidad, C.descripcion AS descripcion2,
									D.descripcion AS descripcion3
							FROM userpermisos_mantenimiento AS A, puntos_mantenimiento AS D,
									empresas AS B, centros_utilidad AS C
							WHERE A.usuario_id=".$usuario."
									AND A.punto_mantenimiento_id=D.punto_mantenimiento_id
									AND D.empresa_id=B.empresa_id
									AND D.centro_utilidad=C.centro_utilidad
									AND D.empresa_id=C.empresa_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $this->fileError = __FILE__;
      $this->lineError = __LINE__;
			return false;
		}
		while(!$resulta->EOF)
		{
			$var2[$resulta->fields[1]][$resulta->fields[3]][$resulta->fields[4]]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		$mtz[0]='EMPRESAS';
		$mtz[1]='CENTRO DE UTILIDAD';
		$mtz[2]='PUNTO MANTENIMIENTO';
		$url[0]='app';
		$url[1]='Mantenimiento';
		$url[2]='user';
		$url[3]='PrincipalMantenimiento';
		$url[4]='permisomantenimiento';
		$this->salida .=gui_theme_menu_acceso('MANTENIMIENTO', $mtz, $var2, $url, ModuloGetURL('system','Menu'));
		return true;
	}

  //LlamaFormaBusquedaTablas
  function LlamaFormaBusquedaTablas()
  {
      list($dbconn)=GetDBconn();
      $usuario=UserGetUID();
      $query = " SELECT pdb.datname, pu.usename AS datowner, pg_encoding_to_char(encoding) AS datencoding,
                        (SELECT description FROM pg_description pd WHERE pdb.oid=pd.objoid) AS datcomment
                        FROM pg_database pdb, pg_user pu
                        WHERE pdb.datdba = pu.usesysid AND usename<>'postgres';";
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
      $this->FormaBusquedaTablas($datos);
      return true;
  }

  //FUNCION CONECTAR BD
  function GetConnDB()
  {
    $conn=ADONewConnection('postgres');
	  if (!($conn->Connect($_REQUEST['HOST'],$_REQUEST['UserBD'],$_REQUEST['Passwd'],$_REQUEST['BD'])))
    {
        return false;
    }else{
        return $conn;

    }
	}

  //conexión mediante la clase mantenimiento.class.php
  function LlamaFormaVerTablas()
  {
    if (!IncludeFile("classes/MantenimientoBD/mantenimiento.class.php"))
    {
        $this->error = "Error";
        $this->mensajeDeError = "NO SE PUDO INCLUIR : classes/MantenimientoBD/mantenimiento.class.php";
        return false;
    }

    if(!class_exists('mantenimiento'))
    {
        $this->error="Error";
        $this->mensajeDeError="NO EXISTE LA CLASE.";
        return false;
    }

    $mantenimiento= New mantenimiento();
    if (!is_object($mantenimiento))
      {
        $this->error = "Error en la conexión";
        $this->mensajeDeError = "Error DB : " . $mantenimiento->ErrorMsg();
        return false;
      }
      else
      {
				$db= $_SESSION['mantenimiento']['db'];
				$motor= $_SESSION['mantenimiento']['motor'];
        $version1= $_SESSION['mantenimiento']['version1'];
        $datos=$mantenimiento->mantenimiento('tablas');
        $this->FormaVerTablas($datos,$db,$motor,$version1);
        return true;
      }
  }

  //llama forma ver campos de una tabla
  function LlamaFormaVerCampos()
  {
		if (empty($_REQUEST['schemaname']) || empty($_REQUEST['tablename']))
    {
      $schema=$_SESSION['manteniminento']['schema'];
      $tabla=$_SESSION['manteniminento']['table'];
      $propietario=$_SESSION['manteniminento']['propietario'];
    }
		else
    {
      $schema=$_REQUEST['schemaname'];
      $tabla=$_REQUEST['tablename'];
      $propietario=$_REQUEST['tableowner'];
      $_SESSION['manteniminento']['schema']=$_REQUEST['schemaname'];
      $_SESSION['manteniminento']['table']=$_REQUEST['tablename'];
      $_SESSION['manteniminento']['propietario']=$_REQUEST['tableowner'];
    }
    if (!IncludeFile("classes/MantenimientoBD/mantenimiento.class.php"))
    {
        $this->error = "Error";
        $this->mensajeDeError = "NO SE PUDO INCLUIR : classes/MantenimientoBD/mantenimiento.class.php";
        return false;
    }

    if(!class_exists('mantenimiento'))
    {
        $this->error="Error";
        $this->mensajeDeError="NO EXISTE LA CLASE.";
        return false;
    }
    $mantenimiento= New mantenimiento();
    if (!is_object($mantenimiento))
      {
        $this->error = "Error en la conexión";
        $this->mensajeDeError = "Error DB : " .$mantenimiento->ErrorMsg();
        return false;
      } else {

       $campos1=$mantenimiento->mantenimiento('buscartabla',$tabla);
       $campos=$mantenimiento->mantenimiento('campos',$schema,$tabla);
       $fk=$mantenimiento->mantenimiento('fk',$schema,$tabla);
       $pk=$mantenimiento->mantenimiento('pk',$schema,$tabla);
       $tr=$mantenimiento->mantenimiento('referencias',$schema,$tabla);
       $this->FormaVerCampos($campos,$pk,$fk,$tr,$schema,$tabla,$campos1);
       return true;
      }
  }

  //LlamaFormaVerFk
	function LlamaFormaVerFk()
  {
  	$schema=$_REQUEST['schemaname'];
    $tabla=$_REQUEST['tablename'];
    if (!IncludeFile("classes/MantenimientoBD/mantenimiento.class.php"))
    {
        $this->error = "Error";
        $this->mensajeDeError = "NO SE PUDO INCLUIR : classes/MantenimientoBD/mantenimiento.class.php";
        return false;
    }

    if(!class_exists('mantenimiento'))
    {
        $this->error="Error";
        $this->mensajeDeError="NO EXISTE LA CLASE.";
        return false;
    }
    $mantenimiento= New mantenimiento();
    if (!is_object($mantenimiento))
      {
        $this->error = "Error en la conexión";
        $this->mensajeDeError = "Error DB : " .$mantenimiento->ErrorMsg();
        return false;
      } else {
       $datos=$mantenimiento->mantenimiento('fk',$schema,$tabla);
			 $this->FormaVerFk($datos);
			 return true;
			}
  }

	//LLAMA FORMA VER CAMPOS REFERENCIADOS
  function LlamaFormaVerCamposReferenciados()
  {
    if (empty($_REQUEST['tablename']))
    {
      $schema=$_SESSION['manteniminento']['schema'];
      $tabla=$_SESSION['manteniminento']['table'];
      $propietario=$_SESSION['manteniminento']['propietario'];
    }
    else
    {
      $schema=$_REQUEST['schemaname'];
      $tabla=$_REQUEST['tablename'];
      $propietario=$_REQUEST['tableowner'];
      $_SESSION['manteniminento']['schema']=$_REQUEST['schemaname'];
      $_SESSION['manteniminento']['table']=$_REQUEST['tablename'];
      $_SESSION['manteniminento']['propietario']=$_REQUEST['tableowner'];
    }

    list($dbconn)=GetDBConn();
    if (!IncludeFile("classes/MantenimientoBD/mantenimiento.class.php"))
    {
        $this->error = "Error";
        $this->mensajeDeError = "NO SE PUDO INCLUIR : classes/MantenimientoBD/mantenimiento.class.php";
        $this->fileError = __FILE__;
        $this->lineError = __LINE__;
        return false;
    }

    if(!class_exists('mantenimiento'))
    {
        $this->error="Error";
        $this->mensajeDeError="NO EXISTE LA CLASE.";
        $this->fileError = __FILE__;
        $this->lineError = __LINE__;
        return false;
    }
    $mantenimiento = New mantenimiento();
    //$connect=$conn->Conectar('postgres');
    if (!is_object($mantenimiento))
      {
        $this->error = "Error en la conexión";
        $this->mensajeDeError = "Error DB : " .$mantenimiento->ErrorMsg();
        $this->fileError = __FILE__;
        $this->lineError = __LINE__;
        return false;
      }
      else
      {
       //trae campos referenciados
       $llaves=$mantenimiento->mantenimiento('ref',$schema,$tabla); //extraer los campos que solo se van a visualizar
       //trae tablas referenciados
       $tablasRef=$mantenimiento->mantenimiento('tablasref',$schema,$tabla); //extraer los campos que solo se van a visualizar
       $datos=$mantenimiento->mantenimiento('campos',$schema,$tabla);//en estecaso las llaves foraneas
       $fk=$mantenimiento->mantenimiento('fk',$schema,$tabla);
       $pk=$mantenimiento->mantenimiento('pk',$schema,$tabla);
       //tablas referenciadas
       //$tr=$clas->TablasReferenciadasBD($connect,$schema,$tabla);
       $this->FormaVerCamposReferenciados($datos,$pk,$fk,$schema,$tabla,$propietario,$llaves,$tablasRef);
       return true;
      }
  }

	//LlamaFormaVerDatos
  function LlamaFormaVerDatos()
  {
  	$campos=$_REQUEST['campos'];
    $tablasref=$_REQUEST['tablas'];
  	$schema=$_SESSION['mantenimiento']['schema'];
  	$tabla=$_SESSION['mantenimiento']['tabla'];
    if (!IncludeFile("classes/MantenimientoBD/mantenimiento.class.php"))
    {
        $this->error = "Error";
        $this->mensajeDeError = "NO SE PUDO INCLUIR : classes/MantenimientoBD/mantenimiento.class.php";
        $this->fileError = __FILE__;
        $this->lineError = __LINE__;
        return false;
    }

    if(!class_exists('mantenimiento'))
    {
        $this->error="Error";
        $this->mensajeDeError="NO EXISTE LA CLASE MANTENIMIENTO.";
        $this->fileError = __FILE__;
        $this->lineError = __LINE__;
        return false;
    }
    $mantenimiento= New mantenimiento();
    if (!is_object($mantenimiento))
      {
        $this->error = "Error en la conexión";
        $this->mensajeDeError = "Error DB : " .$mantenimiento->ErrorMsg();
        $this->fileError = __FILE__;
        $this->lineError = __LINE__;
        return false;
      }
       else
       {
				//list($dbconn)=GetDBconn();
				$fields=$_SESSION['mantenimiento']['seleccion'];

				$sql="SELECT $fields
               FROM $tablasref";
        $datos=$mantenimiento->mantenimiento('select',$sql);
        $atributos=$mantenimiento->mantenimiento('atributos',$schema,$tabla);
        $this->FormaVerDatos($tabla,$campos,$datos);
        return true;
			 }
  }

  //LlamaFormaConectarBD
  function LlamaFormaConectarBD()
  {
		$this->FormaConectarBD();
		return true;
  }

  function GetDatosConexionActual()
  {
    global $ConfigDB;
    return $ConfigDB;
  }

  function RegeneracionTablas()
  {
    list($dbconn) = GetDBconn();
		$rowsaffect=0;
    if (!IncludeFile("classes/MantenimientoBD/mantenimiento.class.php"))
    {
        $this->error = "Error";
        $this->mensajeDeError = "NO SE PUDO INCLUIR : classes/MantenimientoBD/mantenimiento.class.php";
        return false;
    }

    if(!class_exists('mantenimiento'))
    {
        $this->error="Error";
        $this->mensajeDeError="NO EXISTE LA CLASE.";
        return false;
    }
    $mantenimiento= New mantenimiento();										//instanciar clase mantenimiento
		//$versionmotor= $conn->GetDriver('version');		//extraer version actual del motor de BD
   // $conn->IncluirDriver($versionmotor);					//incluir driver para el motor actual
    //$clas= New $versionmotor;											//instanciar driver
    if (!is_object($mantenimiento))
    {
      $this->error = "ERROR AL INSTANCIAR LA CLASE MANTENIMIENTO.";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $this->fileError = __FILE__;
      $this->lineError = __LINE__;
      return false;
    }
    else
		{
      $tablasDelSistema=$mantenimiento->mantenimiento('tablas');	//consultar tablas de la BD actual
      foreach($tablasDelSistema as $numTabla=>$vectorTabla)
      {
        $sql="SELECT COUNT(*) FROM  system_tablas_mantenimiento WHERE tablename='".$vectorTabla[tabla]."';";
        $result=$dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        list($cantidad)=$result->FetchRow();
        if($cantidad)
        {
          $sql="UPDATE system_tablas_mantenimiento SET sw_tipo_mantenimiento=0
                WHERE tablename='".$vectorTabla[tabla]."';";
          $result=$dbconn->Execute($sql);
          if ($dbconn->ErrorNo() != 0)
            {
              $this->error = "ERROR AL ACTUALIZAR";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $this->fileError = __FILE__;
              $this->lineError = __LINE__;
              return false;
            }
            $acc='ACTUALIZADO';
        }
        else
        {
          $sql="INSERT INTO system_tablas_mantenimiento
                VALUES ('".$vectorTabla[tabla]."',2,'".$vectorTabla[comentario]."',3);";
          $result=$dbconn->Execute($sql);
          $rowsaffect++;
          if ($dbconn->ErrorNo() != 0)
            {
              $this->error = "ERROR AL ACTUALIZAR";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $this->fileError = __FILE__;
              $this->lineError = __LINE__;
              return false;
            }
            $acc='INSERTADO';
        }
      }
      $this->FormaConfirmar($acc,$rowsaffect);
      return true;
    }
  }

	//llamaFormaMantenimiento
  function llamaFormaMantenimiento()
  {
		$nombretabla=$_REQUEST['nombretabla'];
  	$this->FormaMantenimiento($nombretabla);
		return true;
  }

  //función traer datos
  function TraerDatosMantenimiento($nombretabla)
	{
   if (!empty($nombretabla))
    {
			$restosql=" WHERE tablename LIKE('%$nombretabla%')";
    }
    else $restosql="";

    list($dbconn) = GetDBconn();
		if(empty($_REQUEST['conteo']))
		{
			$query ="SELECT count(*) FROM
					(
            SELECT tablename, sw_tipo_mantenimiento, observaciones, sw_tmp_estado
            FROM  system_tablas_mantenimiento
						$restosql
          ) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        $this->fileError = __FILE__;
        $this->lineError = __LINE__;

				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
   $sql="(
            SELECT tablename, sw_tipo_mantenimiento, observaciones, sw_tmp_estado
            FROM  system_tablas_mantenimiento
            $restosql
            ORDER BY tablename
          )
          LIMIT ".$this->limit." OFFSET $Of;";
    $resulta=$dbconn->Execute($sql);
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

  //llama forma ver campos de una tabla seleccionada en el mantenimiento
  function LlamaFormaVerCamposTabla()
  {
    list($dbconn)=GetDBconn();
    $tabla=$_REQUEST['tablename'];
    if (!IncludeFile("classes/MantenimientoBD/mantenimiento.class.php"))
    {
        $this->error = "Error";
        $this->mensajeDeError = "NO SE PUDO INCLUIR : classes/MantenimientoBD/mantenimiento.class.php";
        return false;
    }

    if(!class_exists('mantenimiento'))
    {
        $this->error="Error";
        $this->mensajeDeError="NO EXISTE LA CLASE.";
        return false;
    }
    $mantenimiento= New mantenimiento();
    if (!is_object($mantenimiento))
      {
        $this->error = "Error en al instanciar la clese.";
        $this->mensajeDeError = "Error DB : " .$conn->ErrorMsg();
        return false;
      } else {
       $campos=$mantenimiento->mantenimiento('buscartabla',$tabla);
       $datos=$mantenimiento->mantenimiento('campos',$schema,$tabla);
       $fk=$mantenimiento->mantenimiento('fk',$schema,$tabla);
       $pk=$mantenimiento->mantenimiento('pk',$schema,$tabla);
       $tr=$mantenimiento->mantenimiento('referencias',$schema,$tabla);
       $this->FormaVerCampos($datos,$pk,$fk,$tr,$tabla,$campos);
       return true;
      }
  }

	function CambiarEstadoMantenimiento()//Funcion que cambia el estado del mantenimiento
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['estado']==0)
		{
			$query ="UPDATE system_tablas_mantenimiento SET sw_tipo_mantenimiento=1, sw_tmp_estado=1
					WHERE tablename='".$_REQUEST['table']."';";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
				$this->mensajeDeError = "Error DB : " .$dbconn->ErrorMsg();
				return false;
			}
		}
		else
		if($_REQUEST['estado']==1)
		{
      $query ="UPDATE system_tablas_mantenimiento SET sw_tipo_mantenimiento=0,sw_tmp_estado=0
          WHERE tablename='".$_REQUEST['table']."';";
      $resulta = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
        $this->mensajeDeError = "Error DB : " .$dbconn->ErrorMsg();
        return false;
      }
		}
		$this->FormaMantenimiento();
		return true;
	}

  //LlamaFormaListadoTablasMantenimiento
  function LlamaFormaListadoTablasMantenimiento()
  {
  	$nombretabla=$_REQUEST['nombretabla'];
		$this->FormaListadoTablasMantenimiento($nombretabla);
		return true;
  }

  //CONSULATAR LAS TABLAS EXISTENTES EN EL MOMENTO EN LA BD
  function RegeneracionTablas2()
  {
			list($dbconn) = GetDBconn();
      if (!IncludeFile("classes/MantenimientoBD/mantenimiento.class.php"))
      {
          $this->error = "Error";
          $this->mensajeDeError = "NO SE PUDO INCLUIR : classes/MantenimientoBD/mantenimiento.class.php";
          return false;
      }

      if(!class_exists('mantenimiento'))
      {
          $this->error="Error";
          $this->mensajeDeError="NO EXISTE LA CLASE.";
          return false;
      }

			$tablas= new mantenimiento();
      $tablasDelSistema=$tablas->mantenimiento('tablas');	//consultar tablas de la BD actual
      foreach($tablasDelSistema as $numTabla=>$vectorTabla)
      {
        $sql="SELECT COUNT(*) FROM  system_tablas_mantenimiento WHERE tablename='".$vectorTabla[tabla]."';";
        $result=$dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        list($cantidad)=$result->FetchRow();
        if($cantidad)
        {
          $sql="UPDATE system_tablas_mantenimiento SET observaciones=observaciones, sw_tipo_mantenimiento=0
                WHERE tablename='".$vectorTabla[tabla]."';";
          $result=$dbconn->Execute($sql);
          if ($dbconn->ErrorNo() != 0)
            {
              $this->error = "ERROR AL ACTUALIZAR";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $this->fileError = __FILE__;
              $this->lineError = __LINE__;
              return false;
            }
        }
        else
        {
          $sql="UPDATE system_tablas_mantenimiento SET sw_tipo_mantenimiento=3, sw_tmp_estado=3
                WHERE tablename='".$vectorTabla[tabla]."';";
          $result=$dbconn->Execute($sql);
          if ($dbconn->ErrorNo() != 0)
            {
              $this->error = "ERROR AL ACTUALIZAR";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $this->fileError = __FILE__;
              $this->lineError = __LINE__;
              return false;
            }
        }

    }
    $this->FormaMantenimiento();
		return true;
	}

	//TraerDatosTablasMantenimiento()
		function TraerDatosTablasMantenimiento($nombretabla)
    {
      if (!empty($nombretabla))
      {
        $restosql=" AND tablename LIKE('%$nombretabla%')";
      }
      else $restosql="";

      list($dbconn)=GetDBconn();
      $usuario=UserGetUID();
      $query = "SELECT tablename, observaciones
                FROM system_tablas_mantenimiento
                WHERE sw_tipo_mantenimiento=1 $restosql
                ORDER BY tablename;";
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

    //FormaDatosBrowser
	function LlamaFormaDatosBrowser()
  {
  	//UNSET($_SESSION['mantenimiento']);
  	list($dbconn)=GetDBconn();
    if (!IncludeFile("classes/MantenimientoBD/mantenimiento.class.php"))
    {
        $this->error = "Error";
        $this->mensajeDeError = "NO SE PUDO INCLUIR : classes/MantenimientoBD/mantenimiento.class.php";
        return false;
    }

    if(!class_exists('mantenimiento'))
    {
        $this->error="Error";
        $this->mensajeDeError="NO EXISTE LA CLASE.";
        return false;
    }
    $mantenimiento= New mantenimiento();
    if (!is_object($mantenimiento))
      {
        $this->error = "Error en la conexión";
        $this->mensajeDeError = "Error DB : " .$mantenimiento->ErrorMsg();
        return false;
      }
      else
      {
      if (empty($_REQUEST['table']))
        {
       		$_REQUEST['table']=$_SESSION['mantenimiento']['tabla'];
					$tabla=$_REQUEST['table'];
        }
      else
      	{
      		$_SESSION['mantenimiento']['tabla']=$_REQUEST['table'];
       		$tabla=$_REQUEST['table'];
				}
       $schema='public';
       $campos=$mantenimiento->mantenimiento('atributos',$schema,$tabla);
        if(empty($_REQUEST['conteo']))
        {
 		        $sql ="SELECT count(*) FROM
                  (
                    SELECT *
                    FROM $tabla
 	                ) AS r;";
            $resulta=$dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0)
            {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $this->fileError = __FILE__;
              $this->lineError = __LINE__;
              return false;
            }
          list($this->conteo)=$resulta->fetchRow();
        }
        else
        {
          $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of'])
        {
          $Of='0';
        }
        else
        {
          $Of=$_REQUEST['Of'];
          if($_REQUEST['Of'] > $this->conteo)
          {
            $Of='0';
            $_REQUEST['Of']='0';
            $_REQUEST['paso']='1';
          }
        }
        $sql="(
              SELECT *
              FROM $tabla
              )
              LIMIT ".$this->limit." OFFSET $Of;";
            $resulta=$dbconn->Execute($sql);
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
     	  $camposget=$mantenimiento->mantenimiento('ref',$schema,$tabla);
        $_SESSION['mantenimiento']['tabla']=$tabla;
        $_SESSION['mantenimiento']['atributos']=$campos;
        $_SESSION['mantenimiento']['datosbrowser']=$datos;
				$pk=$mantenimiento->mantenimiento('tablasref',$schema,$tabla,'pk');
				//$datos=$mantenimiento->mantenimiento('ref',$schema,$tabla);
				$fk1=$_SESSION['mantenimiento']['refdisnombre1'];
				$fk2=$_SESSION['mantenimiento']['refdisnombre2'];
				$camposrefarray=$_SESSION['mantenimiento']['camposrefarray'];
				$fktabla=$_SESSION['mantenimiento']['tablaref'];
				$this->FormaVerDatos($tabla,$campos,$datos,$pk,$fk1,$fk2,$fktabla,$camposrefarray);
			 	return true;
      }
  }

	//Importar
  function LlamaFormaInsertarDatos()
  {
		//echo $_SESSION['mantenimiento']['dato1'].'AAA'.$_SESSION['mantenimiento']['dato2'].'AAA'.$_SESSION['mantenimiento']['dato3'];

     if (!empty($_REQUEST['dato1']) and empty($dato_1))
     {echo entro;
      $dato_1= $_REQUEST['dato1'];
	    $nombre_campo1= $_REQUEST['nombre_campo1'];
			$_SESSION['mantenimiento']['dato1']=$dato_1;
     }
     else
      if (!empty($_REQUEST['dato1']) and empty($dato_2))
      {echo entro2;
        $dato_2= $_REQUEST['dato1'];
        $nombre_campo1= $_REQUEST['nombre_campo1'];
        $_SESSION['mantenimiento']['dato1']=$dato_1;
      }
      else
      if (!empty($_REQUEST['dato1']) and empty($dato_3))
      {echo entro3;
        $dato_3= $_REQUEST['dato1'];
        $nombre_campo1= $_REQUEST['nombre_campo1'];
        $_SESSION['mantenimiento']['dato1']=$dato_1;
      }
      else
      if (!empty($_REQUEST['dato1']) and empty($dato_4))
      {echo entro4;
        $dato_4= $_REQUEST['dato1'];
        $nombre_campo1= $_REQUEST['nombre_campo1'];
        $_SESSION['mantenimiento']['dato1']=$dato_1;
      }
      else
      if (!empty($_REQUEST['dato1']) and empty($dato_5))
      {
        $dato_5= $_REQUEST['dato1'];
        $nombre_campo1= $_REQUEST['nombre_campo1'];
        $_SESSION['mantenimiento']['dato1']=$dato_1;
      }

     if (!empty($_REQUEST['dato2']) and empty($dato_2))
     {
	    $dato_2= $_REQUEST['dato2'];
	    $nombre_campo2= $_REQUEST['nombre_campo2'];
			$_SESSION['mantenimiento']['dato2']=$dato_2;
     }
     if (!empty($_REQUEST['dato3']))
     {
	    $dato_3= $_REQUEST['dato3'];
	    $nombre_campo3= $_REQUEST['nombre_campo3'];
			$_SESSION['mantenimiento']['dato3']=$dato_3;
     }
     if (!empty($_REQUEST['dato4']))
     {
	    $dato_4= $_REQUEST['dato4'];
	    $nombre_campo4= $_REQUEST['nombre_campo4'];
			$_SESSION['mantenimiento']['dato4']=$dato_4;
     }
     if (!empty($_REQUEST['dato5']))
     {
	    $dato_5= $_REQUEST['dato5'];
	    $nombre_campo4= $_REQUEST['nombre_campo5'];
			$_SESSION['mantenimiento']['dato5']=$dato_5;
     }

  echo $dato_1.'AAA'.$dato_2.'AAA'.$dato_3.'AAA'.$dato_4.'AAA'.$dato_5; 

    if (!empty($_SESSION['mantenimiento']['dato1']))
			$dato_1=$_SESSION['mantenimiento']['dato1'];
    if (!empty($_SESSION['mantenimiento']['dato2']))
			$dato_2=$_SESSION['mantenimiento']['dato2'];
    if (!empty($_SESSION['mantenimiento']['dato3']))
			$dato_3=$_SESSION['mantenimiento']['dato3'];
    if (!empty($_SESSION['mantenimiento']['dato4']))
			$dato_4=$_SESSION['mantenimiento']['dato4'];
    if (!empty($_SESSION['mantenimiento']['dato5']))
			$dato_5=$_SESSION['mantenimiento']['dato5'];

    //$fk1=$_REQUEST['fk1'];
    //$fk2=$_REQUEST['fk2'];
    //tabla referencia
    //$tablaref=$_REQUEST['tablaref'];
    //tabla orrigen
    //$tabla=$_SESSION['mantenimiento']['tabla_'];
    //$atributos=$_SESSION['mantenimiento']['atributos'];
    //$camposrefarray=$_REQUEST['camposrefarray'];
    if (empty($_REQUEST['tabla']) || empty($_REQUEST['campos']))
    {
    	$tabla=$_SESSION['mantenimiento']['tablainsert'];
    	$campos=$_SESSION['mantenimiento']['camposinsert'];
    	$tablaref=$_SESSION['mantenimiento']['tablainsert'];
    	$fk1=$_SESSION['mantenimiento']['fk1insert'];
    	$fk2=$_SESSION['mantenimiento']['fk2insert'];
    	$camposrefarray=$_SESSION['mantenimiento']['camposrefarrayinsert'];
    }
    else
    {
      $tabla=$_REQUEST['tabla'];
      $campos=$_REQUEST['campos'];
	    $tablaref=$_REQUEST['tablaref'];
      $fk1=$_REQUEST['fk1'];
      $fk2=$_REQUEST['fk2'];
			$camposrefarray=$_REQUEST['camposrefarray'];
    	$_SESSION['mantenimiento']['tablainsert']=$tabla;
    	$_SESSION['mantenimiento']['camposinsert']=$campos;
    	$_SESSION['mantenimiento']['tablainsert']=$tablaref;
    	$_SESSION['mantenimiento']['fk1insert']=$fk1;
    	$_SESSION['mantenimiento']['fk2insert']=$fk2;
    	$_SESSION['mantenimiento']['camposrefarrayinsert']=$camposrefarray;
    }
  	$this->FormaInsertarDatos($tabla,$campos,$fk1,$fk2,$camposrefarray,$tablaref,$nombre_campo1,$dato_1,$nombre_campo2,$dato_2,$nombre_campo3,$dato_3,$nombre_campo4,$dato_4,$nombre_campo5,$dato_5);
		return true;
  }

  //INSERTAR DATOS
  function InsertarDatos()
	{
  	//echo $_REQUEST['tabla'];
  	$tabla=$_REQUEST['tabla'];
  	$campos=$_REQUEST['campos'];

		list($dbconn)=GetDBconn();

    for ($i=0; $i<sizeof($campos); $i++)
    {
			if($tables=='')
      	$tables=$campos[$i]['nombre_campo'];
      else
				$tables=$tables.','.$campos[$i]['nombre_campo'];
    }

    for ($i=0; $i<sizeof($campos); $i++)
    {
			if($campo=='')
      	$campo=$_POST['campo'.$i];
      else
				$campo=$campo.','.$_POST['campo'.$i];
    }

    $sql="INSERT INTO $tabla ($tables)
		VALUES($campo)";
		$result=$dbconn->Execute($sql);

    $tabla=$_SESSION['mantenimiento']['tabla'];
    $campos=$_SESSION['mantenimiento']['atributos'];
    $datos=$_SESSION['mantenimiento']['datosbrowser'];

    if ($dbconn->ErrorNo() != 0)
    {
      $this->uno=1;
      $this->frmError["MensajeError"] = "No se insertaron los datos. ".$dbconn->ErrorMsg();
			$this->FormaVerDatos($tabla,$campos,$datos);
      return true;
/*      $this->error = "Error al ejecutar la consulta";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $this->fileError = __FILE__;
      $this->lineError = __LINE__;
      return false;*/
    }
    $this->frmError["MensajeError"]="DATOS GUARDADOS.";
    $this->uno=1;
		$this->FormaVerDatos($tabla,$campos,$datos);
		return true;
  }

  //LlamaFormaeditar
  function LlamaFormaEditar()
  {
	    $dato_1= $_REQUEST['dato1'];
	    $nombre_campo1= $_REQUEST['nombre_campo1'];
	    $dato_2= $_REQUEST['dato2'];
	    $nombre_campo2= $_REQUEST['nombre_campo2'];
	    $dato_3= $_REQUEST['dato3'];
	    $nombre_campo3= $_REQUEST['nombre_campo3'];
	    $dato_4= $_REQUEST['dato4'];
	    $nombre_campo4= $_REQUEST['nombre_campo4'];
	    $dato_5= $_REQUEST['dato5'];
	    $nombre_campo4= $_REQUEST['nombre_campo5'];

    //if  (empty($_REQUEST['fk2']) || empty($_REQUEST['fktabla']) || empty($_REQUEST['tabla']) || empty($_REQUEST['atributos']) || empty($_REQUEST['camposrefarray']))
		if (empty($_REQUEST['nro_pk']))
		{
			$fk1=$_SESSION['mantenimiento']['fk1'];
			$fk2=$_SESSION['mantenimiento']['fk2'];
			$tablaref=$_SESSION['mantenimiento']['fktabla'];
			$tabla=$_SESSION['mantenimiento']['tabla_'];
			$atributos=$_SESSION['mantenimiento']['atributos'];
			$camposrefarray=$_SESSION['mantenimiento']['camposrefarray'];
			$_REQUEST['nro_pk']=$_SESSION['mantenimiento']['nro_pk'];
      $_REQUEST['atributo1']=$_SESSION['mantenimiento']['atributo1'];
      $_REQUEST['atributo2']=$_SESSION['mantenimiento']['atributo2'];
      $_REQUEST['atributo3']=$_SESSION['mantenimiento']['atributo3'];
      $_REQUEST['atributo4']=$_SESSION['mantenimiento']['atributo4'];
      $_REQUEST['atributo5']=$_SESSION['mantenimiento']['atributo5'];
      $_REQUEST['atributo6']=$_SESSION['mantenimiento']['atributo6'];
      $_REQUEST['pk1']=$_SESSION['mantenimiento']['pk1'];
      $_REQUEST['pk2']=$_SESSION['mantenimiento']['pk2'];
      $_REQUEST['pk3']=$_SESSION['mantenimiento']['pk3'];
      $_REQUEST['pk4']=$_SESSION['mantenimiento']['pk4'];
      $_REQUEST['pk5']=$_SESSION['mantenimiento']['pk5'];
      $_REQUEST['pk6']=$_SESSION['mantenimiento']['pk6'];
		}
		else
		{
			$fk1=$_REQUEST['fk1'];
			$fk2=$_REQUEST['fk2'];
			$tablaref=$_REQUEST['fktabla'];
			$tabla=$_REQUEST['tabla'];
			$atributos=$_REQUEST['atributos'];
			$camposrefarray=$_REQUEST['camposrefarray'];
			$_SESSION['mantenimiento']['fk1']=$_REQUEST['fk1'];
			$_SESSION['mantenimiento']['fk2']=$_REQUEST['fk2'];
			$_SESSION['mantenimiento']['fktabla']=$_REQUEST['fktabla'];
			$_SESSION['mantenimiento']['tabla_']=$_REQUEST['tabla'];
			$_SESSION['mantenimiento']['atributos']=$_REQUEST['atributos'];
			$_SESSION['mantenimiento']['camposrefarray']=$_REQUEST['camposrefarray'];
			$_SESSION['mantenimiento']['nro_pk']=$_REQUEST['nro_pk'];
      $_SESSION['mantenimiento']['atributo1']=$_REQUEST['atributo1'];
      $_SESSION['mantenimiento']['atributo2']=$_REQUEST['atributo2'];
      $_SESSION['mantenimiento']['atributo3']=$_REQUEST['atributo3'];
      $_SESSION['mantenimiento']['atributo4']=$_REQUEST['atributo4'];
      $_SESSION['mantenimiento']['atributo5']=$_REQUEST['atributo5'];
      $_SESSION['mantenimiento']['atributo6']=$_REQUEST['atributo6'];
      $_SESSION['mantenimiento']['pk1']=$_REQUEST['pk1'];
      $_SESSION['mantenimiento']['pk2']=$_REQUEST['pk2'];
      $_SESSION['mantenimiento']['pk3']=$_REQUEST['pk3'];
      $_SESSION['mantenimiento']['pk4']=$_REQUEST['pk4'];
      $_SESSION['mantenimiento']['pk5']=$_REQUEST['pk5'];
      $_SESSION['mantenimiento']['pk6']=$_REQUEST['pk6'];
		}

		if ($_REQUEST['nro_pk']==1)
    {
      $campo=$_REQUEST['atributo1'];
      $dato=$_REQUEST['pk1'];
      $cond1= $campo."="."'".$dato."'".";";
    }
		else
  	if ($_REQUEST['nro_pk']==2)
    {
      $campo=$_REQUEST['atributo1'];
      $campo1=$_REQUEST['atributo2'];
      $dato=$_REQUEST['pk1'];
      $dato1=$_REQUEST['pk2'];
      $cond2= $campo."="."'".$dato."'"." AND ".$campo1."="."'".$dato1."'".";";
    }
		else
  	if ($_REQUEST['nro_pk']==3)
    {
      $campo=$_REQUEST['atributo1'];
      $campo1=$_REQUEST['atributo2'];
      $campo2=$_REQUEST['atributo3'];
      $dato=$_REQUEST['pk1'];
      $dato1=$_REQUEST['pk2'];
      $dato2=$_REQUEST['pk3'];
      $cond3= $campo."="."'".$dato."'"." AND ".$campo1."="."'".$dato1."'"." AND ".$campo2."="."'".$dato2."'".";";
    }
		else
  	if ($_REQUEST['nro_pk']==4)
    {
      $campo=$_REQUEST['atributo1'];
      $campo1=$_REQUEST['atributo2'];
      $campo2=$_REQUEST['atributo3'];
      $campo3=$_REQUEST['atributo4'];
      $dato=$_REQUEST['pk1'];
      $dato1=$_REQUEST['pk2'];
      $dato2=$_REQUEST['pk3'];
      $dato3=$_REQUEST['pk4'];
      $cond4= $campo."=".$dato." AND ".$campo1."=".$dato1." AND ".$campo2."=".$dato2." AND ".$campo3."=".$dato3.";";
    }
		else
  	if ($_REQUEST['nro_pk']==5)
    {
      $campo=$_REQUEST['atributo1'];
      $campo1=$_REQUEST['atributo2'];
      $campo2=$_REQUEST['atributo3'];
      $campo3=$_REQUEST['atributo4'];
      $campo4=$_REQUEST['atributo5'];
      $dato=$_REQUEST['pk1'];
      $dato1=$_REQUEST['pk2'];
      $dato2=$_REQUEST['pk3'];
      $dato3=$_REQUEST['pk4'];
      $dato4=$_REQUEST['pk5'];
      $cond5= $campo."="."'".$dato."'"." AND ".$campo1."=".$dato1." AND ".$campo2."=".$dato2." AND ".$campo3."=".$dato3." AND ".$campo4."=".$dato4.";";
    }
		else
  	if ($_REQUEST['nro_pk']==6)
    {
      $campo=$_REQUEST['atributo1'];
      $campo1=$_REQUEST['atributo2'];
      $campo2=$_REQUEST['atributo3'];
      $campo3=$_REQUEST['atributo4'];
      $campo4=$_REQUEST['atributo5'];
      $campo5=$_REQUEST['atributo6'];
      $dato=$_REQUEST['pk1'];
      $dato1=$_REQUEST['pk2'];
      $dato2=$_REQUEST['pk3'];
      $dato3=$_REQUEST['pk4'];
      $dato4=$_REQUEST['pk5'];
      $dato5=$_REQUEST['pk6'];
      $cond6= $campo."="."'".$dato."'"." AND ".$campo1."=".$dato1." AND ".$campo2."=".$dato2." AND ".$campo3."=".$dato3." AND ".$campo4."=".$dato4." AND ".$campo5."=".$dato5.";";
    }
    list($dbconn)=GetDBconn();
    for ($i=0; $i<sizeof($campos); $i++)
    {
			if($campo=='')
      	$campo=$campos[$i][nombre_campo];
      else
				$campo=$campo.','.$campos[$i][nombre_campo];
    }
    $sql="SELECT *
    			FROM $tabla
          WHERE $cond1 $cond2 $cond3 $cond4 $cond5 $cond6";
		$result=$dbconn->Execute($sql);
    if ($dbconn->ErrorNo() != 0)
    {
      $this->error = "Error al ejecutar la consulta";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $this->fileError = __FILE__;
      $this->lineError = __LINE__;
      return false;
    }
    if(!$result->EOF)
    {
      while(!$result->EOF)
        {
          $datos[]=$result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
        }
    }

  	$this->FormaEditar($tabla,$datos,$atributos,$fk1,$fk2,$tablaref,$camposrefarray,$dato_1,$nombre_campo1,$dato_2,$nombre_campo2,$dato_3,$nombre_campo3,$dato_4,$nombre_campo4,$dato_5,$nombre_campo5);
		return true;
  }

  //LlamaFormaBorrar
  function LlamaFormaBorrar()
  {
  	$tabla=$_REQUEST['tabla'];
    $atributos=$_REQUEST['atributos'];
  	if ($_REQUEST['nro_pk']==1)
    {
      $campo=$_REQUEST['atributo1'];
      $dato=$_REQUEST['pk1'];
      $cond1= $campo."=".$dato.";";
    }
		else
  	if ($_REQUEST['nro_pk']==2)
    {
      $campo=$_REQUEST['atributo1'];
      $campo1=$_REQUEST['atributo2'];
      $dato=$_REQUEST['pk1'];
      $dato1=$_REQUEST['pk2'];
      $cond2= $campo."="."'".$dato."'"." AND ".$campo1."="."'".$dato1."'".";";

    }
		else
  	if ($_REQUEST['nro_pk']==3)
    {
      $campo=$_REQUEST['atributo1'];
      $campo1=$_REQUEST['atributo2'];
      $campo2=$_REQUEST['atributo3'];
      $dato=$_REQUEST['pk1'];
      $dato1=$_REQUEST['pk2'];
      $dato2=$_REQUEST['pk3'];
      $cond3= $campo."=".$dato." AND ".$campo1."="."'".$dato1."'"." AND ".$campo2."=".$dato2.";";

    }
		else
  	if ($_REQUEST['nro_pk']==4)
    {
      $campo=$_REQUEST['atributo1'];
      $campo1=$_REQUEST['atributo2'];
      $campo2=$_REQUEST['atributo3'];
      $campo3=$_REQUEST['atributo4'];
      $dato=$_REQUEST['pk1'];
      $dato1=$_REQUEST['pk2'];
      $dato2=$_REQUEST['pk3'];
      $dato3=$_REQUEST['pk4'];
      $cond4= $campo."=".$dato." AND ".$campo1."=".$dato1." AND ".$campo2."=".$dato2." AND ".$campo3."=".$dato3.";";
    }
		else
  	if ($_REQUEST['nro_pk']==5)
    {
      $campo=$_REQUEST['atributo1'];
      $campo1=$_REQUEST['atributo2'];
      $campo2=$_REQUEST['atributo3'];
      $campo3=$_REQUEST['atributo4'];
      $campo4=$_REQUEST['atributo5'];
      $dato=$_REQUEST['pk1'];
      $dato1=$_REQUEST['pk2'];
      $dato2=$_REQUEST['pk3'];
      $dato3=$_REQUEST['pk4'];
      $dato4=$_REQUEST['pk5'];
      $cond5= $campo."="."'".$dato."'"." AND ".$campo1."=".$dato1." AND ".$campo2."=".$dato2." AND ".$campo3."=".$dato3." AND ".$campo4."=".$dato4.";";
    }
		else
  	if ($_REQUEST['nro_pk']==6)
    {
      $campo=$_REQUEST['atributo1'];
      $campo1=$_REQUEST['atributo2'];
      $campo2=$_REQUEST['atributo3'];
      $campo3=$_REQUEST['atributo4'];
      $campo4=$_REQUEST['atributo5'];
      $campo5=$_REQUEST['atributo6'];
      $dato=$_REQUEST['pk1'];
      $dato1=$_REQUEST['pk2'];
      $dato2=$_REQUEST['pk3'];
      $dato3=$_REQUEST['pk4'];
      $dato4=$_REQUEST['pk5'];
      $dato5=$_REQUEST['pk6'];
      $cond6= $campo."="."'".$dato."'"." AND ".$campo1."=".$dato1." AND ".$campo2."=".$dato2." AND ".$campo3."=".$dato3." AND ".$campo4."=".$dato4." AND ".$campo5."=".$dato5.";";
    }
    list($dbconn)=GetDBconn();
    for ($i=0; $i<sizeof($campos); $i++)
    {
			if($campo=='')
      	$campo=$campos[$i][nombre_campo];
      else
				$campo=$campo.','.$campos[$i][nombre_campo];
    }
    $sql="DELETE
    			FROM $tabla
          WHERE $cond1 $cond2 $cond3 $cond4 $cond5 $cond6";
		$result=$dbconn->Execute($sql);
    if ($dbconn->ErrorNo() != 0)
    {
      $this->uno=1;
      $this->frmError["MensajeError"] = "NO SE PUDO ELIMINAR EL REGISTRO. ".$dbconn->ErrorMsg();
      $this->LlamaFormaDatosBrowser();
      return true;
/*      $this->error = "Error al ejecutar la consulta";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $this->fileError = __FILE__;
      $this->lineError = __LINE__;
      return false;*/
    }
    if(!$result->EOF)
    {
      while(!$result->EOF)
        {
          $datos[]=$result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
        }
    }
  	$this->LlamaFormaDatosBrowser();
		return true;
  }
	//LlamaFormaRealizarMantenimiento
	function LlamaFormaRealizarMantenimiento()
	{
		$datos=$_REQUEST['campotipo_id_tercero'];
		//echo $datos;

		if (empty($_REQUEST['tablename']))
    {
			$tabla=$_SESSION['mantenimiento']['table1'];
			$schema=$_SESSION['mantenimiento']['schema1'];
    }
    else
    {
      $tabla=$_REQUEST['tablename'];
      $schema=$_REQUEST['schemaname'];
			$_SESSION['mantenimiento']['table1']=$_REQUEST['tablename'];
			$_SESSION['mantenimiento']['schema1']=$_REQUEST['schemaname'];
    }
    if (!IncludeFile("classes/MantenimientoBD/mantenimiento.class.php"))
    {
        $this->error = "Error";
        $this->mensajeDeError = "NO SE PUDO INCLUIR : classes/MantenimientoBD/mantenimiento.class.php";
        return false;
    }

    if(!class_exists('mantenimiento'))
    {
        $this->error="Error";
        $this->mensajeDeError="NO EXISTE LA CLASE.";
        return false;
    }
    $getcampos= New mantenimiento();
    if (!is_object($getcampos))
      {
        $this->error = "Error en la conexión";
        $this->mensajeDeError = "Error al instanciar classes/MantenimientoBD/mantenimiento.class.php";
        return false;
      }
      else
      {
				$pk=$getcampos->mantenimiento('ref',$schema,$tabla);
				$tablasref=$getcampos->mantenimiento('tablasref',$schema,$tabla,'fk');

				$fk1=$_SESSION['mantenimiento']['refdisnombre1'];
				$fk2=$_SESSION['mantenimiento']['refdisnombre2'];
				$camposrefarray=$_SESSION['mantenimiento']['camposrefarray'];

				$campos=$getcampos->mantenimiento('campos',$schema,$tabla);
        $this->FormaRealizarMantenimiento($tabla,$campos,$fk1,$fk2,$camposrefarray,$pk,$tablasref);
        return true;
      }
  }

	//EDITAR DATOS
  function EditarDatos()
  {
  	$_SESSION['mantenimiento']['tabla']=$_REQUEST['tabla'];
		$tabla=$_REQUEST['tabla'];
		$atributos=$_REQUEST['atributos'];
    $fk2=$_REQUEST['fk2'];

		for($i=0; $i<sizeof($fk2);$i++)
    {
			if($campo=='')
      	$campo=$fk2[$i]."='".$_POST['campo'.$i]."'";
      else
				$campo=$campo.",".$fk2[$i]."='".$_POST['campo'.$i]."'";
    }
		$l=$i+1;
    $m=0;
		for($i=$l; $i<sizeof($atributos)+$l; $i++)
		{
			$n=0;
			while($n<sizeof($fk2))
			{
					if ($fk2[$n]!=$atributos[$m]['nombre_campo'])
					{
							$x=false;
							$f=0;
							while($f<sizeof($fk2))
							{
									if ($fk2[$f]==$atributos[$m]['nombre_campo'])
									{
                   	$x=true;
                  }
									$f++;
							}
							if($x==false)
							{
								//$this->salida .= "<tr><td class=\"".$this->SetStyle("".$atributos[$m]['nombre_campo']."")."\">".$atributos[$m]['nombre_campo'].": </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value='".$datos[0][$atributos[$m]['nombre_campo']]."' size=\"35\" maxlength=\"55\"></td></tr>";
                if($campo=='')
                  $campo=$atributos[$m][nombre_campo]."='".$_POST['campo'.$i]."'";
                else
                  $campo=$campo.",".$atributos[$m][nombre_campo]."='".$_POST['campo'.$i]."'";
									$n=sizeof($fk2);
							}
					}
					$n++;
			}
      $m++;
		}

//     $ciclo=sizeof($campos);
//   	for ($i=0; $i<$ciclo; $i++)
//     {
// 			if($campo=='')
//       	$campo=$campos[$i][nombre_campo]."='".$_POST['campo'.$i]."'";
//       else
// 				$campo=$campo.",".$campos[$i][nombre_campo]."='".$_POST['campo'.$i]."'";
//     }

		//traer	llave primaria para realizar el UPDATE
		$n=0;
  	for ($i=0; $i<sizeof($pk); $i++)
    {
        if($condicion=='')
        {
          $condicion=$pk[$i]."='".$_POST['campo'.$i]."'";
        }
        else
          $condicion=$condicion.' AND '.$pk[$i]."='".$_POST['campo'.$i]."'";
        $n++;
    }

		list($dbconn)=GetDBconn();
    $sql="UPDATE $tabla SET $campo
 							WHERE $condicion";
		$result=$dbconn->Execute($sql);
    if ($dbconn->ErrorNo() != 0)
    {
      $this->uno=1;
      $this->frmError["MensajeError"] = "NO SE ACTUALIZARON LOS DATOS. ".$dbconn->ErrorMsg();
      $this->FormaEditar($_REQUEST['tabla'],$_REQUEST['datos'],$_REQUEST['atributos'],$_REQUEST['fk1'],$_REQUEST['fk2'],$_REQUEST['tablaref'],$_REQUEST['camposrefarray'],$_REQUEST['dato1'],$_REQUEST['nombre_campo1'],$_REQUEST['dato2'],$_REQUEST['nombre_campo2'],$_REQUEST['dato3'],$_REQUEST['nombre_campo3'],$_REQUEST['dato4'],$_REQUEST['nombre_campo4'],$_REQUEST['dato5'],$_REQUEST['nombre_campo5']);
      return true;
/*      $this->error = "Error al actualizar, verifique datos ingresados.";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $this->fileError = __FILE__;
      $this->lineError = __LINE__;
      return false;*/
    }
    $this->uno=1;
    //"tabla"=>$tabla,"datos"=>$datos,"atributos"=>$atributos,"fk1"=>$fk1,"fk2"=>$fk2,"tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"dato1"=>$dato_1,"nombre_campo1"=>$nombre_campo1,"dato2"=>$dato_2,"nombre_campo2"=>$nombre_campo2,"dato3"=>$dato_3,"nombre_campo3"=>$nombre_campo3,"dato4"=>$dato_4,"nombre_campo4"=>$nombre_campo4,"dato5"=>$dato_5,"nombre_campo5"=>$nombre_campo5
		$dbconn->CommitTrans();
    $this->frmError["MensajeError"] = "DATOS ACTUALIZADOS SATISFACTORIAMENTE.";
    $this->LlamaFormaDatosBrowser();
		return true;
  }

  //LlamaFormaTraerDatosref
	function LlamaFormaTraerDatosref()
  {
    if (empty($_REQUEST['campo']) || empty($_REQUEST['reftablas']) || empty($_REQUEST['refcampos']))
    {
      $selcampo=$_SESSION['mantenimiento']['campo'];
      $reftablas=$_SESSION['mantenimiento']['reftablas'];
      $refcampos=$_SESSION['mantenimiento']['refcampos'];
    }
    else
    {
      $selcampo=$_REQUEST['campo'];
      $reftablas=$_REQUEST['reftablas'];
      $refcampos=$_REQUEST['refcampos'];
      $_SESSION['mantenimiento']['campo']=$_REQUEST['campo'];
      $_SESSION['mantenimiento']['reftablas']=$_REQUEST['reftablas'];
      $_SESSION['mantenimiento']['refcampos']=$_REQUEST['refcampos'];
    }

    for ($i=0; $i<sizeof($refcampos); $i++)
    {
        for ($j=0; $j<sizeof($refcampos[$i]); $j++)
        {
     		   $temp=strcmp(trim($refcampos[$i][$j]),trim($selcampo));
          if ($temp==0)
          {
           	$tabla= $reftablas[$i][0];
          }
        }
		}
        list($dbconn)=GetDBconn();
      if(empty($_REQUEST['conteo']))
      {
      	$sql ="SELECT count(*) FROM
            (
        			SELECT *
              FROM $tabla
            ) AS r;";
        $result=$dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error en la consulta.";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $this->fileError = __FILE__;
          $this->lineError = __LINE__;
          return false;
        }
          list($this->conteo)=$result->fetchRow();
      }
      else
      {
        $this->conteo=$_REQUEST['conteo'];
      }
      if(!$_REQUEST['Of'])
      {
        $Of='0';
      }
      else
      {
        $Of=$_REQUEST['Of'];
        if($_REQUEST['Of'] > $this->conteo)
        {
          $Of='0';
          $_REQUEST['Of']='0';
          $_REQUEST['paso']='1';
        }
      }
				$sql ="
          (
            SELECT *
            FROM $tabla
          )
	          LIMIT ".$this->limit." OFFSET $Of;";
        $result=$dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al actualizar, verifique datos ingresados.";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $this->fileError = __FILE__;
          $this->lineError = __LINE__;
          return false;
        }

        if(!$result->EOF)
        {
          while(!$result->EOF)
            {
              $datos[]=$result->GetRowAssoc($ToUpper = false);
              $result->MoveNext();
            }
        }

        if (!IncludeFile("classes/MantenimientoBD/mantenimiento.class.php"))
        {
            $this->error = "Error";
            $this->mensajeDeError = "NO SE PUDO INCLUIR : classes/MantenimientoBD/mantenimiento.class.php";
            return false;
        }

        if(!class_exists('mantenimiento'))
        {
            $this->error="Error";
            $this->mensajeDeError="NO EXISTE LA CLASE.";
            return false;
        }
        $getcampos= New mantenimiento();
        if (!is_object($getcampos))
          {
            $this->error = "Error en la conexión";
            $this->mensajeDeError = "Error al instanciar classes/MantenimientoBD/mantenimiento.class.php";
            return false;
          }
          else
          {
            $pk=$getcampos->mantenimiento('ref',$schema,$tabla);
            $tablasref=$getcampos->mantenimiento('tablasref',$schema,$tabla,'fk');
            $campos=$getcampos->mantenimiento('campos',$schema,$tabla);
  /*          print_r($datos);
            print_r($campos);*/
            $this->FormaTraerDatosref($campos,$datos,$tabla,$selcampo);
            return true;
          }
  }
	//LlamaFormaSecuencias
	function LlamaFormaSecuencias()
  {
  	if (!empty($_REQUEST['nombre']))
    {
    	$seq=$_REQUEST['nombre'];
			$restosql=" AND c.relname LIKE('%$seq%')";
    }
    else $restosql="";

      list($dbconn)=GetDBconn();
      if(empty($_REQUEST['conteo']))
      {
      	$sql ="	SELECT count(*) FROM
                (
                  SELECT c.relname AS seqname,
                        u.usename AS seqowner,
                          (SELECT description
                          FROM pg_description pd
                          WHERE c.oid=pd.objoid) AS seqcomment
                  FROM	pg_class c, pg_user u
                  WHERE c.relowner=u.usesysid AND c.relkind = 'S'
                  ORDER BY seqname
                ) AS r;";
        $result=$dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error en la consulta.";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $this->fileError = __FILE__;
          $this->lineError = __LINE__;
          return false;
        }
          list($this->conteo)=$result->fetchRow();
      }
      else
      {
        $this->conteo=$_REQUEST['conteo'];
      }
      if(!$_REQUEST['Of'])
      {
        $Of='0';
      }
      else
      {
        $Of=$_REQUEST['Of'];
        if($_REQUEST['Of'] > $this->conteo)
        {
          $Of='0';
          $_REQUEST['Of']='0';
          $_REQUEST['paso']='1';
        }
      }
				$sql ="
          (
            SELECT c.relname AS seqname,
                  u.usename AS seqowner,
                    (SELECT description
                    FROM pg_description pd
                    WHERE c.oid=pd.objoid) AS seqcomment
            FROM	pg_class c, pg_user u
            WHERE c.relowner=u.usesysid AND c.relkind = 'S' $restosql
            ORDER BY seqname
          )
	          LIMIT ".$this->limit." OFFSET $Of;";
        $result=$dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al actualizar, verifique datos ingresados.";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $this->fileError = __FILE__;
          $this->lineError = __LINE__;
          return false;
        }

        if(!$result->EOF)
        {
          while(!$result->EOF)
            {
              $datos[]=$result->GetRowAssoc($ToUpper = false);
              $result->MoveNext();
            }
        }

			  $this->FormaSecuencias($datos);
				return true;
  }

  //LlamaFormaFk
	function LlamaFormaFk()
  {
  	$tablaref=$_REQUEST['tablaref'];
  	$camposrefarray=$_REQUEST['camposrefarray'];
  	$nombre_campo1=$_REQUEST['nombre_campo1'];
		//echo $_REQUEST['nombre_campo']."<br><br>";
		//print_r($tablaref); echo "<br><br>";
		//print_r($camposrefarray);
		//echo "<br><br>".'QQ';

		for($i=0; $i<sizeof($camposrefarray); $i++)
		{
			for($j=0; $j<sizeof($camposrefarray[$i]); $j++)
			{
			  if (sizeof($camposrefarray[$i])==2  and ((trim($camposrefarray[$i][$j])==$nombre_campo1) || (trim($camposrefarray[$i][$j+1])==$nombre_campo1)))
        {
					$restosql = $tablaref[$i][0];
          $nombre_campo1=trim($camposrefarray[$i][$j]);
          $nombre_campo2=trim($camposrefarray[$i][$j+1]);
				}
				else
			  //if (sizeof($camposrefarray[$i])==3 and trim($camposrefarray[$i][$j])==trim($nombre_campo1))
        if (sizeof($camposrefarray[$i])==3 and ((trim($camposrefarray[$i][$j])==$nombre_campo1) || (trim($camposrefarray[$i][$j+1])==$nombre_campo1) || (trim($camposrefarray[$i][$j+2])==$nombre_campo1)))
        {
					$restosql = $tablaref[$i][0];
          $nombre_campo1=trim($camposrefarray[$i][$j]);
          $nombre_campo2=trim($camposrefarray[$i][$j+1]);
          $nombre_campo3=trim($camposrefarray[$i][$j+2]);

				}
				else
			  if (sizeof($camposrefarray[$i])==4 and ((trim($camposrefarray[$i][$j])==$nombre_campo1) || (trim($camposrefarray[$i][$j+1])==$nombre_campo1) || (trim($camposrefarray[$i][$j+2])==$nombre_campo1) || (trim($camposrefarray[$i][$j+3])==$nombre_campo1)))
        {
					$restosql = $tablaref[$i][0];
          $nombre_campo1=trim($camposrefarray[$i][$j]);
          $nombre_campo2=trim($camposrefarray[$i][$j+1]);
          $nombre_campo3=trim($camposrefarray[$i][$j+2]);
          $nombre_campo4=trim($camposrefarray[$i][$j+3]);
				}
				else
			  if (sizeof($camposrefarray[$i])==5  and ((trim($camposrefarray[$i][$j])==$nombre_campo1) || (trim($camposrefarray[$i][$j+1])==$nombre_campo1) || (trim($camposrefarray[$i][$j+2])==$nombre_campo1) || (trim($camposrefarray[$i][$j+3])==$nombre_campo1)  || (trim($camposrefarray[$i][$j+4])==$nombre_campo1)))
        {
					$restosql = $tablaref[$i][0];
          $nombre_campo1=trim($camposrefarray[$i][$j]);
          $nombre_campo2=trim($camposrefarray[$i][$j+1]);
          $nombre_campo3=trim($camposrefarray[$i][$j+2]);
          $nombre_campo4=trim($camposrefarray[$i][$j+3]);
          $nombre_campo5=trim($camposrefarray[$i][$j+4]);
				}
				else
        if (sizeof($camposrefarray[$i])==1 and trim($camposrefarray[$i][$j])==$nombre_campo1)
				{
					$restosql = $tablaref[$i][0];
          $nombre_campo1=trim($camposrefarray[$i][$j]);
					$restosql2 = $nombre_campo1;
				}
			}
		}

		if (empty($restosql))
				$restosql=$_REQUEST['tabla'];

    list($dbconn)=GetDBconn();
      if(empty($_REQUEST['conteo']))
      {
 echo     	$sql ="SELECT count(*) FROM
            (
      					SELECT *
                FROM $restosql
            ) AS r;";
          $resulta=$dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al actualizar, verifique dato ingresado.";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $this->fileError = __FILE__;
          $this->lineError = __LINE__;
          return false;
        }
         list($this->conteo)=$resulta->fetchRow();
			}
      else
      {
        $this->conteo=$_REQUEST['conteo'];
      }
      if(!$_REQUEST['Of'])
      {
        $Of='0';
      }
      else
      {
        $Of=$_REQUEST['Of'];
        if($_REQUEST['Of'] > $this->conteo)
        {
          $Of='0';
          $_REQUEST['Of']='0';
          $_REQUEST['paso']='1';
        }
      }
      	$sql ="	(
                  SELECT *
                  FROM $restosql
            		) LIMIT ".$this->limit." OFFSET $Of;";
        $resulta=$dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al actualizar, verifique datos ingresados.";
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

        if (!IncludeFile("classes/MantenimientoBD/mantenimiento.class.php"))
        {
            $this->error = "Error";
            $this->mensajeDeError = "NO SE PUDO INCLUIR : classes/MantenimientoBD/mantenimiento.class.php";
            return false;
        }

        if(!class_exists('mantenimiento'))
        {
            $this->error="Error";
            $this->mensajeDeError="NO EXISTE LA CLASE.";
            return false;
        }
        $getcampos= New mantenimiento();
        if (!is_object($getcampos))
          {
            $this->error = "Error en la conexión";
            $this->mensajeDeError = "Error al instanciar classes/MantenimientoBD/mantenimiento.class.php";
            return false;
          }
          else
          {
            $campos=$getcampos->mantenimiento('campos',$schema,$restosql);
            $this->FormaFk($datos,$campos,$restosql,$nombre_campo1,$nombre_campo2,$nombre_campo3,$nombre_campo4,$nombre_campo5);
            return true;
          }
  }

	//LlamaFormaDatosSecuencia
	function LlamaFormaDatosSecuencia()
  {
  			$nom_secuencia=$_REQUEST['nombre_secuencia'];
				list($dbconn)=GetDBconn();
				$sql ="
					SELECT sequence_name AS seqname, *,
              (SELECT description
              FROM pg_description pd
              WHERE pd.objoid=(	SELECT oid
                                FROM pg_class
                                WHERE relname='$nom_secuencia')) AS seqcomment
					FROM \"$nom_secuencia\" AS s";
        $result=$dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al actualizar, verifique datos ingresados.";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $this->fileError = __FILE__;
          $this->lineError = __LINE__;
          return false;
        }
        if(!$result->EOF)
        {
          while(!$result->EOF)
            {
              $datos[]=$result->GetRowAssoc($ToUpper = false);
              $result->MoveNext();
            }
        }
        $this->FormaDatosSecuencia($datos);
        return true;
  }

	//llamaFormaInsertarFk
  function llamaFormaInsertarFk()
	{
  	$tablaref=$_REQUEST['tablaref'];
  	$camposrefarray=$_REQUEST['camposrefarray'];
  	$nombre_campo1_sel=$_REQUEST['nombre_campo1'];
		echo $_REQUEST['nombre_campo1']."<br><br>";
		print_r($tablaref); echo "<br><br>";
		print_r($camposrefarray);
		//echo "<br><br>".'QQ';

		for($i=0; $i<sizeof($camposrefarray); $i++)
		{
    	$subciclo=sizeof($camposrefarray[$i]);
			for($j=0; $j<$subciclo; $j++)
			{
			  if (sizeof($camposrefarray[$i])==2 and ((trim($camposrefarray[$i][$j])==$nombre_campo1_sel) || (trim($camposrefarray[$i][$j+1])==$nombre_campo1_sel)))
        {
					$restosql = $tablaref[$i][0];
          $nombre_campo1=trim($camposrefarray[$i][$j]);
          $nombre_campo2=trim($camposrefarray[$i][$j+1]);
					$j=$subciclo;
        }
				else
			  //if (sizeof($camposrefarray[$i])==3 and trim($camposrefarray[$i][$j])==trim($nombre_campo1))
        if (sizeof($camposrefarray[$i])==3 and ((trim($camposrefarray[$i][$j])==$nombre_campo1_sel) || (trim($camposrefarray[$i][$j+1])==$nombre_campo1_sel) || (trim($camposrefarray[$i][$j+2])==$nombre_campo1_sel)))
        {
					$restosql = $tablaref[$i][0];
          $nombre_campo1=trim($camposrefarray[$i][$j]);
          $nombre_campo2=trim($camposrefarray[$i][$j+1]);
          $nombre_campo3=trim($camposrefarray[$i][$j+2]);
					$j=$subciclo;
					}
				else
			  if (sizeof($camposrefarray[$i])==4 and ((trim($camposrefarray[$i][$j])==$nombre_campo1_sel) || (trim($camposrefarray[$i][$j+1])==$nombre_campo1_sel) || (trim($camposrefarray[$i][$j+2])==$nombre_campo1_sel)) || (trim($camposrefarray[$i][$j+3])==$nombre_campo1_sel))
        {
					$restosql = $tablaref[$i][0];
          $nombre_campo1=trim($camposrefarray[$i][$j]);
          $nombre_campo2=trim($camposrefarray[$i][$j+1]);
          $nombre_campo3=trim($camposrefarray[$i][$j+2]);
          $nombre_campo4=trim($camposrefarray[$i][$j+3]);
 					$j=$subciclo;
					}
				else
			  if (sizeof($camposrefarray[$i])==5)
        {
					$restosql = $tablaref[$i][0];
          $nombre_campo1=trim($camposrefarray[$i][$j]);
          $nombre_campo2=trim($camposrefarray[$i][$j+1]);
          $nombre_campo3=trim($camposrefarray[$i][$j+2]);
          $nombre_campo4=trim($camposrefarray[$i][$j+3]);
          $nombre_campo5=trim($camposrefarray[$i][$j+4]);
 					$j=$subciclo;
				}
				else
        if (sizeof($camposrefarray[$i])==1 and trim($camposrefarray[$i][$j])==$nombre_campo1_sel)
				{
					$restosql = $tablaref[$i][0];
          $nombre_campo1=trim($camposrefarray[$i][$j]);
					$restosql2 = $nombre_campo1;
				}
			}
		}

      //print_r($camposrefarray);

      if (empty($restosql))
      	$restosql=$_REQUEST['tabla'];

      list($dbconn)=GetDBconn();
     if(empty($_REQUEST['conteo']))
      {
    		$sql ="SELECT count(*) FROM
              (
              SELECT *
              FROM $restosql
              ) AS r;";
              $resulta=$dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0)
            {
              $this->error = "Error al actualizar, verifique datos ingresados.";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $this->fileError = __FILE__;
              $this->lineError = __LINE__;
              return false;
            }
         list($this->conteo)=$resulta->fetchRow();
			}
      else
      {
        $this->conteo=$_REQUEST['conteo'];
      }
      if(!$_REQUEST['Of'])
      {
        $Of='0';
      }
      else
      {
        $Of=$_REQUEST['Of'];
        if($_REQUEST['Of'] > $this->conteo)
        {
          $Of='0';
          $_REQUEST['Of']='0';
          $_REQUEST['paso']='1';
        }
      }
      	$sql ="	(
                  SELECT *
                  FROM $restosql
            		) LIMIT ".$this->limit." OFFSET $Of;";
        $resulta=$dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al actualizar, verifique datos ingresados.";
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

      if (!IncludeFile("classes/MantenimientoBD/mantenimiento.class.php"))
      {
          $this->error = "Error";
          $this->mensajeDeError = "NO SE PUDO INCLUIR : classes/MantenimientoBD/mantenimiento.class.php";
          return false;
      }

      if(!class_exists('mantenimiento'))
      {
          $this->error="Error";
          $this->mensajeDeError="NO EXISTE LA CLASE.";
          return false;
      }
      $getcampos= New mantenimiento();
      if (!is_object($getcampos))
        {
          $this->error = "Error en la conexión";
          $this->mensajeDeError = "Error al instanciar classes/MantenimientoBD/mantenimiento.class.php";
          return false;
        }
        else
        {
          $campos=$getcampos->mantenimiento('campos',$schema,$restosql);
          $this->FormaInsertarFk($datos,$campos,$restosql,$nombre_campo1,$nombre_campo2,$nombre_campo3,$nombre_campo4,$nombre_campo5,$nombre_campo1_sel);
          return true;
        }
  }

	//ReiniciarSecuencia
	function ReiniciarSecuencia()
  {
		$minvalue=$_REQUEST['minimovalor'];
		$sequence=$_REQUEST['nombreseq'];
    $datos=$_REQUEST['datos'];
    $valor=$_REQUEST['valor'];
    $comentario=$_REQUEST['comentario'];

    //INSERTAR COMENTARIO
		if(!empty($comentario))
    {
        list($dbconn)=GetDBconn();
      	$sql = "UPDATE pg_description SET description='\"$comentario\"'
								WHERE pg_class.relowner=pg_user.usesysid AND pg_class.relkind = 'S'
                AND pg_class.oid=pg_description.objoid
								AND  pg_class.relname='\"$sequence\"'";
        $resulta=$dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0)
        {
          $this->uno=1;
          $this->frmError["MensajeError"] = "Error al insertar comentario. ".$dbconn->ErrorMsg();
          $this->FormaDatosSecuencia($datos);
/*          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $this->fileError = __FILE__;
          $this->lineError = __LINE__;*/
          return true;
        }
        else
        {
          $ver=false;
          $this->uno=1;
          $this->frmError["MensajeError"]="SE HA ASIGNADO EL VALOR ".$minvalue;
          $this->FormaDatosSecuencia($datos,$ver);
          return true;
        }

    }

		if (!empty($_POST['asignarvalor']))
    {
    	if (empty($_POST['valor']))
      {
        $ver=true;
        $this->FormaDatosSecuencia($datos,$ver);
        return true;
      }
      else
      {
				$minvalue=$_POST['valor'];
        list($dbconn)=GetDBconn();
      	$sql = "SELECT SETVAL('\"{$sequence}\"', {$minvalue})";
        $resulta=$dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al asignar valor a la secuencia.";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $this->fileError = __FILE__;
          $this->lineError = __LINE__;
          return false;
        }
        else
        {
          $ver=false;
          $this->uno=1;
          $this->frmError["MensajeError"]="SE HA ASIGNADO EL VALOR ".$minvalue;
          $this->FormaDatosSecuencia($datos,$ver);
          return true;
        }
       }
     }
     else
      if (!empty($_POST['siguientevalor']))
      {
          list($dbconn)=GetDBconn();
        	$sql = "SELECT NEXTVAL('\"{$sequence}\"')";
          $resulta=$dbconn->Execute($sql);

          if ($dbconn->ErrorNo() != 0)
          {
            $this->error = "Error al asignar el siguiente valor de la secuencia.";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
          }
          else
          {
            $this->uno=1;
            $this->frmError["MensajeError"]="EL SIGUIENTE VALOR ES ".$resulta->fields[0];
            $this->FormaDatosSecuencia($datos);
            return true;
          }
      }

		list($dbconn)=GetDBconn();
		$sql = "SELECT SETVAL('\"{$sequence}\"', {$minvalue})";
    $resulta=$dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al reiniciar la secuencia.";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->fileError = __FILE__;
			$this->lineError = __LINE__;
			return false;
		}
    else
    {
			$this->uno=1;
	    $this->frmError["MensajeError"]="LA SECUENCIA SE HA REINICIADO AL VALOR MINIMO";
			$this->FormaDatosSecuencia($datos);
			return true;
    }

  }

  //importar datos
  function ImportarDatos()
  {
		echo $_REQUEST['tabla']; exit;
		return true;
  }
}//fin de la clase
?>
