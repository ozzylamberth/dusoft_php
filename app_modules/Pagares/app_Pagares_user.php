<?php
  /**
  * $Id: app_Pagares_user.php,v 1.4 2005/09/28 23:14:39 darling Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * Proposito del Archivo: Manejo de pagares.
  */
  /**
  *Contiene los metodos para realizar las autorizaciones.
  */
  class app_Pagares_user extends classModulo
  {
    var $limit;
    var $conteo;
    /**
    * Constructor de la clase
    */
    function app_Pagares_user()
    {
			$this->limit=GetLimitBrowser();
			return true;
    }
    /**
    * Establece un link, al cual debe regresar, cuando sea llamado desde otro modulo
    *
    * @param String $link Cadena del link
    */
		function SetActionVolver($link)
		{
			SessionDelVar("ActionVolver_Pagares");
			SessionSetVar("ActionVolver_Pagares",$link);
		}
  	/**
  	*
  	*/
  	function main()
  	{
			unset($_SESSION['PAGARES']);
      
			list($dbconn) = GetDBconn();
			GLOBAL $ADODB_FETCH_MODE;
			$query = "SELECT a.documento_id, a.empresa_id, a.centro_utilidad,
								b.razon_social as descripcion1, c.descripcion as descripcion2
								FROM userpermisos_pagares as a, empresas as b, centros_utilidad as c
								WHERE a.usuario_id=".UserGetUID()." and a.empresa_id=b.empresa_id
								and a.empresa_id=c.empresa_id and a.centro_utilidad=c.centro_utilidad";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resulta = $dbconn->Execute($query);	
			while($data = $resulta->FetchRow())
			{
				$pagare[$data['descripcion1']][$data['descripcion2']]=$data;
			}

			$url[0]='app';
			$url[1]='Pagares';
			$url[2]='user';
			$url[3]='Principal';
			$url[4]='Pagare';
			$arreglo[0]='EMPRESA';
			$arreglo[1]='CENTRO UTILIDAD';

			$this->salida.= gui_theme_menu_acceso('PAGARES',$arreglo,$pagare,$url,ModuloGetURL('system','Menu'));
			return true;
	}
	
	function Principal()
	{
			if(empty($_SESSION['PAGARES']['EMPRESA']))
			{
						$_SESSION['PAGARES']['EMPRESA']= $_REQUEST['Pagare']['empresa_id'];
						$_SESSION['PAGARES']['NOMEMPRESA']= $_REQUEST['Pagare']['descripcion1'];						
						$_SESSION['PAGARES']['CENTROU']= $_REQUEST['Pagare']['centro_utilidad'];						
						$_SESSION['PAGARES']['NOMCENTROU']= $_REQUEST['Pagare']['descripcion2'];												
						$_SESSION['PAGARES']['DOCUMENTO']= $_REQUEST['Pagare']['documento_id'];						
			}
			
			unset($_SESSION['CUENTAS']);
			$_SESSION['CUENTAS']['RETORNO']['contenedor']='app';
			$_SESSION['CUENTAS']['RETORNO']['modulo']='Pagares';
			$_SESSION['CUENTAS']['RETORNO']['tipo']='user';
			$_SESSION['CUENTAS']['RETORNO']['metodo']='RetornoFacturacion';
			$_SESSION['CUENTAS']['RETORNO']['argumentos']=array();			
			$_SESSION['CUENTAS']['RETORNO']['empresa']=$_SESSION['PAGARES']['NOMEMPRESA'];			
			$_SESSION['CUENTAS']['RETORNO']['centro']=$_SESSION['PAGARES']['NOMCENTROU'];						
								
			$_SESSION['CUENTAS']['EMPRESA']=$_SESSION['PAGARES']['EMPRESA'];
			$_SESSION['CUENTAS']['CENTROUTILIDAD']=$_SESSION['PAGARES']['CENTROU'];
			//$_SESSION['CUENTAS']['FACTURACION']=$_REQUEST['facturacion'];
			$_SESSION['CUENTAS']['CU']=0;
			//$_SESSION['CUENTAS']['TIPOCUENTA']='01';
			$_SESSION['CUENTAS']['SWCUENTAS']='Cuentas';			
			
			$this->ReturnMetodoExterno('app','Facturacion','user','LlamadoExterno');
			return true;
	}
	
	
	function RetornoFacturacion()
	{
      if(!empty($_SESSION['CUENTAS']['RETORNO']['volver']))
			{
					unset($_SESSION['CUENTAS']);
					$this->main();
					return true;
			}
			
			unset($_SESSION['CUENTAS']);			
			unset($_SESSION['PAGARES']['PACIENTES']);
			//estas dos variables se necesitan para los totales de la cuenta
			$_SESSION['CUENTAS']['EMPRESA']=$_SESSION['PAGARES']['EMPRESA'];
			$_SESSION['CUENTAS']['CENTROUTILIDAD']=$_SESSION['PAGARES']['CENTROU'];		
				
			$_SESSION['PAGARES']['PACIENTES']['Cuenta']=$_REQUEST['Cuenta'];			
			$_SESSION['PAGARES']['PACIENTES']['TipoId']=$_REQUEST['TipoId'];			
			$_SESSION['PAGARES']['PACIENTES']['PacienteId']=$_REQUEST['PacienteId'];			
			$_SESSION['PAGARES']['PACIENTES']['Nivel']=$_REQUEST['Nivel'];					
			$_SESSION['PAGARES']['PACIENTES']['PlanId']=$_REQUEST['PlanId'];								
			$_SESSION['PAGARES']['PACIENTES']['Pieza']=$_REQUEST['Pieza'];								
			$_SESSION['PAGARES']['PACIENTES']['Cama']=$_REQUEST['Cama'];														
			$_SESSION['PAGARES']['PACIENTES']['FechaC']=$_REQUEST['FechaC'];														
			$_SESSION['PAGARES']['PACIENTES']['Ingreso']=$_REQUEST['Ingreso'];																				
			
			unset($_SESSION['PAGARES']['MODIFICAR']);
			unset($_SESSION['PAGARES']['MODIFICARR']);
			unset($_SESSION['PAGARES']['MODIFICARTMP']);			
						
			$this->FormaPrincipalPagares();
			return true;
	}
		
	/**
	* va a llamar  a la forma que  que pide los datos iniciales del pagare
	*/
	function LlamarPedirDatosPagare()
	{
			$this->FormaPedirDatosPagare();
			return true;
	}
		
	function GuardarDatosInicialesPagare()
	{
			unset($_SESSION['PAGARES']['DATOS']);
			if(!is_numeric($_REQUEST['valor']))
			{
					$this->frmError["valor"]=1;
					$this->frmError["MensajeError"]="DIGITE EL VALOR DEL PAGARE.";
					$this->FormaPedirDatosPagare();
					return true;			
			}
			if(empty($_REQUEST['vencimiento']))
			{
					$this->frmError["vencimiento"]=1;
					$this->frmError["MensajeError"]="ELIJA LA FECHA DE VENCIMIENTO.";
					$this->FormaPedirDatosPagare();
					return true;			
			}						
			if(empty($_REQUEST['pago']))
			{
					$this->frmError["pago"]=1;
					$this->frmError["MensajeError"]="ELIJA LA FORMA DE PAGO.";
					$this->FormaPedirDatosPagare();
					return true;			
			}
			if(!empty($_REQUEST['codigo']))
			{		//verificar si el codigo ya existe
					list($dbconn) = GetDBconn();	
					$query = "SELECT codigo_alterno 
                    FROM pagares 
                    WHERE codigo_alterno='".$_REQUEST['codigo']."'
										and empresa_id='".$_SESSION['PAGARES']['EMPRESA']."'";	
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error INSERT INTO pagares";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$this->fileError = __FILE__;
									$this->lineError = __LINE__;
									$dbconn->RollbackTrans();
									return false;
					}		
					if(!$result->EOF)											
					{
							$this->frmError["codigo"]=1;
							$this->frmError["MensajeError"]="EL CODIGO ALTERNO YA EXISTE.";
							$this->FormaPedirDatosPagare();
							return true;						
					}
					$result->Close();
			}			
			
			IncludeLib("funciones_facturacion");
			$saldo=SaldoCuentaPaciente($_SESSION['PAGARES']['PACIENTES']['Cuenta']);
			//el valor del pagare no puede ser mayor q el saldo de la cuenta
			if($_REQUEST['valor'] > $saldo)
			{
					$this->frmError["valor"]=1;
					$this->frmError["MensajeError"]="EL VALOR NO PUEDE SER MAYOR QUE EL SALDO DE LA CUENTA.";
					$this->FormaPedirDatosPagare();
					return true;						
			}
						
			$_SESSION['PAGARES']['DATOS']['pago']=$_REQUEST['pago'];
			$_SESSION['PAGARES']['DATOS']['vencimiento']=$_REQUEST['vencimiento'];
			$_SESSION['PAGARES']['DATOS']['valor']=$_REQUEST['valor'];
			$_SESSION['PAGARES']['DATOS']['codigo']=$_REQUEST['codigo'];
			$_SESSION['PAGARES']['DATOS']['observacion']=$_REQUEST['observacion'];			
			
			$this->FormaPedirDatosResponsable();
			return true;
	}
	
    function GuardarDatosResponsablesPagare()
    {
      $deudores = $this->PagaresDeudores($_SESSION['PAGARES']['DATOS']['prefijo'],$_SESSION['PAGARES']['DATOS']['numero'],$_SESSION['PAGARES']['EMPRESA']);
      
      if(empty($_REQUEST['paciente']))
			{		
				if(empty($_REQUEST['tipoId']))
				{
						$this->frmError["tipoId"]=1;
						$this->frmError["MensajeError"]="DEBE ELEJIR EL TIPO DE DOCUMENTO.";
						$this->FormaPedirDatosResponsable();
						return true;			
				}
				if(empty($_REQUEST['documento']))
				{
						$this->frmError["documento"]=1;
						$this->frmError["MensajeError"]="DIGITE EL NUMERO DEL DOCUMENTO.";
						$this->FormaPedirDatosResponsable();  
						return true;			
				}		
				if(empty($_REQUEST['nombre']))
				{
						$this->frmError["nombre"]=1;
						$this->frmError["MensajeError"]="DIGITE EL NOMBRE DEL RESPONSABLE.";
						$this->FormaPedirDatosResponsable();  
						return true;			
				}		
				if(empty($_REQUEST['pais']) OR empty($_REQUEST['dpto']) OR empty($_REQUEST['mpio']))
				{
						if(!$_REQUEST['pais']){ $this->frmError["pais"]=1; }
						if(!$_REQUEST['dpto']){ $this->frmError["dpto"]=1; }
						if(!$_REQUEST['mpio']){ $this->frmError["mpio"]=1; }
						$this->frmError["MensajeError"]="ESTOS DATOS SON OBLIGATORIOS";
						$this->FormaPedirDatosResponsable();  
						return true;
				}
				if(empty($_REQUEST['telefono']))
				{
						$this->frmError["telefono"]=1;
						$this->frmError["MensajeError"]="DIGITE EL NOMBRE DEL RESPONSABLE.";
						$this->FormaPedirDatosResponsable();  
						return true;			
				}					
				if(empty($_REQUEST['direccion']))
				{
						$this->frmError["direccion"]=1;
						$this->frmError["MensajeError"]="DIGITE EL NOMBRE DEL RESPONSABLE.";
						$this->FormaPedirDatosResponsable();  
						return true;			
				}								
				if(empty($_REQUEST['parentesco']))
				{
						$this->frmError["parentesco"]=1;
						$this->frmError["MensajeError"]="DEBE ELJIR EL PARENTESCO.";
						$this->FormaPedirDatosResponsable();  
						return true;			
				}		
				if(empty($_REQUEST['telefonoT']))
				{
						$this->frmError["telefonoT"]=1;
							$this->frmError["MensajeError"]="DEBE DIGITAR EL TELEFONO DEL TRABAJO.";
						$this->FormaPedirDatosResponsable();  
						return true;			
				}
				if(empty($_REQUEST['direccionT']))
				{
						$this->frmError["direccionT"]=1;
						$this->frmError["MensajeError"]="DEBE DIGITAR LA DIRECCION DEL TRABAJO.";
						$this->FormaPedirDatosResponsable();  
						return true;			
				}
				
        if(!is_numeric($_REQUEST['deudor']))
				{
					$this->frmError["deudor"]=1;
					$this->frmError["MensajeError"]="POR FAVOR SELECCIONE EL TIPO DE DEUDOR.";
					$this->FormaPedirDatosResponsable();  
					return true;			
				}
        else if($deudores > 0 && $_REQUEST['deudor'] == '1')
        {
					$this->frmError["deudor"]=1;
					$this->frmError["MensajeError"]="YA EXISTE UN DEUDOR PARA EL PAGARE.";
					$this->FormaPedirDatosResponsable();  
					return true;	
        }
				//0=>id 1=>descripcion
				$parentesco=explode('||',$_REQUEST['parentesco']);
				$_SESSION['PAGARES']['DATOS']['VECTOR'][$_REQUEST['tipoId'].$_REQUEST['documento']]=array('telefonoT'=>$_REQUEST['telefonoT'],'direccionT'=>$_REQUEST['direccionT'],'tipoId'=>$_REQUEST['tipoId'],'documento'=>$_REQUEST['documento'],'parentesco'=>"'".$parentesco[0]."'",'nomparentesco'=>$parentesco[1],'observacion'=>$_REQUEST['observacion'],'nombre'=>STRTOUPPER($_REQUEST['nombre']),'direccion'=>$_REQUEST['direccion'],'telefono'=>$_REQUEST['telefono'],'celular'=>$_REQUEST['celular'],'pais'=>$_REQUEST['pais'],'dpto'=>$_REQUEST['dpto'],'deudor'=>$_REQUEST['deudor'],'mpio'=>$_REQUEST['mpio'],'sw'=>0);
				$this->ValidarPagaresResponsables($_REQUEST['tipoId'],$_REQUEST['documento'],STRTOUPPER($_REQUEST['nombre']));
			}
			else
			{		//el responsable es el mismo paciente
        if(!is_numeric($_REQUEST['deudor']))
				{
					$this->frmError["deudor"]=1;
					$this->frmError["MensajeError"]="POR FAVOR SELECCIONE EL TIPO DE DEUDOR.";
					$this->FormaPedirDatosResponsable();  
					return true;			
				}		
        else if($deudores > 0 && $_REQUEST['deudor'] == '1')
        {
					$this->frmError["deudor"]=1;
					$this->frmError["MensajeError"]="YA EXISTE UN DEUDOR PARA EL PAGARE.";
					$this->FormaPedirDatosResponsable();  
					return true;	
        }
					list($dbconn) = GetDBconn();			
					$query = "SELECT b.tipo_id_paciente, b.paciente_id, c.residencia_direccion, c.residencia_telefono,
										c.tipo_pais_id, c.tipo_dpto_id, c.tipo_mpio_id, c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre,
										i.direccion as direccion_trabajo, i.telefono as telefono_trabajo
										FROM  cuentas a, ingresos b LEFT JOIN ingresos_empleadores as h on(b.ingreso=h.ingreso)
										LEFT JOIN empleadores as i on(h.tipo_id_empleador=i.tipo_id_empleador and h.empleador_id=i.empleador_id),
										pacientes c
										WHERE a.numerodecuenta=".$_SESSION['PAGARES']['PACIENTES']['Cuenta']."
										and a.ingreso=b.ingreso and b.paciente_id=c.paciente_id and b.tipo_id_paciente=c.tipo_id_paciente";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error INSERT INTO pagares";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$this->fileError = __FILE__;
									$this->lineError = __LINE__;
									$dbconn->RollbackTrans();
									return false;
					}							
					$_SESSION['PAGARES']['DATOS']['VECTOR'][$result->fields[0].$result->fields[1]]=array('telefonoT'=>$result->fields[9],'direccionT'=>$result->fields[8],'tipoId'=>$result->fields[0],'documento'=>$result->fields[1],'parentesco'=>'NULL','nomparentesco'=>'','observacion'=>$_REQUEST['observacion'],'deudor'=>$_REQUEST['deudor'],'nombre'=>$result->fields[7],'direccion'=>$result->fields[2],'telefono'=>$result->fields[3],'celular'=>'','pais'=>$result->fields[4],'dpto'=>$result->fields[5],'mpio'=>$result->fields[6],'sw'=>1);
					$result->Close();
					$this->ValidarPagaresResponsables($result->fields[0],$result->fields[1],$result->fields[7]);
			}	
			$_REQUEST='';			
			return true;
	}	
	
	function ValidarPagaresResponsables($tipo,$id,$nombre)
	{
			$pagares = $this->BuscarPagaresResponsable($tipo,$id);
			$_REQUEST='';	

			//$_SESSION['PAGARES']['MODIFICAR']=cuando es modificacion de datos del responsable no hay que validar
			//si tiene o no mas pagares si no se le cambio la identificacion			
			if(!empty($_SESSION['PAGARES']['MODIFICAR']) AND
				 ($_SESSION['PAGARES']['DATOS']['MODIFICAR']['tipo_id_tercero']==$tipo
				  AND $_SESSION['PAGARES']['DATOS']['MODIFICAR']['tercero_id']==$id))
			{   $pagares=''; }
			//validacion cuando es modificacion de un tmp
			elseif(!empty($_SESSION['PAGARES']['MODIFICARTMP']) AND
				 ($_SESSION['PAGARES']['MODIFICARTMP']['tipoId']==$tipo
				  AND $_SESSION['PAGARES']['MODIFICARTMP']['documento']==$id))
			{   $pagares='';  }
	
		
			if(empty($pagares))
			{		//no tiene mas pagares					
					if(!empty($_SESSION['PAGARES']['MODIFICAR']))			
					{ 	//modifica datos del responsable de un pagare q existe
							$this->ModificarResponsable();
					}
					elseif(!empty($_SESSION['PAGARES']['MODIFICARR']))
					{  	//aqui es caundo ya existe el pagare y crea un nuevo repsonsable	
						 $this->CrearResponsable();  
					}	
					elseif(!empty($_SESSION['PAGARES']['MODIFICARTMP']))				
					{		//cuando modifica un tmp
							//-------validacion de modificacion tmp cambio identificacion borra el anterior
							if($_SESSION['PAGARES']['MODIFICARTMP']['tipoId']!=$tipo
							   OR $_SESSION['PAGARES']['MODIFICARTMP']['documento']!=$id)
							{   unset($_SESSION['PAGARES']['DATOS']['VECTOR'][$_SESSION['PAGARES']['MODIFICARTMP']['tipoId']][$_SESSION['PAGARES']['MODIFICARTMP']['documento']]);  }
							//-------fin validacion																
							$this->ModificarResponsableTmp();  					
					}
					else					
					{  $this->FormaPedirDatosResponsable();  }					
					return true;
			}
			else
			{		//tiene pagares
					//va a la forma donde muestra los pagares para que decidan si dejan a ese responsable
					$this->FormaConfirmaResponsable($pagares,$tipo,$id,$nombre);
					return true;
			}
	}
	
	function FinValidarResponsable()
	{//echo "['PAGARES']['MODIFICARTMP']==>";print_r($_SESSION['PAGARES']['MODIFICARTMP']);
	//echo "<br><br>request==>";print_r($_REQUEST);
			//echo "<br><br>==>".$_SESSION['PAGARES']['MODIFICAR'];
			if(!empty($_REQUEST['Continuar']))
			{		//deja ese responsable
					if(!empty($_SESSION['PAGARES']['MODIFICARR']))			
					{  $this->CrearResponsable();  }
					elseif(!empty($_SESSION['PAGARES']['MODIFICAR']))
					{  	//modifica datos del responsable
						$this->ModificarResponsable();
					}	
					elseif(!empty($_SESSION['PAGARES']['MODIFICARTMP']))
					{  	//modifica datos del responsable tmp
							//-------validacion de modificacion tmp cambio identificacion borra el anterior
							if($_SESSION['PAGARES']['MODIFICARTMP']['tipoId']!=$_REQUEST['tipoId']
							   OR $_SESSION['PAGARES']['MODIFICARTMP']['documento']!=$_REQUEST['id'])
							{   unset($_SESSION['PAGARES']['DATOS']['VECTOR'][$_SESSION['PAGARES']['MODIFICARTMP']['tipoId']][$_SESSION['PAGARES']['MODIFICARTMP']['documento']]);  }
							//-------fin validacion							
							$this->ModificarResponsableTmp();
					}										
					else					
					{  $this->FormaPedirDatosResponsable();  }	
					return true;			
			}
			else
			{		//no acepta al responsable
					unset($_SESSION['PAGARES']['DATOS']['VECTOR'][$_REQUEST['tipoId'].$_REQUEST['id']]);
					if(!empty($_SESSION['PAGARES']['MODIFICARR']) OR !empty($_SESSION['PAGARES']['MODIFICARR']))			
					{  $this->FormaModificarResponsablesPagare();  }
					else					
					{  $this->FormaPedirDatosResponsable();  }	
					return true;			
			}
	}
	
	function EliminarTmpResponsablesPagare()
	{
			unset($_SESSION['PAGARES']['DATOS']['VECTOR'][$_REQUEST['tipoId'].$_REQUEST['documento']]);
			$this->frmError["MensajeError"]="EL RESPONSABLE FUE ELIMINADO.";
			$_REQUEST='';			
			$this->FormaPedirDatosResponsable();
			return true;	
	}

	function GuardarPagare()
	{
				list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();		
							
				//----------------NUMERACION-------------------------
				//cambiamos numeraciones.
				$va=$this->AsignarNumero($_SESSION['PAGARES']['DOCUMENTO'],&$dbconn);
				$numero=$va[numero];
				$prefijo=$va[prefijo];
				//----------------FIN NUMERACION-----------------------

				$f=explode('/',$_SESSION['PAGARES']['DATOS']['vencimiento']);
				$fec=$f[2].'-'.$f[1].'-'.$f[0];
				$query = "INSERT INTO pagares(empresa_id,
																			documento_id,
																			prefijo,
																			numero,
																			fecha_registro,
																			usuario_id,
																			numerodecuenta,
																			sw_estado,
																			valor,
																			vencimiento,
																			tipo_forma_pago_id,
																			codigo_alterno,
																			observacion)
									VALUES('".$_SESSION['PAGARES']['EMPRESA']."','".$_SESSION['PAGARES']['DOCUMENTO']."','$prefijo',$numero,'now()',".UserGetUID().",".$_SESSION['PAGARES']['PACIENTES']['Cuenta'].",'1',".$_SESSION['PAGARES']['DATOS']['valor'].",'".$fec."',".$_SESSION['PAGARES']['DATOS']['pago'].",'".$_SESSION['PAGARES']['DATOS']['codigo']."','".$_SESSION['PAGARES']['DATOS']['observacion']."')";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error INSERT INTO pagares";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;
								$dbconn->RollbackTrans();
								return false;
				}			
				//responsables
				foreach($_SESSION['PAGARES']['DATOS']['VECTOR'] as $k => $v)
				{		//crea o actualiza el tercero
						$this->CrearTercero($v,&$dbconn);
						if(!$v['deudor']) $v['deudor'] = "0";
						$query = "INSERT INTO pagares_responsables(tercero_id,
																												tipo_id_tercero,
																												empresa_id,
																												prefijo,
																												numero,
																												tipo_parentesco_id,
																												observacion,
																												fecha_registro,
																												usuario_id,
																												sw_paciente,
																												
																												direccion_trabajo,
																												telefono_trabajo,
																												nombre,
																												direccion_residencia,
																												telefono_residencia,
																												tipo_pais_id,
																												tipo_dpto_id,
																												tipo_mpio_id,
																												celular,
                                                        sw_deudor)
											VALUES('".$v['documento']."','".$v['tipoId']."','".$_SESSION['PAGARES']['EMPRESA']."','$prefijo',$numero,".$v['parentesco'].",'".$v['observacion']."','now()',".UserGetUID().",'".$v['sw']."',
											'".$v['direccionT']."','".$v['telefonoT']."','".$v['nombre']."','".$v['direccion']."','".$v['telefono']."','".$v['pais']."','".$v['dpto']."','".$v['mpio']."','".$v['celular']."','".$v['deudor']."')";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error INTO pagares_responsables";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$this->fileError = __FILE__;
										$this->lineError = __LINE__;
										$dbconn->RollbackTrans();
										return false;
						}					
				}				
				$dbconn->CommitTrans();
				$this->frmError["MensajeError"]="SE GENERO EL PAGARE.";
				$this->FormaPrincipalPagares();
				return true;
	}
	
	function CrearTercero($v,&$dbconn)
	{
			//verifica q si exista el tercero si no lo crea o actualiza
			$query = "SELECT nombre_tercero
								FROM terceros
								WHERE tipo_id_tercero='".$v['tipoId']."' and tercero_id='".$v['documento']."'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->fileError = __FILE__;
					$this->lineError = __LINE__;	
					$dbconn->RollbackTrans();							
					return false;
			}
			//ya existe en tercero
			if(!$result->EOF)		
			{		//actualiza terceros
					$query = "UPDATE terceros SET nombre_tercero='".$v['nombre']."',
																				tipo_pais_id='".$v['pais']."',
																				tipo_dpto_id='".$v['dpto']."',
																				tipo_mpio_id='".$v['mpio']."',	
																				direccion='".$v['direccion']."',
																				telefono='".$v['telefono']."'																																													
										WHERE tipo_id_tercero='".$v['tipoId']."' and tercero_id='".$v['documento']."'";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error UPDATE terceros";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$this->fileError = __FILE__;
							$this->lineError = __LINE__;	
							$dbconn->RollbackTrans();							
							return false;
					}								
			}				
			else
			{		//no existe
		echo			$query1 = "INSERT INTO terceros(tipo_id_tercero,tercero_id,nombre_tercero,tipo_pais_id,tipo_dpto_id,
										tipo_mpio_id,direccion,telefono,fax,email,celular,sw_persona_juridica,cal_cli,usuario_id,fecha_registro,busca_persona)
										VALUES('".$v['tipoId']."','".$v['documento']."','".$v['nombre']."','".$v['pais']."','".$v['dpto']."','".$v['mpio']."','".$v['direccion']."','".$v['telefono']."','','','".$v['celular']."','1','0',".UserGetUID().",'now()','');";
					$dbconn->Execute($query1);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error NTO terceros";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$this->fileError = __FILE__;
							$this->lineError = __LINE__;
							$dbconn->RollbackTrans();								
							return false;
					}							
			}	
			return true;	
	}
	
	function AsignarNumero($prefijo,&$dbconn)
	{			
			if((!empty($prefijo)))
			{
					$sql="LOCK TABLE documentos IN ROW EXCLUSIVE MODE";
					$result = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0) {
						die(MsgOut("Error al iniciar la transaccion","Error DB : " . $dbconn->ErrorMsg()));
						$this->GuardarNumero(false,&$dbconn);
						return false;
					}
					//actualizacion contado
					$sql="UPDATE documentos set numeracion=numeracion + 1
								WHERE  documento_id='$prefijo' and empresa_id='".$_SESSION['PAGARES']['EMPRESA']."'";
					$result = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0) {
						die(MsgOut("Error al actualizar numeracion","Error DB : " . $dbconn->ErrorMsg()));
						$this->GuardarNumero(false,&$dbconn);
						return false;
					}
					if($dbconn->Affected_Rows() == 0){
						die(MsgOut("Error al actualizar numeracion","El prefijo '$prefijo' no existe."));
						$this->GuardarNumero(false,&$dbconn);
						return false;
					}

					//sacamos el numero de la factura de contado.
					$sql="SELECT numeracion,prefijo FROM documentos
								WHERE documento_id='$prefijo'  and empresa_id='".$_SESSION['PAGARES']['EMPRESA']."'";
					$results = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0) {
						die(MsgOut("Error al traer numeracion","Error DB : " . $dbconn->ErrorMsg()));
						$this->GuardarNumero(false,&$dbconn);
						return false;
					}

					if($results->EOF) {
						die(MsgOut("Error al actualizar numeracion","El tipo de numeracion '$prefijo' no existe."));
						$this->GuardarNumero(false,&$dbconn);
						return false;
					}
					list($numerodoc['numero'],$numerodoc['prefijo'])=$results->fetchRow();

					return $numerodoc;
			}

			die(MsgOut("Error al actualizar numeracion","El documento &nbsp;['$prefijo']&nbsp; esta vacio."));
			return false;
	}

	function LlamarAnulacionPagare()
	{
			$this->FormaAnularPagare($_REQUEST['prefijo'],$_REQUEST['numero'],$_REQUEST['valor'],$_REQUEST['empresa']);
			return true;
	}
	
	function AnularPagare()
	{
			if(empty($_REQUEST['observacion']))
			{
					$this->frmError["observacion"]=1;
					$this->frmError["MensajeError"]="DEBE DIGITAR LA RAZON DE LA ANULACION DEL PAGARE.";
					$this->FormaAnularPagare($_REQUEST['prefijo'],$_REQUEST['numero'],$_REQUEST['valor'],$_REQUEST['empresa']);
					return true;
			}
			
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$query = "UPDATE pagares SET sw_estado='3' WHERE prefijo='".$_REQUEST['prefijo']."' and numero=".$_REQUEST['numero']." and empresa_id='".$_REQUEST['empresa']."'";	
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->fileError = __FILE__;
				$this->lineError = __LINE__;			
				$dbconn->RollbackTrans();
				return false;
			}	
			
			$query = "INSERT INTO auditoria_anulacion_pagares(empresa_id,
																												prefijo, numero,
																												observacion,
																												usuario_id, fecha_registro)
								VALUES('".$_REQUEST['empresa']."','".$_REQUEST['prefijo']."',".$_REQUEST['numero'].",'".$_REQUEST['observacion']."',".UserGetUID().",'now()')";	
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->fileError = __FILE__;
				$this->lineError = __LINE__;			
				$dbconn->RollbackTrans();
				return false;
			}	
					
			$dbconn->CommitTrans();
			$this->frmError["MensajeError"]="SE ANULO EL PAGARE.";
			$this->FormaPrincipalPagares();
			return true;			
	}
	
	function TiposPagos()
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT tipo_forma_pago_id,descripcion FROM tipos_formas_pago";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}

			$result->Close();
			return $var;	
	}
	
	function TiposTerceros()
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT tipo_id_tercero,descripcion FROM tipo_id_terceros ORDER BY indice_de_orden";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}

			$result->Close();
			return $var;	
	}		
	
	function TiposParentescos()
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT tipo_parentesco_id,descripcion FROM tipos_parentescos";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}

			$result->Close();
			return $var;	
	}	
	
	/**
	* Busca el nombre del pais
	* @access public
	* @return array
	* @param int codigo del pais
	*/
	function nombre_pais($Pais)
	{
					list($dbconn) = GetDBconn();
					$query = "SELECT pais FROM tipo_pais WHERE tipo_pais_id='$Pais'";
					$result = $dbconn->Execute($query);

							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}
							else{

									if($result->EOF){
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
											return false;
									}
							}
							$result->Close();
			return $result->fields[0];
	}

	/**
	* Busca el nombre del departamento
	* @access public
	* @return array
	* @param int codigo del pais
	* @param int codigo del departamento
	*/
	function nombre_dpto($Pais,$Dpto)
	{
					list($dbconn) = GetDBconn();
					$query = "SELECT * FROM tipo_dptos WHERE tipo_pais_id='$Pais' AND tipo_dpto_id='$Dpto'";
					$result = $dbconn->Execute($query);

							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}
							else{

									if($result->EOF){
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
											return false;
									}
							}
							$result->Close();
			return $result->fields[2];
	}

	/**
	* Busca el nombre de la ciudad o municipio
	* @access public
	* @return array
	* @param int codigo del pais
		* @param int codigo del departamento
	* @param int codigo del municipio
	*/
	function nombre_ciudad($Pais,$Dpto,$Mpio)
	{
					list($dbconn) = GetDBconn();
					$query = "SELECT * FROM tipo_mpios WHERE tipo_pais_id='$Pais' AND tipo_dpto_id='$Dpto' AND tipo_mpio_id='$Mpio'";
					$result = $dbconn->Execute($query);

							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}
							else{

									if($result->EOF){
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
											return false;
									}
							}
							$result->Close();
			return $result->fields[3];
	}	
	
	function BuscarPagaresCuenta($cuenta)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT a.valor, a.vencimiento, a.prefijo, a.numero, d.descripcion as formapago, a.observacion,
								b.nombre, a.fecha_registro, a.empresa_id, d.tipo_forma_pago_id, a.empresa_id, a.codigo_alterno
								FROM pagares a, system_usuarios b, tipos_formas_pago d
								WHERE a.numerodecuenta=".$cuenta." and a.sw_estado='1'
								and a.tipo_forma_pago_id=d.tipo_forma_pago_id and a.usuario_id=b.usuario_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}

			$result->Close();
			return $var;	
	}
	
	function BuscarPagaresPaciente($cuenta)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT a.valor, a.vencimiento, a.prefijo, a.numero, d.descripcion as formapago,
								b.nombre, a.fecha_registro, a.tipo_forma_pago_id
								FROM pagares a, system_usuarios b, tipos_formas_pago d
								WHERE a.numerodecuenta!=".$cuenta." and a.tipo_forma_pago_id=d.tipo_forma_pago_id
								and a.usuario_id=b.usuario_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}

			$result->Close();
			return $var;		
	}
	
	function BuscarPagaresResponsable($tipo,$id)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT a.observacion, a.sw_paciente, b.prefijo, b.numero, b.fecha_registro,
								d.descripcion as formapago, b.vencimiento, b.valor, f.tipo_id_paciente, f.paciente_id,
								g.primer_nombre||' '||g.segundo_nombre||' '||g.primer_apellido||' '||g.segundo_apellido as nombre
								FROM pagares_responsables a, pagares b, tipos_formas_pago d, cuentas as e, ingresos as f, pacientes  g
								WHERE a.tipo_id_tercero='$tipo' and a.tercero_id='$id'
								and a.empresa_id=b.empresa_id and a.prefijo=b.prefijo  and a.numero=b.numero
								and b.sw_estado='1' and b.tipo_forma_pago_id=d.tipo_forma_pago_id
								and b.numerodecuenta=e.numerodecuenta and e.ingreso=f.ingreso
								and f.paciente_id=g.paciente_id and f.tipo_id_paciente=g.tipo_id_paciente";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}
			$result->Close();
			return $var;			
	}
	
	function LlamarModificarPagare()
	{
			$this->FormaModificarPagare($_REQUEST['vector']);
			return true;
	}
	
	function ModificarDatosPagare()
	{
			unset($_SESSION['PAGARES']['DATOS']);
			unset($_SESSION['PAGARES']['MODIFICAR']);
			unset($_SESSION['PAGARES']['MODIFICARR']);			
			$_SESSION['PAGARES']['MODIFICARR']=TRUE;	
			
			if(empty($_REQUEST['valor']))
			{
					$this->frmError["valor"]=1;
					$this->frmError["MensajeError"]="DIGITE EL VALOR DEL PAGARE.";
					$this->FormaModificarPagare($_REQUEST['vector']);
					return true;			
			}
			if(empty($_REQUEST['vencimiento']))
			{
					$this->frmError["vencimiento"]=1;
					$this->frmError["MensajeError"]="ELIJA LA FECHA DE VENCIMIENTO.";
					$this->FormaModificarPagare($_REQUEST['vector']);
					return true;			
			}						
			if(empty($_REQUEST['pago']))
			{
					$this->frmError["pago"]=1;
					$this->frmError["MensajeError"]="ELIJA LA FORMA DE PAGO.";
					$this->FormaModificarPagare($_REQUEST['vector']);
					return true;			
			}
			if(!empty($_REQUEST['codigo']) AND $_REQUEST['codigo']!=$_REQUEST['vector']['codigo_alterno'])
			{		//verificar si el codigo ya existe
					list($dbconn) = GetDBconn();	
					$query = "SELECT codigo_alterno FROM pagares WHERE codigo_alterno='".$_REQUEST['codigo']."'
										and empresa_id='".$_SESSION['PAGARES']['EMPRESA']."'";	
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error INSERT INTO pagares";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$this->fileError = __FILE__;
									$this->lineError = __LINE__;
									$dbconn->RollbackTrans();
									return false;
					}		
					if(!$result->EOF)											
					{
							$this->frmError["codigo"]=1;
							$this->frmError["MensajeError"]="EL CODIGO ALTERNO YA EXISTE.";
							$this->FormaModificarPagare($_REQUEST['vector']);
							return true;						
					}
					$result->Close();
			}			
			
			IncludeLib("funciones_facturacion");
			$saldo=SaldoCuentaPaciente($_SESSION['PAGARES']['PACIENTES']['Cuenta']);
			//el valor del pagare no puede ser mayor q el saldo de la cuenta
			//al saldo le sumo lo del pagare inicial para saber en realidad cual es el saldo de la cuenta
			if($_REQUEST['valor'] > ($saldo + $_REQUEST['vector']['valor']))
			{
					$this->frmError["valor"]=1;
					$this->frmError["MensajeError"]="EL VALOR NO PUEDE SER MAYOR QUE EL SALDO DE LA CUENTA.";
					$this->FormaModificarPagare($_REQUEST['vector']);
					return true;						
			}
						
			$_SESSION['PAGARES']['DATOS']['pago']=$_REQUEST['pago'];
			$_SESSION['PAGARES']['DATOS']['vencimiento']=$_REQUEST['vencimiento'];
			$_SESSION['PAGARES']['DATOS']['valor']=$_REQUEST['valor'];
			$_SESSION['PAGARES']['DATOS']['codigo']=$_REQUEST['codigo'];
			$_SESSION['PAGARES']['DATOS']['prefijo']=$_REQUEST['vector']['prefijo'];			
			$_SESSION['PAGARES']['DATOS']['numero']=$_REQUEST['vector']['numero'];
			$_SESSION['PAGARES']['DATOS']['observacion']=$_REQUEST['observacion'];				
			if($_REQUEST['vector']['valor'] !=$_REQUEST['valor'] OR $_REQUEST['vector']['codigo_alterno'] !=$_REQUEST['codigo']
				OR $_REQUEST['vector']['vencimiento'] !=$_REQUEST['vencimiento'] OR $_REQUEST['vector']['tipo_forma_pago_id'] !=$_REQUEST['pago']
				OR $_REQUEST['vector']['observacion'] !=$_REQUEST['observacion'] )	
			{  
					//modifcar el pagare
					$vector=$_REQUEST['vector'];
					list($dbconn) = GetDBconn();
					$dbconn->BeginTrans();
					$query = "UPDATE pagares SET valor=".$_REQUEST['valor'].",
																			vencimiento='".$_REQUEST['vencimiento']."',
																			tipo_forma_pago_id=".$_REQUEST['pago'].",
																			codigo_alterno='".$_REQUEST['codigo']."',
																			observacion='".$_REQUEST['observacion']."'
										WHERE prefijo='".$_SESSION['PAGARES']['DATOS']['prefijo']."'
										and numero=".$_SESSION['PAGARES']['DATOS']['numero']." and empresa_id='".$_SESSION['PAGARES']['EMPRESA']."'";	
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {		
									$this->error = "Error INTO pagares";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$this->fileError = __FILE__;
									$this->lineError = __LINE__;
									$dbconn->RollbackTrans();
									return false;
					}			
						
					$query = "INSERT INTO auditoria_modificacion_pagares (empresa_id, prefijo, 
																																numero, valor_ant, valor_act,
																																vencimiento_ant, vencimiento_act, 
																																tipo_forma_pago_id_act,tipo_forma_pago_id_ant,
																																fecha_registro_cambio, usuario_id_cambio) 
										values('".$_SESSION['PAGARES']['EMPRESA']."','".$_SESSION['PAGARES']['DATOS']['prefijo']."',".$_SESSION['PAGARES']['DATOS']['numero'].",
										".$vector['valor'].",".$_REQUEST['valor'].",'".$vector['vencimiento']."','".$_REQUEST['vencimiento']."',".$_REQUEST['pago'].",".$vector['tipo_forma_pago_id'].", 'now()', ".UserGetUID().");";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {		
									$this->error = "Error INTO pagares_responsables";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$this->fileError = __FILE__;
									$this->lineError = __LINE__;
									$dbconn->RollbackTrans();
									return false;
					}
					$dbconn->CommitTrans();					
					//fin modificar pagare
			}
			$_SESSION['PAGARES']['DATOS']['RESPONSABLES']=$this->BuscarResponsablesPagare($_SESSION['PAGARES']['DATOS']['prefijo'],$_SESSION['PAGARES']['DATOS']['numero']);
			
			$this->FormaModificarResponsablesPagare($_REQUEST['vector']);
			return true;	
	}	

	function LlamarFormaModificarResponsablesPagare()
	{
			$_SESSION['PAGARES']['MODIFICARR']=TRUE;	
			$_SESSION['PAGARES']['DATOS']['pago']=$_REQUEST['vector']['tipo_forma_pago_id'];
			$_SESSION['PAGARES']['DATOS']['vencimiento']=$_REQUEST['vector']['vencimiento'];
			$_SESSION['PAGARES']['DATOS']['valor']=$_REQUEST['vector']['valor'];
			$_SESSION['PAGARES']['DATOS']['codigo']=$_REQUEST['vector']['codigo_alterno'];
			$_SESSION['PAGARES']['DATOS']['prefijo']=$_REQUEST['vector']['prefijo'];			
			$_SESSION['PAGARES']['DATOS']['numero']=$_REQUEST['vector']['numero'];			
			$_SESSION['PAGARES']['DATOS']['RESPONSABLES']=$this->BuscarResponsablesPagare($_SESSION['PAGARES']['DATOS']['prefijo'],$_SESSION['PAGARES']['DATOS']['numero']);
			
			$this->FormaModificarResponsablesPagare();
			return true;	
	}
		
	function BuscarResponsablesPagare($prefijo,$numero)
	{
			list($dbconn) = GetDBconn();
/*
			$query = "SELECT a.observacion, a.sw_paciente, b.prefijo, b.numero, b.fecha_registro, a.usuario_id,
								a.tipo_id_tercero, a.tercero_id, c.nombre_tercero, d.descripcion, d.tipo_parentesco_id,
								c.tipo_pais_id, c.tipo_dpto_id, c.tipo_mpio_id, c.direccion, c.telefono, c.celular
								FROM pagares_responsables a, pagares b, terceros c, tipos_parentescos d
								WHERE b.prefijo='$prefijo' and b.numero=$numero and b.empresa_id='".$_SESSION['PAGARES']['EMPRESA']."'
								and b.prefijo=a.prefijo and b.numero=a.numero and b.empresa_id=a.empresa_id
								and c.tipo_id_tercero=a.tipo_id_tercero and c.tercero_id=a.tercero_id
								and a.tipo_parentesco_id=d.tipo_parentesco_id";
*/			
			$query = "SELECT  a.observacion, 
                        a.sw_paciente, 
                        b.prefijo, 
                        b.numero, 
                        b.fecha_registro, 
                        a.usuario_id,
                        a.tipo_id_tercero, 
                        a.tercero_id, 
                        a.nombre,
                        d.descripcion, 
                        d.tipo_parentesco_id,
                        a.tipo_pais_id, 
                        a.tipo_dpto_id, 
                        a.tipo_mpio_id, 
                        a.direccion_residencia, 
                        a.telefono_residencia,
                        a.direccion_trabajo, 
                        a.telefono_trabajo,
                        a.sw_deudor
								FROM    pagares_responsables a 
                        LEFT JOIN tipos_parentescos d 
                        ON(a.tipo_parentesco_id=d.tipo_parentesco_id),
                        pagares b
								WHERE   b.prefijo='$prefijo' 
                and     b.numero=$numero 
                and     b.empresa_id='".$_SESSION['PAGARES']['EMPRESA']."'
								and     b.prefijo=a.prefijo 
                and     b.numero=a.numero 
                and     b.empresa_id=a.empresa_id ";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}
			$result->Close();
			return $var;			
	}
	
	function CrearResponsable()
	{
				list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();	
				foreach($_SESSION['PAGARES']['DATOS']['VECTOR'] as $k => $v)
				{		//crea o actualiza el tercero
						$this->CrearTercero($v,&$dbconn);
												
						$query = "INSERT INTO pagares_responsables(tercero_id,
																												tipo_id_tercero,
																												empresa_id,
																												prefijo,
																												numero,
																												tipo_parentesco_id,
																												observacion,
																												fecha_registro,
																												usuario_id,
																												sw_paciente,
																																																								
																												direccion_trabajo,
																												telefono_trabajo,
																												nombre,
																												direccion_residencia,
																												telefono_residencia,
																												tipo_pais_id,
																												tipo_dpto_id,
																												tipo_mpio_id,
																												celular,
                                                        sw_deudor
                                                      )
											VALUES('".$v['documento']."','".$v['tipoId']."','".$_SESSION['PAGARES']['EMPRESA']."','".$_SESSION['PAGARES']['DATOS']['prefijo']."',".$_SESSION['PAGARES']['DATOS']['numero'].",".$v['parentesco'].",'".$v['observacion']."','now()',".UserGetUID().",'".$v['sw']."',
											'".$v['direccionT']."','".$v['telefonoT']."','".$v['nombre']."','".$v['direccion']."','".$v['telefono']."','".$v['pais']."','".$v['dpto']."','".$v['mpio']."','".$v['celular']."',
                      '".$v['deudor']."')";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {		
										$this->error = "Error INTO pagares_responsables";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$this->fileError = __FILE__;
										$this->lineError = __LINE__;
										$dbconn->RollbackTrans();	
										return false;
						}					
				}			
				$dbconn->CommitTrans();
				$this->frmError["MensajeError"]="SE CREO EL NUEVO RESPONSABLE.";
				unset($_SESSION['PAGARES']['DATOS']['RESPONSABLES']);
				unset($_SESSION['PAGARES']['DATOS']['VECTOR']);
				unset($_SESSION['PAGARES']['MODIFICARR']);	
				unset($_SESSION['PAGARES']['DATOS']['MODIFICARR']);	
				$_SESSION['PAGARES']['DATOS']['RESPONSABLES']=$this->BuscarResponsablesPagare($_SESSION['PAGARES']['DATOS']['prefijo'],$_SESSION['PAGARES']['DATOS']['numero']);

				$this->FormaModificarResponsablesPagare();
				return true;						
	}
		
	function LlamarFormaEliminarResponsable()
	{
			if(sizeof($_SESSION['PAGARES']['DATOS']['RESPONSABLES'])==1)
			{
					$this->frmError["MensajeError"]="EL PAGARE SOLO TIENE UN RESPONSABLE, PARA ELIMINAR ESTE DEBE CREAR EL NUEVO RESPONSABLE.";
					$this->FormaModificarResponsablesPagare();
					return true;			
			}
			
			$this->FormaEliminarResponsable($_REQUEST['vector']);
			return true;
	}
	
	function EliminarResponsable()
	{	
			if(empty($_REQUEST['observacion']))
			{
					$this->frmError["observacion"]=1;
					$this->frmError["MensajeError"]="DEBE DIGITAR EL MOTIVO DE LA ELIMINACION DEL RESPONSABLE.";
					$this->FormaEliminarResponsable($_REQUEST['vector']);
					return true;	
			}
							
			list($dbconn) = GetDBconn();	
			$dbconn->BeginTrans();
			$query = "DELETE FROM pagares_responsables 
								WHERE prefijo='".$_SESSION['PAGARES']['DATOS']['prefijo']."' and numero=".$_SESSION['PAGARES']['DATOS']['numero']." 
								and empresa_id='".$_SESSION['PAGARES']['EMPRESA']."' 
								and tipo_id_tercero='".$_REQUEST['vector']['tipo_id_tercero']."' and tercero_id='".$_REQUEST['vector']['tercero_id']."';";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->fileError = __FILE__;
				$this->lineError = __LINE__;				
				$dbconn->RollbackTrans();
				return false;
			}	
							
			$query = "INSERT INTO auditoria_responsables_pagares (auditoria_responsable_pagare_id,
																														empresa_id, prefijo,numero, 
																														tipo_id_tercero,
																														tercero_id,
																														observacion,
																														tipo_parentesco_id,
																														fecha_registro,
																														usuario_id,
																														sw_paciente,	
																														fecha_registro_cambio,
																														usuario_id_cambio,	
																														motivo,
																														sw_actualizacion) 
								values(nextval('auditoria_responsables_pagare_auditoria_responsable_pagare__seq'),'".$_SESSION['PAGARES']['EMPRESA']."','".$_SESSION['PAGARES']['DATOS']['prefijo']."',".$_SESSION['PAGARES']['DATOS']['numero'].",
								'".$_REQUEST['vector']['tipo_id_tercero']."','".$_REQUEST['vector']['tercero_id']."','".$_REQUEST['vector']['observacion']."','".$_REQUEST['vector']['tipo_parentesco_id']."',
								'".$_REQUEST['vector']['fecha_registro']."',".$_REQUEST['vector']['usuario_id'].",
								'".$_REQUEST['vector']['sw_paciente']."','now()', ".UserGetUID().",'".$_REQUEST['observacion']."','0');";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {		
							$this->error = "Error INSERT INTO auditoria_responsables_pagares";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$this->fileError = __FILE__;
							$this->lineError = __LINE__;
							$dbconn->RollbackTrans();
							return false;
			}			
			$dbconn->CommitTrans();
			unset($_SESSION['PAGARES']['DATOS']['RESPONSABLES']);
			$_SESSION['PAGARES']['DATOS']['RESPONSABLES']=$this->BuscarResponsablesPagare($_SESSION['PAGARES']['DATOS']['prefijo'],$_SESSION['PAGARES']['DATOS']['numero']);
			
			$this->frmError["MensajeError"]="SE ELIMINO EL RESPONSABLE.";
			$this->FormaModificarResponsablesPagare();
			return true;						
	}
	
	function LlamarModificarDatosResponsables()
	{				
				$_REQUEST['tipoId'] = $_REQUEST['datos']['tipo_id_tercero'];
				$_REQUEST['documento'] = $_REQUEST['datos']['tercero_id'];
				$_REQUEST['nombre'] = $_REQUEST['datos']['nombre'];
				$_REQUEST['telefono'] = $_REQUEST['datos']['telefono_residencia'];								
				$_REQUEST['celular'] = $_REQUEST['datos']['celular'];
				$_REQUEST['pais'] = $_REQUEST['datos']['tipo_pais_id'];
				$_REQUEST['dpto'] = $_REQUEST['datos']['tipo_dpto_id'];				
				$_REQUEST['mpio'] = $_REQUEST['datos']['tipo_mpio_id'];
				$_REQUEST['direccion'] = $_REQUEST['datos']['direccion_residencia'];			
				$_REQUEST['parentesco'] = $_REQUEST['datos']['tipo_parentesco_id']."||".$_REQUEST['datos']['descripcion'];
				$_REQUEST['observacion'] = $_REQUEST['datos']['observacion'];		
				$_REQUEST['paciente'] = $_REQUEST['datos']['sw_paciente'];	
				$_REQUEST['deudor'] = $_REQUEST['datos']['sw_deudor'];	
				
				$_REQUEST['direccionT'] = $_REQUEST['datos']['direccion_trabajo'];					
				$_REQUEST['telefonoT'] = $_REQUEST['datos']['telefono_trabajo'];	
								
				unset($_SESSION['PAGARES']['DATOS']['MODIFICAR']);
				unset($_SESSION['PAGARES']['DATOS']['VECTOR']);
				$_SESSION['PAGARES']['DATOS']['MODIFICAR']=$_REQUEST['datos'];											
				$_SESSION['PAGARES']['MODIFICAR']=TRUE;	
				$this->FormaPedirDatosResponsable();
				return true;
	}
	
	function ModificarResponsable()
	{
			//aqui $_SESSION['PAGARES']['DATOS']['VECTOR'] estan los datos del responsable nuevo
			$vector=$_SESSION['PAGARES']['DATOS']['MODIFICAR'];//datos responsables anteriores
			list($dbconn) = GetDBconn();	
			$dbconn->BeginTrans();
			//responsables
			foreach($_SESSION['PAGARES']['DATOS']['VECTOR'] as $k => $v)
			{		
					$query = "DELETE FROM pagares_responsables 
										WHERE prefijo='".$_SESSION['PAGARES']['DATOS']['prefijo']."' and numero=".$_SESSION['PAGARES']['DATOS']['numero']." 
										and empresa_id='".$_SESSION['PAGARES']['EMPRESA']."' 
										and tipo_id_tercero='".$vector['tipo_id_tercero']."' and tercero_id='".$vector['tercero_id']."';";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->fileError = __FILE__;
						$this->lineError = __LINE__;				
						$dbconn->RollbackTrans();
						return false;
					}	
						
					$this->CrearTercero($v,&$dbconn);			
					$query = "INSERT INTO pagares_responsables
                    (
                      tercero_id,
                      tipo_id_tercero,
                      empresa_id,
                      prefijo,
                      numero,
                      tipo_parentesco_id,
                      observacion,
                      fecha_registro,
                      usuario_id,
                      sw_paciente,
                      direccion_trabajo,
                      telefono_trabajo,
                      nombre,
                      direccion_residencia,
                      telefono_residencia,
                      tipo_pais_id,
                      tipo_dpto_id,
                      tipo_mpio_id,
                      celular,
                      sw_deudor
                    )
										VALUES('".$v['documento']."','".$v['tipoId']."','".$_SESSION['PAGARES']['EMPRESA']."','".$_SESSION['PAGARES']['DATOS']['prefijo']."',".$_SESSION['PAGARES']['DATOS']['numero'].",".$v['parentesco'].",'".$v['observacion']."','now()',".UserGetUID().",'".$v['sw']."',
										'".$v['direccionT']."','".$v['telefonoT']."','".$v['nombre']."','".$v['direccion']."','".$v['telefono']."','".$v['pais']."','".$v['dpto']."','".$v['mpio']."','".$v['celular']."',
                    '".$v['deudor']."')";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error INTO pagares_responsables";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$this->fileError = __FILE__;
									$this->lineError = __LINE__;
									$dbconn->RollbackTrans();
									return false;
					}			
			}	
									
			$query = "INSERT INTO auditoria_responsables_pagares (auditoria_responsable_pagare_id,
																														empresa_id, prefijo,numero, 
																														tipo_id_tercero,
																														tercero_id,
																														observacion,
																														tipo_parentesco_id,
																														fecha_registro,
																														usuario_id,
																														sw_paciente,	
																														fecha_registro_cambio,
																														usuario_id_cambio,	
																														motivo,
																														sw_actualizacion) 
								values(nextval('auditoria_responsables_pagare_auditoria_responsable_pagare__seq'),'".$_SESSION['PAGARES']['EMPRESA']."','".$_SESSION['PAGARES']['DATOS']['prefijo']."',".$_SESSION['PAGARES']['DATOS']['numero'].",
								'".$vector['tipo_id_tercero']."','".$vector['tercero_id']."','".$vector['observacion']."','".$vector['tipo_parentesco_id']."',
								'".$vector['fecha_registro']."',".$vector['usuario_id'].",
								'".$vector['sw_paciente']."','now()', ".UserGetUID().",'','1');";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {		
							$this->error = "Error INSERT INTO auditoria_responsables_pagares";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$this->fileError = __FILE__;
							$this->lineError = __LINE__;
							$dbconn->RollbackTrans();
							return false;
			}				
			
			$dbconn->CommitTrans();
			unset($_SESSION['PAGARES']['DATOS']['VECTOR']);
			unset($_SESSION['PAGARES']['DATOS']['RESPONSABLES']);
			unset($_SESSION['PAGARES']['DATOS']['MODIFICAR']);
			unset($_SESSION['PAGARES']['MODIFICAR']);
			unset($_SESSION['PAGARES']['DATOS']['MODIFICAR']);
			unset($_SESSION['PAGARES']['MODIFICARR']);
			$_SESSION['PAGARES']['DATOS']['RESPONSABLES']=$this->BuscarResponsablesPagare($_SESSION['PAGARES']['DATOS']['prefijo'],$_SESSION['PAGARES']['DATOS']['numero']);
			
			$this->frmError["MensajeError"]="SE MODIFICARON LOS DATOS DEL RESPONSABLE.";
			$this->FormaModificarResponsablesPagare();
			return true;			
	}
	
	function LlamarModificarDatosTmpResponsables()
	{			
				$_REQUEST['tipoId'] = $_REQUEST['datos']['tipoId'];
				$_REQUEST['documento'] = $_REQUEST['datos']['documento'];
				$_REQUEST['nombre'] = $_REQUEST['datos']['nombre'];
				$_REQUEST['telefono'] = $_REQUEST['datos']['telefono'];								
				$_REQUEST['celular'] = $_REQUEST['datos']['celular'];
				$_REQUEST['pais'] = $_REQUEST['datos']['pais'];
				$_REQUEST['dpto'] = $_REQUEST['datos']['dpto'];				
				$_REQUEST['mpio'] = $_REQUEST['datos']['mpio'];
				$_REQUEST['direccion'] = $_REQUEST['datos']['direccion'];			
				$_REQUEST['parentesco'] = $_REQUEST['datos']['parentesco']."||".$_REQUEST['datos']['nomparentesco'];
				$_REQUEST['observacion'] = $_REQUEST['datos']['observacion'];		
				$_REQUEST['paciente'] = $_REQUEST['datos']['paciente'];	
				unset($_SESSION['PAGARES']['MODIFICARTMP']);				
				$_SESSION['PAGARES']['MODIFICARTMP']['tipoId']=$_REQUEST['datos']['tipoId'];
				$_SESSION['PAGARES']['MODIFICARTMP']['documento']=$_REQUEST['datos']['documento'];				
				//$_SESSION['PAGARES']['MODIFICARTMP']=TRUE;	
				$this->FormaPedirDatosResponsable();
				return true;
	}
	
	function ModificarResponsableTmp()
	{			
			foreach($_SESSION['PAGARES']['DATOS']['VECTOR'] as $k => $v)
			{		//valida si la modificacion fue de la identificacion y si es asi lo borra
					if($_SESSION['PAGARES']['MODIFICARTMP']['tipoId']!=$v['tipoId']
							OR $_SESSION['PAGARES']['MODIFICARTMP']['documento']!=$v['documento'])
					{   	unset($_SESSION['PAGARES']['DATOS']['VECTOR'][$_SESSION['PAGARES']['MODIFICARTMP']['tipoId']][$_SESSION['PAGARES']['MODIFICARTMP']['documento']]); }			
			}			

			unset($_SESSION['PAGARES']['MODIFICARTMP']);			
			$this->frmError["MensajeError"]="SE MODIFICARON LOS DATOS DEL RESPONSABLE.";
			$this->FormaPedirDatosResponsable();
			return true;			
	}

	function BotonCancelar()
	{
			unset($_SESSION['PAGARES']['MODIFICARTMP']);
			unset($_SESSION['PAGARES']['MODIFICAR']);
			unset($_SESSION['PAGARES']['MODIFICARR']);
			unset($_SESSION['PAGARES']['DATOS']);
			$this->FormaPrincipalPagares();
			return true;
	}
		/**
    *
    */
    function PagaresDeudores($prefijo,$numero,$empresa)
    {
      $sql  = "SELECT COUNT(*) AS contador ";
      $sql .= "FROM   pagares_responsables "; 
      $sql .= "WHERE  empresa_id = '".$empresa."' ";
      $sql .= "AND    prefijo = '".$prefijo."'	";
      $sql .= "AND    numero = ".$numero." ";
      $sql .= "AND    sw_deudor = '1' ";
      
      $cxn = new ConexionBD();

      if(!$rst = $cxn->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos['contador'];
    }
  }
?>