<?php

/**
* Modulo de BDAfiliados.
*
* Modulo para accesar a las diferentes bases de datos
* @author Jaime Andres Valencia Salazar <salazarvaljandresv@yahoo.es>
* @version 1.0
* @package SIIS
*/


/**
* BDAfiliados
*
* Clase para accesar los metodos privados de las clases de cada uno de los planes y 
* la clase estadard para el acceso a la base de datos.
* 
*/


class BDAfiliados
{
	var $tipoidpaciente;
	var $paciente;
	var $plan;
	var $tipobd;
	var $salida;
	var $fecha;
	var $error="";
	var $mensajeDeError="";
	
	
	
	
	
	
/**
* Esta funcion Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
* @param string tipo de documento
* @param string numero de documento
* @param string plan con el que se instancia la clase
* @param boolean tipo de base de datos
* @param date fecha con el que se quiere buscar en la base de datos
*/
	
	
	function BDAfiliados($tipoidpaciente,$paciente,$plan,$tipobd,$fecha)
	{
		unset($_SESSION['DATOSAFILIADOEMPLEADOR']);
		$this->tipoidpaciente=$tipoidpaciente;
		$this->paciente=$paciente;
		$this->plan=$plan;
		if($tipobd===false)
		{
			$this->tipobd=0;
		}
		else
		{
			$this->tipobd=1;
		}
		$this->fecha=$fecha;
		return true;
	}
	
	
	
	
	
	
	
	
	
/**
* Esta funcion busca en diferentes bases de datos
*
* @access public
* @return boolean Para identificar que se realizo.
* @param int cantidad de bases de datos que se desea consultar
*/
	
	
	function BasesdeDatosMultiple($cantbd)
	{
		if(!$this->tipoidpaciente or !$this->paciente or !$this->plan)
		{
			$this->error="Datos incompletos";
			$this->mensajeDeError="Tipo id paciente or paciente or plan or fecha estan vacios";
			return false;
		}
		if(empty($this->fecha))
		{
			$this->fecha=date("Y-m-d");
		}
		list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;
		$a=explode('-',$this->fecha);
		$i=0;
		while($i<$cantbd)
		{
			$sql="select * from informacion_bd where fecha_radicacion>'".date("Y-m-d",mktime(1,1,1,$a[1],1,$a[0]))."' and fecha_radicacion<date_trunc('month',DATE '".date("Y-m-d",mktime(1,1,1,$a[1]+1,1,$a[0]))."')-INTERVAL '1 days' and plan_id=".$this->plan." and sw_tipo_bd='".$this->tipobd."' order by fecha_radicacion desc offset 0 limit 1;";
			//echo $sql1="select * from informacion_bd where fecha_radicacion>'".date("Y-m-d",mktime(1,1,1,$a[1],1,$a[0]))."' and fecha_radicacion&lt;date_trunc('month',DATE '".date("Y-m-d",mktime(1,1,1,$a[1]+1,1,$a[0]))."')-INTERVAL '1 days' and plan_id=".$this->plan." and sw_tipo_bd='".$this->tipobd."' order by fecha_radicacion desc offset 0 limit 1;<br>";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($sql);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			}
			else
			{
				$data = $result->FetchRow();
				$result->Close();
				$sql="select c.descripcion_campo, c.descripcion_nombre, b.nombre_clase from informacion_bd as a join plantillas_bd as b on(a.plantilla_bd_id=b.plantilla_bd_id) join plantillas_detalles as c on(b.plantilla_bd_id=c.plantilla_bd_id) where informacion_bd_id=".$data['informacion_bd_id']." order by a.fecha_vencimiento desc, c.plantilla_detalle_id;";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				}
				else
				{
					$clase=$result->fields[2];
					while(!$result->EOF)
					{
						$datos[$result->fields[0]]=$result->fields[1];
						$result->MoveNext();
					}
					$result->Close();
					$file='classes/BDAfiliados/BDAfiliadosMC.class.php';
					if(!IncludeFile($file,true))
					{
						$this->error="Archivo no existe";
						$this->mensajeDeError=$file;
					}
					else
					{
						$file='includes/includesBDAfiliados/'.$clase.'.php';
						if(!IncludeFile($file,true))
						{
							$this->error="Archivo no existe";
							$this->mensajeDeError=$file;
						}
						else
						{
							if(class_exists($clase))
							{
								if(class_exists('BDAfiliadosMC'))
								{
									$claseplan=New $clase($this->tipoidpaciente,$this->paciente,$data['dbtype'],$data['dbhost'],$data['dbuser'],$data['dbpass'],$data['dbname'],$data['dbtabla'],$data['fecha_radicacion'],$data['fecha_vencimiento'],$datos,$this->plan);
									$salida=$claseplan->RetornarDatosCompletos();
									$this->error=$claseplan->error;
									$this->mensajeDeError=$claseplan->mensajeDeError;
									unset($claseplan);
								}
							}
						}
					}
				}
			}
			if(empty($salida))
			{
				if($salida===false)
				{
					$this->salida[date("Y-m-d",mktime(1,1,1,$a[1],1,$a[0]))]=false;
				}
				else
				{
					$this->salida[date("Y-m-d",mktime(1,1,1,$a[1],1,$a[0]))]='No Esta';
				}
			}
			else
			{
				$this->salida[date("Y-m-d",mktime(1,1,1,$a[1],1,$a[0]))]=$salida;
			}
			$a[1]=$a[1]-1;
			$i++;
		}
		return true;
	}
	
	
	
	
	
	
	
	
	
