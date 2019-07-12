
<?php

/**
* Modulo de Credito Paciente (PHP).
*
//*
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @       Carlos Arturo Henao Quiñonez <cahenao99@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_Credito_Paciente_user.php
*
//*
**/

class app_Credito_Paciente_user extends classModulo
{
	var $uno;//para los errores

	function app_Credito_Paciente_user()
	{
		return true;
	}

	function main()
	{
		$this->PrincipalCreditoPaciente();
		return true;
	}

	function UsuariosCreditoPaciente()//Función de permisos
	{
		list($dbconn) = GetDBconn();
	   $usuario=UserGetUID();		
		 $query = "SELECT D.empresa_id,
				B.razon_social AS descripcion1,	D.centro_utilidad, C.descripcion AS descripcion2,
				D.descripcion AS descripcion3
				FROM userpermisos_credito_paciente AS A, puntos_credito_paciente AS D,
				empresas AS B, centros_utilidad AS C
				WHERE A.usuario_id=".$usuario."
				AND A.punto_credito_paciente_id=D.punto_credito_paciente_id
				AND D.empresa_id=B.empresa_id
				AND D.centro_utilidad=C.centro_utilidad
				AND D.empresa_id=C.empresa_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var2[$resulta->fields[1]][$resulta->fields[3]][$resulta->fields[4]]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		$mtz[0]='EMPRESAS';
		$mtz[1]='CENTRO DE UTILIDAD';
		$mtz[2]='PUNTOS DE CRÉDITO - PACIENTE';
		$url[0]='app';
		$url[1]='Credito_Paciente';
		$url[2]='user';
		$url[3]='PrincipalCreditoPaciente';
		$url[4]='permisocredpaci';
		$this->salida .=gui_theme_menu_acceso('CRÉDITO PACIENTE', $mtz, $var2, $url, ModuloGetURL('system','Menu'));
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

	function BuscarIdPaciente($tipo_id,$TipoId='')//Busca el tipo de documento
	{
		foreach($tipo_id as $value=>$titulo)
		{
			if($value==$TipoId)
			{
				$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
			}
			else
			{
				$this->salida .=" <option value=\"$value\">$titulo</option>";
			}
		}
	}

	function LlamaFormaPagare()
	{
		if(empty($_REQUEST['Documento']) AND empty($_SESSION['crpada']['Documento']))
		{
			$this->LlamaFormaBusqueda();
			return true;
		}
		if(empty($_SESSION['crpada']['Documento']))
		{
			$_SESSION['crpada']['TipoDocum']=$_REQUEST['TipoDocum'];
			$_SESSION['crpada']['Documento']=$_REQUEST['Documento'];
		}
		$_SESSION['crpada']['datospaciep']=$this->BuscarNombrePacip($_SESSION['crpada']['TipoDocum'],$_SESSION['crpada']['Documento']);
		if (empty($_SESSION['crpada']['datospaciep']))		
	    {	
		     $_SESSION['crpada']['datospacie']=$this->BuscarNombrePaci($_SESSION['crpada']['TipoDocum'],$_SESSION['crpada']['Documento']);
      		if(empty($_SESSION['crpada']['datospacie']))
		      {
			      $this->frmError["MensajeError"]="EL TIPO DOCUMENTO '".$_REQUEST['TipoDocum']."' CON No. '".$_POST['Documento']."' NO SE ENCONTRÓ";
       			$this->uno=1;
			      $this->LlamaFormaBusqueda();
		      	return true;
		      }				 
		     $this->FormaPagare();
		     return true;				 
			}	 
		else
		  {
		    $this->FormaPendiente();
		    return true;		
		  }
	}

//6114780
	function BuscarNombrePaci($TipoDo,$Docume)//Busca el nombre completo del paciente
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.numerodecuenta,
				A.ingreso,
				A.total_cuenta,
				A.valor_total_paciente,
				A.valor_total_empresa,
				B.tipo_id_paciente,
				B.paciente_id,
				B.primer_nombre,
				B.segundo_nombre,
				B.primer_apellido,
				B.segundo_apellido,
				B.residencia_telefono,
				B.residencia_direccion,
				C.tipo_id_tercero,
				C.garante_id,
				C.primer_nombre_garante,
				C.segundo_nombre_garante,
				C.primer_apellido_garante,
        C.segundo_apellido_garante,
        C.direccion_garante,
        C.telefono_garante,
				D.ingreso, 
				E.estado
				FROM cuentas AS A
				LEFT JOIN garantes AS C ON
				(A.ingreso=C.ingreso),
				pacientes AS B,
				ingresos AS D, pagare_cuenta AS E
				WHERE A.ingreso=D.ingreso
				AND D.tipo_id_paciente='".$TipoDo."'
				AND D.paciente_id='".$Docume."'
				AND D.tipo_id_paciente=B.tipo_id_paciente
				AND D.paciente_id=B.paciente_id
				AND (A.estado='1' OR A.estado='2')
				AND D.tipo_id_paciente=E.tipo_id_paciente
				AND D.paciente_id=E.paciente_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
   else
    {
	  	$i=0;
			while(!$resulta->EOF)
			{
				$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
				$i++;
				$resulta->MoveNext();
			}

    }
		return $var;
	}

