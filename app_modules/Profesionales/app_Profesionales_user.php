<?php

/**
 * $Id: app_Profesionales_user.php,v 1.2 2010/02/24 12:09:54 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_Profesionales_user extends classModulo
{

	var $limit;
	var $conteo;

	function app_Profesionales_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}


/*asdfasdf*/
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

	/*asdfasdf*/

	function main()
	{
    if(!$this->MantenimientoProfesionales())
		{
			return false;
		}
		return true;
	}

	function DepartamentoProfesionales($url)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		if(!empty($_SESSION['SYSTEM_USUARIO_ID']))
		{
			$sql="select c.empresa_id, c.razon_social as descripcion1 from userpermisos_mantenimiento_profesionales as a, empresas as c where a.usuario_id=".$_SESSION['SYSTEM_USUARIO_ID']." and a.empresa_id=c.empresa_id order by empresa_id;";
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El usuario no se ha registrado.";
			return false;
		}
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($sql);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while ($data = $result->FetchRow()) {
				$prueba4[$data['descripcion1']]=$data;
			}
			$i=1;
		}
		if($i<>0)
		{
			$mtz[0]='Empresas';
			$this->salida.=gui_theme_menu_acceso('MATRIZ DE PERMISOS AMINISTRATIVOS',$mtz,$prueba4,$url,ModuloGetUrl('system','Menu'));
			return true;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El usuario no tiene empresas para mostrar.";
			return false;
		}
	}

	function CallTerceros()
	{
		$_SESSION['INFORM']['RETORNO']['contenedor']='app';
		$_SESSION['INFORM']['RETORNO']['modulo']='Profesionales';
		$_SESSION['INFORM']['RETORNO']['tipo']='user';
		$_SESSION['INFORM']['RETORNO']['metodo']='RetornoTerceros';
		$_SESSION['tercer']['empresa']=$_SESSION['ManProf']['empresa'];
		$_SESSION['tercer']['razonso']=$_SESSION['ManProf']['nomemp'];
		$_SESSION['tercer']['tipo_id_tercero']=$_REQUEST['tipoid'];
		$_SESSION['tercer']['tercero_id']=$_REQUEST['tercero'];
		$this->ReturnMetodoExterno('app','Terceros','user','BusquedaTercer');//IngresaTercer
		return true;
	}

	function RetornoTerceros()
	{
		unset($_SESSION['ManProf']['DATOS']);
		$_SESSION['ManProf']['DATOS']=$_SESSION['INFORM']['DATOS'];
		if($_SESSION['INFORM']['RETORNO']['sw']==1)
		{
			if($_SESSION['INFORM']['DATOS']['existe']==1)
			{
				unset($_SESSION['INFORM']);
				unset($_SESSION['tercer']);
				if($this->PantallaProfesional()==false)
				{
					return false;
				}
			}
			else
			{
				unset($_SESSION['INFORM']);
				unset($_SESSION['tercer']);
				if(empty($_SESSION['PROVEEDORES']))
		    {
					if($this->ListarProfe()==false)
					{
						return false;
					}
				}
				else
				{
				  $this->ReturnMetodoExterno($_SESSION['PROVEEDORES']['RETORNO']['contenedor'],$_SESSION['PROVEEDORES']['RETORNO']['modulo'],$_SESSION['PROVEEDORES']['RETORNO']['tipo'],$_SESSION['PROVEEDORES']['RETORNO']['metodo']);
				}
			}
		}
		else
		{
			unset($_SESSION['INFORM']);
			unset($_SESSION['tercer']);
			if($this->PantallaProfesional()==false)
			{
				return false;
			}
		}
		return true;
	}

	function LlamadaOtrosModulos()
	{
		$_SESSION['ManProf']['Profesional']['tipoid']=$_SESSION['PROVEEDORES']['DATOS']['TipoDocumento'];
		$_SESSION['ManProf']['Profesional']['tercero']=$_SESSION['PROVEEDORES']['DATOS']['Documento'];
		$_SESSION['ManProf']['empresa']=$_SESSION['PROVEEDORES']['DATOS']['empresa'];
		$_SESSION['ManProf']['nomemp']=$_SESSION['PROVEEDORES']['DATOS']['DesEmpresa'];
		$_SESSION['ManProf']['nomdep']=$_SESSION['PROVEEDORES']['DATOS']['descdepartamento'];
		$_SESSION['ManProf']['departamento']=$_SESSION['PROVEEDORES']['DATOS']['departamento'];
		if($this->PantallaProfesionalDepartamento()==false)
		{
			return false;
		}
		return true;
	}



	//Funciones para la creacion de los profesionales OJO

	function ProfesionalesBusqueda()
	{
		list($dbconn) = GetDBconn();
		$sql="select a.tipo_profesional, a.tarjeta_profesional,a.sexo_id, case when a.estado is null then '0' else a.estado end, a.universidad, a.sw_registro_defuncion, a.registro_salud_departamental, a.observacion, case when b.tercero_id is null then '0' else '1' end as circulante from profesionales as a left join circulantes as b on(a.tipo_id_tercero=b.tipo_id_tercero and a.tercero_id=b.tercero_id and b.empresa_id='".$_SESSION['ManProf']['empresa']."') where a.tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."' and a.tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."';";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if(!$result->EOF)
			{
				$datos=$result->GetRowAssoc(false);
			}
		}
		return $datos;
	}


	function ProfesionalesBusquedaDepartamentos()
	{
		if($this->SaberProfesional($_SESSION['ManProf']['Profesional']['tipoid'],$_SESSION['ManProf']['Profesional']['tercero'])==false)
		{
			$this->ReturnMetodoExterno($_SESSION['PROVEEDORES']['RETORNO']['contenedor'],$_SESSION['PROVEEDORES']['RETORNO']['modulo'],$_SESSION['PROVEEDORES']['RETORNO']['tipo'],$_SESSION['PROVEEDORES']['RETORNO']['metodo']);
			return false;
		}
		list($dbconn) = GetDBconn();
		$sql="select b.nombre_tercero, profesional_activo(a.tipo_id_tercero, a.tercero_id,'".$_SESSION['ManProf']['departamento']."') as estado, case when c.tercero_id is null then '0' else '1' end as existe from profesionales as a join terceros as b on(a.tipo_id_tercero=b.tipo_id_tercero and a.tercero_id=b.tercero_id) left join profesionales_departamentos as c on (a.tipo_id_tercero=c.tipo_id_tercero and a.tercero_id=c.tercero_id and c.departamento='".$_SESSION['ManProf']['departamento']."') where a.tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."' and a.tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."';";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if(!$result->EOF)
			{
				$datos=$result->GetRowAssoc(false);
			}
		}
		return $datos;
	}


	function SaberProfesional($tipodocumento,$documento)
	{
		list($dbconn) = GetDBconn();
		$sql="select tercero_id from profesionales where tipo_id_tercero='".$tipodocumento."' and tercero_id='".$documento."';";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		if(empty($result->fields[0]))
		{
			return false;
		}
		return true;
	}


	function ProfesionalesCompleto()
	{
		list($dbconn) = GetDBconn();
		if(empty($_REQUEST['conteo']))
		{
			$sql="select count(*) from profesionales as a, profesionales_empresas as b, terceros as c where b.empresa_id='".$_SESSION['ManProf']['empresa']."' and a.tipo_id_tercero=b.tipo_id_tercero and a.tercero_id=b.tercero_id and a.tipo_id_tercero=c.tipo_id_tercero and a.tercero_id=c.tercero_id;";
			$resulta = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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
	$sql="select c.nombre_tercero, case when a.estado is null then '1' else a.estado end,a.tipo_id_tercero, a.tercero_id, a.tipo_profesional, a.sexo_id, a.tarjeta_profesional, a.universidad, a.sw_registro_defuncion, a.registro_salud_departamental, a.observacion, case when d.tercero_id is null then '0' else '1' end from profesionales as a left join circulantes as d on (a.tercero_id=d.tercero_id and a.tipo_id_tercero=d.tipo_id_tercero and d.empresa_id='".$_SESSION['ManProf']['empresa']."'), profesionales_empresas as b, terceros as c where b.empresa_id='".$_SESSION['ManProf']['empresa']."' and a.tipo_id_tercero=b.tipo_id_tercero and a.tercero_id=b.tercero_id and a.tipo_id_tercero=c.tipo_id_tercero and a.tercero_id=c.tercero_id order by a.nombre LIMIT ".$this->limit." OFFSET $Of;";
		$result = $dbconn->Execute($sql);
		$i=$t=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$profesionales[0][$i]=$result->fields[0];
				$profesionales[1][$i]=$result->fields[1];
				$profesionales[2][$i]=$result->fields[2];
				$profesionales[3][$i]=$result->fields[3];
				$profesionales[4][$i]=$result->fields[4];
				$profesionales[5][$i]=$result->fields[5];
				$profesionales[6][$i]=$result->fields[6];
				$profesionales[7][$i]=$result->fields[7];
				$profesionales[8][$i]=$result->fields[8];
				$profesionales[9][$i]=$result->fields[9];
				$profesionales[10][$i]=$result->fields[10];
				$profesionales[11][$i]=$result->fields[11];
				$i++;
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			return $profesionales;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "No Existen Profesionales para esa empresa.";
			return false;
		}
	}

	function BuscarPacientes()
	{
		$Datos=$this->Buscar1($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],strtoupper($_REQUEST['nombres']),$_REQUEST['TipoProfe']);
		if($Datos)
		{
			$this->ListarProfe($mensaje,$Datos);
			return true;
		}
		else
		{
			$mensaje='La busqueda no arrojo resultados.';
			$this->ListarProfe($mensaje,$Datos);
			return true;
		}
		return true;
	}

	/**
  * Busca los datos de la admision cuando se conoce el tipo_id_paciente y paciente_id.
	* @access public
	* @return array
	* @param string tipo de documento
	* @param int numero de documento
	*/
  function Buscar1($TipoId,$PacienteId,$var,$tipoprof)
	{
			list($dbconn) = GetDBconn();
			if(empty($_REQUEST['conteo']))
			{
				$query="select count(distinct c.nombre_tercero) from profesionales as a join tipo_sexo as f using(sexo_id) join terceros as c on (a.tercero_id=c.tercero_id and a.tipo_id_tercero=c.tipo_id_tercero) join profesionales_empresas as d on (a.tipo_id_tercero=d.tipo_id_tercero and a.tercero_id=d.tercero_id and empresa_id='".$_SESSION['ManProf']['empresa']."') join profesionales_especialidades as b on (a.tipo_id_tercero=b.tipo_id_tercero and a.tercero_id=b.tercero_id) where";
			if(!empty($TipoId) and !empty($PacienteId))
			{
				$query.="(a.tercero_id='".$PacienteId."' and a.tipo_id_tercero='".$TipoId."') or ";
			}
			if(!empty($var))
			{
				$a=explode(' ',$var);
				$s=0;
				foreach($a as $k=>$v)
				{
					if(!empty($v))
					{
						if($s==0)
						{
							$query.="(upper(a.nombre) like '%$v%')";
						}
						else
						{
							$query.=" and (upper(a.nombre) like '%$v%')";
						}
						$s=1;
					}
				}
				$query.=" or ";
			}
			if(!empty($tipoprof))
			{
				$query.="(b.especialidad='".$tipoprof."')";
			}
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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
		 $query;
			$query="select distinct c.nombre_tercero, case when a.estado is null then '1' else a.estado end, a.tipo_id_tercero, a.tercero_id, a.tipo_profesional, f.sexo_id, a.tarjeta_profesional, a.universidad, a.sw_registro_defuncion, a.registro_salud_departamental, a.observacion, case when e.tercero_id is null then '0' else '1' end from profesionales as a join tipo_sexo as f using(sexo_id) join terceros as c on (a.tercero_id=c.tercero_id and a.tipo_id_tercero=c.tipo_id_tercero) join profesionales_empresas as d on (a.tipo_id_tercero=d.tipo_id_tercero and a.tercero_id=d.tercero_id) left join profesionales_especialidades as b on (a.tipo_id_tercero=b.tipo_id_tercero and a.tercero_id=b.tercero_id) left join circulantes as e on (a.tercero_id=e.tercero_id and a.tipo_id_tercero=e.tipo_id_tercero and e.empresa_id='".$_SESSION['ManProf']['empresa']."') where";
			if(!empty($TipoId) and !empty($PacienteId))
			{
				$query.="(a.tercero_id='".$PacienteId."' and a.tipo_id_tercero='".$TipoId."') or ";
			}
			if(!empty($var))
			{
				$a=explode(' ',$var);
				$s=0;
				foreach($a as $k=>$v)
				{
					if(!empty($v))
					{
						if($s==0)
						{
							$query.="(upper(c.nombre_tercero) like '%$v%')";
						}
						else
						{
							$query.=" and (upper(c.nombre_tercero) like '%$v%')";
						}
						$s=1;
					}
				}
				$query.=" or ";
			}
			if(!empty($tipoprof))
			{
				$query.="(b.especialidad='".$tipoprof."')";
			}
			$query.="order by c.nombre_tercero LIMIT ".$this->limit." OFFSET $Of;";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$vars=$this->LlenaMatriz($result);
			return $vars;
	}


	function  LlenaMatriz($result)
	{
		$i=0;
		while (!$result->EOF)
		{
			$vars[0][$i]=$result->fields[0];
			$vars[1][$i]=$result->fields[1];
			$vars[2][$i]=$result->fields[2];
			$vars[3][$i]=$result->fields[3];
			$vars[4][$i]=$result->fields[4];
			$vars[5][$i]=$result->fields[5];
			$vars[6][$i]=$result->fields[6];
			$vars[7][$i]=$result->fields[7];
			$vars[8][$i]=$result->fields[8];
			$vars[9][$i]=$result->fields[9];
			$vars[10][$i]=$result->fields[10];
			$vars[11][$i]=$result->fields[11];
			$result->MoveNext();
			$i++;
		}
		$result->Close();
		return $vars;
	}

	function tipo_id_terceros()
  {
			list($dbconn) = GetDBconn();
			$query = "SELECT tipo_id_tercero, descripcion FROM tipo_id_terceros where sw_personas_naturales='1' ORDER BY indice_de_orden";
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{
					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
						return false;
					}
						while (!$result->EOF) {
							$vars[$result->fields[0]]=$result->fields[1];
							$result->MoveNext();
						}
				}
				$result->Close();
				//print_r($vars);
 		 return $vars;
	}

	function TipoProfesional()
	{
		list($dbconn) = GetDBconn();
			$query = "select tipo_profesional,descripcion from tipos_profesionales;";
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{
					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
						return false;
					}
						while (!$result->EOF) {
							$vars[$result->fields[0]]=$result->fields[1];
							$result->MoveNext();
						}
				}
				$result->Close();
 		 return $vars;
	}

	function sexo()
  {
			list($dbconn) = GetDBconn();
			$query = "SELECT sexo_id,descripcion FROM tipo_sexo ORDER BY indice_de_orden";
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
					if($result->EOF)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_sexo' esta vacia ";
						return false;
					}
						while (!$result->EOF)
						{
							$vars[$result->fields[0]]=$result->fields[1];
							$result->MoveNext();
						}
				}
				$result->Close();
		return $vars;
	}


	function CambiarEstado()
	{
		list($dbconn) = GetDBconn();
		$query = "update profesionales set estado='".$_REQUEST['estado']."' where tipo_id_tercero='".$_REQUEST['tipoterceroe']."' and tercero_id='".$_REQUEST['terceroe']."';";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		if($this->BuscarPacientes()==false)
		{
			return false;
		}
		return true;
	}

	function CambiarEstadoDepartamento()
	{
		list($dbconn) = GetDBconn();
		$query="select count(*) from profesionales_estado where tipo_id_tercero='".$_REQUEST['tipoterceroe']."' and tercero_id='".$_REQUEST['terceroe']."' and departamento='".$_SESSION['ManProf']['departamento']."'";
		$result=$dbconn->Execute($query);
		if($result->fields[0]==0)
		{
			$query="insert into profesionales_empresas (tipo_id_tercero, tercero_id, empresa_id) values ('".$_REQUEST['tipoterceroe']."', '".$_REQUEST['terceroe']."', '".$_SESSION['ManProf']['empresa']."')";
			$dbconn->Execute($query);
			$query = "insert into profesionales_estado (estado, tipo_id_tercero, tercero_id, departamento,empresa_id) values ('".$_REQUEST['estado']."', '".$_REQUEST['tipoterceroe']."', '".$_REQUEST['terceroe']."', '".$_SESSION['ManProf']['departamento']."', '".$_SESSION['ManProf']['empresa']."');";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			if($this->PantallaProfesionalDepartamento()==false)
			{
				return false;
			}
		}
		else
		{
			$query = "update profesionales_estado set estado='".$_REQUEST['estado']."' where tipo_id_tercero='".$_REQUEST['tipoterceroe']."' and tercero_id='".$_REQUEST['terceroe']."';";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			if($this->PantallaProfesionalDepartamento()==false)
			{
				return false;
			}
		}
		return true;
	}


	function Estado()
	{
		list($dbconn) = GetDBconn();
			$query = "SELECT estado,descripcion FROM tipos_estados_profesionales;";
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
					if($result->EOF)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_sexo' esta vacia ";
						return false;
					}
						while (!$result->EOF)
						{
							$vars[$result->fields[0]]=$result->fields[1];
							$result->MoveNext();
						}
				}
				$result->Close();
		return $vars;
	}

	function Especialidades($tipoid,$tercero)
	{
		list($dbconn) = GetDBconn();
		$query = "select b.descripcion from profesionales_especialidades as a, especialidades as b where a.tipo_id_tercero='".$tipoid."' and a.tercero_id='".$tercero."' and a.especialidad=b.especialidad;";
		$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				$datos=$result->fields[0];
				$result->MoveNext();
				while (!$result->EOF)
				{
					$datos.=', '.$result->fields[0];
					$result->MoveNext();
				}
			}
			return $datos;
	}

	function Especialidad()
	{
		list($dbconn) = GetDBconn();
			$query = "select a.especialidad,a.universidad from profesionales_especialidades as a where a.tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."' and a.tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."';";
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
					while (!$result->EOF)
					{
						$vars[$result->fields[0]]=$result->fields[1];
						$result->MoveNext();
					}
				}
				$result->Close();
		return $vars;
	}


