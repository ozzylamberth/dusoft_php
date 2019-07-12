
<?php

/**
* Modulo de Terceros (PHP).
*
* Modulo que permite el mantenimiento de los terceros relacionados a
* la empresa, así como el tipo de relación que mantienen
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_Terceros_user.php
*
* Clase que establece los métodos y las opciones en las que un tercero puede
* relacionarse con la empresa, es decir desde cliente, proveedor, entre otros
**/

class app_Terceros_user extends classModulo
{
	var $uno;//para los errores

	function app_Terceros_user()
	{
		return true;
	}

	function main()
	{
		$this->PrincipalTercer();
		return true;
	}

	function BorrarTercer()//Retorna al modulo que lo llamó si el usuario cancelo la operación
	{
		$_SESSION['INFORM']['RETORNO']['sw']=1;//Cancelo
		$this->ReturnMetodoExterno($_SESSION['INFORM']['RETORNO']['contenedor'],
		$_SESSION['INFORM']['RETORNO']['modulo'],
		$_SESSION['INFORM']['RETORNO']['tipo'],
		$_SESSION['INFORM']['RETORNO']['metodo']);
		return true;
	}

	function SetStyle($campo)//Mensaje de error en caso de no encontrar los datos
	{
		if($this->frmError[$campo] || $campo=="MensajeError")
		{
			if($campo=="MensajeError")
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

	function BuscarIdPaciente($tipo_id,$TipoId='')//Busca el tipo de docuemento
	{
		foreach($tipo_id as $value=>$titulo)
		{
			if($value==$TipoId)
			{
				$this->salida .=" <option value=\"".$value."".','."".$titulo."\" selected>$titulo</option>";
			}
			else
			{
				$this->salida .=" <option value=\"".$value."".','."".$titulo."\">$titulo</option>";
			}
		}
	}

	function TercerosTercer()//Trae los datos para el combo, del tipo de identificación de los terceros
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_id_tercero,
				descripcion
				FROM tipo_id_terceros
				ORDER BY indice_de_orden;";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
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
				$this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
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

	function BuscaDatosTercer($tipodo,$docume)//Función que determina si un tercero esta o no en la BD
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_id_tercero,
				tercero_id,
				nombre_tercero,
				tipo_pais_id,
				tipo_dpto_id,
				tipo_mpio_id,
				direccion,
				telefono,
				fax,
				email,
				celular,
				sw_persona_juridica,
				busca_persona
				FROM terceros
				WHERE tipo_id_tercero='".$tipodo."'
				AND tercero_id='".$docume."';";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarBusquedaTercer()//Valida si la busqueda del usuario arrojo los resultados
	{
		if(empty($_POST['DocumentoB']) OR empty($_POST['TipoDocum']))
		{
			$this->frmError["MensajeError"]="DATOS INCOMPLETOS";
			$this->uno = 1;
			$this->BusquedaTercer();
			return true;
		}
		else
		{
			$var=explode(',',$_POST['TipoDocum']);
			$_SESSION['INFORM']['DATOS']=$this->BuscaDatosTercer($var[0],$_POST['DocumentoB']);
			if($_SESSION['INFORM']['DATOS']==NULL)
			{
				$this->frmError["MensajeError"]="EL TIPO DOCUMENTO '".$var['0']."' CON No. '".$_POST['DocumentoB']."' NO SE ENCONTRÓ";
				$this->uno = 1;
				$this->BusquedaTercer();
				return true;
			}
			else
			{
				$this->IngresaTercer();
				return true;
			}
		}
	}

	function ValidarIngresaTercer()//Válida los datos del tercero y los guarda
	{
		if($_POST['TipoDocum']==NULL)
		{
			$this->frmError["TipoDocum"]=1;
		}
		if($_POST['Documento']==NULL)
		{
			$this->frmError["Documento"]=1;
		}
		if($_POST['nombre']==NULL)
		{
			$this->frmError["nombre"]=1;
		}
		if($_POST['direccion']==NULL)
		{
			$this->frmError["direccion"]=1;
		}
		if(empty($_POST['pais']))
		{
			$this->frmError["pais"]=1;
		}
		if(empty($_POST['dpto']))
		{
			$this->frmError["dpto"]=1;
		}
		if(empty($_POST['mpio']))
		{
			$this->frmError["mpio"]=1;
		}
		if(empty($_POST['persona']))
		{
			$this->frmError["persona"]=1;
		}
		else
		{
			$var=explode(',',$_POST['TipoDocum']);
			if(($var[0]=='NI' OR $var[0]=='NIT') AND $_POST['persona']==2)
			{
				$this->frmError["persona"]=1;
				$_POST['persona']='';
			}
			else if(($var[0]=='CC' OR $var[0]=='CE') AND $_POST['persona']==1)
			{
				$this->frmError["persona"]=1;
				$_POST['persona']='';
			}
		}
		if($_POST['TipoDocum']==NULL||$_POST['Documento']==NULL||
		$_POST['nombre']==NULL||$_POST['direccion']==NULL||
		empty($_POST['pais'])||empty($_POST['dpto'])||
		empty($_POST['mpio'])||empty($_POST['persona']))
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
			$this->uno = 1;
			$this->IngresaTercer();
		}
		else
		{
			if($_POST['persona']==2)
			{
				$_POST['persona']=0;
			}
			if($_POST['beeper1']<>NULL AND $_POST['beeper2']<>NULL)
			{
				$_POST['beeper1']=$_POST['beeper1'].'-'.$_POST['beeper2'];
			}
			else
			{
				$_POST['beeper1']='';
			}
			$var=explode(',',$_POST['TipoDocum']);
			$_POST['TipoDocum']=$var[0];
			list($dbconn) = GetDBconn();
			$usuario=UserGetUID();
			if($_SESSION['tercer']['empresa']<>NULL AND $_SESSION['INFORM']['DATOS']==NULL)
			{
				$query = "INSERT INTO terceros
						(tipo_id_tercero,
						tercero_id,
						nombre_tercero,
						tipo_pais_id,
						tipo_dpto_id,
						tipo_mpio_id,
						direccion,
						telefono,
						fax,
						email,
						celular,
						sw_persona_juridica,
						busca_persona,
						usuario_id)
						VALUES
						('".$_POST['TipoDocum']."',
						'".$_POST['Documento']."',
						'".$_POST['nombre']."',
						'".$_POST['pais']."',
						'".$_POST['dpto']."',
						'".$_POST['mpio']."',
						'".$_POST['direccion']."',
						'".$_POST['telefono']."',
						'".$_POST['fax']."',
						'".$_POST['email']."',
						'".$_POST['celular']."',
						'".$_POST['persona']."',
						'".$_POST['beeper1']."',
						".$usuario.");";
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$_SESSION['tercer']['tipo_id_tercero']=$_POST['TipoDocum'];
				$_SESSION['tercer']['tercero_id']=$_POST['Documento'];
				$_SESSION['tercer']['nombre_tercero']=$_POST['nombre'];
				$_SESSION['INFORM']['DATOS']=$_SESSION['tercer'];
			}
			else if($_SESSION['tercer']['empresa']<>NULL AND $_SESSION['INFORM']['DATOS']<>NULL)
			{
				$query = "UPDATE terceros SET
						nombre_tercero='".$_POST['nombre']."',
						tipo_pais_id='".$_POST['pais']."',
						tipo_dpto_id='".$_POST['dpto']."',
						tipo_mpio_id='".$_POST['mpio']."',
						direccion='".$_POST['direccion']."',
						telefono='".$_POST['telefono']."',
						fax='".$_POST['fax']."',
						email='".$_POST['email']."',
						celular='".$_POST['celular']."',
						sw_persona_juridica='".$_POST['persona']."',
						busca_persona='".$_POST['beeper1']."',
						usuario_id=".$usuario."
						WHERE tipo_id_tercero='".$_SESSION['INFORM']['DATOS']['tipo_id_tercero']."'
						AND tercero_id='".$_SESSION['INFORM']['DATOS']['tercero_id']."';";
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
					}
				$_SESSION['tercer']['tipo_id_tercero']=$_POST['TipoDocum'];
				$_SESSION['tercer']['tercero_id']=$_POST['Documento'];
				$_SESSION['tercer']['nombre_tercero']=$_POST['nombre'];
			}
			$_SESSION['INFORM']['RETORNO']['sw']=2;//Guardó
			$this->ReturnMetodoExterno($_SESSION['INFORM']['RETORNO']['contenedor'],
			$_SESSION['INFORM']['RETORNO']['modulo'],
			$_SESSION['INFORM']['RETORNO']['tipo'],
			$_SESSION['INFORM']['RETORNO']['metodo']);
		}
		return true;
	}

}//fin de la clase
?>
