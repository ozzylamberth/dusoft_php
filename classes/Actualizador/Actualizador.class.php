<?
/**
 * $Id: Actualizador.class.php,v 1.1 2006/02/17 18:49:22 ehudes Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS
 * 
 */

include_once "Archive/Tar.php";//CLASE DE LA LIBRERIA PEAR

/**
 * Clase para actualizar los módulos de la aplicación
 * y la base de datos
 *
 * @author    Ehudes García <efgarcia@ipsoft-sa.com>
 * @version   $Revision: 1.1 $
 * @package   IPSOFT-SIIS-CLASES
 */
class Actualizador
{
	/**
	 * Ruta y Nombre del archivo que contiene el paquete
	 * este archivo debe estar en extension tar.gz o tgz
	 *
	 * @var string Archivo
	 */
	var $Archivo;
	
	/**
	 * Ruta absoluta de la aplicación
	 *
	 * @var string DirApp
	 */
	var $DirApp;
	
	/**
	 * Numero del error
	 * 
	 * @var int Error
	 */
	var $Error;
	
	/**
	 * Mensajde del error
	 *
	 * @var string MsjError
	 */
	var $MsjError;
	
	/**
	 * Objeto adodb de conexión a la base de datos
	 *
	 * @var object dbconn
	 */
	var $dbconn;

	/**
	 * Nombre de la carpeta que contiene el pauqete
	 * 
	 * @var string paquete
	 */
	var $paquete;
	
	/**
	 *  Log de registro del proceso de actualizacion
	 *
	 * @var string log
	 */
	var $log;
	
	/**
	 * Arreglo que contiene el nombre de los archivos que se tienen que
	 * reemplazar en la aplicación
	 */
	var $app;

	/**
	 * Arreglo con las sentencias sql que contiene el paquete
	 * 
	 * @var array sqls
	 */
	var $sqls;
	
	/**
	 * Arreglo con los programas php que contiene el paquete
	 *
	 * @var array programas
	 */
	var $programas;
	
	/**
	 * Arreglo con el contenido de los documentos pre y post  que estan en la carpeta docs.
	 * del paquete
	 *
	 * @var array docs
	 */
	var $docs;
	
	/**
	 * Objeto tar
	 *
	 * @var object Tar
	 * access private
	 */
	var $Tar;
	
	/**
	 * Usuario propietario de la aplicación
	 *
	 * @var string UserApp
	 */
	var $UserApp;
	
	/**
	 * Grupo propietario de la aplicación
	 *
	 * @var string GrpApp
	 */
	var $GrpApp;
	
	/**
	 * Permisos sobre los archivos de la aplicación
	 * 
	 * @var octal perms
	 */
	var $perms;

	/**
	 * Archivo del backup de la aplicacion
	 * 
	 * @var string backup
	 */
	var $backup;
	
	/**
	 * Constructor de la clase
	 *
	 * @param string Archivo
	 * @param string UserAdminBd
	 * @param string PassAdminDb
	 * @param string DirApp
	 */
	function Actualizador($Archivo,$DirApp,$UserAdminBd,$PassAdminBd,$UserApp,$GrpApp,$Perms=0777)
	{
		if(file_exists($Archivo))
			$this->Archivo=$Archivo;
		else
		{
			$this->Error=1;
			$this->MsjError="El archivo $Archivo no existe";
			return false;
		}
		if(file_exists($DirApp))
		{
			
			if(!is_writable($DirApp))
			{
				$this->Error=1;
				$this->MsjError="El directorio $DirApp no tiene permisos de escritura";
				return false;
			}
			$this->DirApp=$DirApp;
		}
		else
		{
			$this->Error=1;
			$this->MsjError="El directorio $DirApp no existe";
			return false;
		}
		$this->log="";
		$this->Error=0;
		$this->MsjError="";
		$this->UserApp=$UserApp;
		$this->GrpApp=$GrpApp;
		$this->perms=$Perms;
		//Conexion a la base de datos
		global $ConfigDB;
		if(!$this->Conexion($ConfigDB['dbtype'],$UserAdminBd,$PassAdminBd,$ConfigDB['dbhost'],$ConfigDB['dbname']))
		{
			return false;
		}
		//carga del paquete los diferente arreglos como el de sqls, programas, docs
		return $this->CargarPaquete();
	}
	