function TraerSubEspecialidad($especialidad,$tipoid,$tercero)
{
	list($dbconn) = GetDBconn();
  $query = "select b.descripcion from profesionales_especialidades as a,
		especialidades_sub_especialidades as b where a.tipo_id_tercero='".$tipoid."'
		and a.tercero_id='".$tercero."' and a.sub_especialidad=b.sub_especialidad_id
		and a.especialidad='$especialidad'";
		$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				$datos=$result->fields[0];
				$result->MoveNext();
				while (!$result->EOF)
				{
					$datos.=', '.$result->fields[0];
					$result->MoveNext();
				}
			}
			return $datos;

}


function Sub_Especialidad($especialidad)
{
			list($dbconn) = GetDBconn();
			$query = "select sub_especialidad_id,descripcion from especialidades_sub_especialidades
								where especialidad='$especialidad';";
			$result = $dbconn->Execute($query);
					if($result->EOF)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_sexo' esta vacia ";
						return false;
					}
						while (!$result->EOF)
						{
							$vars[$result->fields[0]]=$result->fields[1];
							$result->MoveNext();
						}

		$result->Close();
		return $vars;
}



function CrearSubEspecialidad()
{

	if(is_numeric($_REQUEST['sub']) OR empty($_REQUEST['sub']))
	{
		$this->frmError["MensajeError"] = "POR FAVOR COLOQUE UNA DESCRIPCION,SOLO CARACTERES";
		$this->frmAdicionarSubEspecialidad();
		return true;
	}
	$esp_id=$_REQUEST['especialidad'];
	$desc=$_REQUEST['descripcion'];
	$_SESSION['PROF']['SUB_ESPECIALIDAD']=$_REQUEST['sub'];
	$this->frmAdicionarEspecialidad();
	return true;
}



