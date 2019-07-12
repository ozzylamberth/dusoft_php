<?php

/**
 * $Id: app_Remisiones_userclasses_HTML.php,v 1.2 2005/06/02 23:10:36 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo logico de las autorizaciones.
 */

/**
*Contiene los metodos visuales para realizar las autorizaciones.
*/

class app_Remisiones_userclasses_HTML extends app_Remisiones_user
{
	/**
	*Constructor de la clase app_Autorizacion_user_HTML
	*El constructor de la clase app_Autorizacion_user_HTML se encarga de llamar
	*a la clase app_Autorizacion_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  function app_Remisiones_user_HTML()
	{
				$this->salida='';
				$this->app_Remisiones_user();
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
	function FormaRemision($arr,$vector,$diag)
	{
				$this->salida .= ThemeAbrirTabla('HOJA TRIAGE');
				IncludeLib("funciones_admision");
				$dat=$this->DatosTriage($_SESSION['REMISIONES']['DATOS']['triage_id']);
				$accion=MoDuloGetURL('app','Remisiones','user','AccionesRemision');
				$this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "			  </table><br>";
				$this->salida .= "            <form name=\"forma\" action=\"$accion\" method=\"post\">";
				$this->salida .= "			      <table width=\"70%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= "				       <tr>";
				$this->salida .= "				          <td class=\"modulo_list_claro\" colspan=\"4\" align=\"center\"><b>DEPARTAMENTO DE SERVICIOS DE ".$dat['descripcion']."</b></td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\">INSTITUCION QUE REMITE: </td>";
				$this->salida .= "				          <td class=\"modulo_list_claro\" colspan=\"3\">".$this->NombreEmpresa($dat['empresa_id'])."</td>";
				$this->salida .= "				       </tr>";
				$rem=$this->BuscarRemision();
				if(!empty($rem))
				{
						$this->salida .= "				       <tr>";
						$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\">REMITIDO POR: </td>";
						$this->salida .= "				          <td class=\"modulo_list_claro\" colspan=\"3\">".$rem[descripcion]."</td>";
						$this->salida .= "				       </tr>";
				}
				$this->salida .= "				       <tr>";
				$this->salida .= "				          <td align=\"center\" width=\"25%\" class=\"modulo_table_list_title\">IDENTIFICACION: </td>";
				$this->salida .= "				          <td class=\"modulo_list_claro\" width=\"17%\">".$dat['tipo_id_paciente']." ".$dat['paciente_id']."</td>";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\" width=\"10%\">PACIENTE: </td>";
				$this->salida .= "				          <td class=\"modulo_list_claro\" width=\"30%\">".$this->NombrePaciente($dat['tipo_id_paciente'],$dat['paciente_id'])."</td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\">CLASIFICACION: </td>";
				$estilo=ColorTriage($dat[nivel_triage_id]);
				$this->salida .= "				          <td class=\"$estilo\">NIVEL ".$dat[nivel_triage_id]."</td>";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\">FECHA: </td>";
				$this->salida .= "				          <td class=\"modulo_list_claro\">".date('d/m/Y h:i')."</td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr >";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\">CAUSAS PROBABLES: </td>";
				$this->salida .= "				          <td class=\"modulo_list_claro\" colspan=\"3\">";
				$this->salida .= "			      	 <table width=\"100%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= "				         <tr class=\"modulo_table_list_title\">";
				$this->salida .= "                   <td>NIVEL</td>";
				$this->salida .= "                   <td>CAUSA PROBABLE</td>";
				$this->salida .= "				         </tr>";
				$causas=$this->BuscarCausas();
				for($i=0; $i<sizeof($causas);)
				{
						$this->salida .= "				         <tr class=\"modulo_list_oscuro\">";
						$estilo=ColorTriage($causas[$i][nivel_triage_id]);
						$this->salida .= "                   <td class=\"$estilo\" width=\"15%\" align=\"center\">NIVEL ".$causas[$i][nivel_triage_id]."</td>";
						$this->salida .= "                   <td width=\"75%\">";
						$this->salida .= "			      	 			 <table width=\"100%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
						$d=$i;
						while($causas[$i][nivel_triage_id]==$causas[$d][nivel_triage_id])
						{
								$estiloClaro=ColorTriageClaro($causas[$i][nivel_triage_id]);
								$this->salida .= "				         			 <tr class=\"modulo_list_claro\">";
								$this->salida .= "                  			 <td class=\"$estiloClaro\">".$causas[$d][descripcion]."</td>";
								$this->salida .= "				         			 </tr>";
								$d++;
						}
						$i=$d;
						$this->salida .= "			   			       </table>";
						$this->salida .= "                   </td>";
						$this->salida .= "				         </tr>";
				}
				$this->salida .= "			   			 </table>";
				$this->salida .= "              </td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr >";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\">MOTIVO CONSULTA: </td>";
				if(!empty($_REQUEST['MotivoConsulta']))
				{  $dat[motivo_consulta]=$_REQUEST['MotivoConsulta'];  }
				$this->salida .= "				          <td class=\"modulo_list_claro\" colspan=\"3\"><textarea name=\"MotivoConsulta\" cols=\"90\" rows=\"2\" class=\"textarea\">".$dat[motivo_consulta]."</textarea></td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr >";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\">SIGNOS VITALES: </td>";
				$this->salida .= "				          <td class=\"modulo_list_claro\" colspan=\"3\">";
				$sig=$this->BuscarSignosVitales();
				$glas=$sig[respuesta_motora_id] + $sig[respuesta_verbal_id]+ $sig[apertura_ocular_id];
				if(empty($glas)){   $glas='--';  }
				$this->salida .= "			      	 <table width=\"70%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= "				         <tr align=\"center\" class=\"modulo_table_list_title\">";
				$this->salida .= "				         <td>F.C.</td>";
				$this->salida .= "				         <td>F.R.</td>";
				$this->salida .= "				         <td>PESO(Kg)</td>";
				$this->salida .= "				         <td>T.A.</td>";
				$this->salida .= "				         <td>TEMP.</td>";
				$this->salida .= "				         <td>EVA.</td>";
				$this->salida .= "				         <td>GLASGOW</td>";
				$this->salida .= "				         </tr>";
				$this->salida .= "				         <tr>";
				$this->salida .= "				           <td class=\"modulo_list_claro\" width=\"10%\">".$sig[signos_vitales_fc]."</td>";
				$this->salida .= "				           <td class=\"modulo_list_claro\" width=\"10%\">".$sig[signos_vitales_fr]."</td>";
				$this->salida .= "				           <td class=\"modulo_list_claro\" width=\"15%\">".$sig[signos_vitales_peso]."</td>";
				$this->salida .= "				           <td class=\"modulo_list_claro\" width=\"15%\">".$sig[signos_vitales_taalta]." / ".$sig[signos_vitales_tabaja]."</td>";
				$this->salida .= "				           <td class=\"modulo_list_claro\" width=\"10%\">".$sig[signos_vitales_temperatura]."</td>";
				$this->salida .= "				           <td class=\"modulo_list_claro\" width=\"10%\">".$sig[evaluacion_dolor]."</td>";
				$this->salida .= "				           <td class=\"modulo_list_claro\" width=\"10%\">".$glas."</td>";
				$this->salida .= "				         </tr>";
				$this->salida .= "			   			 </table>";
				$this->salida .= "									</td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr >";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\">OBSERVACION: </td>";
				if(!empty($_REQUEST['observacion']))
				{  $dat[observacion_medico]=$_REQUEST['observacion'];  }
				$this->salida .= "				          <td class=\"modulo_list_claro\" colspan=\"3\"><textarea name=\"observacion\" cols=\"90\" rows=\"2\" class=\"textarea\" >".$dat[observacion_medico]."</textarea></td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr >";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\">DIAGNOSTICO: </td>";
				$this->salida .= "				          <td class=\"modulo_list_oscuro\" colspan=\"3\">";
				$diaTriage=$this->BuscarDiagnosticoTriage($_SESSION['REMISIONES']['DATOS']['triage_id']);
				if(!empty($_SESSION['DIAGNOSTICO']) OR !empty($diaTriage))
				{
						$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"100%\">";
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="  <td width=\"9%\">CODIGO</td>";
						$this->salida.="  <td width=\"88%\">DESCRIPCION</td>";
						$this->salida.="  <td width=\"3%\"></td>";
						$this->salida.="</tr>";
						if(!empty($_SESSION['DIAGNOSTICO']) )
						{
								foreach($_SESSION['DIAGNOSTICO'] as $k => $v)
								{
									foreach($v as $k1 => $v1)
									{
											$this->salida.="<tr class=\"modulo_list_claro\">";
											$this->salida.="  <td align=\"center\">".$k."</td>";
											$this->salida.="  <td>".$k1."</td>";
											$this->salida.="  <input type = hidden name=codigodi".$k." value = ".$k."></td>";
											$accion2=ModuloGetURL('app','Remisiones','user','EliminarDiagnostico',array('codigoED'=>$k,'dat'=>$_REQUEST));
											$this->salida.="  <td><a href='$accion2'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
											$this->salida.="</tr>";
									}
								}
						}						
						if(!empty($diaTriage) AND empty($_SESSION['DIAGNOSTICO']))
						{
								for($k=0; $k<sizeof($diaTriage); $k++)
								{
										$this->salida.="<tr class=\"modulo_list_claro\">";
										$this->salida.="  <td align=\"center\">".$diaTriage[$k][diagnostico_id]."</td>";
										$this->salida.="  <td>".$diaTriage[$k][diagnostico_nombre]."</td>";
										$this->salida.="  <input type = hidden name=codigodi".$diaTriage[$k][diagnostico_id]." value = ".$diaTriage[$k][descripcion]."></td>";
										$accion2=ModuloGetURL('app','Remisiones','user','EliminarDiagnostico',array('codigoED'=>$diaTriage[$k][diagnostico_id],'dat'=>$_REQUEST));
										$this->salida.="  <td></td>";
										$this->salida.="</tr>";
										//0 dig 1 nombre
										$_SESSION['DIAGNOSTICO'][$diaTriage[$k][diagnostico_id]][$diaTriage[$k][diagnostico_nombre]]=$diaTriage[$k][diagnostico_id];
								}
						}
						$this->salida.="</table><br>";
				}
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"5\">DIAGNOSTICOS</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"6%\">CODIGO:</td>";
				$this->salida .="<td width=\"11%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10 	name = 'codigoDiag'    ></td>" ;
				$this->salida.="<td width=\"10%\">DIAGNOSTICO:</td>";
				$this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = 'descripcionDiag'   value =\"".$_REQUEST['descripcionDiag']."\"></td>" ;
				$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"Diagnostico\" type=\"submit\" value=\"BUSCAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
				if(!empty($diag))
				{
					$this->FormaResultadosDiagnosticos($diag);
				}
				$this->salida .= "				       </td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr >";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\">REMITIR A: </td>";
				$this->salida .= "				          <td class=\"modulo_list_claro\" colspan=\"3\"><textarea name=\"observacionRemision\" cols=\"90\" rows=\"2\" class=\"textarea\" >".$_REQUEST['observacionRemision']."</textarea></td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\" colspan=\"4\">INSTITUCIONES A REMITIR: </td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$this->salida .= "				          <td align=\"center\" colspan=\"4\" class=\"modulo_list_oscuro\">";
				if(!empty($_SESSION['CENTROS']))
				{
						$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"65%\">";
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="  <td width=\"90%\">INSTITUCION</td>";
						$this->salida.="  <td width=\"6%\">NIVEL</td>";
						$this->salida.="  <td width=\"6%\"></td>";
						$this->salida.="</tr>";
						foreach($_SESSION['CENTROS'] as $k => $v)
						{
							foreach($v as $k1 => $v1)
							{
									foreach($v1 as $k2 => $v2)
									{
											$this->salida.="<tr class=\"modulo_list_claro\">";
											$this->salida.="  <td>".$k1."</td>";
											$this->salida.="  <td align=\"center\">".$k2."</td>";
											$this->salida.="  <input type = hidden name=Rem".$k." value = ".$k."></td>";
											$accion=ModuloGetURL('app','Remisiones','user','EliminarCentro',array('codigoEC'=>$k,'dat'=>$_REQUEST));
											$this->salida.="  <td><a href='$accion'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
											$this->salida.="</tr>";
									}
							}
						}
						$this->salida.="</table><br>";
				}
				//$accion1=ModuloGetURL('app','Remisiones','user','Busqueda',array('datos'=>$arr));
				//$this->salida .= "<form name=\"formadesapoyo\" action=\"$accion1\" method=\"post\">";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"7\">CENTROS DE REMISION</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"5%\">TIPO</td>";
				$this->salida.="<td width=\"10%\" align = left >";
				$this->salida.="<select  name = 'criterio'  class =\"select\">";
				$this->salida .= " <option value=\"Todas\">TODOS LOS NIVELES</option>";
				$nivel=$this->Niveles();
				for($i=0; $i<sizeof($nivel); $i++)
				{
						$this->salida .=" <option value=\"".$nivel[$i][nivel]."\">INSTITUCION ".$nivel[$i][descripcion]."</option>";
				}
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="<td width=\"6%\">CODIGO:</td>";
				$this->salida .="<td width=\"11%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10 	name = 'codigo' ></td>" ;
				$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
				$this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = 'descripcion' ></td>" ;
				$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"Buscar\" type=\"submit\" value=\"BUSCAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
				//$this->salida.="</form>";
				if(!empty($vector))
				{
					$this->FormaResultados($vector);
				}
				$this->salida .= "				       </td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "			     </table><br>";
        $this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\">";
				$this->salida .= "<tr>";
        $this->salida .= "<td  align=\"center\"><input class=\"input-submit\" name=\"Aceptar\" type=\"submit\" value=\"ACEPTAR\"></td>";
				$this->salida .= "</form>";
			/*	$accion=MoDuloGetURL('app','Remisiones','user','main');
				$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<td  align=\"center\"><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"CANCELAR\"></td>";
				$this->salida .= "</form>";*/
				$this->salida .= "</tr>";
        $this->salida .= " </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}