	/**
	 * Ejecuta la instalación del paquete, ejecuta los sql, los programas
	 * y copia los scripts de la aplicación
	 * 
	 * @return boolean
	 * @access public
	 */
	function Ejecutar()
	{
		if($this->Error==0)
		{
			$this->dbconn->LogSQL();
			echo "aqui empieza la transaccion<br>";
			$this->dbconn->StartTrans();//Inicia una transaccion
			if(!$this->EjecutarSqls())
				return false;
			if(!$this->dbconn->HasFailedTrans())//pregunta si hubo un error en la ejecucion de sqls
			{
				if(!$this->EjecutarProgramas())
					return false;
				if(!$this->dbconn->HasFailedTrans())//pregunta si hubo un error en la ejecucion de los programas
				{
					if(!$this->DescomprimirPaquete())
					{
						$this->dbconn->FailTrans();//Aborta la transaccion
					}
				}
			}
			echo "aqui se termina la transaccion";
			return $this->dbconn->CompleteTrans();//Termina la transaccion
			$this->dbconn->LogSQL(false);
		}
		return false;
	}

	/**
	 * Ejecuta los sql que vienen en el paquete
	 * 
	 * @param array sqls
	 * @return boolean
	 * @access private
	 */
	function EjecutarSqls()
	{
		$size=sizeof($this->sqls);
		if($size>0)
		{
			for($i=1;$i<=$size;$i++)
			{
				//ECHO $this->sqls[$i];
				$this->dbconn->Execute($this->sqls[$i]);
				if($this->dbconn->ErrorNo() != 0)
				{
					$this->Error=1;
					$this->MsjError="Error al ejecutar un sql". $this->dbconn->ErrorMsg();
					return false;
				}
			}
		}
		return true;
	}//Fin EjecutarSqls
	
	/**
	 * Ejecuta los programas que vienen en el paquete
	 * los programas son scripts php que normalmente afectan a la base de datos
	 * 
	 * @param array programas
	 * @return boolean
	 * @access private
	 */
	function EjecutarProgramas()
	{
		$size=sizeof($this->programas);
		if($size>0)
		{
			for($i=1;$i<=$size;$i++)
			{
				eval($this->programas[$i]);
				if(class_exists("pr$i"))
				{
					$clase="pr".$i;
					$obj = new $clase();
					if(method_exists ($obj,"Ejecutar"))
					{
						if(!$obj->Ejecutar(&$this->dbconn))
						{
							$this->Error=1;
							$this->MsjError="Error en la ejecución de la actualización 1".$this->dbconn->ErrorMsg();
							return false;
						}
					}
					else
					{
						$this->Error=1;
						$this->MsjError="Error en la ejecución de la actualización 2";
						return false;
					}
				}
				else
				{
					$this->Error=1;
					$this->MsjError="Error en la ejecución de la actualización 3";
					return false;
				}
			}
		}
		return true;
	}//Fin EjecutarProgramas
	
	/**
	 * Retorna el log
	 * 
	 * @return string
	 * @access public
	 */
	function GetLog()
	{
		return $this->log;
	}//Fin GetLog
	
	/**
	 * Modifica el log
	 * 
	 * @param string log
	 * @access public
	 */
	function SetLog($log)
	{
		$this->log .= $log;
	}//Fin SetLog
	