function CancelarProceso()
{
	unset($_SESSION['PROF']['SUB_ESPECIALIDAD']);
	$this->frmAdicionarEspecialidad();
	return true;
}


function InsertarEspecialidad()
{

	if(is_numeric($_REQUEST['uni']) OR empty($_REQUEST['uni']))
	{
		$this->frmError["MensajeError"] = "DIGITE EL NOMBRE DE LA UNIVERSIDAD,NO ES PERMITIDO SOLO NUMEROS!";
		$this->frmAdicionarEspecialidad();
		return true;
	}


	if(empty($_REQUEST['especialidad']) OR $_REQUEST['especialidad']=='-1')
	{
		$this->frmError["MensajeError"] = "SELECCIONE UNA ESPECIALIDAD POR FAVOR !";
		$this->frmAdicionarEspecialidad();
		return true;
	}


		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		//Revisemos q ya no exista esta especialidad.
		$query = "SELECT COUNT(*) FROM profesionales_especialidades
							WHERE especialidad='".$_REQUEST['especialidad']."'
							AND tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."'
							AND tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar profesionales_especialidades";
			$this->mensajeDeError = "Ocurrió un error al intentar obtener la secuencia de la
				solicitud de devolucion.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			$dbconn->RollbackTrans();
			return false;
		}

		//si entra aca es por q ya existe la especialidad
		if($result->fields[0] > 0)
		{
				$this->frmError["MensajeError"] = "EL PROFESIONAL YA TIENE ESTA ESPECIALIDAD!";
				$this->frmAdicionarEspecialidad();
				return true;
		}


	if(empty($_REQUEST['sub']) && ! empty($_SESSION['PROF']['SUB_ESPECIALIDAD']))
	{

		$query = "SELECT
		nextval('public.especialidades_sub_especialidades_sub_especialidad_id_seq')";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar obtener la secuencia de la
				solicitud de devolucion.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			$dbconn->RollbackTrans();
			return false;
		}

		$query = "insert into  especialidades_sub_especialidades (sub_especialidad_id,especialidad,descripcion) values
		('".$result->fields[0]."','".$_REQUEST['especialidad']."', '".$_SESSION['PROF']['SUB_ESPECIALIDAD']."');";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
		}
		$sub_especialidad=$result->fields[0];
	}
	else
	{
		$sub_especialidad=$_REQUEST['sub'];
	}


	$query = "insert into profesionales_especialidades (tipo_id_tercero, tercero_id, especialidad,sub_especialidad, universidad) values ('".$_SESSION['ManProf']['Profesional']['tipoid']."', '".$_SESSION['ManProf']['Profesional']['tercero']."', '".$_REQUEST['especialidad']."','$sub_especialidad', '".$_REQUEST['uni']."');";
	$dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0)
	{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
	}
	$dbconn->CommitTrans();
	$this->frmError["MensajeError"] = "DATOS GUARDADOS SATISFACTORIAMENTE";
	$this->PantallaProfesional();
	return true;


}



	function TipoEspecialidades()
	{
		list($dbconn) = GetDBconn();
			$query = "select especialidad,descripcion from especialidades;";
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
					if($result->EOF)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_sexo' esta vacia ";
						return false;
					}
						while (!$result->EOF)
						{
							$vars[$result->fields[0]]=$result->fields[1];
							$result->MoveNext();
						}
				}
				$result->Close();
		return $vars;
	}

	function EliminarEspecialidad()
	{
		list($dbconn) = GetDBconn();

		//si es 1 es por q es medico especilista,entonces debemos buscar si tiene especialidades
		//antes de insertar o actualizar.
		if($_REQUEST['TipoProf']=='1')
		{

			//Revisemos q ya no exista esta especialidad.
				 $query = "SELECT COUNT(*) FROM profesionales_especialidades
									WHERE
									tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."'
									AND tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."'";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al buscar profesionales_especialidades";
					$this->mensajeDeError = "Ocurrió un error al intentar obtener la secuencia de la
						solicitud de devolucion.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					 return false;
				}

				//si entra aca es por q ya existe la especialidad
				if($result->fields[0]==1)
				{
						$this->frmError["MensajeError"] = "PARA PODER ELIMINAR LA ESPECIALIDAD SE DEBE CAMBIAR EL TIPO PROFESIONAL DIFERENTE A MEDICO ESPECILISTA !";
						$this->PantallaProfesional();
						return true;
				}
		}



		$query = "delete from profesionales_especialidades where tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."' and tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."' and especialidad='".$_REQUEST['espe']."';";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		if($this->PantallaProfesional()==false)
		{
			return false;
		}
		return true;
	}

	function ModificarEspecialidad()
	{
		list($dbconn) = GetDBconn();
		foreach($_REQUEST as $k=>$t)
		{
			if(substr_count ($k,'universidade')==1)
			{
				$s=explode(",",$k);
				$query = "update profesionales_especialidades set universidad='".$t."' where tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."' and tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."' and especialidad='".$s[1]."';";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
		}
		if($this->PantallaProfesional()==false)
		{
			return false;
		}
		return true;
	}



function Insertar_Profesional_Departamento()
{

if(is_array($_REQUEST['op']))
{

		list($dbconn) = GetDBconn();
  	$query="select count(*) from  profesionales_departamentos
					where tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."'
					AND tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."'";
    $resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al buscar en system_usuarios_empresas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
			if($resulta->fields[0] > 0)
			{
				$query="DELETE  FROM profesionales_departamentos
				WHERE tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."'
				AND tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."'";

				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al borrar en system_usuarios_departamentos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}

		foreach($_REQUEST['op'] as $index=>$codigo)
		{
						$query="INSERT INTO profesionales_departamentos
								( tercero_id,tipo_id_tercero,departamento)
								VALUES
								('".$_SESSION['ManProf']['Profesional']['tercero']."',
								'".$_SESSION['ManProf']['Profesional']['tipoid']."',
								'$codigo')";
						$resulta=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar en system_usuarios_departamentos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
							}
		}
$this->frmError["MensajeError"] = "DATOS GUARDADOS SATISFACTORIAMENTE !";
}
else
{
	$this->frmError["MensajeError"] = "SE DEBE SELECCIONAR COMO MINIMO UNA CASILLA !";
}


$this->Pantalla_Asignar_dpto_Profesional();
return true;

}



	function GuardarEspecialidad()
	{
		list($dbconn) = GetDBconn();
		$query = "insert into profesionales_especialidades (tipo_id_tercero, tercero_id, especialidad, universidad) values ('".$_SESSION['ManProf']['Profesional']['tipoid']."', '".$_SESSION['ManProf']['Profesional']['tercero']."', '".$_REQUEST['especialidad']."', '".$_REQUEST['universidades']."');";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return true;
	}
 /**
  * Funcion donde se busca los profesionales.
  */ 
