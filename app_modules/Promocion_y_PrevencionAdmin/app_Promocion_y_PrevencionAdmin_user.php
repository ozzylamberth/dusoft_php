
<?php

/**
* Modulo de Promocion_y_Prevencion (PHP).
*
//*
*
* @author Carlos A. Henao <carlosarturohenao@gmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_Promocion_y_PrevencionAdmin_user.php
*
//*
**/

class app_Promocion_y_PrevencionAdmin_user extends classModulo
{
    var $uno;//para los errores
    
    function app_Promocion_y_PrevencionAdmin_user()
    {
        return true;
    }

    function main()
    {
        $this->PrincipalPyP();
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
            $sql="  SELECT B.empresa_id, B.razon_social as desc_emp,C.centro_utilidad,C.descripcion as desc_cen
                    FROM userpermisos_pypadmin AS A, empresas AS B,centros_utilidad AS C
                    WHERE A.usuario_id=".$_SESSION['SYSTEM_USUARIO_ID']." 
                    AND A.empresa_id=B.empresa_id";
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
						$k=0;
            if ($dbconn->ErrorNo() != 0)
            {
								$this->error = "Error al Cargar el Modulo";
            		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            		return false;
            }
            else
            {
								while ($data = $result->FetchRow()) 
								{
                    $prueba6[$data['desc_emp']][$data['desc_cen']]=$data;
                    $_SESSION['SEGURIDAD']['EMPRESA_ID']=$data['empresa_id'];
										$_SESSION['SEGURIDAD']['CENTRO_ID']=$data['centro_utilidad'];
										//$_SESSION['SEGURIDAD']['UNIDAD_ID']=$data['unidad_funcional'];
                    $i=1;
										$k++;
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
						//$mtz1[1]="CENTRO DE UTILIDAD";
            $com[0]=$mtz1;
            $com[1]=$prueba6;
            $url[0]='app';
            $url[1]='Promocion_y_PrevencionAdmin';
            $url[2]='user';
            $url[3]='SeleccionParametros';
            $url[4]='Promocion_y_PrevencionAdmin';
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
					$titulo = "ADMINISTRACIÓN PYP";
					$boton = "VOLVER";//REGRESAR
					$accion=ModuloGetURL('system','Menu','user','main');
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return false;
        }
        return true;
    }

	function TraerNombrePrograma($programa_id) 
    {
		list($dbconn) = GetDBconn();
		$query="SELECT a.descripcion
					FROM pyp_programas a
					WHERE programa_id=$programa_id
					AND sw_estado='1';";
		
		$result = $dbconn->Execute($query);
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
	
    function TraerDatos($submodulos)
    {
		list($dbconn) = GetDBconn();
		
		if($submodulos)
		{
			$sql="";
		}
		else
		{
			$sql=" WHERE a.programa_id NOT IN
			(
				SELECT programa_id
				FROM pyp_programas_planes
				WHERE sw_estado='1'
			)";
		}
		
		$query="SELECT a.programa_id,a.hc_modulo,a.app_modulo, a.descripcion, a.sw_estado
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
    
    function TraerDatosPlanes()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT a.plan_id,a.plan_descripcion
				FROM planes a
        		WHERE a.estado='1';";
        
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
    
    function TraerProgramasYplanes($Programa_id)
    {
		list($dbconn) = GetDBconn();
		$query="SELECT a.plan_id,a.programa_id
				FROM pyp_programas_planes a
				WHERE a.estado='1'
				AND a.programa_id=$Programa_id;";
		
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
	
	function TraerProgramasYplanesCu($Programa_id)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT a.programa_id, a.empresa_id, a.centro_utilidad,a.unidad_funcional
						FROM pyp_programas_unidad_funcional a
						WHERE a.estado='1'
						AND a.programa_id=$Programa_id
						AND a.empresa_id='".$_SESSION['SEGURIDAD']['EMPRESA_ID']."'";
						//AND a.centro_utilidad='".$_SESSION['SEGURIDAD']['CENTRO_ID']."';"; 
		
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
	
	function TraerDatosCu()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT a.empresa_id,a.centro_utilidad,a.unidad_funcional,a.descripcion
						FROM unidades_funcionales a
						WHERE a.empresa_id='".$_SESSION['SEGURIDAD']['EMPRESA_ID']."'";
						//AND a.centro_utilidad='".$_SESSION['SEGURIDAD']['CENTRO_ID']."';";
		
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
    
		function TraerDatosEditar($programa)
		{
        list($dbconn) = GetDBconn();
        
		$query="SELECT programa_id, hc_modulo,app_modulo, descripcion
				FROM pyp_programas
				WHERE programa_id='".$programa."';";
        
		$result = $dbconn->Execute($query);
        
		if ($dbconn->ErrorNo() != 0)
		{
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
    }
		
		$query="SELECT sexo_id,edad_max,edad_min 
				FROM system_hc_submodulos 
				WHERE submodulo='".$result->fields[1]."';";
        
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
    
	function GuardarProgramas()
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
        
		if($_REQUEST['confirmar'])
		{
			$query="UPDATE pyp_programas SET sw_estado='0'
        			WHERE programa_id=".$_REQUEST['submodulos'].";";
        	
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
        	{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
        	}
			
			$dbconn->CommitTrans();
			$this->SeleccionParametros();
			return true;
		}
		else
		{
				$usuario=UserGetUID();
        if($_REQUEST['submodulos']=='-1')
        {
          $this->frmError["submodulo"]=1;
        }
        if(empty($_REQUEST['programa']))
        {
          $this->frmError["programa"]=1;
        }
        if(empty($_REQUEST['edadmin']) OR empty($_REQUEST['edadmax']))
        {
          $this->frmError["rango"]=1;
        }
        elseif(!is_numeric($_REQUEST['edadmin']) OR !is_numeric($_REQUEST['edadmax']))
        {
          $this->frmError["rango"]=1;
        }
        if($_REQUEST['femenino']!=on AND $_REQUEST['masculino']!=on)
        {
          $this->frmError["sexo"]=1;
        }
        
        if($_REQUEST['submodulos']=='-1' OR empty($_REQUEST['programa']) 
        OR empty($_REQUEST['edadmin']) OR empty($_REQUEST['edadmax']) 
        OR ($_REQUEST['femenino']!=on AND $_REQUEST['masculino']!=on)
        AND(is_numeric($_REQUEST['edadmin']) AND is_numeric($_REQUEST['edadmax'])))
        {
            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
            $this->uno=1;
            $this->FrmIngresarProgramas();
            return true;
        }
        elseif(!is_numeric($_REQUEST['edadmin']) OR !is_numeric($_REQUEST['edadmax']))
        {
            $this->frmError["MensajeError"]="LOS DATOS DEBEN SER NUMERICOS";
            $this->uno=1;
//             $this->FrmIngresarProgramas();
            return true;
        }

        $planes=$_REQUEST['planes'];
        $noplan=false;
        for($i=0; $i<sizeof($planes);$i++)
        {  
            if($_REQUEST[$planes[$i][plan_id]])
            {
             $noplan=true;
             $i=sizeof($planes);
            }
            
        }
        if(!$noplan)
        {
            $this->frmError["MensajeError"]="DEBE SELECCIONAR UN PLAN";
            $this->uno=1;
            $this->FrmIngresarProgramas();
            return true;
        }

        $planesCu=$_REQUEST['CU'];
        $noplancu=false;
        for($i=0; $i<sizeof($planesCu);$i++)
        { 
						if($_REQUEST['descripcion_uf'][$i])
						{
						$noplancu=true;
						$i=sizeof($planesCu);
						}
				}
      
        if(!$noplancu)
        {
            $this->frmError["MensajeError"]="DEBE SELECCIONAR LA UNIDAD FUNCIONAL";
            $this->uno=1;
            $this->FrmIngresarProgramas();
            return true;
        }
        $query="UPDATE pyp_programas SET sw_estado='1'
        			WHERE programa_id=".$_REQUEST['submodulos'].";";
					
		$result = $dbconn->Execute($query);
		
        //VERIFICAR SI YA EXISTEN DATOS
        $query="SELECT count(*)
        		FROM pyp_programas_planes a
        		WHERE a.programa_id=".$_REQUEST['submodulos'].";";
				
        $result = $dbconn->Execute($query);
        
		if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if ($result->fields[0]>0)
        {
		$query="DELETE
		 				FROM pyp_programas_planes
        		WHERE programa_id=".$_REQUEST['submodulos'].";";
        $result = $dbconn->Execute($query);
        }
        //FIN VERIFICAR SI YA EXISTEN DATOS
        
        //ACTUALIZAR EN PROGRAMAS PYP
        //$datos_programa=$this->TraerDatosPyP($_REQUEST['submodulos']);
       $query="UPDATE pyp_programas SET descripcion='".$_REQUEST['programa']."'
		 		WHERE programa_id=".$_REQUEST['submodulos'];
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollBackTrans();
            $this->frmError["MensajeError"]="VERIFICAR DATOS 1. ".$query.'--'.$dbconn->ErrorMsg();
            $this->uno=1;
            $this->FrmIngresarProgramas();
            return true;
        }
        //FIN ACTUALIZAR EN PROGRAMAS PYP
        for($i=0; $i<sizeof($planes);$i++)
        {  
            if($_REQUEST[$planes[$i][plan_id]])
            {
                $query ="INSERT INTO pyp_programas_planes
                            (
                                plan_id,
																programa_id,
                                estado
                            )
                            VALUES
                            (
                                ".$_REQUEST[$planes[$i][plan_id]].",
                                ".$_REQUEST['submodulos'].",
                                '1'
                            );";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollBackTrans();
                    $this->frmError["MensajeError"]="VERIFICAR DATOS 2. ".$dbconn->ErrorMsg();
                    $this->uno=1;
                    $this->FrmIngresarProgramas();
                    return true;
                }
            }
        }
        //GUARDAR EN PROGRAMAS PYP UNIDAD FUNCIONAL
        //VERIFICAR SI YA EXISTEN DATOS
        $query="SELECT count(*)
        FROM pyp_programas_unidad_funcional a
        WHERE a.programa_id=".$_REQUEST['submodulos'].";";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if ($result->fields[0]>0)
        {
				$query="DELETE
        FROM pyp_programas_unidad_funcional
        WHERE programa_id=".$_REQUEST['submodulos'].";";
        $result = $dbconn->Execute($query);
        }
        //
        for($i=0;$i<sizeof($planesCu);$i++)
        {
				if($_REQUEST['descripcion_uf'][$i])
            {
              $query ="INSERT INTO pyp_programas_unidad_funcional
                            (
                               	empresa_id, 
                                centro_utilidad,
																unidad_funcional,
                                programa_id,
								estado
                            )
                            VALUES
                            (
                                '".$planesCu[$i][empresa_id]."',
                                '".$planesCu[$i][centro_utilidad]."',
								'".$_REQUEST['descripcion_uf'][$i]."',
								".$_REQUEST['submodulos'].",
                                '1'
							);";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollBackTrans();
                    $this->frmError["MensajeError"]="VERIFICAR DATOS 3. ".$query.'--'.$dbconn->ErrorMsg();
                    $this->uno=1;
                    $this->FrmIngresarProgramas();
                    return true;
                }
            }
        }
				
				
        if($_REQUEST['femenino']==on AND $_REQUEST['masculino']==on)
        {
            $sexo='NULL';
        }
				elseif($_REQUEST['femenino']==on)
        {
            $sexo="'F'";          
        }
				elseif($_REQUEST['masculino']==on)
				{
					$sexo="'M'";   
				}
				
        $datos_programa=$this->TraerDatosPyP($_REQUEST['submodulos']);
        //VER SI EL MODULO YA EXISTE
        $query="SELECT count(*)
        FROM system_hc_submodulos 
        WHERE submodulo='".$datos_programa[0][hc_modulo]."';";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
				
        if ($result->fields[0]>0)
        {
					$query ="UPDATE system_hc_submodulos SET 
									descripcion='".$datos_programa[0][descripcion]."',
									sexo_id=".$sexo.",
									edad_max=".$_REQUEST['edadmax'].",
									edad_min=".$_REQUEST['edadmin']."
									WHERE submodulo='".$datos_programa[0][hc_modulo]."';";
				
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$dbconn->RollBackTrans();
							$this->frmError["MensajeError"]="UPDATE DATOS SYSTEM_HC_SUBMODULOS. ".$query.'--'.$dbconn->ErrorMsg();
							$this->uno=1;
							$this->FrmIngresarProgramas();
							return false;
					}
        }
        else
				{//
					$query ="INSERT INTO system_hc_submodulos
											(
													submodulo,
													descripcion,
													sexo_id,
													edad_max,
													edad_min
											)
											VALUES
											(
													'".$datos_programa[0][hc_modulo]."',
													'".$datos_programa[0][descripcion]."',
													".$sexo.",
													".$_REQUEST['edadmax'].",
													".$_REQUEST['edadmin']."
											);";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$dbconn->RollBackTrans();
							$this->frmError["MensajeError"]="VERIFICAR DATOS 4. ".$query.'--'.$dbconn->ErrorMsg();
							$this->uno=1;
							$this->FrmIngresarProgramas();
							return false;
					}
				}
        //
        $this->frmError["MensajeError"]="DATOS GUARDADOS.";
        $this->uno=1;
        $this->FrmIngresarProgramas();
        $dbconn->CommitTrans();
		
        return true;
		}
  }
    
	
	function GuardarDatosCu()
    {
		if($_REQUEST['unidad_funcional']=='-1')
		{
						$this->frmError["cu"]=1;
						$this->frmError["MensajeError"]="DEBE SELECCIONAR LA UNIDAD FUNCIONAL";
						$this->uno=1;
						$this->FrmAdministrarCU();
						return true;                
		}
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $usuario=UserGetUID();
        if($_REQUEST['VER']=='VER')
        {
            $query="SELECT a.programa_id, a.hc_modulo,
                            a.app_modulo, a.descripcion,
                            b.unidad_funcional,b.centro_utilidad,
                            c.descripcion as descu
                        FROM pyp_programas a, 
                            pyp_programas_unidad_funcional b,
                            unidades_funcionales c
                        WHERE b.unidad_funcional='".$_REQUEST['unidad_funcional']."'
                        AND a.programa_id=b.programa_id
                        AND b.unidad_funcional=c.unidad_funcional;";
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
            $this->FrmAdministrarCU($_REQUEST['unidad_funcional'],$vars);
            return true;
			}	

			//INSERTAR 
			//DATOS UNIDAD FUNCIONAL
			$datosCu=$_REQUEST['datosCu'];
			//DATOS DE LOS PROGRAMAS DE PYP
			$datos=$_REQUEST['datos'];
			$programas=false;
			for($i=0; $i<sizeof($datos);$i++)
			{
				if($_REQUEST[$datos[$i][programa_id]])
				{
					$programas=true;
					$i=sizeof($datos);
				}
			}
			if(!$programas)
			{
					$this->frmError["MensajeError"]="DEBE SELECCIONAR UN PROGRAMA";
					$this->uno=1;
					$this->FrmAdministrarCU();
					return true;                
			}

			//VERIFICAR SI EL REGISTRO YA EXISTE
			$query="SELECT count(*)
			FROM pyp_programas_unidad_funcional
			WHERE empresa_id='".$_SESSION['SEGURIDAD']['EMPRESA_ID']."'"
			//AND centro_utilidad='".$_SESSION['SEGURIDAD']['CENTRO_ID']."'
			." AND unidad_funcional='".$_REQUEST['unidad_funcional']."';";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			if ($result->fields[0]>0)
			{
				$query="DELETE
				FROM pyp_programas_unidad_funcional
				WHERE empresa_id='".$_SESSION['SEGURIDAD']['EMPRESA_ID']."'"
				//AND centro_utilidad='".$_SESSION['SEGURIDAD']['CENTRO_ID']."'
				." AND unidad_funcional='".$_REQUEST['unidad_funcional']."';";
				$result = $dbconn->Execute($query);
			}
			
			//FIN VERIFICAR SI EL REGISTRO YA EXISTE
			for($i=0; $i<sizeof($datos);$i++)
			{
				if($_REQUEST[$datos[$i][programa_id]])
				{
					$query ="INSERT INTO pyp_programas_unidad_funcional
					(
						empresa_id,
						centro_utilidad,
						unidad_funcional,
						programa_id,
						estado
					)
					VALUES
					(  
						'".$_SESSION['SEGURIDAD']['EMPRESA_ID']."',
						'".$datosCu[$i][centro_utilidad]."',
						'".$_REQUEST['unidad_funcional']."',
						".$_REQUEST[$datos[$i][programa_id]].",
						'1'
					);";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
								$dbconn->RollBackTrans();
								$this->frmError["MensajeError"]="VERIFICAR DATOS 5. ".$query.'--'.$dbconn->ErrorMsg();
								$this->uno=1;
								$this->FrmAdministrarCU();
								return true;
					}
					//
				}
			}
			$this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS.";
			$this->uno=1;
			$this->FrmAdministrarCU();
			$dbconn->CommitTrans();
			return true;
    }
	
}//fin de la clase
?>