	function BuscarNombrePacip()//Busca los datos completos del paciente que tiene
	{
    $TipoDo = $_SESSION['crpada']['TipoDocum'];
    $Docume = $_SESSION['crpada']['Documento'];                               //pagares pendientes
		list($dbconn) = GetDBconn();
  	$query = "SELECT A.pagare_id, A.numerodecuenta, A.tipo_id_paciente,
		                     A.paciente_id, A.ingreso, A.fecha_elaboracion, A.fecha_vencimiento,
							  			   A.valor, A.observacion, A.abono, A.saldo, D.descripcion,
											   B.primer_apellido, B.segundo_apellido, B.primer_nombre,
											   B.segundo_nombre,
											   C.tipo_id_tercero, C.garante_id, C.primer_nombre_garante,
											   C.segundo_nombre_garante, C.primer_apellido_garante,
											   C.segundo_apellido_garante, C.direccion_garante, C.telefono_garante
						      FROM pagare_cuenta A, pacientes B, garantes C, pagare_estado D
		              WHERE A.tipo_id_paciente='".$TipoDo."' AND A.paciente_id=".$Docume." AND A.paciente_id=B.paciente_id
						      AND A.ingreso=C.ingreso AND A.estado=D.estado
							    ORDER BY A.pagare_id";
		$resulta = $dbconn->Execute($query);
		//$RC=$resulta->RecordCount();
		//echo $RC;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
		{
			$i=0;
			while(!$resulta->EOF)
				{
						$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
						$i++;
						$resulta->MoveNext();
				}
		}
		return $var;
	}
		  