function TodoProfesionales()
{
    list($dbconn) = GetDBconn();
    $query  = " SELECT * ";
    $query .= " FROM profesionales "; 
    $query .= " WHERE tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."' ";
    $query .= " AND     tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."' ";
                      //print_r($query."consulta");
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al listar las empresas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;

		while (!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
	return $var;
}

/**
  * Funcion donde se busca los usuarios de profesionales.
  */ 
function UsuariosProfesionales($nombre)
{
    list($dbconn) = GetDBconn();
    $query  = " SELECT * ";
    $query .= " FROM system_usuarios "; 
    $query .= " WHERE nombre='".$nombre."' ";
   
                      //print_r($query."consulta");
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al listar las empresas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;

		while (!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
	return $var;
}
	function GuardarProfesional()
	{
		list($dbconn) = GetDBconn();
    $profesionales_todo=$this->TodoProfesionales();
    
		//si es 1 es por q es medico especilista,entonces debemos buscar si tiene especialidades
		//antes de insertar o actualizar.
		if($_REQUEST['TipoProf']=='1')
		{

			//Revisemos q ya no exista esta especialidad.
				 $query = "SELECT COUNT(*) FROM profesionales_especialidades
									WHERE
									tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."'
									AND tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."'";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al buscar profesionales_especialidades";
					$this->mensajeDeError = "Ocurrió un error al intentar obtener la secuencia de la
						solicitud de devolucion.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					 return false;
				}
        
        ///print_r($result."consulta");
				//si entra aca es por q ya existe la especialidad
				if($result->fields[0] < 1)
				{
						$_SESSION['ManProf']['Profesional']['nombrep']=$_REQUEST['nombrep'];
						//$_SESSION['ManProf']['Profesional']['TipoProf']=$_REQUEST['TipoProf'];
						$_SESSION['ManProf']['Profesional']['Sexo']=$_REQUEST['Sexo'];
						$_SESSION['ManProf']['Profesional']['TarjProf']=$_REQUEST['TarjProf'];
						$_SESSION['ManProf']['Profesional']['estado']=$_REQUEST['estado'];
						$_SESSION['ManProf']['Profesional']['universidad']=$_REQUEST['universidad'];
						$_SESSION['ManProf']['Profesional']['observacion']=$_REQUEST['observacion'];
						$_SESSION['ManProf']['Profesional']['registro_salud']=$_REQUEST['registro_salud'];
						$_SESSION['ManProf']['Profesional']['defuncion']=$_REQUEST['defuncion'];
						$_SESSION['ManProf']['Profesional']['circulante']=$_REQUEST['circulante'];
						$this->frmError["MensajeError"] = "PARA PODER GUARDAR EL TIPO PROFESIONAL = MEDICO ESPECILISTA ,  DEBE TENER CREADA ALMENOS 1 ESPECIALIDAD!";
						$this->PantallaProfesional();
						return true;
				}
		}
		$usuarios_profesionales=$this->UsuariosProfesionales($_SESSION['ManProf']['Profesional']['nombrep']);         
		
		 // print_r($_SESSION);        
		if($_SESSION['ManProf']['Profesional']['Existe']==1)
		{
   // print_r($profesionales_todo);
			$dbconn->BeginTrans();
			$query="delete from circulantes where tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."' and tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."' and empresa_id='".$_SESSION['ManProf']['empresa']."';";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			$query = "update profesionales set tipo_profesional='".$_REQUEST['TipoProf']."', tarjeta_profesional='".$_REQUEST['TarjProf']."', sexo_id='".$_REQUEST['Sexo']."', universidad='".$_REQUEST['universidad']."', observacion='".$_REQUEST['observacion']."', registro_salud_departamental='".$_REQUEST['registro_salud']."', sw_registro_defuncion='".$_REQUEST['defuncion']."', fecha_registro='".date("Y-m-d H:i:s")."', usuario_id=".$profesionales_todo[0]['usuario_id']."  where tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."' and tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."';";
		//print_r($query);
      $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
      //print_r($query);
			$query="select count(*) from profesionales_empresas where tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."' and tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."' and empresa_id='".$_SESSION['ManProf']['empresa']."';";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			if($result->fields[0]==0)
			{
				$query="insert into profesionales_empresas (tipo_id_tercero, tercero_id, empresa_id) values ('".$_SESSION['ManProf']['Profesional']['tipoid']."', '".$_SESSION['ManProf']['Profesional']['tercero']."', '".$_SESSION['ManProf']['empresa']."');";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
			if(!empty($_REQUEST['circulante']))
			{
				$query="insert into circulantes (tipo_id_tercero, tercero_id, empresa_id) values ('".$_SESSION['ManProf']['Profesional']['tipoid']."', '".$_SESSION['ManProf']['Profesional']['tercero']."', '".$_SESSION['ManProf']['empresa']."');";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
			$dbconn->CommitTrans();
		}
		else
		{
			$dbconn->BeginTrans();
			$query="delete from circulantes where tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."' and tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."' and empresa_id='".$_SESSION['ManProf']['empresa']."';";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			$query = "insert into profesionales (tipo_profesional, tarjeta_profesional, sexo_id, universidad, observacion, registro_salud_departamental, sw_registro_defuncion, fecha_registro, usuario_id, tipo_id_tercero, tercero_id) values ('".$_REQUEST['TipoProf']."', '".$_REQUEST['TarjProf']."', '".$_REQUEST['Sexo']."', '".$_REQUEST['universidad']."', '".$_REQUEST['observacion']."', '".$_REQUEST['registro_salud']."', '".$_REQUEST['defuncion']."', '".date("Y-m-d H:i:s")."', ".$usuarios_profesionales[0]['usuario_id'].", '".$_SESSION['ManProf']['Profesional']['tipoid']."', '".$_SESSION['ManProf']['Profesional']['tercero']."');";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			$query="select count(*) from profesionales_empresas where tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."' and tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."' and empresa_id='".$_SESSION['ManProf']['empresa']."';";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			if($result->fields[0]==0)
			{
				$query="insert into profesionales_empresas (tipo_id_tercero, tercero_id, empresa_id) values ('".$_SESSION['ManProf']['Profesional']['tipoid']."', '".$_SESSION['ManProf']['Profesional']['tercero']."', '".$_SESSION['ManProf']['empresa']."');";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
			if(!empty($_REQUEST['circulante']))
			{
				$query="insert into circulantes (tipo_id_tercero, tercero_id, empresa_id) values ('".$_SESSION['ManProf']['Profesional']['tipoid']."', '".$_SESSION['ManProf']['Profesional']['tercero']."', '".$_SESSION['ManProf']['empresa']."');";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
			$dbconn->CommitTrans();
		}
          $target_path1 ="images/firmas_profesionales/";
		$target_path = $target_path1.$_SESSION['ManProf']['Profesional']['tipoid']."*".$_SESSION['ManProf']['Profesional']['tercero'].".jpg";
		//print_r($_FILES['datafile']['tmp_name'], $target_path);
		if(move_uploaded_file($_FILES['datafile']['tmp_name'], $target_path)) 
    {
			echo "The file ".basename($target_path)." has been uploaded";
               $this->frmError["MensajeError"] = "DATOS GUARDADOS SATISFACTORIAMENTE !";
       $query= " UPDATE profesionales 
                 SET    firma='".$_SESSION['ManProf']['Profesional']['tipoid']."*".$_SESSION['ManProf']['Profesional']['tercero'].".jpg"."' 
                WHERE  tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."'
                AND    tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."' ";
       // print_r($query);
       //'".$_REQUEST['TipoProf']."', '".$_REQUEST['TarjProf']."'
       $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
        $dbconn->CommitTrans();
		} 
		else{
			$this->frmError["MensajeError"] = "No se pudo subir el archivo de la firma";
			}
		
		
		$_SESSION['ManProf']['Profesional']['nombrep']=$_REQUEST['nombrep'];
		$_SESSION['ManProf']['Profesional']['TipoProf']=$_REQUEST['TipoProf'];
		$_SESSION['ManProf']['Profesional']['Sexo']=$_REQUEST['Sexo'];
	 	$_SESSION['ManProf']['Profesional']['circulante']=$_REQUEST['circulante'];
		if($this->PantallaProfesional()==false)
		{
			return false;
		}
		return true;
	}

	function Desicion()
	{

//print_r($_REQUEST);exit;

		if(!empty($_REQUEST['VOLVER']))
		{
		  if(empty($_SESSION['PROVEEDORES']))
			{
				if($this->ListarProfe()==false)
				{
					return false;
				}
			}
			else
			{
			  $this->ReturnMetodoExterno($_SESSION['PROVEEDORES']['RETORNO']['contenedor'],$_SESSION['PROVEEDORES']['RETORNO']['modulo'],$_SESSION['PROVEEDORES']['RETORNO']['tipo'],$_SESSION['PROVEEDORES']['RETORNO']['metodo']);
				return true;
			}
		}
		else
		{
			if(!empty($_REQUEST['ADICIONAR']))
			{
				if($_REQUEST['especialidad']!='-1')
				{
					if($this->GuardarEspecialidad()==false)
					{
						return false;
					}
				}
				if($this->PantallaProfesional()==false)
				{
					return false;
				}
			}
			else
			{
				if(!empty($_REQUEST['MODIFICAR']))
				{
					if($this->ModificarEspecialidad()==false)
					{
						return false;
					}
				}
				else
				{
					if($this->GuardarProfesional()==false)
					{
						return false;
					}
				}
			}
		}
		return true;
	}

	function GuardarProfesionalDepartamento()
	{
		list($dbconn) = GetDBconn();
		$query="select count(*) from profesionales_departamentos where tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."' and tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."' and departamento='".$_SESSION['ManProf']['departamento']."';";
		$result=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		if($result->fields[0]==0)
		{
			$query="insert into profesionales_departamentos (tipo_id_tercero, tercero_id, departamento) values ('".$_SESSION['ManProf']['Profesional']['tipoid']."', '".$_SESSION['ManProf']['Profesional']['tercero']."', '".$_SESSION['ManProf']['departamento']."');";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
		return true;
	}






function Traer_Informacion($empresa_id,$tercero,$tipo)
{
  	list($dbconn) = GetDBconn();
		$query="select a.departamento,a.descripcion,c.centro_utilidad,c.descripcion as centro,u.descripcion as unidad,e.tipo_id_tercero,e.tercero_id
				from centros_utilidad c,unidades_funcionales u,departamentos a
				left join profesionales_departamentos
				as e on(e.departamento=a.departamento AND e.tipo_id_tercero='$tipo' AND e.tercero_id='$tercero')
				WHERE c.empresa_id='$empresa_id'
				AND c.centro_utilidad=u.centro_utilidad
				AND u.empresa_id=c.empresa_id AND a.centro_utilidad=c.centro_utilidad AND u.unidad_funcional=a.unidad_funcional
				AND a.empresa_id=c.empresa_id
				ORDER BY u.unidad_funcional,c.centro_utilidad";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al listar las empresas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;

		while (!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
	return $var;
}





	function DesicionDepartamento()
	{
		if(!empty($_REQUEST['VOLVER']))
		{
		$this->ReturnMetodoExterno($_SESSION['PROVEEDORES']['RETORNO']['contenedor'],$_SESSION['PROVEEDORES']['RETORNO']['modulo'],$_SESSION['PROVEEDORES']['RETORNO']['tipo'],$_SESSION['PROVEEDORES']['RETORNO']['metodo']);
				return true;
		}
		else
		{
			if(!empty($_REQUEST['GUARDAR']))
			{
				if($this->GuardarProfesionalDepartamento()==false)
				{
					return false;
				}
				if($this->PantallaProfesionalDepartamento()==false)
				{
					return false;
				}
			}
			else
			{
				if($this->PantallaProfesionalDepartamento()==false)
				{
					return false;
				}
			}
		}
		return true;
	}

}
?>