	/**
	*
	*/
	function FormaResultados($arr)
	{
			//$accion=ModuloGetURL('app','Remisiones','user','GuardarCentro');
			$this->salida .= "<form name=\"formadesapoyo\" action=\"$accion\" method=\"post\">";
			if ($arr)
			{
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"70%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"30%\">INSTITUCION</td>";
					$this->salida.="  <td width=\"10%\">NIVEL</td>";
					$this->salida.="  <td width=\"5%\"></td>";
					$this->salida.="</tr>";
					for($i=0;$i<sizeof($arr);$i++)
					{
							$this->salida.="<tr class=\"modulo_list_claro\">";
							$this->salida.="  <td>".$arr[$i][descripcion]."</td>";
							$this->salida.="  <td align=\"center\">".$arr[$i][nivel]."</td>";
							$this->salida.="  <td align=\"center\"><input type = checkbox name=centro".$arr[$i][centro_remision]." value =\"".$arr[$i][centro_remision]."||".$arr[$i][descripcion]."||".$arr[$i][nivel]."\"></td>";
							$this->salida.="</tr>";
					}
					$this->salida.="<tr class=\"modulo_list_claro\">";
					$this->salida .= "<td align=\"right\" colspan=\"3\"><input class=\"input-submit\" name=\"Guardar\" type=\"submit\" value=\"GUARDAR\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";
					$this->salida .=$this->RetornarBarra();
			}
			//$this->salida.="</form>";
	}

