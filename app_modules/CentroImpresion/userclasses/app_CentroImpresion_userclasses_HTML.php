<?php
/**
* app_CentroImpresion_user_HTML.php  17/01/2003
*
* Proposito del Archivo: Manejo visual de las autorizaciones.
* Copyright (C) 2003 InterSoftware Ltda.
* Email: intersof@telesat.com.co
* @autor: Darling Liliana Dorado y Jairo Duvan Diaz
* @version SIIS v 0.1
* @package SIIS
*/


/**
*Contiene los metodos visuales para realizar las autorizaciones.
*/

class app_CentroImpresion_userclasses_HTML extends app_CentroImpresion_user
{
	/**
	*
	*/

  function app_CentroImpresion_user_HTML()
	{
				$this->salida='';
				$this->app_CentroImpresion_user();
				return true;
	}


  function SetStyle($campo)
	{
				if ($this->frmError[$campo] || $campo=="MensajeError"){
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					return ("label_error");
				}
			return ("label");
	}


	/**
	*
	*/
	function FormaMetodoBuscar($arr)
	{
			$this->salida.= ThemeAbrirTabla('BUSCAR PACIENTE');
			$accion=ModuloGetURL('app','CentroImpresion','user','Buscar');
      $this->salida .= "<table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
			$this->salida .= "<tr class=\"modulo_table_list_title\">";
			$this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA:</td>";
	    $this->salida .= "</tr>";
			$this->salida .= "<tr class=\"modulo_list_claro\" >";
			$this->salida .= "<td width=\"40%\" >";
			$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
			$this->salida .= "<tr><td>";
			$this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
			$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
			$tipo_id=$this->tipo_id_paciente();
			$this->BuscarIdPaciente($tipo_id,'');
			$this->salida .= "</select></td></tr>";
			$this->salida .= "<tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\"></td></tr>";
			$this->salida .= "<tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\"></td></tr>";
			$this->salida .= "<tr><td class=\"label\">No. SOLICITUD: </td><td><input type=\"text\" class=\"input-text\" name=\"Solicitud\" maxlength=\"32\"></td></tr>";
			$this->salida .= "<tr><td class=\"label\">No. ORDEN: </td><td><input type=\"text\" class=\"input-text\" name=\"Orden\" maxlength=\"32\"></td></tr>";
			$this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
			$this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
			$this->salida .= "</form>";
			$actionM=ModuloGetURL('system','Menu','user','main');
			$this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
			$this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form>";
			$this->salida .= "</tr>";
			$this->salida .= "</table></td></tr>";
			$this->salida .= "</td></tr></table>";
			$this->salida .= "</td>";
			$this->salida .= "<tr >";
			$this->salida .= "<td>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
      		$this->salida .= "</table>";
			$this->salida .= "		   </td>";
			$this->salida .= "		</tr>";
			$this->salida .= "	</table>";
			//mensaje
			$this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "	</table>";
			if(!empty($arr))
			{
						$d=0;
						$this->salida .= "		 <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
						$this->salida .= "			<tr align=\"center\" class=\"modulo_table_list_title\">";
						$this->salida .= "				<td width=\"25%\">IDENTIFICACION</td>";
						$this->salida .= "				<td width=\"45%\">PACIENTE</td>";
						$this->salida .= "				<td width=\"10%\"></td>";
						$this->salida .= "			</tr>";
						for($i=$d; $i<sizeof($arr); $i++)
						{
									if($i % 2) {  $estilo="modulo_list_claro";  }
									else {  $estilo="modulo_list_oscuro";   }
									$this->salida .= "			<tr class=\"$estilo\">";
									$this->salida .= "				<td>".$arr[$i][tipo_id_paciente]." ".$arr[$i][paciente_id]."</td>";
									$this->salida .= "				<td>".$arr[$i][nombre]."".$arr[$i][evolucion_id]."</td>";
									$accion=ModuloGetURL('app','CentroImpresion','user','Detalle',array('tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre]));
									$this->salida .= "				<td align=\"center\"><a href=\"$accion\">VER</a></td>";
									$this->salida .= "			</tr>";	
						}
						$this->salida .= " </table>";
						$this->conteo=$_SESSION['SPY2'];
						$this->salida .=$this->RetornarBarrad();
			}
			$this->salida .= ThemeCerrarTabla();
    	return true;

	}

	/**
	*
	*/
	function CalcularNumeroPasos($conteo){
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	function CalcularBarra($paso){
		$barra=floor($paso/10)*10;
		if(($paso%10)==0){
			$barra=$barra-10;
		}
		return $barra;
	}

	function CalcularOffset($paso){
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}


	function RetornarBarrad(){
		$this->conteo;
		$this->limit;

		if($this->limit>=$this->conteo){
				return '';
		}
		$paso=$_REQUEST['paso'];
		if(is_null($paso)){
    	$paso=1;
		}
   		$vec='';
		foreach($_REQUEST as $v=>$v1)
		{
			if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
			{   $vec[$v]=$v1;   }
		}
		if(empty($vec)) {  $vec=array(); }
		$accion=ModuloGetURL('app','CentroImpresion','user','Buscar',$vec);
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;

		$this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1){
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=1;
		}else{
     // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
    }
		$barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
      $colspan+=2;
		}else{
      $diferencia=$numpasos-9;
			if($diferencia<=0){$diferencia=1;}//cambiar en todas las barra
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					$this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos){
  			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}else{
       // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      }
		}
		if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
		$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
	}


 /**
	* Se utilizada listar en el combo los diferentes tipo de identifiacion de los pacientes
	* @access private
	* @return void
	*/
	function BuscarIdPaciente($tipo_id,$TipoId='')
	{
				foreach($tipo_id as $value=>$titulo)
				{
					if($value==$TipoId){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				}
	}

	/**
	*
	*/
	function FormaDetalle()
	{
			$this->salida .= ThemeAbrirTabla('DETALLE ORDENES Y SOLICITUDES');
			$this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= 						$this->SetStyle("MensajeError");
			$this->salida .= "				</table>";
			$this->salida .= "		 <table width=\"90%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" >";
			$this->salida .= "			<tr align=\"center\">";
			$this->salida .= "				<td colspan=\"8\" align=\"center\">";
			$this->salida .= "		 <table width=\"70%\" border=\"0\" align=\"center\">";
			$this->salida .= "			<tr>";
			$this->salida .= "				<td class=\"modulo_table_list_title\" colspan=\"6\" align=\"left\">DATOS PACIENTE </td>";
			$this->salida .= "			</tr>";
			$this->salida .= "			<tr>";
			$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">IDENTIFICACION: </td><td width=\"20%\" class=\"modulo_list_claro\">".$_SESSION['CENTRO']['IMPRESION']['PACIENTE']['tipo_id_paciente']." ".$_SESSION['CENTRO']['IMPRESION']['PACIENTE']['paciente_id']."</td>";
			$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">PACIENTE:</td><td width=\"40%\" class=\"modulo_list_claro\" colspan=\"3\">".$_SESSION['CENTRO']['IMPRESION']['PACIENTE']['nombre']."</td>";
			$this->salida .= "			</tr>";
			$this->salida .= " 			</table>";
			//solicitudes.
			if(!empty($_SESSION['CENTRO']['IMPRESION']['ARREGLOS']['SOLICITUDES']))
			{  $this->FormaSolicitudes('FormaDetalle');  }			
			//ordenes de servicios
			if(!empty($_SESSION['CENTRO']['IMPRESION']['ARREGLOS']['ORDENES']))
			{  $this->ListadoOsAuto('FormaDetalle');  }
			//ordenes no autorizadas.
			if(!empty($_SESSION['CENTRO']['IMPRESION']['ARREGLOS']['SOLICITUDESNOAUTO']))
			{  $this->ListadoOsNoAuto('FormaDetalle');  }
			$this->salida .= "			</td></tr>";
			$this->salida .= "				</table>";
			$this->salida.="<br><table align=\"center\" border=\"0\" width=\"85%\">";
			$this->salida.="<tr>";
			$this->salida.="  <td align=\"center\">";
			$accion=ModuloGetURL('app','CentroImpresion','user','Buscar',array('TipoDocumento'=>$_SESSION['CENTRO']['IMPRESION']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['CENTRO']['IMPRESION']['PACIENTE']['paciente_id'],'nombres'=>$_SESSION['CENTRO']['IMPRESION']['PACIENTE']['nombre']));
			$this->salida .='<form name="forma" action="'.$accion.'" method="post">';
			$this->salida .="<input type=\"submit\" align=\"center\" name=\"retorno\" value=\"Volver\" class=\"input-submit\"></form></td>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida .= ThemeCerrarTabla();
			return true;
	}




//dar
	function FormaSolicitudes($regreso)
	{
			$arr=$_SESSION['CENTRO']['IMPRESION']['ARREGLOS']['SOLICITUDES'];
			$this->salida .= "		 <br><table width=\"95%\" border=\"0\" align=\"center\">";
			$this->salida .= "			<tr class=\"modulo_table_title\"><td colspan=\"5\" align=\"center\">SOLICITUDES</td></tr>";
			for($i=0; $i<sizeof($arr);)
			{
					$d=$i;
					if($arr[$i][plan_id]==$arr[$d][plan_id]
					  AND $arr[$i][servicio]==$arr[$d][servicio])
					{
									$this->salida .= "			<tr><td colspan=\"5\" class=\"modulo_table_title\">PLAN:".$arr[$i][plan_descripcion]."</td></tr>";
									$this->salida .= "			<tr>";
									$this->salida .= "				<td class=\"modulo_table_title\" width=\"15%\">SERVICIO: </td>";
									$this->salida .= "				<td class=\"modulo_list_claro\" colspan='4' width=\"13%\">".$arr[$i][desserv]."</td>";
									$this->salida .= "			</tr>";
									$this->salida .= "			<tr class=\"modulo_table_title\">";
									$this->salida .= "				<td>FECHA</td>";
									$this->salida .= "				<td width=\"8%\">SOLICITUD</td>";
									$this->salida .= "				<td width=\"8%\">CARGO</td>";
									$this->salida .= "				<td width=\"50%\">DESCRIPCION</td>";
									$this->salida .= "				<td width=\"10%\">TIPO</td>";
									$this->salida .= "			</tr>";
					}
					while($arr[$i][plan_id]==$arr[$d][plan_id]
					 AND $arr[$i][servicio]==$arr[$d][servicio])
					{
							if($d % 2) {  $estilo="modulo_list_claro";  }
							else {  $estilo="modulo_list_oscuro";   }
							$this->salida .= "			<tr class=\"$estilo\">";
							$this->salida .= "				<td align=\"center\">".$this->FechaStamp($arr[$i][fecha])." ".$this->HoraStamp($arr[$i][fecha])."</td>";
							$this->salida .= "				<td align=\"center\">".$arr[$d][hc_os_solicitud_id]."</td>";
							$this->salida .= "				<td align=\"center\">".$arr[$d][cargos]."</td>";
							$this->salida .= "				<td>".$arr[$d][descar]."</td>";
							$this->salida .= "				<td align=\"center\">".$arr[$d][desos]."</td>";
							$this->salida .= "			</tr>";

							$value=$this->DatosTramite($arr[$d][hc_os_solicitud_id]);
							if(!empty($value))
							{
									$this->salida .= "			<tr class=\"$estilo\">";
									$this->salida .= "				<td colspan=\"5\"   cellspacing=\"5\"  cellspading=\"2\">";
									$this->salida .= "   <table border=\"0\" width=\"100%\" align=\"center\">";
									for($n=0; $n<sizeof($value); $n++)
									{
											$this->salida .= "             <tr class=\"modulo_table_list_title\">";
											$this->salida .= "             		<td colspan=\"8\" align=\"LEFT\">TRAMITES</td>";
											$this->salida .= "             </tr>";
											$this->salida .= "		      <tr>";
											if(!empty($value[$n][sw_personalmente]))
											{  $value[$n][nombre]='Personalmente';  }
											if(!empty($value[$n][sw_telefonica]))
											{  $value[$n][sw_telefonica]='Si';  }
											else
											{  $value[$n][sw_telefonica]='No';  }
											$this->salida .= "             		<td class=\"modulo_table_list_title\" align=\"LEFT\" width=\"12%\">RECIBIO: </td>";
											$this->salida .= "             		<td class=\"modulo_list_claro\">".$value[$n][nombre]."</td>";
											$this->salida .= "             		<td class=\"modulo_table_list_title\" align=\"LEFT\" width=\"7%\">FECHA: </td>";
											$this->salida .= "             		<td class=\"modulo_list_claro\" width=\"18%\">".$this->FechaStamp($value[$n][fecha_resgistro])." ".$this->HoraStamp($value[$n][fecha_resgistro])."</td>";
											$this->salida .= "             		<td class=\"modulo_table_list_title\" align=\"LEFT\" width=\"7%\">USUARIO: </td>";
											$this->salida .= "             		<td class=\"modulo_list_claro\" width=\"25%\">".$value[$n][usuario]."</td>";

											$this->salida .= "                 <td class=\"modulo_table_list_title\" align=\"LEFT\" width=\"4%\">TELE: </td>";
											$this->salida .= "                 <td class=\"modulo_list_claro\" width=\"2%\">".$value[$n][sw_telefonica]."</td>";

											$this->salida .= "		      </tr>";
											$this->salida .= "		      <tr>";
											$this->salida .= "             		<td class=\"modulo_table_list_title\" align=\"LEFT\">OBSERVACION : </td>";
											$this->salida .= "             		<td colspan=\"7\" class=\"modulo_list_claro\">".$value[$n][observacion_autorizador]."</td>";
											$this->salida .= "		      </tr>";
											$this->salida .= "		      <tr>";
											$this->salida .= "             		<td class=\"modulo_table_list_title\" align=\"LEFT\">OBS. PACIENTE: </td>";
											$this->salida .= "             		<td colspan=\"7\" class=\"modulo_list_claro\">".$value[$n][observacion_paciente]."</td>";
											$this->salida .= "		      </tr>";
									}
									$this->salida .= "			 </table>";
									$this->salida .= "				</td>";
									$this->salida .= "			</tr>";
							}
							$d++;
					}
					$i=$d;
			}
			//Variable de session que contiene el arreglo de las solicitudes para cuando se vayan a imprimir
			$_SESSION['CENTRAL']['ARR_SOLICITUDES']=$arr;
			$this->salida .= " </table>";
	}

/**
	* Separa la hora del formato timestamp
	* @access private
	* @return string
	* @param date hora
	*/



	/**
	*
	*/
	function ListadoOsNoAuto($regreso)
	{
			$arr=$_SESSION['CENTRO']['IMPRESION']['ARREGLOS']['SOLICITUDESNOAUTO'];
			if(!empty($arr))
			{
					$this->salida .= "	<br><table width=\"95%\" border=\"0\" align=\"center\" >";
					$this->salida .= "			<tr class=\"modulo_table_title\"><td colspan=\"7\" align=\"center\">SOLICITUDES NO AUTORIZADAS</td></tr>";
					$this->salida .= "			<tr class=\"modulo_table_list_title\">";
					$this->salida .= "				<td width=\"10%\">FECHA</td>";
					$this->salida .= "				<td width=\"10%\">CARGO</td>";
					$this->salida .= "				<td colspan=\"2\" width=\"50%\">DESCRIPCION</td>";
					$this->salida .= "				<td width=\"10%\">TIPO</td>";
					$this->salida .= "				<td width=\"11%\">PLAN</td>";
					$this->salida .= "				<td width=\"10%\"></td>";
					$this->salida .= "			</tr>";
					for($d=0; $d<sizeof($arr); $d++)
					{
									if($d % 2) {  $estilo="modulo_list_claro";  }
									else {  $estilo="modulo_list_oscuro";   }
									$this->salida .= "			<tr class=\"$estilo\">";
									$this->salida .= "				<td>".$this->FechaStamp($arr[$d][fecha])." ".$this->HoraStamp($arr[$d][fecha])."</td>";
									$this->salida .= "				<td align=\"center\">".$arr[$d][cargos]."</td>";
									$this->salida .= "				<td colspan=\"2\">".$arr[$d][descar]."</td>";
									$this->salida .= "				<td align=\"center\">".$arr[$d][desos]."</td>";
									$this->salida .= "				<td align=\"center\">".$arr[$d][plan_descripcion]."</td>";
									$accion=ModuloGetURL('app','CentroImpresion','user','ReporteSolicitudesNoAuto',array('regreso'=>$regreso,'datos'=>$arr[$d],'tipoid'=>$arr[$d][tipo_id_paciente],'paciente'=>$arr[$d][paciente_id],'solicitud'=>$arr[$d][hc_os_solicitud_id]));
									$this->salida .= "				<td align=\"center\" width=\"7%\"><img border='0' src=\"".GetThemePath()."/images/imprimir.png\"><a href=\"$accion\">&nbsp;IMPRIMIR</a></td>";
									$this->salida .= "			</tr>";
					}
					$this->salida .= " </table><br>";

			}
	}



	//DARLING
	/**
	* Separa la fecha del formato timestamp
	* @access private
	* @return string
	* @param date fecha
	*/

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

		/**
	* Separa la hora del formato timestamp
	* @access private
	* @return string
	* @param date hora
	*/
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


	/**
	*
	*/
	function ListadoOsAuto($regreso)
	{
			$var=$_SESSION['CENTRO']['IMPRESION']['ARREGLOS']['ORDENES'];
			if(!empty($var))
			{
				//	$this->salida .= ThemeAbrirTabla('ORDENES SERVICIO AUTORIZADAS',850);
					$rec=0;
					for($i=0; $i<sizeof($var);)
					{
								$d=$i;
								$this->salida .= "	<br><table width=\"95%\" border=\"1\" align=\"center\" >";
								$this->salida .= "			<tr class=\"modulo_table_title\">";
								$this->salida .= "				<td colspan=\"5\" align=\"left\">NUMERO DE ORDEN ".$var[$i][orden_servicio_id]."</td>";
								$this->salida .= "			</tr>";
								$this->salida .= "			<tr>";
								$this->salida .= "				<td colspan=\"5\" class=\"modulo_list_claro\">";
								$this->salida .= "						<table width=\"100%\" border=\"1\" align=\"center\" class=\"\">";
								$this->salida .= "								<tr>";
								$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">TIPO AFILIADO: </td>";
								$this->salida .= "										<td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][tipo_afiliado_nombre]."</td>";
								$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">RANGO: </td>";
								$this->salida .= "										<td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][rango]."</td>";
								$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">SEMANAS COT.: </td>";
								$this->salida .= "										<td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][semanas_cotizadas]."</td>";
								$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">SERVICIO: </td>";
								$this->salida .= "										<td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][desserv]."</td>";
								$this->salida .= "								</tr>";
								$this->salida .= "								<tr>";
								$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">AUT. INT.: </td>";
								$this->salida .= "										<td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][autorizacion_int]."</td>";
								$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">AUT. EXT: </td>";
								$this->salida .= "										<td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][autorizacion_ext]."</td>";
								$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">AUTORIZADOR: </td>";
								$this->salida .= "										<td width=\"5%\" colspan=\"3\" class=\"hc_table_submodulo_list_title\">".$var[$d][autorizador]."</td>";
								$this->salida .= "								</tr>";
								$this->salida .= "								<tr>";
								$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">PLAN: </td>";
								$this->salida .= "										<td width=\"5%\" class=\"hc_table_submodulo_list_title\" colspan=\"7\" align=\"left\">".$var[$d][plan_descripcion]."</td>";
								$this->salida .= "								</tr>";
								$this->salida .= "								<tr>";
								$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">OBSERVACIONES: </td>";
								$this->salida .= "										<td width=\"5%\" colspan=\"7\" class=\"hc_table_submodulo_list_title\" align=\"left\">".$var[$d][observacion]."</td>";
								$this->salida .= "								</tr>";
								$this->salida .= " 						</table>";
								$this->salida .= "				</td>";
								$this->salida .= "			</tr>";
								$sw_conteo=0;
								while($var[$i][orden_servicio_id]==$var[$d][orden_servicio_id])
								{
										$this->salida .= "			<tr>";
										$this->salida .= "				<td colspan=\"5\">";
										$this->salida .= "				<table width=\"99%\" border=\"0\" align=\"center\">";
										$this->salida .= "			<tr class=\"modulo_table_list_title\">";
										$this->salida .= "				<td width=\"6%\">ITEM</td>";
										$this->salida .= "				<td width=\"6%\">CANT.</td>";
										$this->salida .= "				<td width=\"10%\">CARGO</td>";
										$this->salida .= "				<td width=\"45%\">DESCRICPION</td>";
										$this->salida .= "				<td width=\"20%\">PROVEEDOR</td>";
										$this->salida .= "			</tr>";
										if($d % 2) {  $estilo="modulo_list_claro";  }
										else {  $estilo="modulo_list_oscuro";   }
										$this->salida .= "			<tr class=\"$estilo\">";
										$this->salida .= "				<td align=\"center\">".$var[$d][numero_orden_id]."</td>";
										$this->salida .= "				<td align=\"center\">".$var[$d][cantidad]."</td>";
										if(!empty($var[$d][cargo_cups])){  $cargo=$var[$d][cargo_cups];  }
										else {  $cargo=$var[$d][cargoext];   }
										$this->salida .= "				<td align=\"center\">".$cargo."</td>";
										$this->salida .= "				<td>".$var[$d][descripcion]."</td>";
										$p='';
										if(!empty($var[$d][departamento]))
										{  $p='DPTO. '.$var[$d][desdpto];  $id=$var[$d][departamento]; }
										else
										{  $p=$var[$d][planpro];  $id=$var[$d][plan_proveedor_id];}
										if($var[$d][sw_estado]==7)
										{  $p='TRANSCRIPCION';  }
										$this->salida .= "				<td align=\"center\">".$p."</td>";
										$this->salida .= "			</tr>";
										$this->salida .= "			<tr class=\"modulo_list_oscuro\">";
										$this->salida .= "				<td colspan=\"5\">";
										$this->salida .= "						<table width=\"100%\" border=\"0\" align=\"center\">";
										$this->salida .= "								<tr class=\"modulo_list_claro\">";
										$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">ACTIVACION: </td>";
										$this->salida .= "										<td width=\"5%\" colspan=\"2\">".$this->FechaStamp($var[$d][fecha_activacion])."</td>";
										$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">VENC.: </td>";
										$x='';
										if(date("Y-m-d") > $var[$d][fecha_vencimiento]) $x='VENCIDA';
										$this->salida .= "										<td width=\"5%\" >".$this->FechaStamp($var[$d][fecha_vencimiento])."</td>";
										$this->salida .= "										<td width=\"5%\" class=\"label_error\" align=\"center\">".$x."</td>";
										$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">REFRENDAR HASTA: </td>";
										$this->salida .= "										<td width=\"5%\">".$this->FechaStamp($var[$d][fecha_refrendar])."</td>";
										$this->salida .= "								</tr>";
										$this->salida .= " 						</table>";
										$this->salida .= "		<table width=\"100%\" border=\"0\" align=\"center\">";
										$this->salida .= "			<tr class=\"modulo_list_claro\" align=\"center\">";
										$this->salida .= "										<td width=\"7%\" class=\"modulo_table_list_title\">ESTADO: </td>";
										$this->salida .= "										<td width=\"9%\" class=\"hc_table_submodulo_list_title\" colspan=\"2\">".$var[$d][estado]."</td>";
										$this->salida .= "				<td width=\"20%\"></td>";
										$accion=ModuloGetURL('app','CentroImpresion','user','ReporteOrdenServicio',array('regreso'=>$regreso,'orden'=>$var[$d][orden_servicio_id],'plan'=>$var[$d][plan_id],'tipoid'=>$var[$d][tipo_id_paciente],'paciente'=>$var[$d][paciente_id],'afiliado'=>$var[$d][tipo_afiliado_id]));
										if($x!='VENCIDA' AND ($var[$d][estado]=='PAGADO' OR $var[$d][estado]=='ACTIVO' OR $var[$d][estado]=='TRANSCRIPCION'))
										{
											$sw_conteo=$sw_conteo+1;
											$rec=$this->RevisarRecepcionOrden($var[$i][orden_servicio_id]);
											$this->salida .= "				<td width=\"10%\"></td>";
											$dat=array('regreso'=>$regreso,'orden'=>$var[$d][orden_servicio_id],'plan'=>$var[$d][plan_id],'tipoid'=>$var[$d][tipo_id_paciente],'paciente'=>$var[$d][paciente_id],'afiliado'=>$var[$d][tipo_afiliado_id]);
										}
										else
										{ $sw_conteo=0;	$this->salida .= "				<td width=\"10%\"></td>";  }
										$this->salida .= "			</tr>";
										$this->salida .= " 			</table>";
										$this->salida .= "				</td>";
										$this->salida .= "			</tr>";
										$this->salida .= " 			</table>";
										$this->salida .= "				</td>";
										$this->salida .= "			</tr>";
										$d++;
								}
								if(!empty($rec))
								{
										$this->salida .= "		<tr>";
										$this->salida .= "			<td colspan=\"5\">";
										$this->salida .= "				<table width=\"99%\" border=\"0\" align=\"center\">";
										$this->salida .= "					<tr class=\"modulo_table_title\">";
										$this->salida .= "					<td colspan=\"3\">ENTREGAS</td>";
										$this->salida .= "					</tr>";
										$this->salida .= "					<tr class=\"modulo_table_list_title\">";
										$this->salida .= "						<td width=\"6%\">RECIBIO</td>";
										$this->salida .= "						<td width=\"6%\">FECHA</td>";
										$this->salida .= "						<td width=\"6%\">USUARIO</td>";
										$this->salida .= "					</tr>";
										for($j=0; $j<(sizeof($rec)); $j++)
										{
												$this->salida .= "					<tr>";
												if(!empty($rec[$j][sw_personalmente]))
												{  $x='PERSONALMENTE'; }
												else
												{  $x=$rec[$j][nombre];  }
												$this->salida .= "						<td width=\"6%\" class=\"modulo_list_claro\">".$x."</td>";
												$this->salida .= "						<td width=\"6%\" class=\"modulo_list_claro\" align=\"center\">".$this->FechaStamp($rec[$j][fecha_registro])." ".$this->HoraStamp($rec[$j][fecha_registro])."</td>";
												$this->salida .= "						<td width=\"6%\" class=\"modulo_list_claro\">".$rec[$j][nom]."</td>";
												$this->salida .= "					</tr>";
										}
										$this->salida .= " 				</table>";
										$this->salida .= "			</td>";
										$this->salida .= "		</tr>";
								}								
								$accion=ModuloGetURL('app','CentroImpresion','user','ReporteOrdenServicio',$dat);
								if($sw_conteo > 0)
								{
										IF(sizeof($rec) < 1)
										{$boton='Imprimir';}else{$boton='Re-Imprimir';}
										$this->salida .='<form name=formilla'.$i.'  action="'.$accion.'" method="post">';
										$this->salida .= "				<tr class='modulo_list_claro'><td  class='modulo_list_claro' align='left' width=\"60%\"><label class='label_mark'>&nbsp;Nombre: &nbsp;</label>&nbsp;<input type='text' size='40' maxlength='40' name='nom' class=\"input-text\">&nbsp;&nbsp;&nbsp;<label class='label_mark'>Personal</label>&nbsp;<input type=\"checkbox\" name=\"sw_personal\"></td>";
										$this->salida .= "				<td  class='modulo_list_claro' align='left' width=\"10%\"  colspan=\"4\"><input class='input-submit' type='submit' name='mandar' value='$boton'>&nbsp;<label class='label_error'>".$_REQUEST['v']."</label></a></td></tr>";
										$this->salida .='</form>';
								}
								$i=$d;
								$this->salida .= " 			</table><br>";
					}//fin for
				//	$this->salida .= ThemeCerrarTabla();
			}

			
	}

   /**
  * Forma para los mansajes
  * @access private
  * @return void
  */
  function FormaMensaje($mensaje,$titulo,$accion,$boton)
  {
        $this->salida .= ThemeAbrirTabla($titulo);
        $this->salida .= "            <table width=\"60%\" align=\"center\" >";
        $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "               <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
        if($boton){
           $this->salida .= "               <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
        }
       else{
           $this->salida .= "               <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
       }
        $this->salida .= "           </form>";
        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }

//-----------------------------------------------------------------------------------
}//fin clase

?>

