<?php
//Reporte de prueba formato HTML
//
//Un reporte es una clase con el nombre de reporte y el sufijo '_report'
class concentimiento_html_report
{
	//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
	var $datos;

	//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
	//NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

    //CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
    function evolucion_odontologica_html_report($datos=array())
    {
          $this->datos=$datos;
          return true;
    }


	function GetMembrete()
	{
		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
                                                                 'subtitulo'=>'',
                                                                 'logo'=>'logo_grande.jpg',
                                                                 'align'=>'left'));
		return $Membrete;
	}

//FUNCION CrearReporte()
//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
	function CrearReporte()
	{
		$pfj=$this->frmPrefijo;
		$ingreso=$_SESSION['con']['ingreso'];
		$evolucion=$_SESSION['con']['evolucion'];
		$user=$_SESSION['con']['usuario'];
		//echo $_SESSION['con']['busquedas']; exit;
		$infoEvolucion = $this->Get_Formato($_SESSION['con']['busquedas']);//id_concentimiento
		//print_r($infoEvolucion); exit;
		//$Cuentas = $this->BuscarCuentas($this->datos[cuenta]);
		//$usuario = $this->NombreUs($this->datos[usuario_id]);

		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar', 'inscripcion'.$pfj=>$programas));
		$Salida.="<form name=\"evoluciones_odontologicas$pfj\" action=\"$accion\" method=\"post\">";

		$Salida.="<br><br><center>";
		$Salida.="<label><font size='5' face='arial'>".$infoEvolucion[0][descripcion]."</font></label>";
		$Salida.="</center><br><br><br><br>";

		$Salida.="<table border=\"0\" width=\"80%\" align=\"center\" class=\"hc_table_list\">";
		$Salida.="<tr class=\"hc_table_list_title\">";
		$Salida.="<td width=\"100%\" align=\"justify\"><font size='4' face='arial'>".$infoEvolucion[0][formato]."</font></td>";
		$datos_paciente=$this->Select_Datos_Paciente($ingreso);     
		$Salida.="</tr>";
		$Salida.="<tr class=\"hc_table_list_title\">";
		$Salida.="<td width=\"100%\" align=\"left\"><font size='2' face='arial'>".$datos_paciente[0][nombre]."</font></td>";
		$Salida.="</tr>";
		$Salida.="<tr class=\"hc_table_list_title\">";
		$Salida.="<td width=\"100%\" align=\"left\"><font size='2' face='arial'>".$datos_paciente[0][tipo_id_paciente]."-".$datos_paciente[0][paciente_id]."</font></td>";
		$Salida.="</tr>";
		$Salida.="</table><BR>";
		$Salida.="<br><br>";
//
    $datos1=$this->BuscarResponsable($ingreso,$evolucion);
		if ($datos1[4]<>NULL)
		{
			$Salida.="<table border=\"0\" width=\"80%\" align=\"center\" class=\"hc_table_list\">";
			$Salida.="<tr class=\"modulo_table_list_title\">";
			//$Salida.="<td align=\"left\" width=\"5%\">SEL</td>";
			$Salida.="<td align=\"center\" width=\"95%\"><font size=2><b>CONCENTIMIENTOS INFORMADOS</b></font></td>";
			$Salida.="</tr>";
			$tmp=explode('-',$datos1[4]);
			for($j=0;$j<sizeof($tmp);$j++)
			{
				$subtmp[$j]=explode(',',$tmp[$j]);
				$subtmp1[$j]=$subtmp[$j][1];
			}
			for($i=0; $i<sizeof($subtmp1); $i++)
			{
				$datos2=$this->BuscarDescripcionItemInf($_SESSION['con']['busquedas'],$subtmp1[$i]);
				$Salida.="<tr class=\"modulo_list_claro\">";
				//$Salida.="<td align=\"left\" width=\"5%\"><input type=checkbox name=\"con".$pfj."".$i."\" value=\"".$areas[$i][id_concentimiento].','.$areas[$i][item_id]."\"></td>";
				$Salida.="<td align=\"left\" width=\"95%\">".$datos2."</td>";
				$Salida.="</tr>";
			}
			$Salida.="</table><BR>";
		} 

		if ($datos1[5]<>NULL)
		{
			$Salida.="<table border=\"0\" width=\"80%\" align=\"center\" class=\"hc_table_list\">";
			$Salida.="<tr class=\"modulo_table_list_title\">";
			$Salida.="<td align=\"center\" width=\"95%\"><b>OBSERVACIONES</b></td>";
			$Salida.="</tr>";
			$Salida.="<tr class=\"modulo_list_claro\">";
			$Salida.="<td align=\"left\" width=\"95%\">".$datos1[5]."</td>";
			$Salida.="</tr>";
			$Salida.="</table><BR>";
		} 

//
		if ($datos[0]<>NULL)
		{
			$Salida.="<table border=\"0\" width=\"80%\" align=\"center\" class=\"hc_table_list\">";
			$Salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$Salida.="<td width=\"60%\" align=\"center\"><font size='3' face='arial'>RESPONSABLE</font></td>";
			$Salida.="</tr>";
			$Salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$Salida.="<td width=\"20%\" align=\"left\"><font size='3' face='arial'>TIPO DOCUMENTO</font></td><td width=\"80%\" align=\"left\"><font size='2' face='arial'>".$datos[0]."</font></td>";
			$Salida.="</tr>";
			$Salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$Salida.="<td width=\"20%\" align=\"left\"><font size='3' face='arial'>DOCUMENTO</font></td><td width=\"80%\" align=\"left\"><font size='2' face='arial'>".$datos[1]."</font></td>";
			$Salida.="</tr>";
			$Salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$Salida.="<td width=\"20%\" align=\"left\"><font size='3' face='arial'>NOMBRE</font></td><td width=\"80%\" align=\"left\"><font size='2' face='arial'>".$datos[2]."</font></td>";
			$Salida.="</tr>";
			$parentesco=$this->BuscarParentesco($datos[3]);
			$Salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$Salida.="<td width=\"20%\" align=\"left\"><font size='3' face='arial'>PARENTESCO</font></td><td width=\"80%\" align=\"left\"><font size='2' face='arial'>".$parentesco."</font></td>";
			$Salida.="</tr>";
			$Salida.="</table><BR>";
		} 
			$usuario=$this->NombreUs($user);
			$Salida.="<table border=\"0\" width=\"80%\" align=\"center\" class=\"hc_table_list\">";
			$Salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$Salida.="<td width=\"20%\" align=\"left\"><font size='2' face='arial'><b>USUARIO:</b></font></td><td width=\"80%\" align=\"left\"><font size='2' face='arial'>".$usuario[0]." - ".$usuario[1]."</font></td>";
			$Salida.="</tr>";
			$Salida.="</table><BR>";
          
		$Salida.="</form>";
    return $Salida;
   }


	//AQUI TODOS LOS METODOS QUE USTED QUIERA
	function Get_Formato($concentimiento)
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$concentimiento=$_SESSION['con']['busquedas'];
		$query="SELECT descripcion,formato
		FROM hc_concentimientos
		WHERE id_concentimiento=$concentimiento";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while (!$resulta->EOF)
		{
		$odonto[]=$resulta->GetRowAssoc($ToUpper = false);
		$resulta->MoveNext();
		}

		return $odonto;
	}

	function BuscarResponsable($ingreso,$evolucion)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT MAX(hc_concentimientos_confirmaciones_id)
		FROM hc_concentimientos_confirmaciones;";
		$result = $dbconn->Execute($query);

		$query="SELECT 	tipo_id_reponsable,
										id_responsable,
										parentesco_responsable,
										nombre_responsable,
										concentimientos_informados,
										observacion
		FROM hc_concentimientos_confirmaciones
		WHERE ingreso=".$ingreso."
					AND evolucion_id=".$evolucion."
					AND hc_concentimientos_confirmaciones_id=".$result->fields[0].";";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$odonto[0]=$resulta->fields[0];
		$odonto[1]=$resulta->fields[1];
		$odonto[2]=$resulta->fields[2];
		$odonto[3]=$resulta->fields[3];
		$odonto[4]=$resulta->fields[4];
		$odonto[5]=$resulta->fields[5];
		return $odonto;
	}

	function NombreUs($user)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT usuario_id, nombre
		FROM system_usuarios
		WHERE usuario_id=".$user.";";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}

		$usuario[0]=$resulta->fields[0];
		$usuario[1]=$resulta->fields[1];
		//list($usuario) = $resulta->FetchRow();se utiliza cuando solo hay un campo en le select
		return $usuario;
	}
     
	function BuscarParentesco($parentesco_id)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT descripcion
		FROM tipos_parentescos
		WHERE tipo_parentesco_id='".$parentesco_id."';";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		list($parentesco) = $resulta->FetchRow();
		return $parentesco;
	}

	function BuscarDescripcionItemInf($id_concentimiento, $vectoritems)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT A.descripcion
							FROM hc_concentimientos_items A,
										hc_concentimientos B
							WHERE A.id_concentimiento=".$id_concentimiento."
							AND A.item_id=".$vectoritems."
							AND A.id_concentimiento=B.id_concentimiento;";
			$resulta = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($descripcion) = $resulta->FetchRow();
	/*		while(!$resulta->EOF)
			{
				$var[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}*/
			return $descripcion;
	}
     
	function Select_Datos_Paciente($ingreso)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT a.tipo_id_paciente, a.paciente_id, btrim(b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido) as nombre
						FROM ingresos a, pacientes b
						WHERE b.ingreso=a.ingreso AND a.ingreso = $ingreso;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}



	function FechaStamp($fecha)
	{
          if($fecha){
               $fech = strtok ($fecha,"-");
               for($l=0;$l<3;$l++)
               {
                    $date[$l]=$fech;
                    $fech = strtok ("-");
               }
               return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
          }
	}

	function HoraStamp($hora)
     {
          $hor = strtok ($hora," ");
          for($l=0;$l<4;$l++)
          {
                    $time[$l]=$hor;
                    $hor = strtok (":");
          }

          $x = explode (".",$time[3]);
          return  $time[1].":".$time[2].":".$x[0];
     }
    //---------------------------------------
}

?>