	/**
	*
	*/
	function FormaResultadosDiagnosticos($arr)
	{
			if ($arr)
			{
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"9%\">CODIGO</td>";
					$this->salida.="  <td width=\"80%\">DESCRIPCION</td>";
					$this->salida.="  <td width=\"5%\"></td>";
					$this->salida.="</tr>";
					for($i=0;$i<sizeof($arr);$i++)
					{
							$this->salida.="<tr class=\"modulo_list_claro\">";
							$this->salida.="  <td align=\"center\">".$arr[$i][diagnostico_id]."</td>";
							$this->salida.="  <td>".$arr[$i][diagnostico_nombre]."</td>";
							$this->salida.="  <td align=\"center\"><input type = checkbox name=diag".$arr[$i][diagnostico_id]." value =\"".$arr[$i][diagnostico_id]."||".$arr[$i][diagnostico_nombre]."\"></td>";
							$this->salida.="</tr>";
					}
					$this->salida.="<tr class=\"modulo_list_claro\">";
					$this->salida .= "<td align=\"right\" colspan=\"3\"><input class=\"input-submit\" name=\"GuardarDiag\" type=\"submit\" value=\"GUARDAR\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";
					$this->salida .=$this->RetornarBarraD();
			}
	}


	/**
	*
	*/
 	function RetornarBarraD()
	{
    if($this->limit>=$this->conteo){
        return '';
    }
    $paso=$_REQUEST['paso'];
    if(empty($paso)){
      $paso=1;
    }
    $vec='';
    foreach($_REQUEST as $v=>$v1)
    {
      if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
      {   $vec[$v]=$v1;   }
    }

		$accion=ModuloGetURL('app','Remisiones','user','BusquedaDiagnostico',$vec);
    $barra=$this->CalcularBarra($paso);
    $numpasos=$this->CalcularNumeroPasos($this->conteo);
    $colspan=1;

    $this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
    if($paso > 1){
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
      $colspan+=1;
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
      if($diferencia<=0){$diferencia=1;}
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
      $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table><br>";
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
    $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table><br>";
    }
	}



