<?php
	
  function Busqueda($tipo_id_tercero,$tercero_id,$nombre,$tipo)
	{
		$objResponse = new xajaxResponse();  
		
		$objC=new app_Quirurgicos_user;

		if(!$tercero_id AND !$nombre)
		{
			$mensaje="INGRESE EL DOCUMENTO DE IDENTIFICACION O EL NOMBRE";         
			$objResponse->assign("error","innerHTML","<center>".$mensaje."</center>");  
		}
		else
		{
			$salida="";
			if($tipo=='1')
			{
				$capatd='capaCiru';
				$especialista=$objC->profesionalesEspecialista($tipo_id_tercero,$tercero_id,$nombre);
			}
			elseif($tipo=='2')
			{
				$capatd='capaAnes';
				$especialista=$objC->profesionalesEspecialistaAnestecistas($tipo_id_tercero,$tercero_id,$nombre);
			}	
			if(!empty($especialista))
			{
				$salida="<br><table align=\"center\" width=\"100%\" class=\"modulo_table_list\">";
				$salida.="<tr class=\"modulo_table_list_title\">";
				$salida.="	<td>TIPO DOCUMENTO</td>";
				$salida.="	<td>DOCUMENTO</td>";
				$salida.="	<td>NOMBRE</td>";
				$salida.="	<td>ACCION</td>";
				$salida.="</tr>";
				foreach($especialista as $esp)
				{
					$salida.="<tr class=\"modulo_list_claro\">";
					$salida.="	<td>".$esp['tipo_id_tercero']."</td>";
					$salida.="	<td>".$esp['tercero_id']."</td>";
					$salida.="	<td>".$esp['nombre']."</td>";
					$salida.="	<td align=\"center\"><input type=\"radio\" name=\"sele_radio\" id=\"sele_radio$i\" value=\"\" onclick=\"RadioSelecionado('".$esp['tipo_id_tercero']."','".$esp['tercero_id']."');\"></td>";
					$salida.="</tr>";
				}
				$salida.="<tr class=\"modulo_list_claro\">";
				$salida.="	<td colspan=\"3\">&nbsp;</td>";
				$salida.="	<td><input type=\"button\" name=\"seleccionar\" class=\"input-submit\" value=\"SELECCIONAR\" onclick=\"Especialista('".$tipo."')\"></td>";
				$salida.="</tr>";
				$salida.="</table>";
				$objResponse->assign('especialistas',"style.display","");
				$objResponse->assign('especialistas',"innerHTML",$salida);
			}
			else
			{
				$mensaje="NO SE ENCONTRARON REGISTROS";         
				$objResponse->assign("error","innerHTML","<center>".$mensaje."</center>");  
			}
		}
		
		return $objResponse;
  }
	
	function SelectEspecialista($tipo_id,$id,$tipo)
	{
		$objResponse = new xajaxResponse();  
		
		$objC=new app_Quirurgicos_user;
		
		$salida="";
		
		if($tipo=='1')
		{
			$capatd='capaCiru';
			$nom="cirujano";
			$especialista=$objC->profesionalesEspecialista();
		}
		elseif($tipo=='2')
		{
			$capatd='capaAnes';
			$nom="anesteciologo";
			$especialista=$objC->profesionalesEspecialistaAnestecistas();
		}
	
		$salida .= "					<select name=\"$nom\" class=\"select\">";
		$salida .= "						<option value=\"\">--SELECCIONE CIRUJANO--</option>";
		foreach($especialista as $esp)
		{
			$sel="";
			
			if($esp['tipo_id_tercero']==$tipo_id and $esp['tercero_id']==$id)
				$sel="selected";
			$salida .= "					<option value=\"".$esp['tipo_id_tercero']."__".$esp['tercero_id']."\" $sel>".$esp['nombre']."</option>";
		}
		$salida .= "					</select>";

		$objResponse->assign($capatd,"innerHTML",$salida);  
		$objResponse->assign("d2Container","style.display","none");  
		
		return $objResponse;
	}
     
?>