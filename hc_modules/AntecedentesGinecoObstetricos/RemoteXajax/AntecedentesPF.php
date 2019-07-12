<?php
	/**************************************************************************************
	* $Id: AntecedentesPF.php,v 1.2 2007/02/01 20:43:16 luis Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* @author Luis Alejandro Vargas	
	**************************************************************************************/
	function Antecedentes($titulo)
	{
		$objResponse=new xajaxResponse();
		$evolucion=SessionGetVar("Evolucion");
		
		$ante=new AntecedentesGO();
		$antecedentes=$ante->GetDatosHistorialEmbarazos($evolucion);

		$objResponse->assign("h","innerHTML",sizeof($antecedentes)+1);
		$objResponse->assign("h1","value",sizeof($antecedentes)+1);
		
		$objResponse->call("IniciarPF");
		$objResponse->assign("tituloPF","innerHTML","<center>".$titulo."</center>");
		$objResponse->assign("d2ContainerPF","style.display","block");
		
		return $objResponse;
	}
	
	function GuardarAntecedentes($datos)
	{
		$objResponse=new xajaxResponse();
		
		$evolucion=SessionGetVar("Evolucion");
		$mensaje="";
		
		if(!$datos[1])
			$mensaje="DEBE INGRESAR EL AÑO DE TERMINACION";
		elseif($datos[1] > Date("Y"))
			$mensaje="EL AÑO DE TERMINACION ES MAYOR QUE EL AÑO ACTUAL";
		elseif(strlen($datos[1]) < 4 OR !is_numeric($datos[1]))
			$mensaje="EL AÑO DE DEBE ESTAR COMPUESTO POR 4 DIGITOS";
		elseif(!$datos[2])
			$mensaje="DEBE INGRESAR LOS MESES DE GESTACION";
		elseif($datos[2] > 9)
			$mensaje="LOS MESES DE GESTACION SOBREPASAN LOS 9 MESES";
		elseif(!$datos[3])
			$mensaje="DEBE SELECCIONAR EL TIPO DE PARTO";
		elseif(!$datos[4])
			$mensaje="DEBE SELECCIONAR EL ESTADO DE NACIMIENTO";
		
		$objResponse->assign("errorPF","innerHTML","<center>".$objResponse->setTildes($mensaje)."<center>");
		
		if(!$mensaje)
		{
			$ante=new AntecedentesGO();
			if(!$ante->GuardarHistorialAntecedentesPF($evolucion,$datos))
				$objResponse->alert($ante->ErrorDB());
			else
			{
				$antecedentes=$ante->GetDatosHistorialEmbarazos($evolucion);
				SessionSetVar("n_hijo",sizeof($antecedentes));
		
				if(sizeof($antecedentes) == SessionGetVar("num_hijos_vivos"))
					$objResponse->assign("enlace","innerHTML","HISTORIAL DE ANTECEDENTES DE EMBARAZOS");
				
				$html = CrearHtml($antecedentes);
				$html2=$objResponse->setTildes($html);
				
				$objResponse->assign("AntecedentesPF","innerHTML",$html2);
				$objResponse->assign("d2ContainerPF","style.display","none");
			}
		}
		return $objResponse;
	}
	
	function CrearHtml($antecedentes)
	{
		$salida="";
		$salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
		$salida.="	<tr class=\"hc_table_submodulo_list_title\">";
		$salida.="		<td>NUMERO HIJO</td>";
		$salida.="		<td>AÑO TERMINACION</td>";
		$salida.="		<td>MESES DE GESTACION</td>";
		$salida.="		<td>TIPO PARTO</td>";
		$salida.="		<td>ESTADO</td>";
		$salida.="	</tr>";

		$k=0;
		foreach($antecedentes as $ante)
		{
			if($k%2==0)
				$estilo="hc_submodulo_list_claro";
			else
				$estilo="hc_submodulo_list_oscuro";
				
			switch($ante['tipo_parto'])
			{
				case 1:
					$tipo_parto="VAGINAL";
				break;
				case 2:
					$tipo_parto="CESAREA";
				break;
			}
			
			switch($ante['estado_nacimiento'])
			{
				case 1:
					$estado="ABORTO";
				break;
				case 2:
					$estado="NACIDO VIVO";
				break;
				case 3:
					$estado="NACIDO MUERTO";
				break;
			}

			$salida.=" <tr class=\"$estilo\" align=\"center\">";
			$salida.="		<td>".$ante['numero_hijo']."</td>";
			$salida.="		<td>".$ante['año_terminacion']."</td>";
			$salida.="		<td>".$ante['meses_gestacion']."</td>";
			$salida.="		<td>".$tipo_parto."</td>";
			$salida.="		<td>".$estado."</td>";
			$salida.="	</tr>";
			$k++;
		}
		$salida.="	</table>";
		return $salida;
	}
	
	function Consegeria()
	{
		$objResponse=new xajaxResponse();
		
		$programa=SessionGetVar("Programa");
		$inscripcion=SessionGetVar("Inscripcion_$programa");
		
		$ante=new AntecedentesGO();
		if($ante->GuardarRecibioConsegeria($inscripcion))
		{
			$valor="<img src=\"".GetThemePath()."/images/checksi.png\">";
			$objResponse->assign("consegeria","innerHTML",$valor);
		}
		else
			$objResponse->alert($ante->ErrorDB());
		
		return $objResponse;
	}
?>