/**
* Esta funcion crea la informacion de la base de datos de multiples bases de datos en el vector salida
*
* @access public
* @return boolean Para identificar que se realizo.
* @param int cantidad de bases de datos que se desea consultar
*/
	
	
	function GetDatosAfiliadoMultiple($cantbd)
	{
		if($cantbd=='')
		{
			$cantbd=1;
		}
		if($this->BasesdeDatosMultiple($cantbd)==false)
		{
			return false;
		}
		return true;
	}
	
	
	
	
	
	
	
	
/**
* Esta funcion crea la informacion de la base de datos en el vector salida
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	
	function GetDatosAfiliado()
	{
		if(!$this->tipoidpaciente or !$this->paciente or !$this->plan)
		{
			$this->error="Datos incompletos";
			$this->mensajeDeError="Tipo id paciente or paciente or plan estan vacios";
			return false;
		}
		list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;
		$sql="select sw_ocultar from planes_ocultar where plan_id=".$this->plan;
		$result = $dbconn->Execute($sql);
		$sw_ocultar=$result->fields[0];
		if(empty($this->fecha))
		{
			$sql="select dbtype, dbhost, dbuser, dbpass, dbname, dbtabla, fecha_radicacion, fecha_vencimiento from informacion_bd where plan_id=".$this->plan." and sw_estado='1' and sw_tipo_bd='".$this->tipobd."' order by fecha_vencimiento desc;";
		}
		else
		{
			$sql="select dbtype, dbhost, dbuser, dbpass, dbname, dbtabla, fecha_radicacion, fecha_vencimiento from informacion_bd where plan_id=".$this->plan." and sw_tipo_bd='".$this->tipobd."' and fecha_radicacion='".$this->fecha."' order by fecha_vencimiento desc;";
		}
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$data = $result->FetchRow();
		$result->Close();
		if(empty($this->fecha))
		{
			$sql="select c.descripcion_campo, c.descripcion_nombre, b.nombre_clase
						from informacion_bd as a join plantillas_bd as b on(a.plantilla_bd_id=b.plantilla_bd_id)
						join plantillas_detalles as c on(b.plantilla_bd_id=c.plantilla_bd_id)
						where a.plan_id=".$this->plan." and a.sw_estado='1'
						order by a.fecha_vencimiento desc, c.plantilla_detalle_id;";
		}
		else
		{
			$sql="select c.descripcion_campo, c.descripcion_nombre, b.nombre_clase
						from informacion_bd as a join plantillas_bd as b on(a.plantilla_bd_id=b.plantilla_bd_id)
						join plantillas_detalles as c on(b.plantilla_bd_id=c.plantilla_bd_id)
						where a.plan_id=".$this->plan." and fecha_radicacion='".$this->fecha."'
						order by a.fecha_vencimiento desc, c.plantilla_detalle_id;";
		}
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$clase=$result->fields[2];
		while(!$result->EOF)
		{
			$datos[$result->fields[0]]=$result->fields[1];
			$result->MoveNext();
		}
		$file='classes/BDAfiliados/BDAfiliadosMC.class.php';
		if(!IncludeFile($file,true))
		{
			$this->error="Archivo no existe";
			$this->mensajeDeError=$file;
			return false;
		}
		$file='includes/includesBDAfiliados/'.$clase.'.php';
		if(!IncludeFile($file,true))
		{
			$this->error="Archivo no existe";
			$this->mensajeDeError=$file;
			return false;
		}
		if(!class_exists($clase))
		{
			return false;
		}
		if(!class_exists('BDAfiliadosMC'))
		{
			return false;
		}
		if($sw_ocultar!=1)
		{
			$claseplan=New $clase($this->tipoidpaciente,$this->paciente,$data['dbtype'],$data['dbhost'],$data['dbuser'],$data['dbpass'],$data['dbname'],$data['dbtabla'],$data['fecha_radicacion'],$data['fecha_vencimiento'],$datos,$this->plan);
		}
		else
		{
			$claseplan=New $clase($this->tipoidpaciente,$this->paciente,$data['dbtype'],$data['dbhost'],$data['dbuser'],$data['dbpass'],$data['dbname'],$data['dbtabla'],'','',$datos,$this->plan);
		}
		$salida=$claseplan->RetornarDatosCompletos();
		if($salida===false)
		{
			$this->error=$claseplan->error;
			$this->mensajeDeError=$claseplan->mensajeDeError;
			return false;
		}
		$this->salida=$salida;
		return true;
	}
	
	
	
	
	
	
	
	
	
/**
* Esta funcion retorna un vector con la informacion de los pacientes de urgencias
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	
	
	function Urgencias()
	{
		if(!$this->plan)
		{
			$this->error="Datos incompletos";
			$this->mensajeDeError="plan esta vacio";
			return false;
		}
		list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;
		if(empty($this->fecha))
		{
			$sql="select dbtype, dbhost, dbuser, dbpass, dbname, dbtabla, fecha_radicacion, fecha_vencimiento from informacion_bd where plan_id=".$this->plan." and sw_estado='1' and sw_tipo_bd='".$this->tipobd."' order by fecha_vencimiento desc;";
		}
		else
		{
			$sql="select dbtype, dbhost, dbuser, dbpass, dbname, dbtabla, fecha_radicacion, fecha_vencimiento from informacion_bd where plan_id=".$this->plan." and sw_tipo_bd='".$this->tipobd."' and fecha_radicacion='".$this->fecha."' order by fecha_vencimiento desc;";
		}
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$data = $result->FetchRow();
		$result->Close();
		if(empty($this->fecha))
		{
			$sql="select c.descripcion_campo, c.descripcion_nombre, b.nombre_clase from informacion_bd as a join plantillas_bd as b on(a.plantilla_bd_id=b.plantilla_bd_id) join plantillas_detalles as c on(b.plantilla_bd_id=c.plantilla_bd_id) where a.plan_id=".$this->plan." and a.sw_estado='1' order by a.fecha_vencimiento desc, c.plantilla_detalle_id;";
		}
		else
		{
			$sql="select c.descripcion_campo, c.descripcion_nombre, b.nombre_clase from informacion_bd as a join plantillas_bd as b on(a.plantilla_bd_id=b.plantilla_bd_id) join plantillas_detalles as c on(b.plantilla_bd_id=c.plantilla_bd_id) where a.plan_id=".$this->plan." and fecha_radicacion='".$this->fecha."' order by a.fecha_vencimiento desc, c.plantilla_detalle_id;";
		}
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$clase=$result->fields[2];
		while(!$result->EOF)
		{
			$datos[$result->fields[0]]=$result->fields[1];
			$result->MoveNext();
		}
		$file='classes/BDAfiliados/BDAfiliadosMC.class.php';
		if(!IncludeFile($file,true))
		{
			$this->error="Archivo no existe";
			$this->mensajeDeError=$file;
			return false;
		}
		$file='includes/includesBDAfiliados/'.$clase.'.php';
		if(!IncludeFile($file,true))
		{
			$this->error="Archivo no existe";
			$this->mensajeDeError=$file;
			return false;
		}
		//$clase='plan';
		if(!class_exists($clase))
		{
			return false;
		}
		if(!class_exists('BDAfiliadosMC'))
		{
			return false;
		}
		$claseplan=New $clase('','',$data['dbtype'],$data['dbhost'],$data['dbuser'],$data['dbpass'],$data['dbname'],$data['dbtabla'],$data['fecha_radicacion'],$data['fecha_vencimiento'],$datos,$this->plan);
		$claseplan->ConexionBD();
		$result=$claseplan->ExecuteSql($claseplan->SqlGetPacientesUrgencias());
		return $result;
	}
	
	
	
	
	
	
	
	
	
	
	
	//Funcion para busqueda por nombres del paciente
	
	
	function BusquedaNombresPaciente($nombres,$apellidos)
	{
		if(!$this->plan)
		{
			$this->error="Datos incompletos";
			$this->mensajeDeError="plan esta vacio";
			return false;
		}
		if(!$nombres and !$apellidos)
		{
			$this->error="Esta vacio el nombre y el apellido";
			$this->mensajeDeError="Esta vacio el nombre";
			return false;
		}
		list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;
		if(empty($this->fecha))
		{
			$sql="select dbtype, dbhost, dbuser, dbpass, dbname, dbtabla, fecha_radicacion, fecha_vencimiento from informacion_bd where plan_id=".$this->plan." and sw_estado='1' and sw_tipo_bd='".$this->tipobd."' order by fecha_vencimiento desc;";
		}
		else
		{
			$sql="select dbtype, dbhost, dbuser, dbpass, dbname, dbtabla, fecha_radicacion, fecha_vencimiento from informacion_bd where plan_id=".$this->plan." and sw_tipo_bd='".$this->tipobd."' and fecha_radicacion='".$this->fecha."' order by fecha_vencimiento desc;";
		}
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$data = $result->FetchRow();
		$result->Close();
		if(empty($this->fecha))
		{
			$sql="select c.descripcion_campo, c.descripcion_nombre, b.nombre_clase from informacion_bd as a join plantillas_bd as b on(a.plantilla_bd_id=b.plantilla_bd_id) join plantillas_detalles as c on(b.plantilla_bd_id=c.plantilla_bd_id) where a.plan_id=".$this->plan." and a.sw_estado='1'
						order by a.fecha_vencimiento desc, c.plantilla_detalle_id
						LIMIT 10 OFFSET 0;";
		}
		else
		{
			$sql="select c.descripcion_campo, c.descripcion_nombre, b.nombre_clase from informacion_bd as a join plantillas_bd as b on(a.plantilla_bd_id=b.plantilla_bd_id) join plantillas_detalles as c on(b.plantilla_bd_id=c.plantilla_bd_id) where a.plan_id=".$this->plan." and fecha_radicacion='".$this->fecha."'
						order by a.fecha_vencimiento desc, c.plantilla_detalle_id
						LIMIT 10 OFFSET 0;";
		}
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$clase=$result->fields[2];
		while(!$result->EOF)
		{
			$datos[$result->fields[0]]=$result->fields[1];
			$result->MoveNext();
		}
		$file='classes/BDAfiliados/BDAfiliadosMC.class.php';
		if(!IncludeFile($file,true))
		{
			$this->error="Archivo no existe";
			$this->mensajeDeError=$file;
			return false;
		}
		$file='includes/includesBDAfiliados/'.$clase.'.php';
		if(!IncludeFile($file,true))
		{
			$this->error="Archivo no existe";
			$this->mensajeDeError=$file;
			return false;
		}
		//$clase='plan';
		if(!class_exists($clase))
		{
			$this->error="clase no existe";
			$this->mensajeDeError=$clase;
			return false;
		}
		if(!class_exists('BDAfiliadosMC'))
		{
			$this->error="clase no existe";
			$this->mensajeDeError='BDAfiliadosMC';
			return false;
		}
		$claseplan=New $clase('','',$data['dbtype'],$data['dbhost'],$data['dbuser'],$data['dbpass'],$data['dbname'],$data['dbtabla'],$data['fecha_radicacion'],$data['fecha_vencimiento'],$datos,$this->plan);
		$claseplan->ConexionBD();
		$result=$claseplan->ExecuteSql($claseplan->SqlGetPacientesConNombres($nombres,$apellidos));
		if($result!=false)
		{
			$result1=$claseplan->ConvertirResult(&$result);
		}
		$result->close();
		unset($claseplan);
		return $result1;
	}
	
	
	
	
	
	
	
	
	
	
	//funcion busqueda por cotizante
	
	
	function BusquedaCotizantePaciente($tipoidpaciente,$pacienteid)
	{
		if(!$this->plan)
		{
			$this->error="Datos incompletos";
			$this->mensajeDeError="plan esta vacio";
			return false;
		}
		if(!$tipoidpaciente and !$pacienteid)
		{
			$this->error="Esta vacio el tipo de identificacion y el apellido";
			$this->mensajeDeError="Esta vacio el nombre";
			return false;
		}
		list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;
		if(empty($this->fecha))
		{
			$sql="select dbtype, dbhost, dbuser, dbpass, dbname, dbtabla, fecha_radicacion, fecha_vencimiento from informacion_bd where plan_id=".$this->plan." and sw_estado='1' and sw_tipo_bd='".$this->tipobd."' order by fecha_vencimiento desc;";
		}
		else
		{
			$sql="select dbtype, dbhost, dbuser, dbpass, dbname, dbtabla, fecha_radicacion, fecha_vencimiento from informacion_bd where plan_id=".$this->plan." and sw_tipo_bd='".$this->tipobd."' and fecha_radicacion='".$this->fecha."' order by fecha_vencimiento desc;";
		}
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$data = $result->FetchRow();
		$result->Close();
		if(empty($this->fecha))
		{
			$sql="select c.descripcion_campo, c.descripcion_nombre, b.nombre_clase from informacion_bd as a join plantillas_bd as b on(a.plantilla_bd_id=b.plantilla_bd_id) join plantillas_detalles as c on(b.plantilla_bd_id=c.plantilla_bd_id) where a.plan_id=".$this->plan." and a.sw_estado='1' order by a.fecha_vencimiento desc, c.plantilla_detalle_id;";
		}
		else
		{
			$sql="select c.descripcion_campo, c.descripcion_nombre, b.nombre_clase from informacion_bd as a join plantillas_bd as b on(a.plantilla_bd_id=b.plantilla_bd_id) join plantillas_detalles as c on(b.plantilla_bd_id=c.plantilla_bd_id) where a.plan_id=".$this->plan." and fecha_radicacion='".$this->fecha."' order by a.fecha_vencimiento desc, c.plantilla_detalle_id;";
		}
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$clase=$result->fields[2];
		while(!$result->EOF)
		{
			$datos[$result->fields[0]]=$result->fields[1];
			$result->MoveNext();
		}
		$file='classes/BDAfiliados/BDAfiliadosMC.class.php';
		if(!IncludeFile($file,true))
		{
			$this->error="Archivo no existe";
			$this->mensajeDeError=$file;
			return false;
		}
		$file='includes/includesBDAfiliados/'.$clase.'.php';
		if(!IncludeFile($file,true))
		{
			$this->error="Archivo no existe";
			$this->mensajeDeError=$file;
			return false;
		}
		//$clase='plan';
		if(!class_exists($clase))
		{
			$this->error="clase no existe";
			$this->mensajeDeError=$clase;
			return false;
		}
		if(!class_exists('BDAfiliadosMC'))
		{
			$this->error="clase no existe";
			$this->mensajeDeError='BDAfiliadosMC';
			return false;
		}
		$claseplan=New $clase('','',$data['dbtype'],$data['dbhost'],$data['dbuser'],$data['dbpass'],$data['dbname'],$data['dbtabla'],$data['fecha_radicacion'],$data['fecha_vencimiento'],$datos,$this->plan);
		if(!method_exists($claseplan,"SqlGetGrupoFamiliar"))
		{
			$this->error="LA FUNCION PARA LA REVISION DEL GRUPO FAMILIAR NO EXISTE";
			$this->mensajeDeError='no existe SqlGetGrupoFamiliar';
			return false;
		}
		$claseplan->ConexionBD();
		$result=$claseplan->ExecuteSql($claseplan->SqlGetGrupoFamiliar($tipoidpaciente,$pacienteid));
		return $result;
	}
	
	
	
	
	
	
	
	
	
	
	//Busqueda de autorizacion
	
	function BusquedaNumeroAutorizacion($noAutorizacion,$Prestador)
	{
		if(!$this->plan)
		{
			$this->error="Datos incompletos";
			$this->mensajeDeError="plan esta vacio";
			return false;
		}
		if(!$noAutorizacion or !$Prestador)
		{
			$this->error="Esta vacio el Numero de Autorizacion o el departamento";
			$this->mensajeDeError="Esta vacio la Autorizacion o el departamento";
			return false;
		}
		list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;
		$sql="select dbtype, dbhost, dbuser, dbpass, dbname, dbtabla, fecha_radicacion, fecha_vencimiento from informacion_bd where plan_id=".$this->plan." and sw_estado='1' and sw_tipo_bd='".$this->tipobd."' order by fecha_vencimiento desc;";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$data = $result->FetchRow();
		$result->Close();
		$sql="select c.descripcion_campo, c.descripcion_nombre, b.nombre_clase from informacion_bd as a join plantillas_bd as b on(a.plantilla_bd_id=b.plantilla_bd_id) join plantillas_detalles as c on(b.plantilla_bd_id=c.plantilla_bd_id) where a.plan_id=".$this->plan." and a.sw_estado='1' order by a.fecha_vencimiento desc, c.plantilla_detalle_id;";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$clase=$result->fields[2];
		while(!$result->EOF)
		{
			$datos[$result->fields[0]]=$result->fields[1];
			$result->MoveNext();
		}
		$file='classes/BDAfiliados/BDAfiliadosMC.class.php';
		if(!IncludeFile($file,true))
		{
			$this->error="Archivo no existe";
			$this->mensajeDeError=$file;
			return false;
		}
		$file='includes/includesBDAfiliados/'.$clase.'.php';
		if(!IncludeFile($file,true))
		{
			$this->error="Archivo no existe";
			$this->mensajeDeError=$file;
			return false;
		}
		if(!class_exists($clase))
		{
			$this->error="clase no existe";
			$this->mensajeDeError=$clase;
			return false;
		}
		if(!class_exists('BDAfiliadosMC'))
		{
			$this->error="clase no existe";
			$this->mensajeDeError='BDAfiliadosMC';
			return false;
		}
		$claseplan=New $clase('','',$data['dbtype'],$data['dbhost'],$data['dbuser'],$data['dbpass'],$data['dbname'],$data['dbtabla'],$data['fecha_radicacion'],$data['fecha_vencimiento'],$datos,$this->plan);
		if(!method_exists($claseplan,"SqlGetNumeroAutorizacion"))
		{
			$this->error="LA FUNCION PARA LA EXTRACCION DEL NUMERO DE AUTORIZACION";
			$this->mensajeDeError='no existe SqlGetNumeroAutorizacion';
			return false;
		}
		$claseplan->ConexionBD();
		//$claseplan->SqlGetNumeroAutorizacion($noAutorizacion,$Prestador);
		$result=$claseplan->ExecuteSql($claseplan->SqlGetNumeroAutorizacion($noAutorizacion,$Prestador));
		return $result;
	}
	
	
	
	
	
	
	
	
	//peticion de autorizacion
	
	function PeticionAutorizacion($observacion,$fecha,$departamento)
	{
		if(!$this->plan)
		{
			$this->error="Datos incompletos";
			$this->mensajeDeError="plan esta vacio";
			return false;
		}
		if(!$fecha or !$departamento)
		{
			$this->error="Esta vacio la observacion, la fecha y el departamento";
			$this->mensajeDeError="Esta vacio la observacion, la fecha y el departamento";
			return false;
		}
		list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;
		$sql="select dbtype, dbhost, dbuser, dbpass, dbname, dbtabla, fecha_radicacion, fecha_vencimiento from informacion_bd where plan_id=".$this->plan." and sw_estado='1' and sw_tipo_bd='".$this->tipobd."' order by fecha_vencimiento desc;";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$data = $result->FetchRow();
		$result->Close();
		$sql="select c.descripcion_campo, c.descripcion_nombre, b.nombre_clase from informacion_bd as a join plantillas_bd as b on(a.plantilla_bd_id=b.plantilla_bd_id) join plantillas_detalles as c on(b.plantilla_bd_id=c.plantilla_bd_id) where a.plan_id=".$this->plan." and a.sw_estado='1' order by a.fecha_vencimiento desc, c.plantilla_detalle_id;";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$clase=$result->fields[2];
		while(!$result->EOF)
		{
			$datos[$result->fields[0]]=$result->fields[1];
			$result->MoveNext();
		}
		$file='classes/BDAfiliados/BDAfiliadosMC.class.php';
		if(!IncludeFile($file,true))
		{
			$this->error="Archivo no existe";
			$this->mensajeDeError=$file;
			return false;
		}
		$file='includes/includesBDAfiliados/'.$clase.'.php';
		if(!IncludeFile($file,true))
		{
			$this->error="Archivo no existe";
			$this->mensajeDeError=$file;
			return false;
		}
		if(!class_exists($clase))
		{
			$this->error="clase no existe";
			$this->mensajeDeError=$clase;
			return false;
		}
		if(!class_exists('BDAfiliadosMC'))
		{
			$this->error="clase no existe";
			$this->mensajeDeError='BDAfiliadosMC';
			return false;
		}
		$claseplan=New $clase($this->tipoidpaciente,$this->paciente,$data['dbtype'],$data['dbhost'],$data['dbuser'],$data['dbpass'],$data['dbname'],$data['dbtabla'],$data['fecha_radicacion'],$data['fecha_vencimiento'],$datos,$this->plan);
		$datos=$claseplan->PedirAutorizacion($observacion,$fecha,$departamento);
		if($datos==false)
		{
			$this->error=$claseplan->error;
			$this->mensajeDeError=$claseplan->mensajeDeError;
			return false;
		}
		return $datos;
	}
	
	
	
	
	
	
	
	
	
	
	
	function TraerSedes($departamento)
	{
		if(!$this->plan)
		{
			$this->error="Datos incompletos";
			$this->mensajeDeError="plan esta vacio";
			return false;
		}
		if(!$departamento)
		{
			$this->error="Esta vacio el departamento";
			$this->mensajeDeError="Esta vacio el departamento";
			return false;
		}
		list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;
		$sql="select dbtype, dbhost, dbuser, dbpass, dbname, dbtabla, fecha_radicacion, fecha_vencimiento from informacion_bd where plan_id=".$this->plan." and sw_estado='1' and sw_tipo_bd='".$this->tipobd."' order by fecha_vencimiento desc;";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$data = $result->FetchRow();
		$result->Close();
		$sql="select c.descripcion_campo, c.descripcion_nombre, b.nombre_clase from informacion_bd as a join plantillas_bd as b on(a.plantilla_bd_id=b.plantilla_bd_id) join plantillas_detalles as c on(b.plantilla_bd_id=c.plantilla_bd_id) where a.plan_id=".$this->plan." and a.sw_estado='1' order by a.fecha_vencimiento desc, c.plantilla_detalle_id;";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$clase=$result->fields[2];
		while(!$result->EOF)
		{
			$datos[$result->fields[0]]=$result->fields[1];
			$result->MoveNext();
		}
		$file='classes/BDAfiliados/BDAfiliadosMC.class.php';
		if(!IncludeFile($file,true))
		{
			$this->error="Archivo no existe";
			$this->mensajeDeError=$file;
			return false;
		}
		$file='includes/includesBDAfiliados/'.$clase.'.php';
		if(!IncludeFile($file,true))
		{
			$this->error="Archivo no existe";
			$this->mensajeDeError=$file;
			return false;
		}
		if(!class_exists($clase))
		{
			$this->error="clase no existe";
			$this->mensajeDeError=$clase;
			return false;
		}
		if(!class_exists('BDAfiliadosMC'))
		{
			$this->error="clase no existe";
			$this->mensajeDeError='BDAfiliadosMC';
			return false;
		}
		$claseplan=New $clase($this->tipoidpaciente,$this->paciente,$data['dbtype'],$data['dbhost'],$data['dbuser'],$data['dbpass'],$data['dbname'],$data['dbtabla'],$data['fecha_radicacion'],$data['fecha_vencimiento'],$datos,$this->plan);
		if(!method_exists($claseplan,"SqlGetTraerSedes"))
		{
			return false;
		}
		return $claseplan->SqlGetTraerSedes($departamento);
	}


}//fin clase
?>