	/**
	*
	*/
 	function RetornarBarra($tipo)
	{
    if($this->limit>=$this->conteo){
        return '';
    }
    $paso=$_REQUEST['paso'];
    if(empty($paso)){
      $paso=1;
    }
    $vec='';
    foreach($_REQUEST as $v=>$v1)
    {
      if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
      {   $vec[$v]=$v1;   }
    }

		if($tipo=='Triage')
		{  $accion=ModuloGetURL('app','Remisiones','user','BuscarPacientesTriage',$vec); }
		else
		{  $accion=ModuloGetURL('app','Remisiones','user','Busqueda',$vec);  }
    $barra=$this->CalcularBarra($paso);
    $numpasos=$this->CalcularNumeroPasos($this->conteo);
    $colspan=1;

    $this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
    if($paso > 1){
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
      $colspan+=1;
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
      if($diferencia<=0){$diferencia=1;}
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
      $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table><br>";
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
    $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table><br>";
    }
	}

	/**
	*
	*/
	function CalcularNumeroPasos($conteo)
	{
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	/**
	*
	*/
	function CalcularBarra($paso)
	{
		$barra=floor($paso/10)*10;
		if(($paso%10)==0)
		{
			$barra=$barra-10;
		}
		return $barra;
	}

	/**
	*
	*/
	function CalcularOffset($paso)
	{
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}


	/**
	*
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

				return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
		}
 }

//----------------------------------------------------------------------------------------------------

}//fin clase

?>