	/**
	 * Descomprime del paquete la parte de la aplicación
	 * y cambia los permisos de escritura, usuario propietario y grupo propietario
	 *
	 * @return boolean
	 * @access private
	 */
	function DescomprimirPaquete()
	{
		if(isset($this->app) || sizeof($this->app)>0)
		{
			if($this->Tar->extractList($this->paquete."app/", $this->DirApp,$this->paquete."app"))
			{
				foreach($this->app as $archivo)
				{
					//cambia los permisos del archivo
					chmod($this->DirApp."/".$archivo,$this->perms);
					//cambia el usuario propietario
					chown($this->DirApp."/".$archivo,$this->UserApp);
					//cambia el grupo propietario
					chgrp($this->DirApp."/".$archivo,$this->GrpApp);
				}
			}
			else
			{
				$this->Error=1;
				$this->MsjError="Error al descomprimir el paquete";
				return false;
			}
		}
		return true;
	}//Fin DescomprimirPaquete
	
	/**
	 * Realiza la conexion a la base de datos
	 * 
	 * @param string dbType
	 * @param string User
	 * @param string Pass
	 * @param string dbHost
	 * @param string dbName
	 * @return boolean
	 * @access private
	 */
	function Conexion($dbType,$User,$Pass,$dbHost,$dbName)
	{
		//conexion
		$this->dbconn = ADONewConnection($dbType);
		if (!($this->dbconn->Connect($dbHost, $User,$Pass,$dbName))) 
		{
			$this->Error=1;
			$this->MsjError="PERMISOS DB : Error en la Conexión a la Base de Datos";//,$this->DBconn->ErrorMsg();
			return false;
		}
		//verifica que la conexion se haya realizado con un superusuario
		$sql =  "SELECT usesuper FROM pg_user WHERE usename='$User';";
		$SuperUsuario=$this->dbconn->GetOne($sql);
		if ($this->dbconn->ErrorNo() != 0)
		{
			$this->Error=2;
			$this->MsjError="ERROR QUERY : " . $this->dbconn->ErrorMsg();
			return false;
		}
		elseif($SuperUsuario=='f')
		{
			$this->Error=3;
			$this->MsjError="ERROR : No se puede realizar la actualización, el usuario $User no es superusuario";
		}
		return true;
	}//Fin de Conexion
	
	/**
	 * Carga el contenido del paquete, verifica los permisos
	 * de los archivo de la aplicación que se van a reemplazar
	 * 
	 * @return boolean
	 * @access private
	 */
	function CargarPaquete()
	{
		if(!file_exists($this->Archivo))
		{
			$this->Error=1;
			$this->MsjError="El archivo ".$this->Archivo." no existe";
			return false;
		}
		$this->Tar=new Archive_Tar($this->Archivo,true);
		if(!is_object($this->Tar))
		{
			$this->Error=1;
			$this->MsjError="No se pudo crear el objeto Tar";
			return false;
		}
		$v_list=$this->Tar->listContent();
		if(sizeof($v_list)>0)
		{
			$this->paquete=$v_list[0]['filename'];
			$long=strlen($this->paquete);
			unset($v_list[0]);
			foreach($v_list as $v)
			{
				$tmp=substr($v['filename'],$long,3);
				$str=substr($v['filename'],strlen($this->paquete.$tmp."/"));
				if($str!='')
				{
					switch($tmp)
					{
						case 'app':
							//Se verifica los permisos de las carpetas y archivos en la aplicación
							if(file_exists($this->DirApp."/".$str))
							{
								if(!is_writable($this->DirApp."/".$str))
								{
									$this->Error=1;
									$this->MsjError="No hay permisos de escritura en los archivos de la aplicación para instalar el paquete";
									return false;
								}
							}
							$this->app[]=$str;
							break;
						case 'sql':
							//Se carga el arreglo de sqls
							if(substr($str,-1)!="~")
							{
								preg_match('/bd(.*?)[\.]sql/i', $str, $matches);
								if(!empty($matches[1]))
								{
									$index=(int)$matches[1];
									$this->sqls[$index]=$this->Tar->extractInString($v['filename']);
								}
							}
							break;
						case 'doc':
							//Se carga la documentación del paquete
							switch($str)
							{
								case 'pre':
									$this->docs['pre']=$this->Tar->extractInString($v['filename']);
									break;
								case 'post':
									$this->docs['post']=$this->Tar->extractInString($v['filename']);
									break;
							}
							break;
						case 'pro':
							//Se carga los programas del paquete
							if(substr($str,-1)!="~")
							{
								preg_match('/pr(.*?)[\.]php/i', $str, $matches);
								if(!empty($matches[1]))
								{
									$programa = "";
									$programa = $this->Tar->extractInString($v['filename']);
									$programa = str_replace('<'.'?php','<'.'?',$programa);
									$programa = '?'.'>'.trim($programa).'<'.'?';
									$index1=(int)$matches[1];
									$this->programas[$index1]=$programa;
								}
							}
							break;
					}
				}
			}
		}
		else
		{
			$this->Error=1;
			$this->MsjError="El archivo ".$this->Archivo." no es válido";
			return false;
		}
	}//Fin CargarPaquete
	
