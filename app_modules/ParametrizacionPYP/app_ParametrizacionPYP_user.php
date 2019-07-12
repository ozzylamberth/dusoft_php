
<?php

/**
* Modulo de ParametrizacionPYP (PHP).
*
//*
*
* @author Carlos A. Henao <carlosarturohenao@gmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_ParametrizacionPYP_user.php
*
//*
**/

class app_ParametrizacionPYP_user extends classModulo
{
    var $uno;//para los errores
    
    function app_ParametrizacionPYP_user()
    {
				return true;
    }

    function main()
    {
        $this->PrincipalPYP();
        return true;
    }
    
    function SetStyle($campo)//Mensaje de error en caso de no encontrar los datos
    {
        if ($this->frmError[$campo] || $campo=="MensajeError")
        {
            if ($campo=="MensajeError")
            {
                return ("<tr><td class='label_error' colspan='2' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
            }
            else
            {
                return ("label_error");
            }
        }
        return ("label");
    }

    function UsuariosPyP()//Función de permisos
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        if(!empty($_SESSION['SYSTEM_USUARIO_ID']))
        {				
						$sql="SELECT B.empresa_id, B.razon_social as desc_emp,C.centro_utilidad,C.descripcion as desc_cen,D.unidad_funcional,D.descripcion as desc_uni 
									FROM userpermisos_pypadmin AS A, 
									empresas AS B,
									centros_utilidad AS C, 
									unidades_funcionales AS D
									WHERE A.usuario_id=".$_SESSION['SYSTEM_USUARIO_ID']."  
									AND A.empresa_id=B.empresa_id
									ORDER BY C.centro_utilidad";
        }
        else
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "El usuario no se ha registrado.";
            return false;
        }
        unset($_SESSION['SEGURIDAD']);
        if(empty($_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][0]))
        {
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
                    $prueba6[$data['desc_emp']][$data['desc_cen']]=$data;
                    $_SESSION['SEGURIDAD']['EMPRESA_ID']=$data['empresa_id'];
										$_SESSION['SEGURIDAD']['CENTRO_ID']=$data['centro_utilidad'];
										$_SESSION['SEGURIDAD']['UNIDAD_ID']=$data['unidad_funcional'];
                    $i=1;
                }
            }
        }
        else
        {
            $i=1;
        }
        if($i<>0)
        {
            $mtz1[0]="EMPRESA";
            $com[0]=$mtz1;
            $com[1]=$prueba6;
            $url[0]='app';
            $url[1]='ParametrizacionPYP';
            $url[2]='user';
            $url[3]='FrmParametrizacionProgramas';
            $url[4]='ParametrizacionPYP';
            if(empty($_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][0]))
            {
                $_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][]=$mtz1;
                $_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][2]=$prueba6;
            }
            $nombre='SELECCIONAR EMPRESA';
            $accion=ModuloGetURL('system','Menu','user','main');
            $this->salida.=gui_theme_menu_acceso($nombre,$_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][0],$_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][2],$url,$accion);
            return $com;
        }
        else
        {
            $mensaje = "EL USUARIO NO TIENE PERMISOS PARA ACCEDER AL MODULO.";
            $titulo = "PARAMETRIZACION PYP";
            $boton = "VOLVER";//REGRESAR
            $accion=ModuloGetURL('system','Menu','user','main');
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return false;
        }
        return true;
    }

		function TraerAyudas($tipoayuda)
		{
				if($tipoayuda=='USUARIO')
				{
					$tabla="pyp_ayudas_educativas_profesionales";
					$campos="programa_id, ayuda_id,tema,contenido ";
				}
				elseif($tipoayuda=='PACIENTES')
				{
					$tabla="pyp_ayudas_educativas_pacientes";
					$campos="programa_id, ayuda_educativa_id,tema,nombre_archivo";
				}
        list($dbconn) = GetDBconn();
        $query="SELECT $campos
                    FROM $tabla
										WHERE programa_id=".$_SESSION['programa']."";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos){
        while(!$result->EOF){
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
              }
            }
        }
        $result->Close();
        return $vars;
		}

	function TraerAyudasEditar($programa,$ayuda,$tipoayuda)
		{
				if($tipoayuda=='USUARIO')
				{
					$tabla="pyp_ayudas_educativas_profesionales";
					$campos="programa_id, ayuda_id,tema,contenido ";
					$cond="ayuda_id=".$ayuda."AND programa_id=".$_SESSION['programa']."";
				}
				elseif($tipoayuda=='PACIENTES')
				{
					$tabla="pyp_ayudas_educativas_pacientes";
					$campos="programa_id, ayuda_educativa_id,tema,nombre_archivo";
					$cond="ayuda_educativa_id=".$ayuda." AND programa_id=".$_SESSION['programa']."";
				}
				list($dbconn) = GetDBconn();
				$query="SELECT $campos
								FROM $tabla
								WHERE $cond;";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}else{
						$datos=$result->RecordCount();
						if($datos){
				if(!$result->EOF){
										$vars=$result->GetRowAssoc($toUpper=false);
							}
						}
				}
				$result->Close();
				return $vars;
		}

		function EliminarAyudasUsuario()
		{
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $usuario=UserGetUID();
        $query="DELETE
        FROM pyp_ayudas_educativas_profesionales
        WHERE ayuda_id=".$_REQUEST['ayuda']."
				AND programa_id=".$_SESSION['programa']."";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollBackTrans();
            $this->frmError["MensajeError"]="VERIFICAR DATOS. ".$query.'--'.$dbconn->ErrorMsg();
            $this->uno=1;
            $this->FrmAyudasUsuario();
            return true;
        }
        $this->frmError["MensajeError"]="DATOS ELIMINADOS.";
        $this->uno=1;
        $this->FrmAyudasUsuario();
        $dbconn->CommitTrans();
        return true;
		}

		function EliminarAyudasPaciente()
		{
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $usuario=UserGetUID();
        $query="DELETE
        FROM pyp_ayudas_educativas_pacientes
        WHERE ayuda_educativa_id=".$_REQUEST['ayuda']."
				AND programa_id=".$_SESSION['programa']."";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollBackTrans();
            $this->frmError["MensajeError"]="VERIFICAR DATOS. ".$query.'--'.$dbconn->ErrorMsg();
            $this->uno=1;
            $this->FrmAyudasPaciente();
            return true;
        }
        $this->frmError["MensajeError"]="DATOS ELIMINADOS.";
        $this->uno=1;
        $this->FrmAyudasPaciente();
        $dbconn->CommitTrans();
        return true;
		}

	function GuardarAyudasUsuario()
	{
				if(empty($_REQUEST['contenido']))
				{
						$this->frmError["contenido"]=1;
						$this->frmError["MensajeError"]="EL CAMPO CONTENIDO NO DEBE SER VACIO";
						$this->uno=1;
						$this->FrmAyudasUsuario();
						return true;                
				}
				if( empty($_REQUEST['tema']))
				{
						$this->frmError["tema"]=1;
						$this->frmError["MensajeError"]="EL CAMPO TEMA NO DEBE SER VACIO";
						$this->uno=1;
						$this->FrmAyudasUsuario();
						return true;                
				}

				list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();
				$usuario=UserGetUID();
	
				//VERIFICAR SI EL REGISTRO YA EXISTE
				if(!empty($_REQUEST['ayuda']))
				{
						$query="SELECT count(*)
						FROM pyp_ayudas_educativas_profesionales
						WHERE ayuda_id=".$_REQUEST['ayuda'].";";
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
								$this->error = "Error al Cargar el Modulo - GuardarAyudasUsuario";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}
						if ($result->fields[0]>0)
						{
						$query="DELETE
						FROM pyp_ayudas_educativas_profesionales
						WHERE ayuda_id=".$_REQUEST['ayuda'].";";
						$result = $dbconn->Execute($query);
						}
				}
				//FIN VERIFICAR SI EL REGISTRO YA EXISTE
				//INSERTAR AYUDA
				if(!empty($_REQUEST['ayuda']))
				{
					$campos="ayuda_id,";
					$datos="".$_REQUEST['ayuda'].",";
				}
				else
				{
					$campos="";
					$datos="";
				}
				if(!empty($_SESSION['programa']))
				{
					$campos1="programa_id,";
					$datos1="".$_SESSION['programa'].",";
				}
				else
				{
					$campos1="";
					$datos1="";
				}
					$query ="INSERT INTO pyp_ayudas_educativas_profesionales
									(
										$campos
										$campos1
										tema,
										contenido
									)
									VALUES
									(
										$datos
										$datos1
										'".$_REQUEST['tema']."',
										'".$_REQUEST['contenido']."'
									);";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$dbconn->RollBackTrans();
							$this->frmError["MensajeError"]="VERIFICAR DATOS. ".$query.'--'.$dbconn->ErrorMsg();
							$this->uno=1;
							$this->FrmAyudasUsuario();
							return true;
					}
				//FIN INSERTAR AYUDA
				$this->frmError["MensajeError"]="DATOS INSERTADOS O MODIFICADOS.";
				$this->uno=1;
				$_REQUEST['tema']="";
				$_REQUEST['contenido']="";
				$_REQUEST['ayuda']="";
				$this->FrmAyudasUsuario();
				$dbconn->CommitTrans();
				return true;
	}
	
	
	function TraerDatosPyP($programa_id)
	{
        list($dbconn) = GetDBconn();
        if($programa_id)
        {
            $sql=" WHERE a.programa_id=".$programa_id." AND a.sw_estado='1'";
        }
        else
        {
            $sql="WHERE a.sw_estado='1'";
        }
        
		$query="SELECT a.programa_id, a.hc_modulo,
				a.app_modulo, a.descripcion,a.sw_estado
				FROM pyp_programas a
				$sql
				ORDER BY a.programa_id";
        
		$result = $dbconn->Execute($query);
        
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
        }
		else
		{
			$datos=$result->RecordCount();
            if($datos)
			{
				while(!$result->EOF)
				{
						$vars[]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
				}
            }
        }
		
        $result->Close();
		return $vars;
	}
	
	function GuardarAyudasPaciente()
	{
				if(empty($_FILES['ubicacion']['name']))
				{
						$this->frmError["ubicacion"]=1;
						$this->frmError["MensajeError"]="EL CAMPO NOMBRE ARCHIVO NO DEBE SER VACIO";
						$this->uno=1;
						$this->FrmAyudasPaciente();
						return true;                
				}
				if(empty($_REQUEST['tema']))
				{
						$this->frmError["tema"]=1;
						$this->frmError["MensajeError"]="EL CAMPO TEMA NO DEBE SER VACIO";
						$this->uno=1;
						$this->FrmAyudasPaciente();
						return true;                
				}
				
				//MANEJO DEL UPLOAD
				//tomar el valor de un elemento de tipo texto del formulario
				//datos del arhivo
				$nombre_archivo = $_FILES['ubicacion']['name'];
				$tipo_archivo = $_FILES['ubicacion']['type'];
				$tamano_archivo = $_FILES['ubicacion']['size'];
				//CARGAR LA VARIABLE DE MODULO
				$dir_upload=ModuloGetVar('app','Promocion_y_PrevencionAdmin','ruta_archivo');
				//comprobar si las características del archivo son las apropiadas
				
				if ($tamano_archivo > 1000000)
				{
						$this->frmError["MensajeError"]="se permiten archivos de 1000 Kb máximo.";
						$this->uno=1;
						$this->FrmAyudasPaciente();
						return true;                
				}
				else
				{
						if(!move_uploaded_file($_FILES['ubicacion']['tmp_name'], $dir_upload.$nombre_archivo))
						{
							$this->frmError["MensajeError"]="Ocurrió algún error al subir el fichero. No pudo guardarse.";
							$this->uno=1;
							$this->FrmAyudasPaciente();
							return true;                
						}
				}
				//FIN MANEJO DEL UPLOAD

				list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();
				$usuario=UserGetUID();
				
				//VERIFICAR SI EL REGISTRO YA EXISTE
				if(!empty($_REQUEST['ayuda']))
				{
						$query="SELECT count(*)
						FROM pyp_ayudas_educativas_pacientes
						WHERE ayuda_educativa_id=".$_REQUEST['ayuda']."
						AND programa_id=".$_SESSION['programa']."";
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
								$this->error = "Error al Cargar el Modulo - GuardarAyudasPaciente";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}
						if ($result->fields[0]>0)
						{
						$query="DELETE
						FROM pyp_ayudas_educativas_pacientes
						WHERE ayuda_educativa_id=".$_REQUEST['ayuda']."
						AND programa_id=".$_SESSION['programa']."";
						$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
									$dbconn->RollBackTrans();
									$this->frmError["MensajeError"]="VERIFICAR DATOS. ".$query.'--'.$dbconn->ErrorMsg();
									$this->uno=1;
									$this->FrmAyudasPaciente();
									return false;
							}
						}
				}
				//FIN VERIFICAR SI EL REGISTRO YA EXISTE
				//INSERTAR AYUDA
				if(!empty($_REQUEST['ayuda']))
				{
					$campos="ayuda_educativa_id,";
					$datos="".$_REQUEST['ayuda'].",";
				}
				else
				{
					$campos="";
					$datos="";
				}
				if(!empty($_SESSION['programa']))
				{
					$campos1="programa_id,";
					$datos1="".$_SESSION['programa'].",";
				}
				else
				{
					$campos1="";
					$datos1="";
				}
				
				$path=dirname($_SERVER['SCRIPT_FILENAME']);
				$query ="INSERT INTO pyp_ayudas_educativas_pacientes
											(
												$campos
												$campos1
												tema,
												nombre_archivo
											)
											VALUES
											(
												
												$datos
												$datos1
												'".$_REQUEST['tema']."',
												'".$path.'/'.$dir_upload.$nombre_archivo."'
											);";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$dbconn->RollBackTrans();
							$this->frmError["MensajeError"]="VERIFICAR DATOS. ".$query.'--'.$dbconn->ErrorMsg();
							$this->uno=1;
							$this->FrmAyudasPaciente();
							return false;
					}
				//FIN INSERTAR AYUDA
				$this->frmError["MensajeError"]="DATOS INSERTADOS O MODIFICADOS.";
				$this->uno=1;
				$_REQUEST['ubicacion']="";
				$_REQUEST['tema']="";
				$_REQUEST['ayuda']="";
				$this->FrmAyudasPaciente();
				$dbconn->CommitTrans();
				return true;
	}

	function GetCronogramaCitas()
	{
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;
		
		$query="SELECT b.cargo,b.descripcion,a.observacion,a.alias
						FROM pyp_cargos a
						JOIN cups as b on(a.cargo_cups=b.cargo)
						WHERE a.programa_id=".$_SESSION['programa']."
						ORDER BY b.descripcion";
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;	
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
            		$this->error = "Error al Cargar el Modulo ParametrizacionPYP - GetCronogramaCitas ";
            		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
           	 	return false;
    }
		else
		{
            $datos=$result->RecordCount();
            if($datos)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
            		}
        	}
	
        	$result->Close();
        	
		return $vars;
	}
	
	function GuardarCronogramaCitas($datos)
	{
		list($dbconn) = GetDBconn();
		
		$periodos=9;
		
		for($i=0;$i<$periodos;$i++)
		{
			if($datos['rango_inicio'.$i]==-1 || $datos['rango_fin'.$i]==-1 || $datos['rango_media'.$i]==-1)
			{
				$this->ban=1;
				$this->frmError["MensajeError"]="LOS DATOS SON OBLIGATORIOS (rango de semanas, semana de gestacion)";
				
				return false;
			}
			
			if($datos['rango_inicio'.$i]!=-1 && $datos['rango_fin'.$i]!=-1 && $datos['rango_media'.$i]!=-1)
			{
				if($datos['rango_fin'.$i] < $datos['rango_inicio'.$i])
				{
					$this->ban=1;
					$this->frmError["MensajeError"]="EL RANGO INICIAL DEBE SER MENOR O IGUAL AL RANGO FINAL (RANGO DE SEMANAS) EN EL PERIODO ".($i+1);
					
					return false;
				}
				
				if($datos['rango_media'.$i] < $datos['rango_inicio'.$i] || $datos['rango_media'.$i] > $datos['rango_fin'.$i])
				{
					$this->ban=1;
					$this->frmError["MensajeError"]="LA SEMANA DE GESTACION DEBE ESTAR ENTRE EL RANGO DE SEMANAS EN EL PERIODO ".($i+1);
					
					return false;
				}
				
				if($i<$periodos-1)
				{
					if($datos['rango_fin'.$i] > $datos['rango_inicio'.($i+1)])
					{
						$this->ban=1;
						$this->frmError["MensajeError"]="LOS PERIODOS DEBEN SER EXCLUYENTES, PERIODOS ".($i+1)." y ".($i+2);
						return false;
					}
				}
			}
			
			if(empty($datos['medico'.$i]) && empty($datos['enfermera'.$i]))
			{
				$this->ban=1;
				$this->frmError["MensajeError"]="SELECCIONE UN EL PROFESIONAL EN EL PERIODO ".($i+1);
					
				return false;
			}
		}
		
		$numero_pro=$datos['numero_pro'];

		for($i=0;$i<$periodos;$i++)
		{
			$x=0;
			
			$query="SELECT periodo_id
							FROM pyp_periodos_programa
							WHERE periodo_metrica=$i
							AND programa_id=".$_SESSION['programa'].";";
			
			$result = $dbconn->Execute($query);
			$periodo_id=$result->fields[0];
			
			if(!$periodo_id)
			{
				$query="SELECT max(periodo_id)
								FROM pyp_periodos_programa";
					
				$result = $dbconn->Execute($query);
				$periodo_id=$result->fields[0]+1;
				
				$x=1;
			}
			
			$rango_inicio[$i]=$datos['rango_inicio'.$i];
			$rango_fin[$i]=$datos['rango_fin'.$i];
			$rango_media[$i]=$datos['rango_media'.$i];
			$med[$i]=$datos['medico'.$i];
			$enf[$i]=$datos['enfermera'.$i];

			if($rango_inicio[$i]!=-1 and $rango_fin[$i]!=-1 and $rango_media[$i]!=-1)
			{
				if($x==1)
				{
					$query="INSERT INTO pyp_periodos_programa VALUES
					(
					".$periodo_id.",
					".$_SESSION['programa'].",
					".$rango_inicio[$i].",
					".$rango_fin[$i].",
					".$rango_media[$i].",
					".$i."
					)";
				}
				else
				{
					$query="UPDATE pyp_periodos_programa 
									SET rango_inicio=".$rango_inicio[$i].",
									rango_fin=".$rango_fin[$i].",
									rango_media=".$rango_media[$i].",
									periodo_metrica=".$i."
									WHERE periodo_id=$periodo_id
									AND programa_id=".$_SESSION['programa'].";";
				}
				
				$result = $dbconn->Execute($query);
	
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo ParametrizacionPYP - GuardarCronogramaCitas - SQL1";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
					$this->ban=1;
					return false;
				}
				
				$query="DELETE 
								FROM pyp_profesional_periodos_programa 
								WHERE periodo_id=".$periodo_id."
								AND programa_id=".$_SESSION['programa'].";";
					
					$result = $dbconn->Execute($query);
					
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo  ParametrizacionPYP- GuardarCronogramaCitas - SQL4";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
						$this->ban=1;
						return false;
					}

				if(!empty($med[$i]))
				{
					$query="INSERT INTO pyp_profesional_periodos_programa 
					VALUES
					(
					".$periodo_id.",
					".$_SESSION['programa'].",
					'".$med[$i]."'
					)";
					
					$result = $dbconn->Execute($query);
	
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo ParametrizacionPYP - GuardarCronogramaCitas - SQL3 ";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
						$this->ban=1;
						return false;
       		}
				}

				if(!empty($enf[$i]))
				{
					$query="INSERT INTO pyp_profesional_periodos_programa 
					VALUES
					(
					".$periodo_id.",
					".$_SESSION['programa'].",
					'".$enf[$i]."'
					)";
					
					$result = $dbconn->Execute($query);
					
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo ParametrizacionPYP - GuardarCronogramaCitas - SQL5";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
						$this->ban=1;
						return false;
       		}
				}
				
				$query="DELETE 
								FROM pyp_programas_cargos_periodo 
								WHERE periodo_id=".$periodo_id."
								AND programa_id=".$_SESSION['programa'].";";
						
				$result = $dbconn->Execute($query);
				
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo  ParametrizacionPYP- GuardarCronogramaCitas - SQL6";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
					$this->ban=1;
					return false;
				}
				
				for($k=0;$k<$numero_pro;$k++)
				{
					$procedimiento[$k][$i]=$datos['procedimiento'.$k.$i];
					
					if(!empty($procedimiento[$k][$i]))
					{
						$query="INSERT INTO pyp_programas_cargos_periodo VALUES
						(
						$periodo_id,
						".$_SESSION['programa'].",
						'".$procedimiento[$k][$i]."'
						)";

						$result = $dbconn->Execute($query);
						
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo  ParametrizacionPYP- GuardarCronogramaCitas - SQL7";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
							$this->ban=1;
							return false;
						}
					}
				}
			}
		}
		
		$numproT=$datos['numproT'];
		
		for($k=0;$k<$numproT;$k++)
		{
			$query="UPDATE pyp_cargos SET observacion='".$datos['observacion'.$k]."'
					WHERE programa_id=".$_SESSION['programa']." AND cargo_cups='".$datos['cargos'.$k]."'";
			
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo  ParametrizacionPYP - GuardarCronogramaCitas - SQL8";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
		
		$this->ban=1;
		$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE";
		
		return true;
	}
	
	
	function  GetPeriodosPrograma()
	{
		list($dbconn) = GetDBconn();

		$query="SELECT a.*,b.cargo_cups
						FROM pyp_periodos_programa a
						JOIN pyp_programas_cargos_periodo as b on (a.periodo_id=b.periodo_id and a.programa_id=b.programa_id)	
						WHERE a.programa_id=".$_SESSION['programa']."
						ORDER BY a.periodo_id";
		
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo ParametrizacionPYP - GetPeriodosPrograma";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
    }
		else
		{
			$datos=$result->RecordCount();
			if($datos)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
      }
    }
		  	
		return $vars;
	}
	
	
	function  GetPeriodosProgramaProfesional()
	{
		list($dbconn) = GetDBconn();
		
		$query="SELECT *
						FROM pyp_profesional_periodos_programa
						WHERE programa_id=".$_SESSION['programa']."";
		
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo ParametrizacionPYP - GetPeriodosPrograma";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
    }
		else
		{
			$datos=$result->RecordCount();
			if($datos)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
      }
    }
        	
		return $vars;
	}
	
	
	function GetProtocolosAtencion()
	{
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;
		
		$query="SELECT *
						FROM pyp_protocolos_atencion
						WHERE programa_id=".$_SESSION['programa'];
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;	
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
            		$this->error = "Error al Cargar el Modulo";
            		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
           	 	return false;
       		}
		else
		{
            		$datos=$result->RecordCount();
            		if($datos)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
            		}
        	}
	
        	$result->Close();
        	
		return $vars;
	}
	
	function AdicionarProtocoloAtencion()
	{
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;
		
		$query="SELECT max(protocolo_id) 
						FROM pyp_protocolos_atencion";
					
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$result = $dbconn->Execute($query);
		
		while($res=$result->FetchRow())
		{
			$protocolo=$res[0]+1;
		}
				
		$result->Close();
		
		if(empty($_REQUEST['nombre']))
		{
			$this->frmError["nombre"]=1;
			$this->frmError["MensajeError"]="EL CAMPO NOMBRE NO DEBE SER VACIO";
			$this->ban=1;
			$this->FrmProtocolosAtencion();
			return true;   
		}
		
		if(empty($_REQUEST['url']))
		{
			$this->frmError["url"]=1;
			$this->frmError["MensajeError"]="EL CAMPO URL NO DEBE SER VACIO";
			$this->ban=1;
			$this->FrmProtocolosAtencion();
			return true; 
		}
		
		$nombre=$_REQUEST['nombre'];
		$url=$_REQUEST['url'];
		$programa=$_REQUEST['programa'];
		
		if($_REQUEST['protocolo'])
		{
			$query="UPDATE pyp_protocolos_atencion SET 
				nombre='$nombre', url='$url'
				WHERE protocolo_id=".$_REQUEST['protocolo'];
			
		}
		else
		{
			$query="INSERT INTO pyp_protocolos_atencion 
				VALUES($protocolo,'$nombre',$programa,'$url')";
		}
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
            		$this->error = "Error al Cargar el Modulo - ";
            		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
           	 	return false;
       		}
		$result->Close();
		
		$this->frmError["MensajeError"]="DATOS INSERTADOS O MODIFICADOS";
		$this->ban=1;
		$_REQUEST['nombre']="";
		$_REQUEST['url']="";
		
		$this->FrmProtocolosAtencion();
		
		return true;
	}
	
	function TraerProtocolosAtencion($protocolo)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="SELECT nombre,url 
			FROM pyp_protocolos_atencion
			WHERE protocolo_id=$protocolo
			AND programa_id=".$_SESSION['programa'];
		
			
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
            		$this->error = "Error al Cargar el Modulo";
            		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
           	 	return false;
       		}
		else
		{
            		$datos=$result->RecordCount();
            		if($datos)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
            		}
        	}

		$result->Close();

        	return $vars;
	}
	
	function EliminarProtocoloAtencion($protocolo)
	{
		list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;
		
		if(sizeof($protocolo))
		{
			for($i=0;$i< sizeof($protocolo);$i++)
			{
				$query="DELETE
					FROM pyp_protocolos_atencion
					WHERE protocolo_id=".$protocolo[$i];
				
				$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
				$result = $dbconn->Execute($query);
			}
			if ($dbconn->ErrorNo() != 0)
			{
            			$this->error = "Error al Cargar el Modulo";
            			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
           	 		return false;
       		}
		
			$result->Close();
			$this->frmError["MensajeError"]="DATOS ELIMINADOS";
			$this->ban=1;
		}
		else
		{
			$this->frmError["MensajeError"]="SELECCIONE POR LO MENOS UN PROTOCOLO DE ATENCIÒN";
			$this->ban=1;
		}
		return true;
	}
	
	function ReporteSeguimientoCitas($_REQUEST)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$this->ProcesarSqlConteo();
		$fecha="";
		$filtro="";
		$order="";
		
		$fecha_ini=$this->FechaStamp($_REQUEST['fecha_ini']);
		$fecha_fin=$this->FechaStamp($_REQUEST['fecha_fin']);

		if(!empty($fecha_ini) and !empty($fecha_fin))
		{
			$fecha="AND i.fecha_registro>='$fecha_ini' AND i.fecha_registro<='$fecha_fin'";
		}
		switch($_REQUEST['filtro'])
		{
			case 1:
					switch($_REQUEST['opcion'])
					{
						case 1:
							$filtro="AND d.sw_estado='2'";
						break;
						case 2:
							$filtro="AND d.sw_estado='3'";
						break;
					}
			break;
			case 2:
					switch($_REQUEST['opcion'])
					{
						case 1:
							$filtro="AND f.clasificacion_riesgo='1'";
						break;
						case 2:
							$filtro="AND f.clasificacion_riesgo='2'";
						break;
						case 3:
							$filtro="AND f.clasificacion_riesgo is null";
						break;
					}
			break;
			case 3:
					switch($_REQUEST['opcion'])
					{
					case 1:
						$filtro="AND f.riesgo_biologico >= 0";
					break;
					case 2:
						$filtro="AND f.riesgo_psicosocial >= 0";
					break;
					}
			break;
			case 4:
					switch($_REQUEST['opcion'])
					{
						case 1:
							$filtro="AND lower(pdiag.desc_cpn)='itu'";
						break;
						case 2:
							$filtro="AND lower(pdiag1.desc_cpn)='cervicovaginitis'";
						break;
						case 3:
							$filtro="AND e1.valor is not null";
						break;
						case 4:
							$filtro="AND e.valor is not null";
						break;
					}
			break;
			case 5:
					switch($_REQUEST['opcion'])
					{
						case 1:
							$filtro="AND n.sw_estado='3'";
						break;
						case 2:
							$filtro="AND n.sw_estado!='3' AND n.sw_estado is not null";
						break;
					}
			break;
			case 6:
					switch($_REQUEST['opcion'])
					{
						case 1:
							$filtro="AND i.contacto_telefonico is not null";
						break;
						case 2:
							$filtro="AND i.ips_direccionada!=''";
						break;
						case 3:
							$filtro="AND n.sw_estado='3' AND (i.contacto_telefonico is not null OR i.ips_direccionada is not null)";
						break;
						case 4:
							$filtro="AND i.observacion is not null";
						break;
					}
			break;
		}

		switch($_REQUEST['ordenado_por'])
		{
			case 1:
				$order="ORDER BY b.primer_nombre||' '||b.segundo_nombre ||' '|| b.primer_apellido ||' '|| b.segundo_apellido";
			break;
			case 2:
				$order="ORDER BY i.fecha_registro";
			break;
		}
		
		$query="	SELECT 
							DISTINCT a.inscripcion_id,
							d.fecha_ideal_proxima_cita,
							c.fecha_calulada_parto,
							c.fecha_ultimo_periodo,
							b.primer_nombre||' '||b.segundo_nombre ||' '|| b.primer_apellido ||' '|| b.segundo_apellido as nombre_paciente,
							b.residencia_direccion,
							b.residencia_telefono,
							a.paciente_id as pd,
							a.tipo_id_paciente as tpd,
							h.nombre,
							a.estado,
							e.semana,
							pdiag.desc_cpn as itu,
							pdiag1.desc_cpn as cervico,
							e1.valor as hta,
							e.valor as diabetes_gestacional,
							f.riesgo_biologico as biologico,
							f.riesgo_psicosocial as psicosocial,
							f1.valor as remision1,
							f2.valor as remision2,
							p.fecha_turno,
							n.sw_estado,
							i.ips_direccionada as ips_dir,
							i.pyp_cpn_seguimiento_id as seguimiento_id,
							i.fecha_registro as fecha_contacto,
							i.contacto_telefonico,
							i.evolucion_id,
							i.observacion,
							CASE f.clasificacion_riesgo 
							WHEN 1 THEN 'BAJO'
							WHEN 2 THEN 'ALTO'
							END as riesgo,
							CASE d.sw_estado
							WHEN '1' THEN 'INSCRITO SIN ATENCION'
							WHEN '2' THEN 'PRIMERA ATENCION'
							WHEN '3' THEN 'CONTROL'
							WHEN '4' THEN 'CIERRE'
							END as tipo_atencion
							FROM pacientes as b
							JOIN pyp_inscripciones_pacientes AS a 
							ON
							(
								a.tipo_id_paciente=b.tipo_id_paciente  AND a.paciente_id=b.paciente_id
							)
							JOIN pyp_inscripcion_cpn AS c 
							ON
							(
								c.inscripcion_id=a.inscripcion_id
							)
							JOIN pyp_evoluciones_procesos AS d 
							ON
							(
								d.inscripcion_id=c.inscripcion_id
							)
							LEFT JOIN pyp_cpn_registro_riesgo_evolucion as e
							ON
							(
								d.inscripcion_id=e.inscripcion_id 
								AND e.evolucion_id=d.evolucion_id
								AND e.riesgo_id=3
							)
							LEFT JOIN pyp_cpn_registro_riesgo_evolucion as e1
							ON
							(
								d.inscripcion_id=e1.inscripcion_id 
								AND e1.evolucion_id=d.evolucion_id
								AND e1.riesgo_id=12
							)
							LEFT JOIN pyp_cpn_conducta as f
							ON
							(
								d.inscripcion_id=f.inscripcion_id 
								AND f.evolucion_id=d.evolucion_id
							)
							LEFT JOIN pyp_cpn_codigos_evolucion_gestacion_valores as f1
							ON
							(
								d.inscripcion_id=f1.inscripcion_id 
								AND f1.evolucion_id=d.evolucion_id
								AND f1.codigo_evolucion_id=5
							)
							LEFT JOIN pyp_cpn_codigos_evolucion_gestacion_valores as f2
							ON
							(
								d.inscripcion_id=f2.inscripcion_id 
								AND f2.evolucion_id=d.evolucion_id
								AND f2.codigo_evolucion_id=6
							)
							LEFT JOIN profesionales_usuarios AS g 
							ON
							(
								a.usuario_id=g.usuario_id
							)
							LEFT JOIN profesionales AS h 
							ON
							(
								g.tipo_tercero_id=h.tipo_id_tercero AND g.tercero_id=h.tercero_id
							)
							JOIN pyp_cpn_seguimiento AS i
							ON
							(
								d.inscripcion_id=i.inscripcion_id
								AND d.evolucion_id=i.evolucion_id
							)
							LEFT JOIN pyp_cpn_motivos_seguimiento AS j
							ON
							(
								i.pyp_cpn_seguimiento_id=j.pyp_cpn_seguimiento_id
							)
							LEFT JOIN  pyp_cpn_motivos AS k
							ON
							(
								j.pyp_cpn_motivo_seguimiento_id=k.pyp_cpn_motivo_seguimiento_id
							)
							LEFT JOIN agenda_citas_asignadas AS l 
							ON
							(
								i.cita_asignada_id=l.agenda_cita_asignada_id
								AND i.cita_asignada_id NOT IN (
																							SELECT agenda_cita_asignada_id
																							FROM agenda_citas_asignadas_cancelacion
																							)
							)
							LEFT JOIN os_cruce_citas AS m 
							ON
							(
								l.agenda_cita_asignada_id=m.agenda_cita_asignada_id
							)
							LEFT JOIN os_maestro AS n 
							ON
							(
								m.numero_orden_id=n.numero_orden_id
							)
							LEFT JOIN agenda_citas AS o
							ON
							(
								l.agenda_cita_id=o.agenda_cita_id
							)
							LEFT JOIN agenda_turnos AS p 
							ON
							(
								o.agenda_turno_id=p.agenda_turno_id
							)
							LEFT JOIN hc_diagnosticos_ingreso as diagin
							ON
							(
								d.evolucion_id=diagin.evolucion_id
							)
							LEFT JOIN hc_diagnosticos_egreso as diageg
							ON
							(
								d.evolucion_id=diageg.evolucion_id
								
							)
							LEFT JOIN diagnosticos as diag
							ON
							(
								diag.diagnostico_id=diagin.tipo_diagnostico_id
								OR diag.diagnostico_id=diageg.tipo_diagnostico_id
							)
							LEFT JOIN pyp_diagnosticos as pdiag
							ON
							(
								diag.diagnostico_id=pdiag.diagnostico_id
								AND lower(pdiag.desc_cpn)='itu'
							)
							LEFT JOIN pyp_diagnosticos as pdiag1
							ON
							(
								diag.diagnostico_id=pdiag1.diagnostico_id
								AND lower(pdiag1.desc_cpn)='cervicovaginitis'
							)
						WHERE	p.fecha_turno is not null
						$fecha
						$filtro
						$order";
	
		$result = $dbconn->Execute($query);

		$this->conteo=$result->RecordCount();
		
		$query = $query." LIMIT ".$this->limit." OFFSET ".$this->offset."";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Error en el Modulo app_ParametrizacionPYP - ReporteSegumientoCitas - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
			$this->ban=1;
			return false;
		}
		else
		{
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$dbconn->CommitTrans();
		
		return $vars;
	}
	
	/*function ReporteSeguimientoMensual($_REQUEST,$op)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$this->ProcesarSqlConteo();
		
		$fecha_ini=$this->FechaStamp($_REQUEST['fecha_ini']);
		$fecha_fin=$this->FechaStamp($_REQUEST['fecha_fin']);
		
		$programa=$_SESSION['programa'];
		
		if($fecha_ini > $fecha_fin)
		{
			$this->frmError["MensajeError"]="LA FECHA INICIAL DEBE SER MENOR A LA FECHA FINAL";
			$this->ban=1;
			return false;
		}
		
		if($fecha_fin > date("Y-m-d"))
		{
			$this->frmError["MensajeError"]="LA FECHA INICIAL DEBE SER MENOR A LA FECHA ACTUAL";
			$this->ban=1;
			return false;
		}
		
		if($fecha_ini > date("Y-m-d"))
		{
			$this->frmError["MensajeError"]="LA FECHA FINAL DEBE SER MENOR A LA FECHA ACTUAL";
			$this->ban=1;
			return false;
		}

		if(!empty($fecha_ini) AND !empty($fecha_fin))
		{
			$fecha0=" AND cuen.fecha_registro>='$fecha_ini'
							AND cuen.fecha_registro<='$fecha_fin' ";
			
			$fecha1=" WHERE cuen.fecha_registro>='$fecha_ini'
							AND cuen.fecha_registro<='$fecha_fin' ";
							
			$fecha2=" AND pip.fecha_inscripcion>='$fecha_ini'
							AND pip.fecha_inscripcion<='$fecha_fin' ";
			
			$fecha3=" WHERE pip.fecha_inscripcion>='$fecha_ini'
							AND pip.fecha_inscripcion<='$fecha_fin' ";
			
			$fecha4=" AND pcs.fecha_registro>='$fecha_ini'
							AND pcs.fecha_registro<='$fecha_fin' ";
			
			$fecha5=" AND pcs.fecha_registro>='$fecha_ini'
							AND pcs.fecha_registro<='$fecha_fin' ";
			
			$fecha6=" AND pdv.fecha_registro>='$fecha_ini'
							AND pdv.fecha_registro<='$fecha_fin' ";
			
			$fecha7=" WHERE pdv.fecha_registro>='$fecha_ini'
							AND pdv.fecha_registro<='$fecha_fin' ";
		}
		else
		{//AND pip.programa_id=$programa
			if(!$op)
			{
				$fecha0="AND TO_CHAR(cuen.fecha_registro,'YYYY')=TO_CHAR(now(),'YYYY')";
				$fecha1="WHERE TO_CHAR(cuen.fecha_registro,'YYYY')=TO_CHAR(now(),'YYYY')";
				$fecha2="AND TO_CHAR(pip.fecha_inscripcion,'YYYY')=TO_CHAR(now(),'YYYY')";
				$fecha3="WHERE TO_CHAR(pip.fecha_inscripcion,'YYYY')=TO_CHAR(now(),'YYYY')";
				$fecha4="AND TO_CHAR(pcs.fecha_registro,'YYYY')=TO_CHAR(now(),'YYYY')";
				$fecha5="WHERE TO_CHAR(pcs.fecha_registro,'YYYY')=TO_CHAR(now(),'YYYY')";
				$fecha6="AND TO_CHAR(pdv.fecha_registro,'YYYY')=TO_CHAR(now(),'YYYY')";
				$fecha7="WHERE TO_CHAR(pdv.fecha_registro,'YYYY')=TO_CHAR(now(),'YYYY')";
			}
			else
			{
				$fecha0="AND TO_CHAR(cuen.fecha_registro,'YYYY-MM')=TO_CHAR(now(),'YYYY-MM')";
				$fecha1="WHERE TO_CHAR(cuen.fecha_registro,'YYYY-MM')=TO_CHAR(now(),'YYYY-MM')";
				$fecha2="AND TO_CHAR(pip.fecha_inscripcion,'YYYY-MM')=TO_CHAR(now(),'YYYY-MM')";
				$fecha3="WHERE TO_CHAR(pip.fecha_inscripcion,'YYYY-MM')=TO_CHAR(now(),'YYYY-MM')";
				$fecha4="AND TO_CHAR(pcs.fecha_registro,'YYYY-MM')=TO_CHAR(now(),'YYYY-MM')";
				$fecha5="WHERE TO_CHAR(pcs.fecha_registro,'YYYY-MM')=TO_CHAR(now(),'YYYY-MM')";
				$fecha6="AND TO_CHAR(pdv.fecha_registro,'YYYY-MM')=TO_CHAR(now(),'YYYY-MM')";
				$fecha7="WHERE TO_CHAR(pdv.fecha_registro,'YYYY-MM')=TO_CHAR(now(),'YYYY-MM')";
			}
		}
		
		$query = "	
		(
			SELECT COUNT(*),TO_NUMBER(TO_CHAR(cuen.fecha_registro,'MM'),99) AS mes,
			'GESTACION NUEVAS SEGUN TIPO DE VINCULACION' as tipo1,'Cotizantes' as tipo2
			FROM pyp_inscripciones_pacientes as pip
			JOIN pyp_evoluciones_procesos as pepc
			ON
			(
				pip.inscripcion_id=pepc.inscripcion_id
				
			)
			JOIN hc_evoluciones as evo
			ON
			(
				pepc.evolucion_id=evo.evolucion_id
			)
			JOIN ingresos as ing
			ON
			(
				evo.ingreso=ing.ingreso
			)
			JOIN cuentas as cuen
			ON
			(
				ing.ingreso=cuen.ingreso
			)
			JOIN tipos_afiliado as ta
			ON
			(
				ta.tipo_afiliado_id=cuen.tipo_afiliado_id
			)
			WHERE 	ta.tipo_afiliado_id=1
			$fecha0
			AND TO_CHAR(cuen.fecha_registro,'YYYY')=TO_CHAR(now(),'YYYY')
			GROUP 	BY TO_NUMBER(TO_CHAR(cuen.fecha_registro,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(cuen.fecha_registro,'MM'),99)
		)
		UNION
		(
			SELECT 	COUNT(*),TO_NUMBER(TO_CHAR(cuen.fecha_registro,'MM'),99),
			'GESTACION NUEVAS SEGUN TIPO DE VINCULACION' as tipo1,'Conyugues Cotizantes' as tipo2
			FROM pyp_inscripciones_pacientes as pip
			JOIN pyp_evoluciones_procesos as pepc
			ON
			(
				pip.inscripcion_id=pepc.inscripcion_id
			)
			JOIN hc_evoluciones as evo
			ON
			(
				pepc.evolucion_id=evo.evolucion_id
			)
			JOIN ingresos as ing
			ON
			(
				evo.ingreso=ing.ingreso
			)
			JOIN cuentas as cuen
			ON
			(
				ing.ingreso=cuen.ingreso
			)
			JOIN tipos_afiliado as ta
			ON
			(
				ta.tipo_afiliado_id=cuen.tipo_afiliado_id
			)
			WHERE 	ta.tipo_afiliado_id=2
			$fecha0
			GROUP 	BY TO_NUMBER(TO_CHAR(cuen.fecha_registro,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(cuen.fecha_registro,'MM'),99)
		)
		UNION
		(
			SELECT 	COUNT(*),TO_NUMBER(TO_CHAR(cuen.fecha_registro,'MM'),99) AS mes,
			'GESTACION NUEVAS SEGUN TIPO DE VINCULACION' as tipo1,'Beneficiarios' as tipo2
			FROM pyp_inscripciones_pacientes as pip
			JOIN pyp_evoluciones_procesos as pepc
			ON
			(
				pip.inscripcion_id=pepc.inscripcion_id
			)
			JOIN hc_evoluciones as evo
			ON
			(
				pepc.evolucion_id=evo.evolucion_id
			)
			JOIN ingresos as ing
			ON
			(
				evo.ingreso=ing.ingreso
			)
			JOIN cuentas as cuen
			ON
			(
				ing.ingreso=cuen.ingreso
			)
			JOIN tipos_afiliado as ta
			ON
			(
				ta.tipo_afiliado_id=cuen.tipo_afiliado_id
			)
			WHERE 	ta.tipo_afiliado_id=3
			$fecha0
			GROUP 	BY TO_NUMBER(TO_CHAR(cuen.fecha_registro,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(cuen.fecha_registro,'MM'),99)
		)
		UNION
		(
			SELECT 	COUNT(*),TO_NUMBER(TO_CHAR(cuen.fecha_registro,'MM'),99) AS mes,
			'GESTACION NUEVAS SEGUN TIPO DE VINCULACION' as tipo1,'Adicionales' as tipo2
			FROM pyp_inscripciones_pacientes as pip
			JOIN pyp_evoluciones_procesos as pepc
			ON
			(
				pip.inscripcion_id=pepc.inscripcion_id
			)
			JOIN hc_evoluciones as evo
			ON
			(
				pepc.evolucion_id=evo.evolucion_id
			)
			JOIN ingresos as ing
			ON
			(
				evo.ingreso=ing.ingreso
			)
			JOIN cuentas as cuen
			ON
			(
				ing.ingreso=cuen.ingreso
			)
			JOIN tipos_afiliado as ta
			ON
			(
				ta.tipo_afiliado_id=cuen.tipo_afiliado_id
			)
			WHERE 	ta.tipo_afiliado_id=4
			$fecha0
			GROUP 	BY TO_NUMBER(TO_CHAR(cuen.fecha_registro,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(cuen.fecha_registro,'MM'),99)
		)
		UNION
		(
			SELECT 	COUNT(*),TO_NUMBER(TO_CHAR(cuen.fecha_registro,'MM'),99) AS mes,
			'GESTACION NUEVAS SEGUN TIPO DE VINCULACION' as tipo1,'Asegurado' as tipo2
			FROM pyp_inscripciones_pacientes as pip
			JOIN pyp_evoluciones_procesos as pepc
			ON
			(
				pip.inscripcion_id=pepc.inscripcion_id
			)
			JOIN hc_evoluciones as evo
			ON
			(
				pepc.evolucion_id=evo.evolucion_id
			)
			JOIN ingresos as ing
			ON
			(
				evo.ingreso=ing.ingreso
			)
			JOIN cuentas as cuen
			ON
			(
				ing.ingreso=cuen.ingreso
			)
			JOIN tipos_afiliado as ta
			ON
			(
				ta.tipo_afiliado_id=cuen.tipo_afiliado_id
			)
			WHERE 	ta.tipo_afiliado_id=5
			$fecha0
			GROUP 	BY TO_NUMBER(TO_CHAR(cuen.fecha_registro,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(cuen.fecha_registro,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(*),TO_NUMBER(TO_CHAR(cuen.fecha_registro,'MM'),99) as mes,
			'GESTACION NUEVAS SEGUN TIPO DE VINCULACION' as tipo1,'Total' as tipo2
			FROM pyp_inscripciones_pacientes as pip
			JOIN pyp_evoluciones_procesos as pepc
			ON
			(
				pip.inscripcion_id=pepc.inscripcion_id
			)
			JOIN hc_evoluciones as evo
			ON
			(
				pepc.evolucion_id=evo.evolucion_id
			)
			JOIN ingresos as ing
			ON
			(
				evo.ingreso=ing.ingreso
			)
			JOIN cuentas as cuen
			ON
			(
				ing.ingreso=cuen.ingreso
			)
			JOIN tipos_afiliado as ta
			ON
			(
				ta.tipo_afiliado_id=cuen.tipo_afiliado_id
			)
			$fecha1
			GROUP BY TO_NUMBER(TO_CHAR(cuen.fecha_registro,'MM'),99)
			ORDER BY TO_NUMBER(TO_CHAR(cuen.fecha_registro,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'EDAD' as tipo1,'< 15' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN 	pacientes AS p
						ON
						(
							pip.tipo_id_paciente=p.tipo_id_paciente  AND pip.paciente_id=p.paciente_id
						)
						WHERE (pip.fecha_inscripcion - p.fecha_nacimiento)/365 < 15
						$fecha2
						GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'EDAD' as tipo1,'15 - 19' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN 	pacientes AS p
						ON
						(
							pip.tipo_id_paciente=p.tipo_id_paciente  AND pip.paciente_id=p.paciente_id
						)
						WHERE (pip.fecha_inscripcion - p.fecha_nacimiento)/365 >= 15 and (pip.fecha_inscripcion - p.fecha_nacimiento)/365<=19
						$fecha2
						GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'EDAD' as tipo1,'20 - 34' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN 	pacientes AS p
						ON
						(
							pip.tipo_id_paciente=p.tipo_id_paciente  AND pip.paciente_id=p.paciente_id
						)
						WHERE (pip.fecha_inscripcion - p.fecha_nacimiento)/365>=20 and (pip.fecha_inscripcion - p.fecha_nacimiento)/365<=34
						$fecha2
						GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'EDAD' as tipo1,'35 ó mas' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN 	pacientes AS p
						ON
						(
							pip.tipo_id_paciente=p.tipo_id_paciente  AND pip.paciente_id=p.paciente_id
						)
						WHERE (pip.fecha_inscripcion - p.fecha_nacimiento)/365>=35
						$fecha2
						GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'NUMERO DE EMBARAZOS' as tipo1,'1' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN 	pyp_inscripcion_cpn AS pic
						ON
						(
							pip.inscripcion_id=pic.inscripcion_id
						)
						WHERE		numero_embarazos_previos+1=1
						$fecha2
						GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'NUMERO DE EMBARAZOS' as tipo1,'2 - 4' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN 	pyp_inscripcion_cpn AS pic
						ON
						(
							pip.inscripcion_id=pic.inscripcion_id
						)
						WHERE		numero_embarazos_previos+1>=2 AND numero_embarazos_previos+1<=4
						$fecha2
						GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'NUMERO DE EMBARAZOS' as tipo1,'5 ó mas' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN 	pyp_inscripcion_cpn AS pic
						ON
						(
							pip.inscripcion_id=pic.inscripcion_id
						)
						WHERE		numero_embarazos_previos+1>=5
						$fecha2
						GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'TRIMESTRE INICIACION CPN' as tipo1,'1' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN 	pyp_inscripcion_cpn AS pic
						ON
						(
							pip.inscripcion_id=pic.inscripcion_id
						)
						WHERE		(pip.fecha_inscripcion-pic.fecha_ultimo_periodo)/7>=0
						AND 		(pip.fecha_inscripcion-pic.fecha_ultimo_periodo)/7<=27
						$fecha2
						GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'TRIMESTRE INICIACION CPN' as tipo1,'2' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN 	pyp_inscripcion_cpn AS pic
						ON
						(
							pip.inscripcion_id=pic.inscripcion_id
						)
						WHERE		(pip.fecha_inscripcion-pic.fecha_ultimo_periodo)/7>=28
						AND 		(pip.fecha_inscripcion-pic.fecha_ultimo_periodo)/7<=32
						$fecha2
						GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'TRIMESTRE INICIACION CPN' as tipo1,'3' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN 	pyp_inscripcion_cpn AS pic
						ON
						(
							pip.inscripcion_id=pic.inscripcion_id
						)
						WHERE		(pip.fecha_inscripcion-pic.fecha_ultimo_periodo)/7>=33
						AND 		(pip.fecha_inscripcion-pic.fecha_ultimo_periodo)/7<=40
						$fecha2
						GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'CLASIFICACION RIESGO' as tipo1,'Bajo' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN 	pyp_evoluciones_procesos AS pepc
						ON
						(
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN 	pyp_cpn_conducta AS pdv
						ON
						(
							pepc.inscripcion_id=pdv.inscripcion_id
							AND pepc.evolucion_id=pdv.evolucion_id
						)
						WHERE		pdv.clasificacion_riesgo=1
						$fecha2
						GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'CLASIFICACION RIESGO' as tipo1,'Alto' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN 	pyp_evoluciones_procesos AS pepc
						ON
						(
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN 	pyp_cpn_conducta AS pdv
						ON
						(
							pepc.inscripcion_id=pdv.inscripcion_id
							AND pepc.evolucion_id=pdv.evolucion_id
						)
						WHERE		pdv.clasificacion_riesgo=2
						$fecha2
						GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'CLASIFICACION RIESGO' as tipo1,'Sin Riesgo' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN 	pyp_evoluciones_procesos AS pepc
						ON
						(
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN 	pyp_cpn_conducta AS pdv
						ON
						(
							pepc.inscripcion_id=pdv.inscripcion_id
							AND pepc.evolucion_id=pdv.evolucion_id
						)
						WHERE		pdv.riesgo_biologico=0 AND riesgo_psicosocial=0
						$fecha2
						GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'PATOLOGIA ASOCIADA' as tipo1,'ITU' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN 	pyp_evoluciones_procesos AS pepc
						ON
						(
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN hc_diagnosticos_ingreso as diagin
						ON
						(
							pepc.evolucion_id=diagin.evolucion_id
						)
			JOIN hc_diagnosticos_egreso as diageg
						ON
						(
							pepc.evolucion_id=diageg.evolucion_id
							
						)
			JOIN diagnosticos as diag
						ON
						(
							diag.diagnostico_id=diagin.tipo_diagnostico_id
							OR diag.diagnostico_id=diageg.tipo_diagnostico_id
						)
			JOIN pyp_diagnosticos as pdiag
						ON
						(
							diag.diagnostico_id=pdiag.diagnostico_id
						)
			WHERE		lower(pdiag.desc_cpn)='itu'
			$fecha2
			GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'PATOLOGIA ASOCIADA' as tipo1,'Cervicovaginitis' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN 	pyp_evoluciones_procesos AS pepc
						ON
						(
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN hc_diagnosticos_ingreso as diagin
						ON
						(
							pepc.evolucion_id=diagin.evolucion_id
						)
			JOIN hc_diagnosticos_egreso as diageg
						ON
						(
							pepc.evolucion_id=diageg.evolucion_id
							
						)
			JOIN diagnosticos as diag
						ON
						(
							diag.diagnostico_id=diagin.tipo_diagnostico_id
							OR diag.diagnostico_id=diageg.tipo_diagnostico_id
						)
			JOIN pyp_diagnosticos as pdiag
						ON
						(
							diag.diagnostico_id=pdiag.diagnostico_id
						)
			WHERE lower(pdiag.desc_cpn)='cervicovaginitis'
			$fecha2
			GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'PATOLOGIA ASOCIADA' as tipo1,'HTA en Embarazo' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN 	pyp_evoluciones_procesos AS pepc
						ON
						(
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN pyp_cpn_registro_riesgo_evolucion as prev
						ON
						(
							pepc.inscripcion_id=prev.inscripcion_id 
							AND prev.evolucion_id=pepc.evolucion_id
							AND prev.riesgo_id=3
						)
			$fecha3
			GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'PATOLOGIA ASOCIADA' as tipo1,'Diabetes Gestacional' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN 	pyp_evoluciones_procesos AS pepc
						ON
						(
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN pyp_cpn_registro_riesgo_evolucion as prev
						ON
						(
							pepc.inscripcion_id=prev.inscripcion_id 
							AND prev.evolucion_id=pepc.evolucion_id
							AND prev.riesgo_id=12
						)
			$fecha3
			GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'TIPO CITA' as tipo1,'Citas 1 Vez' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip
			JOIN 	pyp_evoluciones_procesos AS pepc
						ON
						(
							pip.inscripcion_id=pepc.inscripcion_id
						)
			WHERE 	pepc.sw_estado=2
			$fecha2
			GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'TIPO CITA' as tipo1,'Citas Control' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip
			JOIN 	pyp_evoluciones_procesos AS pepc
						ON
						(
							pip.inscripcion_id=pepc.inscripcion_id
						)
			WHERE 	pepc.sw_estado=3
			$fecha2
			GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'TIPO CITA' as tipo1,'Postparto' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip
			JOIN 	pyp_evoluciones_procesos AS pepc
						ON
						(
							pip.inscripcion_id=pepc.inscripcion_id
						)
			WHERE 	pepc.sw_estado=4
			$fecha2
			GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'REMISIONES' as tipo1,'Especialista' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN 	pyp_evoluciones_procesos AS pepc
						ON
						(
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN 	pyp_cpn_codigos_evolucion_gestacion_valores AS pdv
						ON
						(
							pepc.inscripcion_id=pdv.inscripcion_id
							AND pepc.evolucion_id=pdv.evolucion_id
							AND pdv.codigo_evolucion_id=6
						)
			WHERE		pdv.valor=1
			$fecha2
			GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'REMISIONES' as tipo1,'Salud Oral' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN 	pyp_evoluciones_procesos AS pepc
						ON
						(
							pip.inscripcion_id=pepc.inscripcion_id
						)
		JOIN 	pyp_cpn_codigos_evolucion_gestacion_valores AS pdv
						ON
						(
							pepc.inscripcion_id=pdv.inscripcion_id
							AND pepc.evolucion_id=pdv.evolucion_id
							AND pdv.codigo_evolucion_id=5
						)
			WHERE		pdv.valor=1
			$fecha2
			GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'SEGUIMIENTO' as tipo1,'Inasistentes CPN' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN pyp_evoluciones_procesos as pepc
						ON
						( 
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN agenda_citas_asignadas AS l 
						ON
						(
							pip.tipo_id_paciente=l.tipo_id_paciente
							AND pip.paciente_id=l.paciente_id
							AND l.agenda_cita_asignada_id NOT IN (
																						SELECT agenda_cita_asignada_id
																						FROM agenda_citas_asignadas_cancelacion
																						)
						)
			JOIN os_cruce_citas AS m 
						ON
						(
							l.agenda_cita_asignada_id=m.agenda_cita_asignada_id
						)
			JOIN os_maestro AS n 
						ON
						(
							m.numero_orden_id=n.numero_orden_id
						)
			JOIN agenda_citas AS o
						ON
						(
							l.agenda_cita_id=o.agenda_cita_id
						)
			JOIN agenda_turnos AS p 
						ON
						(
							o.agenda_turno_id=p.agenda_turno_id
						)
			WHERE	pepc.fecha_ideal_proxima_cita < now() AND p.fecha_turno < now()
						AND n.sw_estado!='3'
						$fecha2
			GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pcs.fecha_registro),TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99),
			'SEGUIMIENTO' as tipo1,'Inasistentes Contactadas' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN pyp_evoluciones_procesos as pepc
						ON
						( 
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN pyp_cpn_seguimiento as pcs
			ON
			(
				pepc.inscripcion_id=pcs.inscripcion_id
				AND pepc.evolucion_id=pcs.evolucion_id
			)
			JOIN agenda_citas_asignadas AS l 
						ON
						(
							pcs.cita_asignada_id=l.agenda_cita_asignada_id
							AND l.agenda_cita_asignada_id NOT IN (
																						SELECT agenda_cita_asignada_id
																						FROM agenda_citas_asignadas_cancelacion
																					)
						)
			JOIN os_cruce_citas AS m 
						ON
						(
							l.agenda_cita_asignada_id=m.agenda_cita_asignada_id
						)
			JOIN os_maestro AS n 
						ON
						(
							m.numero_orden_id=n.numero_orden_id
						)
			JOIN agenda_citas AS o
						ON
						(
							l.agenda_cita_id=o.agenda_cita_id
						)
			JOIN agenda_turnos AS p 
						ON
						(
							o.agenda_turno_id=p.agenda_turno_id
						)
			WHERE	pepc.fecha_ideal_proxima_cita < now() AND p.fecha_turno< now()
						AND n.sw_estado!='3'
						$fecha4
			GROUP 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pcs.fecha_registro),TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99),
			'SEGUIMIENTO' as tipo1,'Seguimiento Alto Riesgo' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN pyp_evoluciones_procesos as pepc
						ON
						( 
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN pyp_cpn_seguimiento as pcs
						ON
						(
							pepc.inscripcion_id=pcs.inscripcion_id
							AND pepc.evolucion_id=pcs.evolucion_id
						)
			JOIN pyp_cpn_motivos_seguimiento as pcms
						ON
						(
							pcs.pyp_cpn_seguimiento_id=pcms.pyp_cpn_seguimiento_id
						)
			WHERE pcms.pyp_cpn_motivo_seguimiento_id=5
			$fecha4
			GROUP 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pcs.fecha_registro),TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99),
			'SEGUIMIENTO' as tipo1,'Contacto Telefonico' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN pyp_evoluciones_procesos as pepc
						ON
						( 
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN pyp_cpn_seguimiento as pcs
						ON
						(
							pepc.inscripcion_id=pcs.inscripcion_id
							AND pepc.evolucion_id=pcs.evolucion_id
						)
			JOIN pyp_cpn_motivos_seguimiento as pcms
						ON
						(
							pcs.pyp_cpn_seguimiento_id=pcms.pyp_cpn_seguimiento_id
						)
			WHERE pcs.contacto_telefonico is not null
			$fecha4
			GROUP 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pcs.fecha_registro),TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99),
			'SEGUIMIENTO' as tipo1,'Direccionamiento a Otra IPS' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN pyp_evoluciones_procesos as pepc
						ON
						( 
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN pyp_cpn_seguimiento as pcs
						ON
						(
							pepc.inscripcion_id=pcs.inscripcion_id
							AND pepc.evolucion_id=pcs.evolucion_id
						)
			WHERE pcs.ips_direccionada is not null
			$fecha4
			GROUP 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pcs.fecha_registro),TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99),
			'SEGUIMIENTO' as tipo1,'Captacion Efectiva' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN pyp_evoluciones_procesos as pepc
						ON
						( 
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN pyp_cpn_seguimiento as pcs
						ON
						(
							pepc.inscripcion_id=pcs.inscripcion_id
							AND pepc.evolucion_id=pcs.evolucion_id
						)
			JOIN agenda_citas_asignadas AS l 
						ON
						(
							
							pcs.cita_asignada_id=l.agenda_cita_asignada_id
							AND l.agenda_cita_asignada_id NOT IN (
																						SELECT agenda_cita_asignada_id
																						FROM agenda_citas_asignadas_cancelacion
																						)
						)
			JOIN os_cruce_citas AS m 
						ON
						(
							l.agenda_cita_asignada_id=m.agenda_cita_asignada_id
						)
			JOIN os_maestro AS n 
						ON
						(
							m.numero_orden_id=n.numero_orden_id
						)
			JOIN agenda_citas AS o
						ON
						(
							l.agenda_cita_id=o.agenda_cita_id
						)
			JOIN agenda_turnos AS p 
						ON
						(
							o.agenda_turno_id=p.agenda_turno_id
						)
			WHERE 	n.sw_estado='3'
			$fecha4
			GROUP 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pcs.fecha_registro),TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99),
			'CONTROL POSTPARTO O ABORTO' as tipo1,'Citadas' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN pyp_evoluciones_procesos as pepc
						ON
						( 
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN pyp_cpn_seguimiento as pcs
						ON
						(
							pepc.inscripcion_id=pcs.inscripcion_id
							AND pepc.evolucion_id=pcs.evolucion_id
						)
			JOIN pyp_cpn_motivos_seguimiento as pcms
						ON
						(
							pcs.pyp_cpn_seguimiento_id=pcms.pyp_cpn_seguimiento_id
						)
			JOIN agenda_citas_asignadas AS l 
						ON
						(
							
							pcs.cita_asignada_id=l.agenda_cita_asignada_id
							AND l.agenda_cita_asignada_id NOT IN (
																						SELECT agenda_cita_asignada_id
																						FROM agenda_citas_asignadas_cancelacion
																						)
						)
			JOIN os_cruce_citas AS m 
						ON
						(
							l.agenda_cita_asignada_id=m.agenda_cita_asignada_id
						)
			JOIN os_maestro AS n 
						ON
						(
							m.numero_orden_id=n.numero_orden_id
						)
			JOIN agenda_citas AS o
						ON
						(
							l.agenda_cita_id=o.agenda_cita_id
						)
			JOIN agenda_turnos AS p 
						ON
						(
							o.agenda_turno_id=p.agenda_turno_id
						)
			WHERE 	pcs.cita_asignada_id is not null
							AND pcms.pyp_cpn_motivo_seguimiento_id=14
							AND n.sw_estado!='3'
							$fecha4
			GROUP 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pcs.fecha_registro),TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99),
			'CONTROL POSTPARTO O ABORTO' as tipo1,'Realizadas' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN pyp_evoluciones_procesos as pepc
						ON
						( 
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN pyp_cpn_seguimiento as pcs
						ON
						(
							pepc.inscripcion_id=pcs.inscripcion_id
							AND pepc.evolucion_id=pcs.evolucion_id
						)
			JOIN pyp_cpn_motivos_seguimiento as pcms
						ON
						(
							pcs.pyp_cpn_seguimiento_id=pcms.pyp_cpn_seguimiento_id
						)
			JOIN agenda_citas_asignadas AS l 
						ON
						(
							
							pcs.cita_asignada_id=l.agenda_cita_asignada_id
							AND l.agenda_cita_asignada_id NOT IN (
																						SELECT agenda_cita_asignada_id
																						FROM agenda_citas_asignadas_cancelacion
																						)
						)
			JOIN os_cruce_citas AS m 
						ON
						(
							l.agenda_cita_asignada_id=m.agenda_cita_asignada_id
						)
			JOIN os_maestro AS n 
						ON
						(
							m.numero_orden_id=n.numero_orden_id
						)
			JOIN agenda_citas AS o
						ON
						(
							l.agenda_cita_id=o.agenda_cita_id
						)
			JOIN agenda_turnos AS p 
						ON
						(
							o.agenda_turno_id=p.agenda_turno_id
						)
			WHERE 	pcs.cita_asignada_id is not null
							AND pcms.pyp_cpn_motivo_seguimiento_id=14
							AND n.sw_estado='3'
							$fecha4
			GROUP 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pcs.fecha_registro),TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99),
			'CONTROL POSTPARTO O ABORTO' as tipo1,'Inasistentes Contactadas' as tipo2
			FROM 	pyp_inscripciones_pacientes AS pip 
			JOIN pyp_evoluciones_procesos as pepc
						ON
						( 
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN pyp_cpn_seguimiento as pcs
						ON
						(
							pepc.inscripcion_id=pcs.inscripcion_id
							AND pepc.evolucion_id=pcs.evolucion_id
						)
			JOIN pyp_cpn_motivos_seguimiento as pcms
						ON
						(
							pcs.pyp_cpn_seguimiento_id=pcms.pyp_cpn_seguimiento_id
						)
			WHERE 	pcms.pyp_cpn_motivo_seguimiento_id=14
			$fecha4
			GROUP 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pdv.fecha_registro),TO_NUMBER(TO_CHAR(pdv.fecha_registro,'MM'),99),
			'CONTROL USUARIAS ATENDIDAS' as tipo1,' Primera Vez Medico' as tipo2
			FROM pyp_inscripciones_pacientes AS pip 
			JOIN pyp_evoluciones_procesos as pepc
						ON
						( 
							pip.inscripcion_id=pepc.inscripcion_id
							AND pepc.sw_estado='2'
						)
			JOIN 	pyp_cpn_codigos_evolucion_gestacion_valores AS pdv
						ON
						(
							pepc.inscripcion_id=pdv.inscripcion_id
							AND pepc.evolucion_id=pdv.evolucion_id
						)
			JOIN profesionales_usuarios as pu
						ON
						(
							pdv.usuario_id=pu.usuario_id
						)
			JOIN profesionales as pro
						ON
						(
							pu.tipo_tercero_id=pro.tipo_id_tercero 
							AND pu.tercero_id=pro.tercero_id
						)
			JOIN tipos_profesionales as tpro
						ON
						(
							pro.tipo_profesional=tpro.tipo_profesional
						)
			WHERE 	tpro.tipo_profesional=1 OR tpro.tipo_profesional=2
			$fecha6
			GROUP 	BY TO_NUMBER(TO_CHAR(pdv.fecha_registro,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pdv.fecha_registro,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pdv.fecha_registro),TO_NUMBER(TO_CHAR(pdv.fecha_registro,'MM'),99),
			'CONTROL USUARIAS ATENDIDAS' as tipo1,' Primera Vez Enfermera' as tipo2
			FROM pyp_inscripciones_pacientes AS pip 
			JOIN pyp_evoluciones_procesos as pepc
						ON
						( 
							pip.inscripcion_id=pepc.inscripcion_id
							AND pepc.sw_estado='2'
						)
			JOIN 	pyp_cpn_codigos_evolucion_gestacion_valores AS pdv
						ON
						(
							pepc.inscripcion_id=pdv.inscripcion_id
							AND pepc.evolucion_id=pdv.evolucion_id
						)
			JOIN profesionales_usuarios as pu
						ON
						(
							pdv.usuario_id=pu.usuario_id
						)
			JOIN profesionales as pro
						ON
						(
							pu.tipo_tercero_id=pro.tipo_id_tercero 
							AND pu.tercero_id=pro.tercero_id
						)
			JOIN tipos_profesionales as tpro
						ON
						(
							pro.tipo_profesional=tpro.tipo_profesional
						)
			WHERE 	tpro.tipo_profesional=3
			$fecha6
			GROUP 	BY TO_NUMBER(TO_CHAR(pdv.fecha_registro,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pdv.fecha_registro,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pdv.fecha_registro),TO_NUMBER(TO_CHAR(pdv.fecha_registro,'MM'),99),
			'CONTROL USUARIAS ATENDIDAS' as tipo1,' Control por Medico' as tipo2
			FROM pyp_inscripciones_pacientes AS pip 
			JOIN pyp_evoluciones_procesos as pepc
						ON
						( 
							pip.inscripcion_id=pepc.inscripcion_id
							AND pepc.sw_estado='3'
						)
			JOIN 	pyp_cpn_codigos_evolucion_gestacion_valores AS pdv
						ON
						(
							pepc.inscripcion_id=pdv.inscripcion_id
							AND pepc.evolucion_id=pdv.evolucion_id
						)
			JOIN profesionales_usuarios as pu
						ON
						(
							pdv.usuario_id=pu.usuario_id
						)
			JOIN profesionales as pro
						ON
						(
							pu.tipo_tercero_id=pro.tipo_id_tercero 
							AND pu.tercero_id=pro.tercero_id
						)
			JOIN tipos_profesionales as tpro
						ON
						(
							pro.tipo_profesional=tpro.tipo_profesional
						)
			WHERE 	tpro.tipo_profesional=1 OR tpro.tipo_profesional=2
			$fecha6
			GROUP 	BY TO_NUMBER(TO_CHAR(pdv.fecha_registro,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pdv.fecha_registro,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pdv.fecha_registro),TO_NUMBER(TO_CHAR(pdv.fecha_registro,'MM'),99),
			'CONTROL USUARIAS ATENDIDAS' as tipo1,' Control por Enfermera' as tipo2
			FROM pyp_inscripciones_pacientes AS pip 
			JOIN pyp_evoluciones_procesos as pepc
						ON
						( 
							pip.inscripcion_id=pepc.inscripcion_id
							AND pepc.sw_estado='3'
						)
			JOIN 	pyp_cpn_codigos_evolucion_gestacion_valores AS pdv
						ON
						(
							pepc.inscripcion_id=pdv.inscripcion_id
							AND pepc.evolucion_id=pdv.evolucion_id
						)
			JOIN profesionales_usuarios as pu
						ON
						(
							pdv.usuario_id=pu.usuario_id
						)
			JOIN profesionales as pro
						ON
						(
							pu.tipo_tercero_id=pro.tipo_id_tercero 
							AND pu.tercero_id=pro.tercero_id
						)
			JOIN tipos_profesionales as tpro
						ON
						(
							pro.tipo_profesional=tpro.tipo_profesional
						)
			WHERE 	tpro.tipo_profesional=3
			$fecha6
			GROUP 	BY TO_NUMBER(TO_CHAR(pdv.fecha_registro,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pdv.fecha_registro,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pdv.fecha_registro),TO_NUMBER(TO_CHAR(pdv.fecha_registro,'MM'),99),
			'CONTROL USUARIAS ATENDIDAS' as tipo1,'Alto Riesgo por Especialista' as tipo2
			FROM pyp_inscripciones_pacientes AS pip 
			JOIN pyp_evoluciones_procesos as pepc
						ON
						(
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN 	pyp_cpn_conducta AS pdv
						ON
						(
							pepc.inscripcion_id=pdv.inscripcion_id
							AND pepc.evolucion_id=pdv.evolucion_id
							AND pdv.clasificacion_riesgo=2
						)
			JOIN 	pyp_cpn_codigos_evolucion_gestacion_valores AS pdv1
						ON
						(
							pepc.inscripcion_id=pdv1.inscripcion_id
							AND pepc.evolucion_id=pdv1.evolucion_id
							AND (pdv1.codigo_evolucion_id=5 OR pdv1.codigo_evolucion_id=6)
							AND pdv1.valor=1
						)
			$fecha7
			GROUP 	BY TO_NUMBER(TO_CHAR(pdv.fecha_registro,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pdv.fecha_registro,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'CONTROL USUARIAS ATENDIDAS' as tipo1,'Total Usuarias Atendidas' as tipo2
			FROM pyp_inscripciones_pacientes AS pip 
			JOIN pyp_evoluciones_procesos as pepc
						ON
						(
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN 	pyp_cpn_codigos_evolucion_gestacion_valores AS pdv
						ON
						(
							pepc.inscripcion_id=pdv.inscripcion_id
							AND pepc.evolucion_id=pdv.evolucion_id
						)
			$fecha3
			GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		UNION
		(
			SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
			'CONTROL USUARIAS ATENDIDAS' as tipo1,'Total Usuarias Atendidas' as tipo2
			FROM pyp_inscripciones_pacientes AS pip 
			JOIN pyp_evoluciones_procesos as pepc
						ON
						(
							pip.inscripcion_id=pepc.inscripcion_id
						)
			JOIN 	pyp_cpn_codigos_evolucion_gestacion_valores AS pdv
						ON
						(
							pepc.inscripcion_id=pdv.inscripcion_id
							AND pepc.evolucion_id=pdv.evolucion_id
						)
			$fecha3
			GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
			ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
		)
		";
	
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error en el Modulo app_ParametrizacionPYP - ReporteSegumientoCitas - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
			$this->ban=1;
			return false;
		}
		else
		{
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars[$result->fields[2]][$result->fields[3]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$dbconn->CommitTrans();
		return $vars;
	}*/
	
	
	function ReporteSeguimientoMensual($_REQUEST,$op)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$this->ProcesarSqlConteo();
		
		$fecha_ini=$this->FechaStamp($_REQUEST['fecha_ini']);
		$fecha_fin=$this->FechaStamp($_REQUEST['fecha_fin']);
		
		$programa=$_SESSION['programa'];

		if(!empty($fecha_ini) AND !empty($fecha_fin))
		{
			if($fecha_ini > $fecha_fin)
			{
				$this->frmError["MensajeError"]="LA FECHA INICIAL DEBE SER MENOR A LA FECHA FINAL";
				$this->ban=1;
				return false;
			}
			
			if($fecha_fin > date("Y-m-d"))
			{
				$this->frmError["MensajeError"]="LA FECHA INICIAL DEBE SER MENOR A LA FECHA ACTUAL";
				$this->ban=1;
				return false;
			}
			
			if($fecha_ini > date("Y-m-d"))
			{
				$this->frmError["MensajeError"]="LA FECHA FINAL DEBE SER MENOR A LA FECHA ACTUAL";
				$this->ban=1;
				return false;
			}
			
			$fecha0=" AND pip.fecha_inscripcion>='$fecha_ini'
							AND pip.fecha_inscripcion<='$fecha_fin'
							AND pip.programa_id=1
							";
			
			$fecha1=" WHERE pip.fecha_inscripcion>='$fecha_ini'
							AND pip.fecha_inscripcion<='$fecha_fin'
							AND pip.programa_id=1 
							";
							
			$fecha2=" AND pcs.fecha_registro>='$fecha_ini'
							AND pcs.fecha_registro<='$fecha_fin' 
							AND pip.programa_id=1
							";
		}
		else
		{
			if(!$op)
			{
				$fecha0="AND TO_CHAR(pip.fecha_inscripcion,'YYYY')=TO_CHAR(now(),'YYYY') AND pip.programa_id=".$_SESSION['programa'];
				$fecha1="WHERE TO_CHAR(pip.fecha_inscripcion,'YYYY')=TO_CHAR(now(),'YYYY') AND pip.programa_id=".$_SESSION['programa'];
				$fecha2="AND TO_CHAR(pcs.fecha_registro,'YYYY')=TO_CHAR(now(),'YYYY') AND pip.programa_id=".$_SESSION['programa'];
			}
			else
			{
				$fecha0="AND TO_CHAR(pip.fecha_inscripcion,'YYYY-MM')=TO_CHAR(now(),'YYYY-MM') AND pip.programa_id=".$_SESSION['programa'];
				$fecha1="WHERE TO_CHAR(pip.fecha_inscripcion,'YYYY-MM')=TO_CHAR(now(),'YYYY-MM') AND pip.programa_id=".$_SESSION['programa'];
				$fecha2="AND TO_CHAR(pcs.fecha_registro,'YYYY-MM')=TO_CHAR(now(),'YYYY-MM') AND pip.programa_id=".$_SESSION['programa'];
			}
		}
		
		$query = "
				(
					SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) AS mes,
					'GESTACION NUEVAS SEGUN TIPO DE VINCULACION' as tipo1,'Cotizantes' as tipo2
					FROM pyp_inscripciones_pacientes as pip
					JOIN pyp_evoluciones_procesos as pepc
					ON
					(
						pip.inscripcion_id=pepc.inscripcion_id
						AND pepc.evolucion_id = (SELECT MAX(evolucion_id) FROM pyp_evoluciones_procesos WHERE inscripcion_id=pip.inscripcion_id)
					)
					JOIN hc_evoluciones as evo
					ON
					(
						pepc.evolucion_id=evo.evolucion_id
					)
					JOIN ingresos as ing
					ON
					(
						evo.ingreso=ing.ingreso
					)
					JOIN cuentas as cuen
					ON
					(
						ing.ingreso=cuen.ingreso
					)
					JOIN tipos_afiliado as ta
					ON
					(
						ta.tipo_afiliado_id=cuen.tipo_afiliado_id
					)
					WHERE 	ta.tipo_afiliado_id=1
					$fecha0
					GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
					ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
				)
				UNION
				(
					SELECT 	COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99),
					'GESTACION NUEVAS SEGUN TIPO DE VINCULACION' as tipo1,'Conyugues Cotizantes' as tipo2
					FROM pyp_inscripciones_pacientes as pip
					JOIN pyp_evoluciones_procesos as pepc
					ON
					(
						pip.inscripcion_id=pepc.inscripcion_id
						AND pepc.evolucion_id = (SELECT MAX(evolucion_id) FROM pyp_evoluciones_procesos WHERE inscripcion_id=pip.inscripcion_id)
					)
					JOIN hc_evoluciones as evo
					ON
					(
						pepc.evolucion_id=evo.evolucion_id
					)
					JOIN ingresos as ing
					ON
					(
						evo.ingreso=ing.ingreso
					)
					JOIN cuentas as cuen
					ON
					(
						ing.ingreso=cuen.ingreso
					)
					JOIN tipos_afiliado as ta
					ON
					(
						ta.tipo_afiliado_id=cuen.tipo_afiliado_id
					)
					WHERE 	ta.tipo_afiliado_id=2
					$fecha0
					GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
					ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
				)
				UNION
				(
					SELECT 	COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) AS mes,
					'GESTACION NUEVAS SEGUN TIPO DE VINCULACION' as tipo1,'Beneficiarios' as tipo2
					FROM pyp_inscripciones_pacientes as pip
					JOIN pyp_evoluciones_procesos as pepc
					ON
					(
						pip.inscripcion_id=pepc.inscripcion_id
						AND pepc.evolucion_id = (SELECT MAX(evolucion_id) FROM pyp_evoluciones_procesos WHERE inscripcion_id=pip.inscripcion_id)
					)
					JOIN hc_evoluciones as evo
					ON
					(
						pepc.evolucion_id=evo.evolucion_id
					)
					JOIN ingresos as ing
					ON
					(
						evo.ingreso=ing.ingreso
					)
					JOIN cuentas as cuen
					ON
					(
						ing.ingreso=cuen.ingreso
					)
					JOIN tipos_afiliado as ta
					ON
					(
						ta.tipo_afiliado_id=cuen.tipo_afiliado_id
					)
					WHERE 	ta.tipo_afiliado_id=3
					$fecha0
					GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
					ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
				)
				UNION
				(
					SELECT 	COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) AS mes,
					'GESTACION NUEVAS SEGUN TIPO DE VINCULACION' as tipo1,'Adicionales' as tipo2
					FROM pyp_inscripciones_pacientes as pip
					JOIN pyp_evoluciones_procesos as pepc
					ON
					(
						pip.inscripcion_id=pepc.inscripcion_id
						AND pepc.evolucion_id = (SELECT MAX(evolucion_id) FROM pyp_evoluciones_procesos WHERE inscripcion_id=pip.inscripcion_id)
					)
					JOIN hc_evoluciones as evo
					ON
					(
						pepc.evolucion_id=evo.evolucion_id
					)
					JOIN ingresos as ing
					ON
					(
						evo.ingreso=ing.ingreso
					)
					JOIN cuentas as cuen
					ON
					(
						ing.ingreso=cuen.ingreso
					)
					JOIN tipos_afiliado as ta
					ON
					(
						ta.tipo_afiliado_id=cuen.tipo_afiliado_id
					)
					WHERE 	ta.tipo_afiliado_id=4
					$fecha0
					GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
					ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
				)
				UNION
				(
					SELECT 	COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) AS mes,
					'GESTACION NUEVAS SEGUN TIPO DE VINCULACION' as tipo1,'Asegurado' as tipo2
					FROM pyp_inscripciones_pacientes as pip
					JOIN pyp_evoluciones_procesos as pepc
					ON
					(
						pip.inscripcion_id=pepc.inscripcion_id
						AND pepc.evolucion_id = (SELECT MAX(evolucion_id) FROM pyp_evoluciones_procesos WHERE inscripcion_id=pip.inscripcion_id)
					)
					JOIN hc_evoluciones as evo
					ON
					(
						pepc.evolucion_id=evo.evolucion_id
					)
					JOIN ingresos as ing
					ON
					(
						evo.ingreso=ing.ingreso
					)
					JOIN cuentas as cuen
					ON
					(
						ing.ingreso=cuen.ingreso
					)
					JOIN tipos_afiliado as ta
					ON
					(
						ta.tipo_afiliado_id=cuen.tipo_afiliado_id
					)
					WHERE 	ta.tipo_afiliado_id=5
					$fecha0
					GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
					ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
				)
				UNION
				(
					SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
					'GESTACION NUEVAS SEGUN TIPO DE VINCULACION' as tipo1,'Total' as tipo2
					FROM pyp_inscripciones_pacientes as pip
					JOIN pyp_evoluciones_procesos as pepc
					ON
					(
						pip.inscripcion_id=pepc.inscripcion_id
						AND pepc.evolucion_id = (SELECT MAX(evolucion_id) FROM pyp_evoluciones_procesos WHERE inscripcion_id=pip.inscripcion_id)
					)
					JOIN hc_evoluciones as evo
					ON
					(
						pepc.evolucion_id=evo.evolucion_id
					)
					JOIN ingresos as ing
					ON
					(
						evo.ingreso=ing.ingreso
					)
					JOIN cuentas as cuen
					ON
					(
						ing.ingreso=cuen.ingreso
					)
					JOIN tipos_afiliado as ta
					ON
					(
						ta.tipo_afiliado_id=cuen.tipo_afiliado_id
					)
					$fecha1
					GROUP BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
					ORDER BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
				)
		";
	
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error en el Modulo app_ParametrizacionPYP - ReporteSegumientoCitas - SQL -TIPO VINCULACION";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
			$this->ban=1;
			return false;
		}
		else
		{
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars[$result->fields[2]][$result->fields[3]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		
		//EDAD
		
		$query="
					(
						SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
						'EDAD' as tipo1,'< 15' as tipo2
						FROM 	pyp_inscripciones_pacientes AS pip 
						JOIN 	pacientes AS p
									ON
									(
										pip.tipo_id_paciente=p.tipo_id_paciente  AND pip.paciente_id=p.paciente_id
									)
									WHERE (pip.fecha_inscripcion - p.fecha_nacimiento)/365 < 15
									$fecha0
									GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
									ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
					)
					UNION
					(
						SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
						'EDAD' as tipo1,'15 - 19' as tipo2
						FROM 	pyp_inscripciones_pacientes AS pip 
						JOIN 	pacientes AS p
									ON
									(
										pip.tipo_id_paciente=p.tipo_id_paciente  AND pip.paciente_id=p.paciente_id
									)
									WHERE (pip.fecha_inscripcion - p.fecha_nacimiento)/365 >= 15 and (pip.fecha_inscripcion - p.fecha_nacimiento)/365<=19
									$fecha0
									GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
									ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
					)
					UNION
					(
						SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
						'EDAD' as tipo1,'20 - 34' as tipo2
						FROM 	pyp_inscripciones_pacientes AS pip 
						JOIN 	pacientes AS p
									ON
									(
										pip.tipo_id_paciente=p.tipo_id_paciente  AND pip.paciente_id=p.paciente_id
									)
									WHERE (pip.fecha_inscripcion - p.fecha_nacimiento)/365>=20 and (pip.fecha_inscripcion - p.fecha_nacimiento)/365<=34
									$fecha0
									GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
									ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
					)
					UNION
					(
						SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
						'EDAD' as tipo1,'35 ó mas' as tipo2
						FROM 	pyp_inscripciones_pacientes AS pip 
						JOIN 	pacientes AS p
									ON
									(
										pip.tipo_id_paciente=p.tipo_id_paciente  AND pip.paciente_id=p.paciente_id
									)
									WHERE (pip.fecha_inscripcion - p.fecha_nacimiento)/365>=35
									$fecha0
									GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
									ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
					);
					
					";
		
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error en el Modulo app_ParametrizacionPYP - ReporteSegumientoCitas - SQL - EDAD";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
				$this->ban=1;
				return false;
			}
			else
			{
				if($result->RecordCount() > 0)
				{
					while(!$result->EOF)
					{
						$vars[$result->fields[2]][$result->fields[3]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
		//NUMERO DE EMBARAZOS
		
		
		$query="
					(
						SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
						'NUMERO DE EMBARAZOS' as tipo1,'1' as tipo2
						FROM 	pyp_inscripciones_pacientes AS pip 
						JOIN 	pyp_inscripcion_cpn AS pic
									ON
									(
										pip.inscripcion_id=pic.inscripcion_id
									)
									WHERE		numero_embarazos_previos+1=1
									$fecha0
									GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
									ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
					)
					UNION
					(
						SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
						'NUMERO DE EMBARAZOS' as tipo1,'2 - 4' as tipo2
						FROM 	pyp_inscripciones_pacientes AS pip 
						JOIN 	pyp_inscripcion_cpn AS pic
									ON
									(
										pip.inscripcion_id=pic.inscripcion_id
									)
									WHERE		numero_embarazos_previos+1>=2 AND numero_embarazos_previos+1<=4
									$fecha0
									GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
									ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
					)
					UNION
					(
						SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
						'NUMERO DE EMBARAZOS' as tipo1,'5 ó mas' as tipo2
						FROM 	pyp_inscripciones_pacientes AS pip 
						JOIN 	pyp_inscripcion_cpn AS pic
									ON
									(
										pip.inscripcion_id=pic.inscripcion_id
									)
									WHERE		numero_embarazos_previos+1>=5
									$fecha0
									GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
									ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
					)
					";
		
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error en el Modulo app_ParametrizacionPYP - ReporteSegumientoCitas - SQL - NUMERO EMBARAZOS";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
				$this->ban=1;
				return false;
			}
			else
			{
				if($result->RecordCount() > 0)
				{
					while(!$result->EOF)
					{
						$vars[$result->fields[2]][$result->fields[3]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			
			
			//TRIMESTRE DE INICIACION CPN 
			
			$query="
						(
							SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
							'TRIMESTRE INICIACION CPN' as tipo1,'1' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN 	pyp_inscripcion_cpn AS pic
										ON
										(
											pip.inscripcion_id=pic.inscripcion_id
										)
										WHERE		TO_NUMBER((pip.fecha_inscripcion-pic.fecha_ultimo_periodo)/7,99)>=0
										AND 		TO_NUMBER((pip.fecha_inscripcion-pic.fecha_ultimo_periodo)/7,99)<=27
										$fecha0
										GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
										ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
							'TRIMESTRE INICIACION CPN' as tipo1,'2' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN 	pyp_inscripcion_cpn AS pic
										ON
										(
											pip.inscripcion_id=pic.inscripcion_id
										)
										WHERE		TO_NUMBER((pip.fecha_inscripcion-pic.fecha_ultimo_periodo)/7,99)>=28
										AND 		TO_NUMBER((pip.fecha_inscripcion-pic.fecha_ultimo_periodo)/7,99)<=32
										$fecha0
										GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
										ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
							'TRIMESTRE INICIACION CPN' as tipo1,'3' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN 	pyp_inscripcion_cpn AS pic
										ON
										(
											pip.inscripcion_id=pic.inscripcion_id
										)
										WHERE		TO_NUMBER((pip.fecha_inscripcion-pic.fecha_ultimo_periodo)/7,99)>=33
										AND 		TO_NUMBER((pip.fecha_inscripcion-pic.fecha_ultimo_periodo)/7,99)<=40
										$fecha0
										GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
										ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						
						";
			
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error en el Modulo app_ParametrizacionPYP - ReporteSegumientoCitas - SQL - TRIMESTRE DE INICIACION CPN";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
				$this->ban=1;
				return false;
			}
			else
			{
				if($result->RecordCount() > 0)
				{
					while(!$result->EOF)
					{
						$vars[$result->fields[2]][$result->fields[3]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			
				
			//CLASIFICACION RIESGO
			
			
			$query="
						(
							SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,'CLASIFICACION RIESGO' as tipo1,'Bajo' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN 	pyp_evoluciones_procesos AS pepc
										ON
										(
											pip.inscripcion_id=pepc.inscripcion_id
											AND pepc.evolucion_id = (SELECT MAX(evolucion_id) FROM pyp_evoluciones_procesos WHERE inscripcion_id=pip.inscripcion_id)
										)
							JOIN 	pyp_cpn_conducta AS pdv
										ON
										(
											pepc.inscripcion_id=pdv.inscripcion_id
											AND pepc.evolucion_id=pdv.evolucion_id
										)
										WHERE		pdv.clasificacion_riesgo=1
										$fecha0
										GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
										ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
							'CLASIFICACION RIESGO' as tipo1,'Alto' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN 	pyp_evoluciones_procesos AS pepc
										ON
										(
											pip.inscripcion_id=pepc.inscripcion_id
											AND pepc.evolucion_id = (SELECT MAX(evolucion_id) FROM pyp_evoluciones_procesos WHERE inscripcion_id=pip.inscripcion_id)
										)
							JOIN 	pyp_cpn_conducta AS pdv
										ON
										(
											pepc.inscripcion_id=pdv.inscripcion_id
											AND pepc.evolucion_id=pdv.evolucion_id
										)
										WHERE		pdv.clasificacion_riesgo=2
										$fecha0
										GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
										ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
							'CLASIFICACION RIESGO' as tipo1,'Sin Riesgo' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN 	pyp_evoluciones_procesos AS pepc
										ON
										(
											pip.inscripcion_id=pepc.inscripcion_id
											AND pepc.evolucion_id = (SELECT MAX(evolucion_id) FROM pyp_evoluciones_procesos WHERE inscripcion_id=pip.inscripcion_id)
										)
							JOIN 	pyp_cpn_conducta AS pdv
										ON
										(
											pepc.inscripcion_id=pdv.inscripcion_id
											AND pepc.evolucion_id=pdv.evolucion_id
										)
										WHERE		pdv.riesgo_biologico=0 AND riesgo_psicosocial=0
										$fecha0
										GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
										ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						";
			
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error en el Modulo app_ParametrizacionPYP - ReporteSegumientoCitas - SQL - CLASIFICACION RIESGO";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
					$this->ban=1;
					return false;
				}
				else
				{
					if($result->RecordCount() > 0)
					{
						while(!$result->EOF)
						{
							$vars[$result->fields[2]][$result->fields[3]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
							$result->MoveNext();
						}
					}
				}
				
			
				
			//PATOLOGIA ASOCIADA
			
			$query="
						(
							SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
							'PATOLOGIA ASOCIADA' as tipo1,'ITU' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN 	pyp_evoluciones_procesos AS pepc
										ON
										(
											pip.inscripcion_id=pepc.inscripcion_id
										)
							JOIN hc_diagnosticos_ingreso as diagin
										ON
										(
											pepc.evolucion_id=diagin.evolucion_id
										)
							JOIN hc_diagnosticos_egreso as diageg
										ON
										(
											pepc.evolucion_id=diageg.evolucion_id
										)
							JOIN diagnosticos as diag
										ON
										(
											diag.diagnostico_id=diagin.tipo_diagnostico_id
											OR diag.diagnostico_id=diageg.tipo_diagnostico_id
										)
							JOIN pyp_diagnosticos as pdiag
										ON
										(
											diag.diagnostico_id=pdiag.diagnostico_id
										)
							WHERE		lower(pdiag.desc_cpn)='itu'
							$fecha0
							GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
							'PATOLOGIA ASOCIADA' as tipo1,'Cervicovaginitis' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN 	pyp_evoluciones_procesos AS pepc
										ON
										(
											pip.inscripcion_id=pepc.inscripcion_id
										)
							JOIN hc_diagnosticos_ingreso as diagin
										ON
										(
											pepc.evolucion_id=diagin.evolucion_id
										)
							JOIN hc_diagnosticos_egreso as diageg
										ON
										(
											pepc.evolucion_id=diageg.evolucion_id
										)
							JOIN diagnosticos as diag
										ON
										(
											diag.diagnostico_id=diagin.tipo_diagnostico_id
											OR diag.diagnostico_id=diageg.tipo_diagnostico_id
										)
							JOIN pyp_diagnosticos as pdiag
										ON
										(
											diag.diagnostico_id=pdiag.diagnostico_id
										)
							WHERE lower(pdiag.desc_cpn)='cervicovaginitis'
							$fecha0
							GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
							'PATOLOGIA ASOCIADA' as tipo1,'HTA en Embarazo' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN 	pyp_evoluciones_procesos AS pepc
										ON
										(
											pip.inscripcion_id=pepc.inscripcion_id
										)
							JOIN pyp_cpn_registro_riesgo_evolucion as prev
										ON
										(
											pepc.inscripcion_id=prev.inscripcion_id 
											AND prev.evolucion_id=pepc.evolucion_id
											AND prev.riesgo_id=12
										)
							$fecha1
							GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
							'PATOLOGIA ASOCIADA' as tipo1,'Diabetes Gestacional' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN 	pyp_evoluciones_procesos AS pepc
										ON
										(
											pip.inscripcion_id=pepc.inscripcion_id
										)
							JOIN pyp_cpn_registro_riesgo_evolucion as prev
										ON
										(
											pepc.inscripcion_id=prev.inscripcion_id 
											AND prev.evolucion_id=pepc.evolucion_id
											AND prev.riesgo_id=3
										)
							$fecha1
							GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						";
			
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error en el Modulo app_ParametrizacionPYP - ReporteSegumientoCitas - SQL - PATOLOGIA ASOCIADA ";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
					$this->ban=1;
					return false;
				}
				else
				{
					if($result->RecordCount() > 0)
					{
						while(!$result->EOF)
						{
							$vars[$result->fields[2]][$result->fields[3]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
							$result->MoveNext();
						}
					}
				}
				
				//TIPO CITA
			
				$query="
							(
								SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
								'TIPO CITA' as tipo1,'Citas 1 Vez' as tipo2
								FROM 	pyp_inscripciones_pacientes AS pip
								JOIN 	pyp_evoluciones_procesos AS pepc
											ON
											(
												pip.inscripcion_id=pepc.inscripcion_id
											)
								WHERE 	pepc.sw_estado=2
								$fecha0
								GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
								ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
							)
							UNION
							(
								SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
								'TIPO CITA' as tipo1,'Citas Control' as tipo2
								FROM 	pyp_inscripciones_pacientes AS pip
								JOIN 	pyp_evoluciones_procesos AS pepc
											ON
											(
												pip.inscripcion_id=pepc.inscripcion_id
											)
								WHERE 	pepc.sw_estado=3
								$fecha0
								GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
								ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
							)
							UNION
							(
								SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
								'TIPO CITA' as tipo1,'Postparto' as tipo2
								FROM 	pyp_inscripciones_pacientes AS pip
								JOIN 	pyp_evoluciones_procesos AS pepc
											ON
											(
												pip.inscripcion_id=pepc.inscripcion_id
											)
								WHERE 	pepc.sw_estado=4
								$fecha0
								GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
								ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
							)
							";
			
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error en el Modulo app_ParametrizacionPYP - ReporteSegumientoCitas - SQL - TIPO CITA ";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
					$this->ban=1;
					return false;
				}
				else
				{
					if($result->RecordCount() > 0)
					{
						while(!$result->EOF)
						{
							$vars[$result->fields[2]][$result->fields[3]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
							$result->MoveNext();
						}
					}
				}
				
				
				
			//REMISIONES
			
			$query="
						(
							SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
							'REMISIONES' as tipo1,'Especialista' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN 	pyp_evoluciones_procesos AS pepc
										ON
										(
											pip.inscripcion_id=pepc.inscripcion_id
										)
							JOIN 	pyp_cpn_codigos_evolucion_gestacion_valores AS pdv
										ON
										(
											pepc.inscripcion_id=pdv.inscripcion_id
											AND pepc.evolucion_id=pdv.evolucion_id
											AND pdv.codigo_evolucion_id=6
										)
							WHERE		pdv.valor=1
							$fecha0
							GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(*),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
							'REMISIONES' as tipo1,'Salud Oral' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN 	pyp_evoluciones_procesos AS pepc
										ON
										(
											pip.inscripcion_id=pepc.inscripcion_id
										)
						JOIN 	pyp_cpn_codigos_evolucion_gestacion_valores AS pdv
										ON
										(
											pepc.inscripcion_id=pdv.inscripcion_id
											AND pepc.evolucion_id=pdv.evolucion_id
											AND pdv.codigo_evolucion_id=5
										)
							WHERE		pdv.valor=1
							$fecha0
							GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						";
			
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error en el Modulo app_ParametrizacionPYP - ReporteSegumientoCitas - SQL - REMISIONES ";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
				$this->ban=1;
				return false;
			}
			else
			{
				if($result->RecordCount() > 0)
				{
					while(!$result->EOF)
					{
						$vars[$result->fields[2]][$result->fields[3]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			
			//SEGUIMIENTO

			
			$query="
						(
							SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
							'SEGUIMIENTO' as tipo1,'Inasistentes CPN' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN pyp_evoluciones_procesos as pepc
										ON
										( 
											pip.inscripcion_id=pepc.inscripcion_id
										)
							JOIN agenda_citas_asignadas AS l 
										ON
										(
											pip.tipo_id_paciente=l.tipo_id_paciente
											AND pip.paciente_id=l.paciente_id
											AND l.agenda_cita_asignada_id NOT IN (
																										SELECT agenda_cita_asignada_id
																										FROM agenda_citas_asignadas_cancelacion
																										)
										)
							JOIN os_cruce_citas AS m 
										ON
										(
											l.agenda_cita_asignada_id=m.agenda_cita_asignada_id
										)
							JOIN os_maestro AS n 
										ON
										(
											m.numero_orden_id=n.numero_orden_id
										)
							JOIN agenda_citas AS o
										ON
										(
											l.agenda_cita_id=o.agenda_cita_id
										)
							JOIN agenda_turnos AS p 
										ON
										(
											o.agenda_turno_id=p.agenda_turno_id
										)
							WHERE	pepc.fecha_ideal_proxima_cita < now() AND p.fecha_turno < now()
										AND n.sw_estado!='3'
										$fecha0
							GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(DISTINCT pcs.fecha_registro),TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99) as mes,
							'SEGUIMIENTO' as tipo1,'Inasistentes Contactadas' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN pyp_evoluciones_procesos as pepc
										ON
										( 
											pip.inscripcion_id=pepc.inscripcion_id
										)
							JOIN pyp_cpn_seguimiento as pcs
							ON
							(
								pepc.inscripcion_id=pcs.inscripcion_id
								AND pepc.evolucion_id=pcs.evolucion_id
							)
							JOIN agenda_citas_asignadas AS l 
										ON
										(
											pcs.cita_asignada_id=l.agenda_cita_asignada_id
											AND l.agenda_cita_asignada_id NOT IN (
																										SELECT agenda_cita_asignada_id
																										FROM agenda_citas_asignadas_cancelacion
																									)
										)
							JOIN os_cruce_citas AS m 
										ON
										(
											l.agenda_cita_asignada_id=m.agenda_cita_asignada_id
										)
							JOIN os_maestro AS n 
										ON
										(
											m.numero_orden_id=n.numero_orden_id
										)
							JOIN agenda_citas AS o
										ON
										(
											l.agenda_cita_id=o.agenda_cita_id
										)
							JOIN agenda_turnos AS p 
										ON
										(
											o.agenda_turno_id=p.agenda_turno_id
										)
							WHERE	pepc.fecha_ideal_proxima_cita < now() AND p.fecha_turno< now()
										AND n.sw_estado!='3'
										$fecha2
							GROUP 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(DISTINCT pcs.fecha_registro),TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99) as mes,
							'SEGUIMIENTO' as tipo1,'Seguimiento Alto Riesgo' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN pyp_evoluciones_procesos as pepc
										ON
										( 
											pip.inscripcion_id=pepc.inscripcion_id
										)
							JOIN pyp_cpn_seguimiento as pcs
										ON
										(
											pepc.inscripcion_id=pcs.inscripcion_id
											AND pepc.evolucion_id=pcs.evolucion_id
										)
							JOIN pyp_cpn_motivos_seguimiento as pcms
										ON
										(
											pcs.pyp_cpn_seguimiento_id=pcms.pyp_cpn_seguimiento_id
										)
							WHERE pcms.pyp_cpn_motivo_seguimiento_id=5
							$fecha2
							GROUP 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(DISTINCT pcs.fecha_registro),TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99) as mes,
							'SEGUIMIENTO' as tipo1,'Contacto Telefonico' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN pyp_evoluciones_procesos as pepc
										ON
										( 
											pip.inscripcion_id=pepc.inscripcion_id
										)
							JOIN pyp_cpn_seguimiento as pcs
										ON
										(
											pepc.inscripcion_id=pcs.inscripcion_id
											AND pepc.evolucion_id=pcs.evolucion_id
										)
							JOIN pyp_cpn_motivos_seguimiento as pcms
										ON
										(
											pcs.pyp_cpn_seguimiento_id=pcms.pyp_cpn_seguimiento_id
										)
							WHERE pcs.contacto_telefonico is not null
							$fecha2
							GROUP 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(DISTINCT pcs.fecha_registro),TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99) as mes,
							'SEGUIMIENTO' as tipo1,'Direccionamiento a Otra IPS' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN pyp_evoluciones_procesos as pepc
										ON
										( 
											pip.inscripcion_id=pepc.inscripcion_id
										)
							JOIN pyp_cpn_seguimiento as pcs
										ON
										(
											pepc.inscripcion_id=pcs.inscripcion_id
											AND pepc.evolucion_id=pcs.evolucion_id
										)
							WHERE pcs.ips_direccionada is not null
							$fecha2
							GROUP 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(DISTINCT pcs.fecha_registro),TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99) as mes,
							'SEGUIMIENTO' as tipo1,'Captacion Efectiva' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN pyp_evoluciones_procesos as pepc
										ON
										( 
											pip.inscripcion_id=pepc.inscripcion_id
										)
							JOIN pyp_cpn_seguimiento as pcs
										ON
										(
											pepc.inscripcion_id=pcs.inscripcion_id
											AND pepc.evolucion_id=pcs.evolucion_id
										)
							JOIN agenda_citas_asignadas AS l 
										ON
										(
											
											pcs.cita_asignada_id=l.agenda_cita_asignada_id
											AND l.agenda_cita_asignada_id NOT IN (
																										SELECT agenda_cita_asignada_id
																										FROM agenda_citas_asignadas_cancelacion
																										)
										)
							JOIN os_cruce_citas AS m 
										ON
										(
											l.agenda_cita_asignada_id=m.agenda_cita_asignada_id
										)
							JOIN os_maestro AS n 
										ON
										(
											m.numero_orden_id=n.numero_orden_id
										)
							JOIN agenda_citas AS o
										ON
										(
											l.agenda_cita_id=o.agenda_cita_id
										)
							JOIN agenda_turnos AS p 
										ON
										(
											o.agenda_turno_id=p.agenda_turno_id
										)
							WHERE 	n.sw_estado='3'
							$fecha2
							GROUP 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
						)
						";
			
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error en el Modulo app_ParametrizacionPYP - ReporteSegumientoCitas - SQL - SEGUIMIENTO ";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
				$this->ban=1;
				return false;
			}
			else
			{
				if($result->RecordCount() > 0)
				{
					while(!$result->EOF)
					{
						$vars[$result->fields[2]][$result->fields[3]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			
			//CONTROL POSTPARTO O ABORTO
			
			$query="
						(
							SELECT COUNT(*),TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99) as mes,
							'CONTROL POSTPARTO O ABORTO' as tipo1,'Citadas' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN pyp_evoluciones_procesos as pepc
										ON
										( 
											pip.inscripcion_id=pepc.inscripcion_id
										)
							JOIN pyp_cpn_seguimiento as pcs
										ON
										(
											pepc.inscripcion_id=pcs.inscripcion_id
											AND pepc.evolucion_id=pcs.evolucion_id
										)
							JOIN pyp_cpn_motivos_seguimiento as pcms
										ON
										(
											pcs.pyp_cpn_seguimiento_id=pcms.pyp_cpn_seguimiento_id
										)
							JOIN agenda_citas_asignadas AS l 
										ON
										(
											
											pcs.cita_asignada_id=l.agenda_cita_asignada_id
											AND l.agenda_cita_asignada_id NOT IN (
																										SELECT agenda_cita_asignada_id
																										FROM agenda_citas_asignadas_cancelacion
																										)
										)
							JOIN os_cruce_citas AS m 
										ON
										(
											l.agenda_cita_asignada_id=m.agenda_cita_asignada_id
										)
							JOIN os_maestro AS n 
										ON
										(
											m.numero_orden_id=n.numero_orden_id
										)
							JOIN agenda_citas AS o
										ON
										(
											l.agenda_cita_id=o.agenda_cita_id
										)
							JOIN agenda_turnos AS p 
										ON
										(
											o.agenda_turno_id=p.agenda_turno_id
										)
							WHERE 	pcs.cita_asignada_id is not null
											AND pcms.pyp_cpn_motivo_seguimiento_id=14
											AND n.sw_estado!='3'
											$fecha2
							GROUP 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(*),TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99) as mes,
							'CONTROL POSTPARTO O ABORTO' as tipo1,'Realizadas' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN pyp_evoluciones_procesos as pepc
										ON
										( 
											pip.inscripcion_id=pepc.inscripcion_id
										)
							JOIN pyp_cpn_seguimiento as pcs
										ON
										(
											pepc.inscripcion_id=pcs.inscripcion_id
											AND pepc.evolucion_id=pcs.evolucion_id
										)
							JOIN pyp_cpn_motivos_seguimiento as pcms
										ON
										(
											pcs.pyp_cpn_seguimiento_id=pcms.pyp_cpn_seguimiento_id
										)
							JOIN agenda_citas_asignadas AS l 
										ON
										(
											
											pcs.cita_asignada_id=l.agenda_cita_asignada_id
											AND l.agenda_cita_asignada_id NOT IN (
																										SELECT agenda_cita_asignada_id
																										FROM agenda_citas_asignadas_cancelacion
																										)
										)
							JOIN os_cruce_citas AS m 
										ON
										(
											l.agenda_cita_asignada_id=m.agenda_cita_asignada_id
										)
							JOIN os_maestro AS n 
										ON
										(
											m.numero_orden_id=n.numero_orden_id
										)
							JOIN agenda_citas AS o
										ON
										(
											l.agenda_cita_id=o.agenda_cita_id
										)
							JOIN agenda_turnos AS p 
										ON
										(
											o.agenda_turno_id=p.agenda_turno_id
										)
							WHERE 	pcs.cita_asignada_id is not null
											AND pcms.pyp_cpn_motivo_seguimiento_id=14
											AND n.sw_estado='3'
											$fecha2
							GROUP 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(*),TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99) as mes,
							'CONTROL POSTPARTO O ABORTO' as tipo1,'Inasistentes Contactadas' as tipo2
							FROM 	pyp_inscripciones_pacientes AS pip 
							JOIN pyp_evoluciones_procesos as pepc
										ON
										( 
											pip.inscripcion_id=pepc.inscripcion_id
										)
							JOIN pyp_cpn_seguimiento as pcs
										ON
										(
											pepc.inscripcion_id=pcs.inscripcion_id
											AND pepc.evolucion_id=pcs.evolucion_id
										)
							JOIN pyp_cpn_motivos_seguimiento as pcms
										ON
										(
											pcs.pyp_cpn_seguimiento_id=pcms.pyp_cpn_seguimiento_id
										)
							WHERE 	pcms.pyp_cpn_motivo_seguimiento_id=14
							$fecha2
							GROUP 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pcs.fecha_registro,'MM'),99)
						)
						";
			
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error en el Modulo app_ParametrizacionPYP - ReporteSegumientoCitas - SQL - CONTROL POSTPARTO O ABORTO ";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
				$this->ban=1;
				return false;
			}
			else
			{
				if($result->RecordCount() > 0)
				{
					while(!$result->EOF)
					{
						$vars[$result->fields[2]][$result->fields[3]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			//CONTROL USUARIAS ATENDIDAS
			
			 $query="
						(
							SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
							'CONTROL USUARIAS ATENDIDAS' as tipo1,' Primera Vez Medico' as tipo2
							FROM pyp_inscripciones_pacientes AS pip 
							JOIN pyp_evoluciones_procesos as pepc
										ON
										( 
											pip.inscripcion_id=pepc.inscripcion_id
											AND pepc.sw_estado='2'
										)
							JOIN 	pyp_cpn_codigos_evolucion_gestacion_valores AS pdv
										ON
										(
											pepc.inscripcion_id=pdv.inscripcion_id
											AND pepc.evolucion_id=pdv.evolucion_id
										)
							JOIN profesionales_usuarios as pu
										ON
										(
											pdv.usuario_id=pu.usuario_id
										)
							JOIN profesionales as pro
										ON
										(
											pu.tipo_tercero_id=pro.tipo_id_tercero 
											AND pu.tercero_id=pro.tercero_id
										)
							JOIN tipos_profesionales as tpro
										ON
										(
											pro.tipo_profesional=tpro.tipo_profesional
										)
							WHERE 	tpro.tipo_profesional=1 OR tpro.tipo_profesional=2
							$fecha0
							GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
							'CONTROL USUARIAS ATENDIDAS' as tipo1,' Primera Vez Enfermera' as tipo2
							FROM pyp_inscripciones_pacientes AS pip 
							JOIN pyp_evoluciones_procesos as pepc
										ON
										( 
											pip.inscripcion_id=pepc.inscripcion_id
											AND pepc.sw_estado='2'
										)
							JOIN 	pyp_cpn_codigos_evolucion_gestacion_valores AS pdv
										ON
										(
											pepc.inscripcion_id=pdv.inscripcion_id
											AND pepc.evolucion_id=pdv.evolucion_id
										)
							JOIN profesionales_usuarios as pu
										ON
										(
											pdv.usuario_id=pu.usuario_id
										)
							JOIN profesionales as pro
										ON
										(
											pu.tipo_tercero_id=pro.tipo_id_tercero 
											AND pu.tercero_id=pro.tercero_id
										)
							JOIN tipos_profesionales as tpro
										ON
										(
											pro.tipo_profesional=tpro.tipo_profesional
										)
							WHERE 	tpro.tipo_profesional=3
							$fecha0
							GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
							'CONTROL USUARIAS ATENDIDAS' as tipo1,' Control por Medico' as tipo2
							FROM pyp_inscripciones_pacientes AS pip 
							JOIN pyp_evoluciones_procesos as pepc
										ON
										( 
											pip.inscripcion_id=pepc.inscripcion_id
											AND pepc.sw_estado='3'
										)
							JOIN 	pyp_cpn_codigos_evolucion_gestacion_valores AS pdv
										ON
										(
											pepc.inscripcion_id=pdv.inscripcion_id
											AND pepc.evolucion_id=pdv.evolucion_id
										)
							JOIN profesionales_usuarios as pu
										ON
										(
											pdv.usuario_id=pu.usuario_id
										)
							JOIN profesionales as pro
										ON
										(
											pu.tipo_tercero_id=pro.tipo_id_tercero 
											AND pu.tercero_id=pro.tercero_id
										)
							JOIN tipos_profesionales as tpro
										ON
										(
											pro.tipo_profesional=tpro.tipo_profesional
										)
							WHERE 	tpro.tipo_profesional=1 OR tpro.tipo_profesional=2
							$fecha0
							GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
							'CONTROL USUARIAS ATENDIDAS' as tipo1,' Control por Enfermera' as tipo2
							FROM pyp_inscripciones_pacientes AS pip 
							JOIN pyp_evoluciones_procesos as pepc
										ON
										( 
											pip.inscripcion_id=pepc.inscripcion_id
											AND pepc.sw_estado='3'
										)
							JOIN 	pyp_cpn_codigos_evolucion_gestacion_valores AS pdv
										ON
										(
											pepc.inscripcion_id=pdv.inscripcion_id
											AND pepc.evolucion_id=pdv.evolucion_id
										)
							JOIN profesionales_usuarios as pu
										ON
										(
											pdv.usuario_id=pu.usuario_id
										)
							JOIN profesionales as pro
										ON
										(
											pu.tipo_tercero_id=pro.tipo_id_tercero 
											AND pu.tercero_id=pro.tercero_id
										)
							JOIN tipos_profesionales as tpro
										ON
										(
											pro.tipo_profesional=tpro.tipo_profesional
										)
							WHERE 	tpro.tipo_profesional=3
							$fecha0
							GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
							'CONTROL USUARIAS ATENDIDAS' as tipo1,'Alto Riesgo por Especialista' as tipo2
							FROM pyp_inscripciones_pacientes AS pip 
							JOIN pyp_evoluciones_procesos as pepc
										ON
										(
											pip.inscripcion_id=pepc.inscripcion_id
										)
							JOIN 	pyp_cpn_conducta AS pdv
										ON
										(
											pepc.inscripcion_id=pdv.inscripcion_id
											AND pepc.evolucion_id=pdv.evolucion_id
											AND pdv.clasificacion_riesgo=2
										)
							JOIN 	pyp_cpn_codigos_evolucion_gestacion_valores AS pdv1
										ON
										(
											pepc.inscripcion_id=pdv1.inscripcion_id
											AND pepc.evolucion_id=pdv1.evolucion_id
											AND (pdv1.codigo_evolucion_id=5 OR pdv1.codigo_evolucion_id=6)
											AND pdv1.valor=1
										)
							$fecha1
							GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
							'CONTROL USUARIAS ATENDIDAS' as tipo1,'Total Usuarias Atendidas' as tipo2
							FROM pyp_inscripciones_pacientes AS pip 
							JOIN pyp_evoluciones_procesos as pepc
										ON
										(
											pip.inscripcion_id=pepc.inscripcion_id
										)
							JOIN 	pyp_cpn_codigos_evolucion_gestacion_valores AS pdv
										ON
										(
											pepc.inscripcion_id=pdv.inscripcion_id
											AND pepc.evolucion_id=pdv.evolucion_id
										)
							$fecha1
							GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						UNION
						(
							SELECT COUNT(DISTINCT pip.fecha_inscripcion),TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99) as mes,
							'CONTROL USUARIAS ATENDIDAS' as tipo1,'Total Usuarias Atendidas' as tipo2
							FROM pyp_inscripciones_pacientes AS pip 
							JOIN pyp_evoluciones_procesos as pepc
										ON
										(
											pip.inscripcion_id=pepc.inscripcion_id
										)
							JOIN 	pyp_cpn_codigos_evolucion_gestacion_valores AS pdv
										ON
										(
											pepc.inscripcion_id=pdv.inscripcion_id
											AND pepc.evolucion_id=pdv.evolucion_id
										)
							$fecha1
							GROUP 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
							ORDER 	BY TO_NUMBER(TO_CHAR(pip.fecha_inscripcion,'MM'),99)
						)
						";
			
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error en el Modulo app_ParametrizacionPYP - ReporteSegumientoCitas - SQL - CONTROL USUARIAS ATENDIDAS ";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
				$this->ban=1;
				return false;
			}
			else
			{
				if($result->RecordCount() > 0)
				{
					while(!$result->EOF)
					{
						$vars[$result->fields[2]][$result->fields[3]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			$dbconn->CommitTrans();
			
		return $vars;
	}
	
	function ProcesarSqlConteo()
	{
		$this->paginaActual = 1;
		$this->offset = 0;
		$this->limit = 20;
		
		if($_REQUEST['offset'])
		{
			$this->paginaActual = intval($_REQUEST['offset']);
			if($this->paginaActual > 1)
			{
				$this->offset = ($this->paginaActual - 1) * ($this->limit);
			}
		}
		
		return true;
	}
	
	function FechaStamp($fecha)
	{
		if($fecha)
		{
			$fech = strtok ($fecha,"-");
			for($l=0;$l<3;$l++)
			{
				$date[$l]=$fech;
				$fech = strtok ("-");
			}
			
			return  ceil($date[2])."-".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."-".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
		}
	}
	
}//fin de la clase
?>
