<?php

class app_CapitacionCoomeva_userclasses_HTML extends app_CapitacionCoomeva_user
{

	function app_CapitacionCoomeva_user_HTML()
	{
	  $this->app_CapitacionCoomeva_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}


	function CreacionUrgenciasContinuacion()
	{
		$fecham=$_REQUEST['Fecha_Urg'];
		$Plan=$_REQUEST['Responsable'];
		$fecha=$_REQUEST['Fecha_final'];
		list($dbconn) = GetDBconn();

		$sql="select nextval('asignanombrevirtualgraph_seq')";
		$result = $dbconn->Execute($sql);

		if($dbconn->ErrorNo() != 0) {
			die ($dbconn->ErrorMsg());
			return false;
		}
		$seq=$result->fields[0];
   	$this->salida  .= ThemeAbrirTabla('Sistema Integral de Informacion en Salud','500');
		if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
		{
				$this->error = "Error";
				$this->mensajeDeError = "No se pudo incluir : classes/notas_enfermeria/revision_sistemas.class.php";
				return false;
		}
		if(!class_exists('BDAfiliados'))
		{
				$this->error="Error";
				$this->mensajeDeError="no existe BDAfiliados";
				return false;
		}
		if(!file_exists("cache/pacientesurg$fecha.txt"))
		{
			echo "no existe";
		}
		$fp=fopen("cache/datoscompletospaurg$fecha.txt","r");
		if(!$fp)
		{
			echo 'no abrio';
		}
		$fw=fopen("cache/tmpdatoscompletospaurg$seq.txt","a+");
		if(!$fw)
		{
			echo 'no creo';
		}
		$actual=fgets($fp,4096);
		$actual=str_replace("\r",'',$actual);
		$actual=str_replace("\n",'',$actual);
		$actual=str_replace("\r\n",'',$actual);
		$texto=$actual.','.$fecham."\r\n";
		$this->escribir($fw,$texto);
		$i=0;
		while(!feof($fp))
		{
			$actual=fgets($fp,4096);
			if($actual!="\r\n")
			{
				$datos=explode(',',$actual);
				$datos[0]=trim($datos[0]);
				$datos[1]=trim($datos[1]);
				$class= New BDAfiliados($datos[0],$datos[1],$Plan,true,$fecham);
				$class->GetDatosAfiliado();
				$salida=$class->salida;
				$actual=str_replace("\r",'',$actual);
				$actual=str_replace("\n",'',$actual);
				$actual=str_replace("\r\n",'',$actual);
				if($salida[campo_activo]===0)
				{
					$texto=$actual.','.'No Activo'."\r\n";
				}
				else
				{
					if(empty($salida))
					{
						$texto=$actual.','.'No Esta'."\r\n";
					}
					else
					{
						$texto=$actual.','.$salida[campo_activo]."\r\n";
					}
				}
				$this->escribir($fw,$texto);
				unset($class);
			}
		}
		if(!fclose($fp))
		{
			die('no cierra');
		}
		if(!fclose($fw))
		{
			die('no cierra');
		}
		if(rename("cache/tmpdatoscompletospaurg$seq.txt","cache/datoscompletospaurg$fecha.txt")==false)
		{
			return false;
		}
		unset ($_SESSION['FechasCoomeva'][$fecham]);
		$i=0;
		$this->salida.="<table align=\"center\" border=\"1\">";
		foreach($_SESSION['FechasCoomeva'] as $k=>$v)
		{
			if($i==0)
			{
				$this->salida.="<tr>";
			}
			$this->salida.="<td>";
			$accion=ModuloGetUrl('app','CapitacionCoomeva','user','FormaUrgenciasContinuacion',array('Responsable'=>$_REQUEST['Responsable'],'Fecha_Urg'=>$k,'Fecha_final'=>$fecha));
			$this->salida.='<a href="'.$accion.'" class="link">'.$k.'</a>';
			$this->salida.="</td>";
			$i++;
			if($i==3)
			{
				$this->salida.="</tr>";
				$i=0;
			}
		}
		$this->salida.="</table>";
		$this->salida.="<table align=\"center\" border=\"0\">";
		$this->salida.="<tr>";
		$this->salida.="<td>";
		$accion=ModuloGetUrl('app','CapitacionCoomeva','user','main');
		$this->salida.="<a href=\"$accion\" class=\"link\">VOLVER</a>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


	function CreacionUrgencias()
 	{
		$fecha=$_REQUEST['Fecha_Urg'];
		$Plan=$_REQUEST['Responsable'];
		list($dbconn) = GetDBconn();
   	$this->salida  .= ThemeAbrirTabla('Sistema Integral de Informacion en Salud','500');
		if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
		{
				$this->error = "Error";
				$this->mensajeDeError = "No se pudo incluir : classes/notas_enfermeria/revision_sistemas.class.php";
				return false;
		}
		if(!class_exists('BDAfiliados'))
		{
				$this->error="Error";
				$this->mensajeDeError="no existe BDAfiliados";
				return false;
		}
		$class= New BDAfiliados('','',$Plan,true,$fecha);
		$result=$class->Urgencias();
		if($result==false)
		{
			$this->error=$class->error;
			$this->mensajeDeError=$class->mensajeDeError;
			return false;
		}
		$i=0;
		$t=0;
		if(file_exists("cache/pacientesurg$fecha.txt"))
		{
			if(!unlink("cache/pacientesurg$fecha.txt"))
			{
				echo "no pudo";
			}
		}
		$fw=fopen("cache/pacientesurg$fecha.txt","a+");
		if(!$fw)
		{
			die('no se pudo abrir el archivo');
		}
		while(!$result->EOF)
		{
			$texto=$result->fields[0].','.$result->fields[1]."\r\n";
			$this->escribir($fw,$texto);
			$result->MoveNext();
		}
		if(!fclose($fw))
		{
			die('no se pudo cerrar el archivo');
		}
		$fp=fopen("cache/pacientesurg$fecha.txt","r");
		if(!$fp)
		{
			die('no abre');
		}
		if(file_exists("cache/datoscompletospaurg$fecha.txt"))
		{
			if(!unlink("cache/datoscompletospaurg$fecha.txt"))
			{
				echo "no pudo";
			}
		}
		$fw=fopen("cache/datoscompletospaurg$fecha.txt","a+");
		if(!$fw)
		{
			die('no se pudo abrir el archivo');
		}
		$texto='tipo_documento,documento,nombre,fecha_entrada,empresa,'.$fecha."\r\n";
		$this->escribir($fw,$texto);
		$i=0;
		while(!feof($fp))
		{
			$actual=fgets($fp,4096);
			if($actual!="\r\n")
			{
				$datos=explode(',',$actual);
				$datos[0]=trim($datos[0]);
				$datos[1]=trim($datos[1]);
				$class= New BDAfiliados($datos[0],$datos[1],$Plan,true,$fecha);
				$class->GetDatosAfiliado();
				$salida=$class->salida;
				$texto=$salida['campo_tipodocumento'].','.$salida['campo_documento'].','.$salida[campo_Primer_nombre].' '.$salida[campo_Segundo_nombre].' '.$salida[campo_Primer_apellido].' '.$salid8a[campo_Segundo_apellido].',2004-02-12,'.$salida[campo_empleador];
				if($salida[campo_activo]===0)
				{
					$texto=$texto.','.'No Activo'."\r\n";
				}
				else
				{
					if(empty($salida))
					{
						$texto=$texto.','.'No Esta'."\r\n";
					}
					else
					{
						$texto=$texto.','.$salida[campo_activo]."\r\n";
					}
				}
				$this->escribir($fw,$texto);
				unset($class);
			}
		}
		if(!fclose($fp))
		{
			die('no cierra');
		}
		if(!fclose($fw))
		{
			die('no cierra');
		}
		unset ($_SESSION['FechasCoomeva'][$fecha]);
		$i=0;
		$this->salida.="<table align=\"center\" border=\"1\">";
		foreach($_SESSION['FechasCoomeva'] as $k=>$v)
		{
			if($i==0)
			{
				$this->salida.="<tr>";
			}
			$this->salida.="<td>";
			$accion=ModuloGetUrl('app','CapitacionCoomeva','user','FormaUrgenciasContinuacion',array('Responsable'=>$_REQUEST['Responsable'],'Fecha_Urg'=>$k,'Fecha_final'=>$fecha));
			$this->salida.='<a href="'.$accion.'" class="link">'.$k.'</a>';
			$this->salida.="</td>";
			$i++;
			if($i==3)
			{
				$this->salida.="</tr>";
				$i=0;
			}
		}
		$this->salida.="</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


	function FormaPrincipal()
 	{
		unset($_SESSION['FechasCoomeva']);
		$responsables=$this->responsables();
   	$this->salida  .= ThemeAbrirTabla('Capitación de Coomeva');
		$accion=ModuloGetUrl('app','CapitacionCoomeva','user','main');
		$this->salida.="<form name=\"\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table align=\"center\">";
		$this->salida.="<tr>";
		$this->salida.="<td>";
		$this->salida.="<select name=\"Responsable\" class=\"select\">";
		$this->MostrarResponsable($responsables,$_REQUEST['Responsable']);
		$this->salida.="</select>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr align=\"center\">";
		$this->salida.="<td>";
		$this->salida.="<input type=\"submit\" name=\"BUSCAR\" value=\"BUSCAR\" class=\"input-submit\">";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="</form>";
		if($_REQUEST['Responsable']!=-1)
		{
			$datos=$this->DatosCoomeva();
			$this->salida.="<br><br>";
			$this->salida.="<table align=\"center\" border=\"1\">";
			$i=0;
			foreach($datos as $k=>$v)
			{
				$_SESSION['FechasCoomeva'][$v['fecha_radicacion']]=1;
				if($i==0)
				{
					$this->salida.="<tr>";
				}
				$this->salida.="<td>";
				$accion=ModuloGetUrl('app','CapitacionCoomeva','user','FormaUrgencias',array('Responsable'=>$_REQUEST['Responsable'],'Fecha_Urg'=>$v['fecha_radicacion']));
				$this->salida.='<a href="'.$accion.'">'.$v['fecha_radicacion'].'</a>';
				$this->salida.="</td>";
				$i++;
				if($i==3)
				{
					$this->salida.="</tr>";
					$i=0;
				}
			}
			$this->salida.="</table>";
		}
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/**
	* Muestra el nombre del tercero con sus respectivos planes
	* @access private
	* @return string
	* @param array arreglor con los tipos de responsable
	* @param int el responsable que viene por defecto
	*/
 function MostrarResponsable($responsables,$Responsable)
 {
      $this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
			for($i=0; $i<sizeof($responsables); $i++)
			{
					if($responsables[$i][plan_id]==$Responsable){
							$this->salida .=" <option value=\"".$responsables[$i][plan_id]."\" selected>".$responsables[$i][plan_descripcion]."</option>";
					}else{
							$this->salida .=" <option value=\"".$responsables[$i][plan_id]."\">".$responsables[$i][plan_descripcion]."</option>";
					}
			}
 }

	function escribir($fw,$texto)
	{
		fwrite($fw,$texto);
	}

}//fin de la clase
?>