	/**
	 * Retorna de la carpeta docs las precondiciones si las hay
	 *
	 * @return string
	 * @access public
	 */
	function GetPrecondiciones()
	{
		if(isset($this->docs['pre']))
			return $this->docs['pre'];
	}//Fin GetPrecondiciones
	
	/**
	 * Retorna de la carpeta docs las postcondiciiones si las hay
	 *
	 * @return string
	 * @access public
	 */
	function GetPostcondiciones()
	{
		if(isset($this->docs['pre']))
			return $this->docs['pre'];
	}//Fin GetPostcondiciones
	
	/**
	 * Crea un comprimido de los archivos de la aplicacin que seran reemplazados
	 * por el paquete , retorna falso si no se pudo crear el backup
	 *
	 * @return boolean
	 * @access private
	 */
	function CrearBackupApp()
	{
		if(file_exists($this->DirApp."/cache") && is_writable($this->DirApp."/cache"))
		{
			$nombre_bck=tempnam($this->DirApp."/cache","BCKAPP_");
		}
		else
			$nombre_bck=tempnam("/tmp","BCKAPP_");
		foreach($this->app as $archivo)
		{
			if(substr($archivo,-1)!="/")
			{
				if(file_exists($this->DirApp."/".$archivo))
					$v_list[]=$this->DirApp."/".$archivo;
			}
		}
		if(empty($v_list))
		{
			$tar_object = new Archive_Tar($nombre_bck);
			if($tar_object->createModify($v_list,"backup",$this->DirApp))
			{
				$this->backup=$nombre_bck;
				chmod($nombre_bck,0777);
				return true;
			}
			else
				return false;
		}
		else
		{
			unlink($nombre_bck);
			return true;
		}
	}//Fin CrearBackupApp
	
	/**
	 * Recupera el backup de la aplicacion en caso de que la actualizacion del 
	 * paquete falle
	 *
	 * @return boolean
	 * @access private
	 */
	function RecuperarBackupApp()
	{
		
	}//Fin RecuperarBackupApp
}//Fin de la clase
$VISTA='HTML';
	$_ROOT = '../../../SIIS/';
	include $_ROOT . '.../../includes/enviroment.inc.php';

$act=new Actualizador("/desarrollo/Pruebas/paquete_prueba.tar.gz","/var/www/html/SIIS1","admin","admin",500,500,0777);
$act->CrearBackupApp();

if($act->Error!=0)
	echo $act->MsjError;
else{
/*
echo $act->paquete."<br>";
print_r($act->app);
print_r($act->sqls);
print_r($act->programas);
print_r($act->docs);
*/

$act->Ejecutar();
if($act->Error!=0)
	echo $act->MsjError;
else
	echo "OK";
}
?>