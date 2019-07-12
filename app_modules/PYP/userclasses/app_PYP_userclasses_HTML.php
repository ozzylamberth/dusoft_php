<?php

class app_PYP_userclasses_HTML extends app_PYP_user
{

	function app_PYP_user_HTML()
	{
		$this->app_PYP_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}


	function Menu()
	{
		$this->salida = ThemeAbrirTabla('Promoción y Prevención');
		$this->salida .= "<br>";
		$this->salida .= "<table width=\"40%\" border=\"1\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\">MENU ADMINISTRATIVO DE PROMOCIÓN Y PREVENCIÓN</td>";
		$this->salida .= "</tr>";
		$this->salida .='<tr class="modulo_list_claro">';
		$this->salida .='<td align="center">';
		$accion=ModuloGetURL('app','PYP','user','LlamarListadoAdministrativo');
		$this->salida .='<a href="'.$accion.'">Programas de Promoción y Prevención</a>';
		$this->salida .='</td>';
		$this->salida .='</tr>';
		$this->salida .='<tr class="modulo_list_oscuro">';
		$this->salida .='<td align="center">';
		$accion=ModuloGetURL('app','PYP','user','LlamarCreacionProtocolos');
		$this->salida .='<a href="'.$accion.'">Creación de Protocolos Médicos</a>';
		$this->salida .='</td>';
		$this->salida .='</tr>';
		$this->salida .= "</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function CreacionProtocolos()
	{
		$this->salida = ThemeAbrirTabla('PROTOCOLOS MÉDICOS');
		$dat=$this->BuscarTodosProtocolos();
		$sexo=$this->sexo();
		if($dat)
		{
			if($this->SetStyle("MensajeError"))
			{
				$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "		 </table>";
			}
			$this->salida .= "<table width=\"90%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "<tr align=\"center\" class=\"modulo_table_list_title\">";
			$this->salida .='<td>PROTOCOLO</td>';
			$this->salida .='<td>CARACTERISTICA</td>';
			$this->salida .='<td>TIEMPO</td>';
			$this->salida .='<td>SEXO</td>';
			$this->salida .='<td>EDAD MIN</td>';
			$this->salida .='<td>EDAD MAX</td>';
			$this->salida .='<td>GESTANTE</td>';
			$this->salida .='<td colspan="3">ACCIONES</td>';
			$this->salida .='</tr>';
			for($i=0; $i<sizeof($dat); $i++)
			{
					$accion=ModuloGetURL('app','PYP','user','ActualizarTipoProtocolo',array('PM'=>1));
					$this->salida .= "  <form name=\"formaactualizar$i\" action=\"$accion\" method=\"post\">";
					if($i % 2) {  $estilo="modulo_list_claro";  }
					else {  $estilo="modulo_list_oscuro";   }
					$this->salida .= "<tr align=\"center\" class=\"$estilo\">";
					$this->salida .='<td><input size="15" maxlength="100" type="text" value="'.$dat[$i][nombre].'" name="nombre" class="input-text"></td>';
					$this->salida .='<td>';
					$this->salida.='<select name="caracteristica" class="select">';
					if($dat[$i][caracteristicas]==0)
					{
						$this->salida.='<option value="0" selected>Pregunte</option>';
					}
					else
					{
						$this->salida.='<option value="0">Pregunte</option>';
					}
					if($dat[$i][caracteristicas]==1)
					{
						$this->salida.='<option value="1" selected>Determine</option>';
					}
					else
					{
						$this->salida.='<option value="1">Determine</option>';
					}
					if($dat[$i][caracteristicas]==2)
					{
						$this->salida.='<option value="2" selected>Verifique</option>';
					}
					else
					{
						$this->salida.='<option value="2">Verifique</option>';
					}
					if($dat[$i][caracteristicas]==3)
					{
						$this->salida.='<option value="3" selected>Todos</option>';
					}
					else
					{
						$this->salida.='<option value="3">Todos</option>';
					}
					$this->salida.='</select>';
					$this->salida .='</td>';
					$this->salida .='<td><input size="3" maxlength="10" type="text" value="'.$dat[$i][tiempo].'" name="tiempo" class="input-text"></td>';
					$this->salida.='<td>';
					$this->salida.='<select name="sexo" class="select">';
					foreach($sexo as $k=>$v)
					{
						if($k==$dat[$i][sexo])
						{
							$this->salida.='<option value="'.$k.'" selected>'.$v.'</option>';
						}
						else
						{
							$this->salida.='<option value="'.$k.'">'.$v.'</option>';
						}
					}
					$this->salida.='</select>';
					$this->salida.='</td>';
					$this->salida .='<td><input size="3" maxlength="10" type="text" value="'.$dat[$i][edad_min].'" name="edad_min" class="input-text"></td>';
					$this->salida .='<td><input size="3" maxlength="10" type="text" value="'.$dat[$i][edad_max].'" name="edad_max" class="input-text"></td>';
					$this->salida .='<td>';
					$this->salida .='<select name="gestante">';
					if($dat[$i][gestante]==1)
					{
						$this->salida .='<option value="1" selected>Activa</option>';
					}
					else
					{
						$this->salida .='<option value="1">Activa</option>';
					}
					if($dat[$i][gestante]==0)
					{
						$this->salida .='<option value="0" selected>Inactiva</option>';
					}
					else
					{
						$this->salida .='<option value="0">Inactiva</option>';
					}
					$this->salida .='</select>';
					$this->salida .='</td>';
					$this->salida .='<input size="3" maxlength="1" type="hidden" value="'.$dat[$i][tipo_protocolo_id].'" name="tipo_protocolo_id" class="input-text"><input size="3" maxlength="1" type="hidden" value="'.$_REQUEST['dat'].'" name="dat" class="input-text"><input size="3" maxlength="1" type="hidden" value="'.$_REQUEST['nombrecronico'].'" name="nombrecronico" class="input-text">';
					$this->salida .="<td align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"GUARDAR\"></td>";
					$this->salida .='</form>';
					$accion=ModuloGetURL('app','PYP','user','EliminarTipoProtocolo',array('tipo_protocolo_id'=>$dat[$i][tipo_protocolo_id]));
					$this->salida .= "  <form name=\"formaactualizar$i\" action=\"$accion\" method=\"post\">";
					$this->salida .="<td align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"ELIMINAR\"></td>";
					$this->salida .='</form>';
					$accion=ModuloGetURL('app','PYP','user','ListadoProtocoloDetalle',array('tipo_protocolo_id'=>$dat[$i][tipo_protocolo_id],'nombreprotocolo'=>$dat[$i][nombre]));
					$this->salida .="<td align=\"center\"><a href=\"$accion\">Detalle</a></td>";
					$this->salida .='</tr>';
			}
			$this->salida .='</table>';
			$this->salida .='<br>';
			$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "<tr>";
			$accion=ModuloGetURL('app','PYP','user','Menu');
			$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
			$this->salida .="<td align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"ATRAS\"></td>";
			$this->salida .='</form>';
			$accion=ModuloGetURL('app','PYP','user','LlamarFormaCrearProtocolo');
			$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
			$this->salida .="<td align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"CREAR\"></td>";
			$this->salida .='</form>';
			$this->salida .='</tr>';
			$this->salida .='</table>';
		}
		else
		{
			$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "<tr>";
			$this->salida .='<td align=center colspan="2">NO HAY TIPOS PROTOCOLOS</td>';
			$this->salida .='</tr>';
			$this->salida .= "<tr>";
			$accion=ModuloGetURL('app','PYP','user','Menu');
			$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
			$this->salida .="<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" value=\"ACEPTAR\"></td>";
			$this->salida .='</form>';
			$accion=ModuloGetURL('app','PYP','user','LlamarFormaCrearProtocolo');
			$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
			$this->salida .="<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" value=\"CREAR\"></td>";
			$this->salida .='</form>';
			$this->salida .='</tr>';
			$this->salida .='</table>';
		}
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaListadoProtocoloDetalle()
	{
		$this->salida = ThemeAbrirTabla('PROTOCOLOS MÉDICOS');
		$dat=$this->BuscarTodosProtocolosDetalle();
		if($dat)
		{
			if($this->SetStyle("MensajeError"))
			{
				$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "		 </table>";
			}
			$this->salida .= "<table width=\"90%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "<tr align=\"center\" class=\"modulo_table_list_title\">";
			$this->salida .='<td>'.$_REQUEST['nombreprotocolo'].'</td>';
			$this->salida .='</tr>';
			$this->salida .='</table>';
			$this->salida .= "<table width=\"90%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "<tr align=\"center\" class=\"modulo_table_list_title\">";
			$this->salida .='<td>PROTOCOLO</td>';
			$this->salida .='<td colspan="2">ACCIONES</td>';
			$this->salida .='</tr>';
			for($i=0; $i<sizeof($dat); $i++)
			{
					$accion=ModuloGetURL('app','PYP','user','GuardarProtocolosDetalle');
					$this->salida .= "  <form name=\"formaactualizar$i\" action=\"$accion\" method=\"post\">";
					if($i % 2) {  $estilo="modulo_list_claro";  }
					else {  $estilo="modulo_list_oscuro";   }
					$this->salida .= "<tr align=\"center\" class=\"$estilo\">";
					$this->salida .='<td><input size="40" maxlength="100" type="text" value="'.$dat[$i][nombre].'" name="nombre" class="input-text"></td>';
					$this->salida .='<input size="3" maxlength="1" type="hidden" value="'.$dat[$i][detalle_protocolo_id].'" name="detalle_protocolo_id" class="input-text"><input size="3" maxlength="1" type="hidden" value="'.$_REQUEST['tipo_protocolo_id'].'" name="tipo_protocolo_id" class="input-text"><input size="3" maxlength="1" type="hidden" value="'.$_REQUEST['nombreprotocolo'].'" name="nombreprotocolo" class="input-text">';
					$this->salida .="<td align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"GUARDAR\"></td>";
					$this->salida .='</form>';
					$accion=ModuloGetURL('app','PYP','user','EliminarProtocolosDetalle',array('detalle_protocolo_id'=>$dat[$i][detalle_protocolo_id],'tipo_protocolo_id'=>$_REQUEST['tipo_protocolo_id'],'nombreprotocolo'=>$_REQUEST['nombreprotocolo']));
					$this->salida .= "  <form name=\"formaactualizar$i\" action=\"$accion\" method=\"post\">";
					$this->salida .="<td align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"ELIMINAR\"></td>";
					$this->salida .='</form>';
					$this->salida .='</tr>';
			}
			$this->salida .='</table>';
			$this->salida .='<br>';
			$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "<tr>";
			$accion=ModuloGetURL('app','PYP','user','CreacionProtocolos');
			$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
			$this->salida .="<td align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"ATRAS\"></td>";
			$this->salida .='</form>';
			$accion=ModuloGetURL('app','PYP','user','LlamarFormaCrearProtocoloDetalle',array('tipo_protocolo_id'=>$_REQUEST['tipo_protocolo_id'],'nombreprotocolo'=>$_REQUEST['nombreprotocolo']));
			$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
			$this->salida .="<td align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"CREAR\"></td>";
			$this->salida .='</form>';
			$this->salida .='</tr>';
			$this->salida .='</table>';
		}
		else
		{
			$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "<tr>";
			$this->salida .='<td align=center colspan="2">NO HAY TIPOS PROTOCOLOS</td>';
			$this->salida .='</tr>';
			$this->salida .= "<tr>";
			$accion=ModuloGetURL('app','PYP','user','CreacionProtocolos');
			$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
			$this->salida .="<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" value=\"ACEPTAR\"></td>";
			$this->salida .='</form>';
			$accion=ModuloGetURL('app','PYP','user','LlamarFormaCrearProtocoloDetalle',array('tipo_protocolo_id'=>$_REQUEST['tipo_protocolo_id'],'nombreprotocolo'=>$_REQUEST['nombreprotocolo']));
			$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
			$this->salida .="<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" value=\"CREAR\"></td>";
			$this->salida .='</form>';
			$this->salida .='</tr>';
			$this->salida .='</table>';
		}
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function ListadoAdministrativo()
	{
		$this->salida = ThemeAbrirTabla('Promoción y Prevención');
		$dat=$this->BuscarCronicos();
		if($dat)
		{
			$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "		 </table>";
			$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "<tr align=\"center\" class=\"modulo_table_list_title\">";
			$this->salida .='<td colspan="2">NOMBRE PROGRAMA PYP</td>';
			$this->salida .='</tr>';
			for($i=0; $i<sizeof($dat); $i++)
			{
				if($i % 2) {  $estilo="modulo_list_claro";  }
				else {  $estilo="modulo_list_oscuro";   }
				$this->salida .= "<tr align=\"center\" class=\"$estilo\">";
				$accion=ModuloGetURL('app','PYP','user','ListadoProtocolo', array('dat'=>$dat[$i][tipo_cronico_id], 'nombrecronico'=>$dat[$i][nombre]));
				$this->salida .='<td><a href='.$accion.'>'.$dat[$i][nombre].'</a></td>';
				$accion=ModuloGetURL('app','PYP','user','EliminarCronico', array('dat'=>$dat[$i][tipo_cronico_id]));
				$this->salida .='<td width="10%"><a href="'.$accion.'">ELIMINAR</a></td>';
				$this->salida .='</tr>';
			}
			$this->salida .='</table>';
			$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "<tr>";
			$accion=ModuloGetURL('app','PYP','user','LlamarFormaCrear');
			$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
			$this->salida .="<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" value=\"CREAR\"></td>";
			$this->salida .='</form>';
			$accion=ModuloGetURL('app','PYP','user','main');
			$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
			$this->salida .="<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" value=\"ATRAS\"></td>";
			$this->salida .='</form>';
			$this->salida .='</tr>';
			$this->salida .='</table>';
		}
		else
		{
			$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "<tr>";
			$this->salida .='<td align=center>NO EXISTEN PROGRAMAS EN PROMOCION Y PREVENCION</td>';
			$this->salida .='</tr>';
			$this->salida .= "<tr>";
			$accion=ModuloGetURL('app','PYP','user','main');
			$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
			$this->salida .="<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" value=\"ACEPTAR\"></td>";
			$this->salida .='</form>';
			$this->salida .='</tr>';
			$this->salida .='</table>';
		}
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaCrearProtocoloDetalle()
	{
			$this->salida = ThemeAbrirTabla('CREAR');
			$this->salida .= "<table width=\"90%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "<tr align=\"center\" class=\"modulo_table_list_title\">";
			$this->salida .='<td>'.$_REQUEST['nombreprotocolo'].'</td>';
			$this->salida .='</tr>';
			$this->salida .='</table>';
			$accion=ModuloGetURL('app','PYP','user','InsertarProtocoloDetalle',array('tipo_protocolo_id'=>$_REQUEST['tipo_protocolo_id'],'nombreprotocolo'=>$_REQUEST['nombreprotocolo']));
			$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
			$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "<tr>";
			$this->salida .='<td align=center class='.$this->SetStyle("Nombre").'>NOMBRE DETALLE: </td>';
			$this->salida .="<td align=center><input type=\"text\" class=\"input-text\" name=\"Nombre\"></td>";
			$this->salida .='</tr>';
			$this->salida .= "<tr>";
			$this->salida .="<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" value=\"ACEPTAR\"></td>";
			$this->salida .='</form>';
			$accion=ModuloGetURL('app','PYP','user','FormaListadoProtocoloDetalle',array('tipo_protocolo_id'=>$_REQUEST['tipo_protocolo_id'],'nombreprotocolo'=>$_REQUEST['nombreprotocolo']));
			$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
			$this->salida .="<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" value=\"CANCELAR\"></td>";
			$this->salida .='</form>';
			$this->salida .='</tr>';
			$this->salida .='</table>';
			$this->salida .= ThemeCerrarTabla();
			return true;
	}


	function FormaCrear()
	{
			$this->salida = ThemeAbrirTabla('CREAR');
			$accion=ModuloGetURL('app','PYP','user','InsertarCronico');
			$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
			$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "<tr>";
			$this->salida .='<td align=center class='.$this->SetStyle("Nombre").'>NOMBRE PROGRAMA: </td>';
			$this->salida .="<td align=center><input type=\"text\" class=\"input-text\" name=\"Nombre\"></td>";
			$this->salida .='</tr>';
			$this->salida .= "<tr>";
			$this->salida .="<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" value=\"ACEPTAR\"></td>";
			$this->salida .='</form>';
			$accion=ModuloGetURL('app','PYP','user','LlamarListadoAdministrativo');
			$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
			$this->salida .="<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" value=\"CANCELAR\"></td>";
			$this->salida .='</form>';
			$this->salida .='</tr>';
			$this->salida .='</table>';
			$this->salida .= ThemeCerrarTabla();
			return true;
	}


	function ListadoProtocolo()
	{
		$this->salida = ThemeAbrirTabla('Promoción y Prevención');
		$dat=$this->BuscarProtocolos($_REQUEST['dat']);
		$sexo=$this->sexo();
		if($dat)
		{
			if($this->SetStyle("MensajeError"))
			{
				$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "		 </table>";
			}
			$this->salida .= "<table width=\"90%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "<tr align=\"center\" class=\"modulo_table_list_title\">";
			$this->salida .='<td>'.$_REQUEST['nombrecronico'].'</td>';
			$this->salida .='</tr>';
			$this->salida .='</table>';
			$this->salida .= "<table width=\"90%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "<tr align=\"center\" class=\"modulo_table_list_title\">";
			$this->salida .='<td>PROTOCOLO</td>';
			$this->salida .='<td>CARACTERISTICA</td>';
			$this->salida .='<td>TIEMPO</td>';
			$this->salida .='</tr>';
			$accion=ModuloGetURL('app','PYP','user','RelacionarCronicosProtocolos',array('dat'=>$_REQUEST['dat'], 'nombrecronico'=>$_REQUEST['nombrecronico']));
			$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
			for($i=0; $i<sizeof($dat); $i++)
			{
					if($i % 2) {  $estilo="modulo_list_claro";  }
					else {  $estilo="modulo_list_oscuro";   }
					$this->salida .= "<tr align=\"center\" class=\"$estilo\">";
					$this->salida .='<td><input size="15" maxlength="100" type="text" value="'.$dat[$i][nombre].'" name="nombre" class="input-text"></td>';
					$this->salida .='<td>';
					$this->salida.='<select name="caracteristica-'.$dat[$i][tipo_protocolo_id].'" class="select">';
					$this->salida.='<option value="-1">--SELECCIONE--</option>';
					if($dat[$i][caracteristica]==='0')
					{
						$this->salida.='<option value="0" selected>Pregunte</option>';
					}
					else
					{
						$this->salida.='<option value="0">Pregunte</option>';
					}
					if($dat[$i][caracteristica]==1)
					{
						$this->salida.='<option value="1" selected>Determine</option>';
					}
					else
					{
						$this->salida.='<option value="1">Determine</option>';
					}
					if($dat[$i][caracteristica]==2)
					{
						$this->salida.='<option value="2" selected>Verifique</option>';
					}
					else
					{
						$this->salida.='<option value="2">Verifique</option>';
					}
					if($dat[$i][caracteristica]==3)
					{
						$this->salida.='<option value="3" selected>Todos</option>';
					}
					else
					{
						$this->salida.='<option value="3">Todos</option>';
					}
					$this->salida.='</select>';
					$this->salida .='</td>';
					$this->salida .='<td><input size="3" maxlength="10" type="text" value="'.$dat[$i][tiempo].'" name="tiempo'.$dat[$i][tipo_protocolo_id].'" class="input-text"></td>';
					$this->salida .='<input size="3" maxlength="1" type="hidden" value="'.$dat[$i][protocolo_cronico_id].'" name="protocolo_cronico'.$dat[$i][tipo_protocolo_id].'" class="input-text"><input size="3" maxlength="1" type="hidden" value="'.$_REQUEST['dat'].'" name="dat" class="input-text"><input size="3" maxlength="1" type="hidden" value="'.$_REQUEST['nombrecronico'].'" name="nombrecronico" class="input-text">';
					$this->salida .='</tr>';
			}
			$this->salida .='</table>';
			$this->salida .='<br>';
			$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "<tr>";
			$this->salida .="<td align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida .='</form>';
			$accion=ModuloGetURL('app','PYP','user','LlamarListadoAdministrativo');
			$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
			$this->salida .="<td align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"ATRAS\"></td>";
			$this->salida .='</form>';
			$this->salida .='</tr>';
			$this->salida .='</table>';
		}
		else
		{
			$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "<tr>";
			$this->salida .='<td align=center>NO HAY TIPOS PROTOCOLOS</td>';
			$this->salida .='</tr>';
			$this->salida .= "<tr>";
			$accion=ModuloGetURL('app','PYP','user','LlamarListadoAdministrativo');
			$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
			$this->salida .="<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" value=\"ACEPTAR\"></td>";
			$this->salida .='</form>';
			$this->salida .='</tr>';
			$this->salida .='</table>';
		}
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaCrearProtocolo()
	{
		$sexo=$this->sexo();
		$this->salida = ThemeAbrirTabla('Promoción y Prevención');
		if($this->SetStyle("MensajeError"))
		{
			$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "		 </table>";
		}
		$this->salida .= "<table width=\"90%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
		$this->salida .= "<tr align=\"center\" class=\"modulo_table_list_title\">";
		$this->salida .='<td>PROTOCOLO</td>';
		$this->salida .='<td>CARACTERISTICA</td>';
		$this->salida .='<td>TIEMPO</td>';
		$this->salida .='<td>SEXO</td>';
		$this->salida .='<td>EDAD MIN</td>';
		$this->salida .='<td>EDAD MAX</td>';
		$this->salida .='<td>GESTANTE</td>';
		$this->salida .='<td>Acción</td>';
		$this->salida .='</tr>';
		$accion=ModuloGetURL('app','PYP','user','InsertarTipoProtocolo');
		$this->salida .= "  <form name=\"formaactualizar$i\" action=\"$accion\" method=\"post\">";
		$this->salida .= "<tr align=\"center\" class=\"modulo_list_oscuro\">";
		$this->salida .='<td><input size="15" maxlength="100" type="text" name="nombre" class="input-text"></td>';
		$this->salida .='<td>';
		$this->salida.='<select name="caracteristica" class="select">';
		$this->salida.='<option value="0">Pregunte</option>';
		$this->salida.='<option value="1">Determine</option>';
		$this->salida.='<option value="2">Verifique</option>';
		$this->salida.='<option value="3">Todos</option>';
		$this->salida.='</select>';
		$this->salida .='</td>';
		$this->salida .='<td><input size="3" maxlength="10" type="text" name="tiempo" class="input-text"></td>';
		$this->salida.='<td>';
		$this->salida.='<select name="sexo" class="select">';
		foreach($sexo as $k=>$v)
		{
			if($k==$dat[$i][sexo])
			{
				$this->salida.='<option value="'.$k.'" selected>'.$v.'</option>';
			}
			else
			{
				$this->salida.='<option value="'.$k.'">'.$v.'</option>';
			}
		}
		$this->salida.='</select>';
		$this->salida.='</td>';
		$this->salida .='<td><input size="3" maxlength="10" type="text" name="edad_min" class="input-text"></td>';
		$this->salida .='<td><input size="3" maxlength="10" type="text" name="edad_max" class="input-text"></td>';
		$this->salida .='<td>';
		$this->salida .='<select name="gestante">';
		$this->salida .='<option value="1">Activa</option>';
		$this->salida .='<option value="0">Inactiva</option>';
		$this->salida .='</select>';
		$this->salida .='</td>';
		$this->salida .='<input size="3" maxlength="1" type="hidden" value="'.$_REQUEST['dat'].'" name="dat" class="input-text">';
		$this->salida .='<input size="3" maxlength="1" type="hidden" value="'.$_REQUEST['nombrecronico'].'" name="nombrecronico" class="input-text">';
		$this->salida .="<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" value=\"GUARDAR\"></td>";
		$this->salida .='</form>';
		$this->salida .='</tr>';
		$this->salida .='</table>';
		$this->salida .='<br>';
		$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
		$this->salida .= "<tr>";
		$accion=ModuloGetURL('app','PYP','user','CreacionProtocolos');
		$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
		$this->salida .="<td align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"ATRAS\"></td>";
		$this->salida .='</form>';
		$this->salida .='</tr>';
		$this->salida .='</table>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


	function SetStyle($campo)
	{
				if ($this->frmError[$campo] || $campo=="MensajeError"){
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					else
					{
						return ("label_error");
					}
				}
			return ("label");
	}



	//fin de funciones creacion profesionales

}
?>