	function TraerformasPago()
  {
	  list($dbconn) = GetDBconn();
  	$query = "SELECT formas_pago_id, descripcion
					FROM compras_formas_pago;"; 
    $resulta = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0)
     {
	 	   $this->error = "Error al Cargar el Modulo";
		   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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
	
  function TraerTipoId()
	{
	  list($dbconn) = GetDBconn();
    $query = "SELECT tipo_id_tercero, descripcion
					FROM tipo_id_terceros;"; 
    $resulta = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0)
     {
	 	   $this->error = "Error al Cargar el Modulo";
		   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
	     return false;
     }
    if(!$resulta->EOF)
     {
	  	 while(!$resulta->EOF)
	  	  {
		 	  	 $Tipo_doc[]=$resulta->GetRowAssoc($ToUpper = false);
			  	 $resulta->MoveNext();
	  	  }
     } 
		return $Tipo_doc;	
	}
	//1109478 23873131
	function ValidarDatosInsertar()//Valida los datos a Insertar
	{ 

		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query ="SELECT NEXTVAL ('pagare_cuenta_pagare_id_seq');";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{         
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollBackTrans();
			return false;
		}
		$indice=$resulta->fields[0];
		$usuario=UserGetUID();
		$fechaACT = date ("Y-m-d H:i:s");

		if(empty($_POST['fecha']))
			{
				$this->frmError["fecha"]=1;
			}
			else
			{//La fecha no va validada con la fecha del sistema
				$fecdes=explode('/',$_POST['fecha']);
				$day=$fecdes[0];
				$mon=$fecdes[1];
				$yea=$fecdes[2];
				if(checkdate($mon, $day, $yea)==0)
				{
					$_POST['fecha']='';
					$this->frmError["fecha"]=1;
				}
				else
				{
					$fechaVEN=$yea.'-'.$mon.'-'.$day;
				}
			}
 
		if(!is_numeric($_POST['valor']) && $_POST['pagareblanco']==1)
			{
				$this->uno=1;
  			$this->frmError["MensajeError"]="EL VALOR INTRODUCIDO NO ES NÚMERICO.";
		  	$this->FormaPagare();
				return true;
			}	
			else
			{ 	
    	if($_POST['valor']==NULL && $_POST['pagareblanco']==0) //PAGARÉ EN BLANCO
		   {
				if (empty($_SESSION['crpada']['datospacie']['garante_id']))
				{ 
				  if ($_POST['deudor']==NULL || $_POST['tel']==NULL || $_POST['identif']==NULL || $_POST['fecha']==NULL)
					 {  		 
							$this->uno=1;
  						$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";			
		  				$this->FormaPagare();
							return true;
				   }
					 else
					 { 
							$fechaPago = $_POST['pagos'];
							if (empty($_SESSION['crpada']['datospacie']['garante_id']))
							{ 
							//pagaré en blanco y sin garante	
								$query ="INSERT INTO garantes
									(
										ingreso,
										tipo_id_tercero,
										garante_id,
										primer_nombre_garante,
										direccion_garante,
										telefono_garante
										)
									VALUES('".$_SESSION['crpada']['datospacie']['ingreso']."','".$_POST['id']."','".$_POST['identif']."','".$_POST['deudor']."','".$_POST['dir']."','".$_POST['tel']."');";
									$resulta = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0)
									{ echo mal; exit;
										$dbconn->RollBackTrans();
										$_POST['numeroctra']='';
										$this->frmError["MensajeError"]="VERIFICAR DATOS";
										$this->uno=1;
										$this->FormaPagare();
										return true;
									}	
								$dbconn->CommitTrans();								
							//	$_SESSION['crpada']['datospacie']['tipo_id_tercero']= $_POST['id'];
							//	$_SESSION['crpada']['datospacie']['garante_id']= $_POST['identif'];
							}

								$query ="INSERT INTO pagare_cuenta
									(
										pagare_id, 
										numerodecuenta,
										tipo_id_paciente,
										paciente_id,
										tipo_id_tercero,
										garante_id,
										ingreso,
										fecha_elaboracion,
										fecha_vencimiento,											
										valor,
										formas_pago_id,
										cancelacion_id,
										estado,
										usuario_id,
										fecha_registro,
										observacion)
									VALUES($indice,'".$_SESSION['crpada']['datospacie']['numerodecuenta']."',
									'".$_SESSION['crpada']['datospacie']['tipo_id_paciente']."',
									'".$_SESSION['crpada']['datospacie']['paciente_id']."','".$_POST['id']."',
									'".$_POST['identif']."','".$_SESSION['crpada']['datospacie']['ingreso']."',
									'".$fechaACT."','".$fechaVEN."',0,'".$_POST['pagos']."','','2',$usuario,
									'".$fechaACT."','".$_POST['observ']."');";
									$resulta = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0)
									{ echo mal; exit;
										$dbconn->RollBackTrans();
										$_POST['numeroctra']='';
										$this->frmError["MensajeError"]="DATOS DUPLICADOS - VERIFICAR DATOS";
										$this->uno=1;
										$this->FormaPagare();
										return true;
									}
									$query ="INSERT INTO pagare_cuenta_blanco
										(
											pagare_id,            
											fecha_elaboracion,   
											fecha_vencimiento,
											formas_pago_id,       
											usuario_id          											
										)
										VALUES(
										$indice, '".$fechaACT."','".$fechaVEN."','".$_POST['pagos']."', $usuario);";
										$resulta = $dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0)
										{ echo mal; exit;
											$dbconn->RollBackTrans();
											$_POST['numeroctra']='';
											$this->frmError["MensajeError"]="DATOS DUPLICADOS - VERIFICAR DATOS";
											$this->uno=1;
											$this->FormaPagare();
											return true;
										}														
								 	
							}			
								$dbconn->CommitTrans();
								$this->PrincipalCreditoPaciente();
								return true;
				}
		 	else
			  {
						list($dbconn) = GetDBconn();
						$dbconn->BeginTrans();
					if(empty($_POST['valor']) && $_POST['pagareblanco']==1)
						{			
							$this->uno=1;
							$this->frmError["MensajeError"]="EL VALOR ES VACIO.";		//exit;
							$this->FormaPagare();
							return true;
						}
						else						 
						{
							if(!empty($_POST['valor']) && $_POST['pagareblanco']==0)
								{			
									$this->uno=1;
									$this->frmError["MensajeError"]="EL VALOR DEBE SER CERO,PAGARÉ EN BLANCO.";		//exit;
									$this->FormaPagare();
									return true;						 
								}
						} 
					if (empty($_SESSION['crpada']['datospacie']['garante_id']))
					{ 
							if ($_POST['deudor']==NULL || $_POST['tel']==NULL || $_POST['identif']==NULL)								
							{
							$this->uno=1;
							$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";	
							$this->FormaPagare();
							return true;						 		 
							}
					}	 
				else
					{	
					if ($_POST['fecha']==NULL)						
					{
							$this->uno=1;
							$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";	
							$this->FormaPagare();
							return true;						 		 
					}
					else
					{
						if (empty($_SESSION['crpada']['datospacie']['garante_id']))
						{
							$query ="INSERT INTO garantes
								(
									ingreso,
									tipo_id_tercero,
									garante_id,
									primer_nombre_garante,
									direccion_garante,
									telefono_garante
									)
								VALUES('".$_SESSION['crpada']['datospacie']['ingreso']."','".$_POST['id']."','".$_POST['identif']."','".$_POST['deudor']."','".$_POST['dir']."','".$_POST['tel']."');";
								$resulta = $dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0)
								{ echo mal; exit;
									$dbconn->RollBackTrans();
									$_POST['numeroctra']='';
									$this->frmError["MensajeError"]="VERIFICAR DATOS";
									$this->uno=1;
									$this->FormaPagare();
									return true;
								}	
								$_SESSION['crpada']['datospacie']['tipo_id_tercero']= $_POST['id'];
								$_SESSION['crpada']['datospacie']['garante_id']= $_POST['identif'];
						}
					$query ="INSERT INTO pagare_cuenta
						(
							pagare_id, 
							numerodecuenta,
							tipo_id_paciente,
							paciente_id,
							tipo_id_tercero,
							garante_id,
							ingreso,
							fecha_elaboracion,
							fecha_vencimiento,											
							valor,
							formas_pago_id,
							cancelacion_id,
							estado,
							usuario_id,
							fecha_registro,
							observacion)
						VALUES($indice,'".$_SESSION['crpada']['datospacie']['numerodecuenta']."',
						'".$_SESSION['crpada']['datospacie']['tipo_id_paciente']."',
						'".$_SESSION['crpada']['datospacie']['paciente_id']."',
						'".$_SESSION['crpada']['datospacie']['tipo_id_tercero']."',
						'".$_SESSION['crpada']['datospacie']['garante_id']."',
						'".$_SESSION['crpada']['datospacie']['ingreso']."','".$fechaACT."','".$fechaVEN."',0,
						'".$_POST['pagos']."','','2',$usuario,'".$fechaACT."','".$_POST['observ']."');";
						$resulta = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{ echo mal2; exit;
							$dbconn->RollBackTrans();
							$this->frmError["MensajeError"]="DATOS DUPLICADOS - VERIFICAR DATOS";
							$this->uno=1;
							$this->FormaPagare();
							return true;
						}
						$query ="INSERT INTO pagare_cuenta_blanco
							(
								pagare_id,            
								fecha_elaboracion,   
								fecha_vencimiento,
								formas_pago_id,       
								usuario_id          											
							)
							VALUES(
							$indice, '".$fechaACT."','".$fechaVEN."','".$_POST['pagos']."', $usuario);";
							$resulta = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{ echo mal; exit;
								$dbconn->RollBackTrans();
								$_POST['numeroctra']='';
								$this->frmError["MensajeError"]="DATOS DUPLICADOS - VERIFICAR DATOS";
								$this->uno=1;
								$this->FormaPagare();
								return true;
							}										
					}
					$dbconn->CommitTrans(); 							
					if($this->frmError["MensajeError"]==NULL)
					{
						$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
					}
					$this->uno=1;
					$this->PrincipalCreditoPaciente();
					return true;	
		}	
	 }
	}
	else	//CONDICIÓN PARA LOS PAGARE QUE TIENEN UN VALOR
	{ 
	if(!empty($_POST['valor']) && is_numeric($_POST['valor']) && $_POST['pagareblanco']==1)
		{	
			if (empty($_SESSION['crpada']['datospacie']['garante_id']))
			{ 
				if ($_POST['deudor']==NULL || $_POST['tel']==NULL || $_POST['identif']==NULL || $_POST['fecha']==NULL)
					{  		 
						$this->uno=1;
						$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";			
						$this->FormaPagare();
						return true;
					}
					else
					{ 
						$fechaPago = $_POST['pagos'];
						if (empty($_SESSION['crpada']['datospacie']['garante_id']))
						{ 
			
							$query ="INSERT INTO garantes
								(
									ingreso,
									tipo_id_tercero,
									garante_id,
									primer_nombre_garante,
									direccion_garante,
									telefono_garante
									)
								VALUES('".$_SESSION['crpada']['datospacie']['ingreso']."','".$_POST['id']."','".$_POST['identif']."','".$_POST['deudor']."','".$_POST['dir']."','".$_POST['tel']."');";
								$resulta = $dbconn->Execute($query); 
								
								if ($dbconn->ErrorNo() != 0)
								{ echo mal1; exit;
									$dbconn->RollBackTrans();
									$_POST['numeroctra']='';
									$this->frmError["MensajeError"]="VERIFICAR DATOS";
									$this->uno=1;
									$this->FormaPagare();
									return true;
								}	
							$dbconn->CommitTrans();								
						//	$_SESSION['crpada']['datospacie']['tipo_id_tercero']= $_POST['id'];
						//	$_SESSION['crpada']['datospacie']['garante_id']= $_POST['identif'];
						}
			
							$query ="INSERT INTO pagare_cuenta
								(
									pagare_id, 
									numerodecuenta,
									tipo_id_paciente,
									paciente_id,
									tipo_id_tercero,
									garante_id,
									ingreso,
									fecha_elaboracion,
									fecha_vencimiento,											
									valor,
									formas_pago_id,
									cancelacion_id,
									estado,
									usuario_id,
									fecha_registro,
									observacion,
									saldo)
								VALUES($indice,'".$_SESSION['crpada']['datospacie']['numerodecuenta']."',
								'".$_SESSION['crpada']['datospacie']['tipo_id_paciente']."',
								'".$_SESSION['crpada']['datospacie']['paciente_id']."','".$_POST['id']."',
								'".$_POST['identif']."','".$_SESSION['crpada']['datospacie']['ingreso']."',
								'".$fechaACT."','".$fechaVEN."','".$_POST['valor']."','".$_POST['pagos']."',
								'','2',$usuario,'".$fechaACT."','".$_POST['observ']."','".$_POST['valor']."');";
								$resulta = $dbconn->Execute($query); 
								if ($dbconn->ErrorNo() != 0)
								{ echo mal; exit;
									$dbconn->RollBackTrans();
									$_POST['numeroctra']='';
									$this->frmError["MensajeError"]="DATOS DUPLICADOS - VERIFICAR DATOS";
									$this->uno=1;
									$this->FormaPagare();
									return true;
								}
						}
				 $dbconn->CommitTrans();		
		  	 $this->PrincipalCreditoPaciente();
				 return true;				 
        }
		  	else
			  {
						list($dbconn) = GetDBconn();
						$dbconn->BeginTrans();
					if(empty($_POST['valor']) && $_POST['pagareblanco']==1)
						{			
							$this->uno=1;
							$this->frmError["MensajeError"]="EL VALOR ES VACIO.";		//exit;
							$this->FormaPagare();
							return true;
						}
						else						 
						{
							if(!empty($_POST['valor']) && $_POST['pagareblanco']==0)
								{			
									$this->uno=1;
									$this->frmError["MensajeError"]="EL VALOR DEBE SER CERO,PAGARÉ EN BLANCO.";		//exit;
									$this->FormaPagare();
									return true;						 
								}
						} 
					if (empty($_SESSION['crpada']['datospacie']['garante_id']))
					{ 
							if ($_POST['deudor']==NULL || $_POST['tel']==NULL || $_POST['identif']==NULL)								
							{
							$this->uno=1;
							$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";	
							$this->FormaPagare();
							return true;						 		 
							}
					}	 
				else
					{	
					if ($_POST['fecha']==NULL)						
					{
							$this->uno=1;
							$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";	
							$this->FormaPagare();
							return true;						 		 
					}
					else
					{
						if (empty($_SESSION['crpada']['datospacie']['garante_id']))
						{
							$query ="INSERT INTO garantes
								(
									ingreso,
									tipo_id_tercero,
									garante_id,
									primer_nombre_garante,
									direccion_garante,
									telefono_garante
									)
								VALUES('".$_SESSION['crpada']['datospacie']['ingreso']."','".$_POST['id']."','".$_POST['identif']."','".$_POST['deudor']."','".$_POST['dir']."','".$_POST['tel']."');";
								$resulta = $dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0)
								{ echo mal; exit;
									$dbconn->RollBackTrans();
									$_POST['numeroctra']='';
									$this->frmError["MensajeError"]="VERIFICAR DATOS";
									$this->uno=1;
									$this->FormaPagare();
									return true;
								}	
								$_SESSION['crpada']['datospacie']['tipo_id_tercero']= $_POST['id'];
								$_SESSION['crpada']['datospacie']['garante_id']= $_POST['identif'];
						}
					$query ="INSERT INTO pagare_cuenta
						(
							pagare_id, 
							numerodecuenta,
							tipo_id_paciente,
							paciente_id,
							tipo_id_tercero,
							garante_id,
							ingreso,
							fecha_elaboracion,
							fecha_vencimiento,											
							valor,
							formas_pago_id,
							cancelacion_id,
							estado,
							usuario_id,
							fecha_registro,
							observacion,
							saldo)
						VALUES($indice,'".$_SESSION['crpada']['datospacie']['numerodecuenta']."',
						'".$_SESSION['crpada']['datospacie']['tipo_id_paciente']."',
						'".$_SESSION['crpada']['datospacie']['paciente_id']."',
						'".$_SESSION['crpada']['datospacie']['tipo_id_tercero']."',
						'".$_SESSION['crpada']['datospacie']['garante_id']."',
						'".$_SESSION['crpada']['datospacie']['ingreso']."','".$fechaACT."','".$fechaVEN."',
						'".$_POST['valor']."','".$_POST['pagos']."','','2',$usuario,'".$fechaACT."',
						'".$_POST['observ']."','".$_POST['valor']."');";
						$resulta = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{ echo mal2; exit;
							$dbconn->RollBackTrans();
							$this->frmError["MensajeError"]="DATOS DUPLICADOS - VERIFICAR DATOS";
							$this->uno=1;
							$this->FormaPagare();
							return true;
						}
					}
					$dbconn->CommitTrans(); 							
					if($this->frmError["MensajeError"]==NULL)
					{
						$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
					}
					$this->uno=1;
          $this->PrincipalCreditoPaciente();					
					return true;	
		}	
	 }
 	}
	else
	{			
		$this->uno=1;
		$this->frmError["MensajeError"]="DATOS NO VALIDOS.";	
		$this->FormaPagare();
		return true;
	}
	}
 }
}

	function LlamaformaAdmin()	
	{
	  $tmp= $_REQUEST['ANULAR'];
	 $this->FormaAdmin($tmp);
	 return true;
	}
	
//LlamaFormaConsultas	 
	function LlamaFormaConsultas()	
	{
	 $this->FormaConsultas();
	 return true;
	}
	
	function BuscarEmpresasPagares()
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigoctra'])
		{
			$codigo=$_REQUEST['codigoctra'];
			$busqueda="AND A.pagare_id LIKE '%$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['ctradescri'])
		{
			$codigo=STRTOUPPER($_REQUEST['ctradescri']);
			$busqueda2="AND UPPER(B.primer_nombre) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{

			$query ="SELECT count(*) FROM
					(
						SELECT pagare_id,
						ingreso,
						fecha_elaboracion,
						fecha_vencimiento,
						valor,
						estado,
						abono
						FROM Pagare_Cuenta) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
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
		$query ="
		    (SELECT A.pagare_id,
							A.numerodecuenta,		
							A.paciente_id,
							A.ingreso,
							A.fecha_elaboracion,
							A.fecha_vencimiento,
							A.valor,
							A.estado, 
							A.abono,
							A.saldo,
							B.primer_apellido,
							B.segundo_apellido,
							B.primer_nombre,
							B.primer_nombre,
							C.descripcion
				FROM pagare_cuenta AS A, pacientes AS B, pagare_estado AS C	
				WHERE (A.estado=1 OR A.estado=2 OR A.estado=3 OR A.estado=4) AND A.paciente_id = B.paciente_id AND A.estado = C.estado
                $busqueda
                $busqueda2
        ORDER BY A.pagare_id);";
        $resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $var;
	}

	function BuscarEmpresasPagaresConsul()
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigoctra'])
		{
			$codigo=$_REQUEST['codigoctra'];
			$busqueda="AND A.pagare_id LIKE '%$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['ctradescri'])
		{
			$codigo=STRTOUPPER($_REQUEST['ctradescri']);
			$busqueda2="AND UPPER(B.primer_nombre) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{

			$query ="SELECT count(*) FROM
					(
						SELECT pagare_id,
						ingreso,
						fecha_elaboracion,
						fecha_vencimiento,
						valor,
						estado,
						abono
						FROM Pagare_Cuenta) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
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
		$query ="
		        (SELECT A.pagare_id,
				A.numerodecuenta,		
				A.paciente_id,
				A.ingreso,
				A.fecha_elaboracion,
				A.fecha_vencimiento,
				A.valor,
				A.estado, 
				A.abono,
				B.primer_apellido,
				B.segundo_apellido,
				B.primer_nombre,
				B.primer_nombre,
				C.descripcion
				FROM pagare_cuenta AS A, pacientes AS B, pagare_estado AS C		
				WHERE (A.estado=0 OR A.estado=5 ) AND A.paciente_id = B.paciente_id AND C.estado = A.estado
                $busqueda
                $busqueda2
                ORDER BY A.pagare_id
				);";
        $resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $var;
	}
	
	function LlamaFormaOpcionesPagare()
	{ 
    $pagare_= $_REQUEST['pagare_id'];
  	$this->FormaOpcionesPagare($pagare_);
		return true;
	}
	
	function LlamaFormaOpcionesPagare2()
	{ 
    $pagare_= $_REQUEST['pagare_id'];
  	$this->FormaOpcionesPagare2($pagare_);
		return true;
	}
	
	//LlamaformaMenuAdmin
	function LlamaformaMenuAdmin()
	{ 
		list($dbconn) = GetDBconn();
	   $usuario=UserGetUID();		
		 $query = "SELECT usuario_id
				FROM pagare_autorizador;";
		$resul = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    if(!$resul->EOF)
     {
	  	 while(!$resul->EOF)
	  	  {
		 	  	 $datos[]=$resul->GetRowAssoc($ToUpper = false);
			  	 $resul->MoveNext();
	  	  }
     }
	 if ($datos[0][usuario_id] == $usuario)
		{
		  $this->uno =0;
	    $this->formaMenuAdmin();
		  return true;
		}
	  else
	  {
	  	$this->uno=1;
    	$this->frmError["MensajeError"]="USUARIO NO AUTORIZADO.";	
	   	$this->formaMenuAdminError();		
			return true;
     }		

		$this->formaMenuAdmin($datos);
		return true;
	}

	function TraerDatosPagare($pag_id)
	{
    	
		list($dbconn) = GetDBconn();
		$query="SELECT A.pagare_id, A.numerodecuenta, A.tipo_id_paciente,
		               A.paciente_id, A.ingreso, A.fecha_elaboracion, A.fecha_vencimiento,
		               A.valor, A.observacion, A.abono, A.saldo,
									 B.primer_apellido, B.segundo_apellido, B.primer_nombre,
		               B.segundo_nombre,
									 C.tipo_id_tercero, C.garante_id, C.primer_nombre_garante,
	                 C.segundo_nombre_garante, C.primer_apellido_garante,
									 C.segundo_apellido_garante, C.direccion_garante, C.telefono_garante
		        FROM pagare_cuenta A, pacientes B, garantes C, ingresos D
		        WHERE A.pagare_id=".$pag_id." AND A.paciente_id=B.paciente_id 
						      AND A.ingreso=C.ingreso";
		$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al buscar el usuario Datos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		else
			{
			$i=0;
			while(!$resulta->EOF)
				{
						$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
						$i++;
						$resulta->MoveNext();
				}
			}
			return $var;
	 }
	
	function Autorizar()
	{
	//echo $_REQUEST['boton'].' '.$_REQUEST['valor'].' '.$_REQUEST['abono'].' '.$_REQUEST['observ'].' '.$_REQUEST['tipo_id_paciente'].' '.$_REQUEST['paciente_id'].' '.$_REQUEST['pagare_id'];
   if ($_REQUEST['boton']=='AUTORIZAR')
	  {
			list($dbconn) = GetDBconn();
			$usuario=UserGetUID();
			$query="UPDATE pagare_cuenta 
							SET valor=".$_REQUEST['valor'].",observacion='".$_REQUEST['observ']."', estado=3
							WHERE tipo_id_paciente='".$_REQUEST['tipo_id_paciente']."'
							AND paciente_id=".$_REQUEST['paciente_id']." AND pagare_id=".$_REQUEST['pagare_id']."";
			$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{echo mal; exit;
					$this->error = "Error al actualizar datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			
			$fecha = date ("Y-m-d H:i:s");
		echo	$query="INSERT INTO pagare_autorizacion (usuario_id_auto, pagare_id, obsevacion,
							sw_tipo_autorizacion, usuario_id,fecha_registro)
							VALUES(".$usuario.",".$_REQUEST['pagare_id'].",'".$_POST['observ']."',1,".$usuario.",'".$fecha."');";
			$resulta=$dbconn->Execute($query); 
				if ($dbconn->ErrorNo() != 0)
				{echo mal; exit;
					$this->error = "Error al insertar datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
		$this->PrincipalCreditoPaciente();
		return true;		
		}
		else
		  if ($_REQUEST['boton']=='GUARDAR')
        $this->ActualizarPago();
      elseif ($_REQUEST['boton']=='VOLVER')		
				   $this->LlamaformaAdmin($_REQUEST['pagos']);    					
	 return true;			
	}
	
	//ActualizarPago
	function ActualizarPago()
	{ echo $_REQUEST['saldo'];
		list($dbconn) = GetDBconn();
		$usuario=UserGetUID();
		$saldo=$_REQUEST['saldo']-$_REQUEST['abono'];
    echo $echo; exit;
	echo	$query="UPDATE pagare_cuenta
		        SET abono=".$_REQUEST['abono']." ,observacion='".$_REQUEST['observ']."', saldo= ".$saldo."
		        WHERE tipo_id_paciente='".$_REQUEST['tipo_id_paciente']."'
						AND paciente_id=".$_REQUEST['paciente_id']." AND pagare_id=".$_REQUEST['pagare_id']."";
		$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{echo mal; exit;
				$this->error = "Error al actualizar datos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
  		$dbconn->CommitTrans(); 							
	  	if($this->frmError["MensajeError"]==NULL)
			{
				$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
			}
				$this->uno=1;
	      $this->PrincipalCreditoPaciente();
	     return true;		
	}	
	
function Anular()
{ echo $_REQUEST['pagare_id'].$_REQUEST['anular'];
	if (!empty($_REQUEST['aceptar']))
	{
		list($dbconn) = GetDBconn();
		$usuario=UserGetUID();	
		$query="UPDATE pagare_cuenta SET estado=5 
		        WHERE pagare_id=".$_REQUEST['pagare_id'].";";
		$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{echo mal; exit;
				$this->error = "Error al actualizar datos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
  		$dbconn->CommitTrans(); 							
	  	if($this->frmError["MensajeError"]==NULL)
			{
				$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
			}
				$this->uno=1;
	      $this->FormaAdmin($_REQUEST['anular']);
				return true;		
	}elseif (!empty($_REQUEST['cancel']))
	{	
	  $this->uno=1;
	  $this->FormaAdmin($_REQUEST['anular']);
	  return true;			
	}
 }			
}//fin de la clase
?>
