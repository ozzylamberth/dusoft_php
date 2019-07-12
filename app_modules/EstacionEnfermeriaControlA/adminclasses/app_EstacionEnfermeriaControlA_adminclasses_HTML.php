<?php

/**app_modules/EstacionEnfermeria/userclasses/app_EstacionEnfermeria_adminclasses_HTML.php
* 31/10/2003 9 am
* ----------------------------------------------------------------------
* Autor: Rosa Maria Angel Diez
* Proposito del Archivo: Manejo visual de la estacion de enfermer&iacute;a
* ----------------------------------------------------------------------
*/

class app_EstacionEnfermeriaControlA_adminclasses_HTML extends app_EstacionEnfermeriaControlA_admin
{
	/**
	*		app_EstacionEnfermeria_adminclasses_HTML()
	*
	*		constructor
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@return string
	*/
	function app_EstacionEnfermeriaControlA_adminclasses_HTML()
	{
	  $this->app_EstacionEnfermeria_admin(); //Constructor del padre 'modulo'
		$this->salida = "";
		return true;
	}


	/**
	*		SetStyle() => Muestra mensajes
	*
	*		crea una fila para poner el mensaje de "Faltan campos por llenar" cambiando a color rojo
	*		el label del campo "obligatorio" sin llenar
	*
	*		@Author Alexander Giraldo
	*		@access Private
	*		@return string
	*		@param string => nombre del input y estilo que qued&oacute; vacio
	*/
	function SetStyle($campo,$colum)//CHANGE
	{
				if ($this->frmError[$campo] || $campo=="MensajeError"){
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='$colum' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					return ("label_error");
				}
			return ("label");
	}

	/**
	*		FormaMensaje() => muestra mensajes al usuario
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param string => mensaje a mostrar
	*		@param string => titulo de la tabla
	*		@param string => action del form
	*		@param string => value del input-submit
	*		@return boolean
	*/
	function FormaMensaje($mensaje,$titulo,$accion,$boton)
	{
		$this->salida .= themeAbrirTabla($titulo);
		$this->salida .= "<br><table width=\"60%\" align=\"center\" class=\"modulo_table\">\n";
		$this->salida .= "	<form name=\"formabuscar\" action=\"$accion\" method=\"post\">\n";
		$this->salida .= "		<tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>\n";
		if(!empty($boton)){
			$this->salida .= "	<tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>\n";
		}
		else{
			$this->salida .= "	<tr><td colspan=\"2\" align=\"center\">&nbsp;</td></tr>\n";
		}
		$this->salida .= "	</form>\n";
		$this->salida .= "</table>\n";
		$this->salida .= themeCerrarTabla();
		return true;
	}//fin FormaMensaje


	/**
	*		ListPiezas()
	*
	*		Muestra un listado con las piezas, camas y sus estados
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param array todos los datos ppales de la estación de enfermeria
	*		@return boolean
	*/
	function ListPiezas($datos_estacion)
	{
		$datosCamas = $this->GetPiezas($datos_estacion);
		if($datosCamas === "ShowMensaje")
		{
			$mensaje = "NO EXISTEN HABITACIONES";
			$titulo = "MENSAJE";
			$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$boton = "VOLVER AL MEN&Uacute; ESTACION";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
		$this->salida  = ThemeAbrirTabla('LISTADO DE PIEZAS [ '.$datos_estacion[descripcion1].' ]');
		$this->salida .= "<br><table width=\"100%\" cellpadding=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
		$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "			<td colspan=\"2\">ACCIONES CAMAS</td>\n";
		$this->salida .= "			<td>EE</td>\n";
		$this->salida .= "			<td>PIEZA</td>\n";
		$this->salida .= "			<td wrap>UBICACION</td>\n";
		$this->salida .= "			<td># CAMAS</td>\n";
		$this->salida .= "			<td>CAMA</td>\n";
		$this->salida .= "			<td>ESTADO</td>\n";
		$this->salida .= "			<td>TIPO CAMA</td>\n";
		$this->salida .= "		</tr>\n";

		$vc=$vp=array();

		for($i=0; $i<sizeof($datosCamas); $i++)
		{
			$vp=$datosCamas[$i][0];
			$vc=$datosCamas[$i][1];

			if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
			$linkAddCamas = ModuloGetURL('app','EstacionEnfermeria','admin','CallFrmCreateCamasyPiezas', array("NoPieza"=>1,"estEnf"=>$vp[2],"piezaId"=>$vp[0],"ubicacion"=>$vp[1],"cantCamasPieza"=>sizeof($vc),"datos_estacion"=>$datos_estacion));
			$linkBloqCamas = ModuloGetURL('app','EstacionEnfermeria','admin','CallFrmBloquearCama', array("pieza"=>$vp[0],"camas"=>urlencode(serialize($vc)),"datos_estacion"=>$datos_estacion));//
			$this->salida .= "	<tr class=\"$estilo\">\n";
			$this->salida .= "		<td align=\"center\" rowspan=\"".sizeof($vc)."\"><a href=\"".$linkAddCamas."\">Adicionar<br>Habitación</a></td>\n";
			$this->salida .= "		<td align=\"center\" rowspan=\"".sizeof($vc)."\"><a href=\"".$linkBloqCamas."\">Actualizar<br>Estado</a></td>\n";
			$this->salida .= "		<td align=\"center\" rowspan=\"".sizeof($vc)."\">".$vp[2]."</td>\n";
			$this->salida .= "		<td align=\"center\" nowrap rowspan=\"".sizeof($vc)."\">".$vp[0]."</td>\n";
			$this->salida .= "		<td align=\"center\" rowspan=\"".sizeof($vc)."\">".$vp[1]."</td>\n";
//			$this->salida .= "		<td align=\"center\" rowspan=\"".sizeof($vc)."\">".$estadoPieza."</td>\n";
			$this->salida .= "		<td align=\"center\" rowspan=\"".sizeof($vc)."\">".sizeof($vc)."</td>\n";

			for($j=0;$j<sizeof($vc);$j++)//datos de las camas
			{
				if ($j!=0)//para que haga la primera fila completa, las demas son del rowspan
				{ $this->salida.="<tr class=\"$estilo\">\n"; }

				if(empty($vc[$j][0]))
				{
					$this->salida .= "	<td align=\"center\" colspan=\"3\">NO HAY CAMAS</td>\n";
				}
				else
				{
					$this->salida .= "	<td align=\"center\">".$vc[$j][0]."</td>\n";
					$this->salida .= "	<td align=\"center\">".$vc[$j][1]."</td>\n";
					$this->salida .= "	<td align=\"center\">".$vc[$j][2]."</td>\n";
					$this->salida .= "</tr>\n";
				}
			}
		}
		$this->salida .= "</table>\n";
		$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
		$this->salida .= "<br><div class='normal_10' align='center'><a href='".$href."'>Volver al Menu Estaci&oacute;n</a></div>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/**
	*		FrmBloquearCama()
	*
	*		Muestra las camas y sus estados permitiendo cambiarlos
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param string ide la pieza
	*		@param array camas de la pieza
	*		@param array todos los datos ppales de la estación de enfermeria
	*		@return boolean
	*/
	function FrmBloquearCama($piezaId, $camas, $datos_estacion)
	{
		$estadosCama = $this->GetEstadosCamas();
		$this->salida  = ThemeAbrirTabla('PIEZA '.$piezaId.' [ '.$datos_estacion[descripcion1].' ]');
		$action = ModuloGetURL('app','EstacionEnfermeria','admin','UpdateEstadosCamas',array("datos_estacion"=>$datos_estacion));
		$this->salida .= "<form action=\"$action\" method=\"POST\"><br>\n";
		$this->salida .= "	<table width=\"100%\" cellpadding=\"2\" border=\"0\" align=\"center\" class=\"normal_10\">\n";
		$this->salida .= "		<tr>\n";
		$this->salida .= "			<td>\n";
		$this->salida .= "				<table width=\"100%\" cellpadding=\"2\" border=\"0\" align=\"center\" class=\"modulo_table_list\">\n";
		$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "						<td>CAMA</td>\n";
		$this->salida .= "						<td>TIPO CAMA</td>\n";
		$this->salida .= "						<td>ESTADO</td>\n";
		$this->salida .= "					</tr>\n";
		/* $camas[0] = cama 					$camas[1] = desc_estado				$camas[2] = desc_tipo_cama
			 $camasas[3] = estado_id 		$camas[4] = tipo_cama_id )*/
		for($i=0; $i<sizeof($camas); $i++)
		{
			if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
			$this->salida .= "				<tr class=\"".$estilo."\" align=\"center\">\n";
			$this->salida .= "					<td>".$camas[$i][0]."</td>\n";
			$this->salida .= "					<td>".$camas[$i][2]."</td>\n";
			$this->salida .= "					<td align=\"center\">\n";
			if($camas[$i][3] == 0)//cama ocupada
				$this->salida .= $camas[$i][1];
			else //hay camas disponibles, se les puede cambiar el estado
			{
				$MostrarBoton = "1";
				$this->salida .= "					<input type=\"hidden\" name=\"camas[]\" value=\"".$camas[$i][0]."\">\n";
				$this->salida .= "					<select name=\"estadoCama[]\" class=\"select\">\n";
				for($j=0; $j<sizeof($estadosCama); $j++)
				{
					if($estadosCama[$j][0] == $camas[$i][3]) $selected = "selected='yes'"; else $selected="";
     				$this->salida .= "				<option value=\"".$estadosCama[$j][0]."\" $selected\">".$estadosCama[$j][1]."</option> \n";
				}
				$this->salida .= "					</select>\n";
			}
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
		}
		$this->salida .= "				</table>\n";
		$this->salida .= "			</td>\n";
		$this->salida .= "		</tr>\n";
		if($MostrarBoton)
		{
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$this->salida .= "<tr align=\"center\"><td><br><br><input type=\"submit\" name=\"submit\" value=\"ACTUALIZAR ESTADOS\" class=\"input-submit\">&nbsp;&nbsp;&nbsp;&nbsp;<input type='reset' name='Reset' value='REESTABLECER' class='input-submit'></td></tr>\n";
		}
		$this->salida .= "</form>\n";
		$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
		$this->salida .= "<tr align=\"center\"><td><br><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a></td></tr>\n";
		$this->salida .= "</table>\n";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/**
	*		FrmCreatePieza()
	*
	*		Formulario que permite crear una habitacion
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param array todos los datos ppales de la estación de enfermeria
	*		@param string id de la pieza (cuando recarga el formulario)
	*		@param string descripcion de la pieza (cuando recarga el formulario)
	*		@param string ubicacion de la pieza(cuando recarga el formulario)
	*		@param integer cantidad de camas(cuando recarga el formulario)
	*		@param string prefijo de numeracion(cuando recarga el formulario)
	*		@return boolean
	*/
	function FrmCreatePieza($datos_estacion,$piezaId,$descripcion,$ubicacion,$cantCamas,$camaPrefijo)//parametros para el error
	{
		$this->salida  = ThemeAbrirTabla('CREAR NUEVA HABITACION [ '.$datos_estacion[descripcion1].' ]');
		$action = ModuloGetURL('app','EstacionEnfermeria','admin','CallFrmCreateCamasyPiezas',array("datos_estacion"=>$datos_estacion));
		$this->salida .= "<form action=\"$action\" method=\"POST\"><br>\n";
		$this->salida .= "	<table cellpadding=\"2\" border=\"0\" align=\"center\">\n";
		$this->salida .= "		<tr>\n";
		$this->salida .= "			<td>\n";
		$this->salida .= "				<fieldset class=\"field\"><legend>DATOS HABITACION</legend>\n";
		$this->salida .= "					<table cellpadding=\"2\" border=\"0\" align=\"left\">\n";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "						<tr>\n";
		$this->salida .= "							<td class=\"".$this->SetStyle("piezaId")."\">N&Uacute;MERO DE HABITACI&Oacute;N</td>\n";
		$this->salida .= "							<td><input type=\"text\" name=\"piezaId\" size=4 maxlength=4 class=\"input-text\" value=\"$piezaId\"></td>\n";
		$this->salida .= "							<td>&nbsp;</td>\n";
		$this->salida .= "						</tr>\n";
		$this->salida .= "						<tr>\n";
		$this->salida .= "							<td class=\"".$this->SetStyle("descripcion")."\">DESCRIPCI&Oacute;N</td>\n";
		$this->salida .= "							<td><input type=\"text\" name=\"descripcion\" size=20 maxlength=20 class=\"input-text\" value=\"$descripcion\"></td>\n";
		$this->salida .= "							<td>&nbsp;</td>\n";
		$this->salida .= "						</tr>\n";
		$this->salida .= "						<tr>\n";
		$this->salida .= "							<td class=\"".$this->SetStyle("ubicacion")."\">UBICACION</td>\n";
		$this->salida .= "							<td><input type=\"text\" name=\"ubicacion\" size=30 class=\"input-text\" value=\"$ubicacion\"></td>\n";
		$this->salida .= "							<td>&nbsp;</td>\n";
		$this->salida .= "						</tr>\n";
		$this->salida .= "						<tr>\n";
		$this->salida .= "							<td class=\"".$this->SetStyle("estEnf")."\">ESTACION DE ENFERMER&Iacute;A</td>\n";
		$this->salida .= "							<td><select name=\"estEnf\" class=\"select\">\n";
		$estaciones = $this->GetEstaciones($datos_estacion);
		for($i=0; $i<sizeof($estaciones); $i++)
		{
			$this->salida .= "									<option value=\"".$estaciones[$i][0]."$".$estaciones[$i][1]."\">".$estaciones[$i][1]."</option>";
		}
		$this->salida .= "									</select></td>\n";
		$this->salida .= "							<td>&nbsp;</td>\n";
		$this->salida .= "						</tr>\n";
		$this->salida .= "						<tr>\n";
		$this->salida .= "							<td class=\"".$this->SetStyle("cantCamas")."\">CANTIDAD DE CAMAS</td>\n";
		$this->salida .= "							<td><input type=\"text\" name=\"cantCamas\" size=3  maxlength=3 class=\"input-text\" value=\"$cantCamas\"></td>\n";
		$this->salida .= "							<td>&nbsp;</td>\n";
		$this->salida .= "						</tr>\n";
		$this->salida .= "				</table>\n<br>";
		$this->salida .= "			</fieldset>\n";
		$this->salida .= "		</td>\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= "		<tr>\n";
		$this->salida .= "			<td colspan=\"3\">\n";
		$this->salida .= "			<fieldset class=\"field\"><legend>NUMERACION CAMAS</legend>\n";
		$this->salida .= "				<table cellpadding=\"2\" border=\"0\" align=\"left\">\n";
		$this->salida .= "					<tr>\n";
		$this->salida .= "						<td class=\"".$this->SetStyle("camaPrefijo")."\">PREFIJO</td>\n";
		$this->salida .= "						<td><input type=\"text\" name=\"camaPrefijo\" size=5 maxlength=5 class=\"input-text\" value=\"$camaPrefijo\"></td>\n";
		$this->salida .= "						<td class=\"".$this->SetStyle("numeracion")."\">NUMERACION</td>\n";
		$this->salida .= "						<td><select name=\"numeracion\" class=\"select\">\n";
		$this->salida .= "									<option value=\"N\">numeros</option>\n";
		$this->salida .= "									<option value=\"L\">Letras</option>\n";
    $this->salida .= "								</select>\n";
		$this->salida .= "						</td>\n";
		$this->salida .= "					</tr>\n";
		$this->salida .= "				</table>\n";
		$this->salida .= "			</fieldset>\n";
		$this->salida .= "			</td>\n";
		$this->salida .= "		</tr>\n";
		$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
		$this->salida .= "		<tr align=\"center\" class='modulo_table_list'>\n";
		$this->salida .= "			<td colspan=\"3\"><input type=\"submit\" name=\"submit\" value=\"CREAR HABITACION\" class=\"input-submit\"><BR><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a></td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "	</table>\n<br>";
		$this->salida .= "</form>\n";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


	/**
	*		FrmCreateCamasyPiezas
	*
	*		Formulario para insertar los datos de las piezas a crear
	*
	*		@Author Rosa Maria Angel
	*		@access Private

	*		@param array datos de la habitacion
	*		@param string prefijo de numeracion(cuando recarga el formulario)
	*		@param string tipo de numeracion
	*		@param array todos los datos ppales de la estación de enfermeria
	*		@return bool
	*/
	function FrmCreateCamasyPiezas($datosPieza,$camaId,$prefijo,$numeracion,$datos_estacion)
	{
	//	$numCama = $this->SetNumeracionCamas($datosPieza[3],$prefijo,$numeracion);
		if(!$datos_estacion){
			$datos_estacion = $_REQUEST['datos_estacion'];
		}//echo "<br>FrmCreateCamasyPiezas<br>"; print_r($datos_estacion);// exit;
		$estadosCama = $this->GetEstadosCamas();

		$tiposCamas = $this->GetTiposCamas();

		$action = ModuloGetURL('app','EstacionEnfermeria','admin','InsertCamasPieza',array("datos_estacion"=>$datos_estacion));
		$this->salida  = ThemeAbrirTabla('CREAR NUEVA HABITACION - PASO 2');
		$this->salida .= "<form action=\"$action\" method=\"POST\">\n";
		$this->salida .= "	<table name=\"transp\" cellpadding=\"2\" border=\"0\" align=\"center\">\n";
		$this->salida .= "		<tr>\n";
		$this->salida .= "			<td>\n";
		$this->salida .= "				<table name=\"ContienePieza\" width=\"100%\" cellpadding=\"2\" border=\"1\" align=\"center\" class=\"normal_10\">\n";
		$this->salida .= "					<tr class=\"modulo_table_title\"><td colspan=\"2\">DATOS HABITACION</td></tr>\n";
		$this->salida .= "					<tr>\n";
		$this->salida .= "						<td>\n";
		$this->salida .= "							<table name=\"tabledatosPiezas\" cellpadding=\"2\" border=\"0\" align=\"center\" class=\"normal_10\">\n";
		$this->salida .= "								<tr>\n";
		$this->salida .= "									<td class=\"label\">N&Uacute;MERO DE HABITACI&Oacute;N</td>\n";
		$this->salida .= "									<td>".$datosPieza[0]."<input type=\"hidden\" name=\"piezaId\" value=\"".$datosPieza[0]."\"></td>\n";
		$this->salida .= "								</tr>\n";
		$this->salida .= "								<tr>\n";
		$this->salida .= "									<td class=\"label\">UBICACION</td>\n";
		$this->salida .= "									<td>".$datosPieza[1]."<input type=\"hidden\" name=\"ubicacion\" value=\"".$datosPieza[1]."\"></td>\n";
		$this->salida .= "								</tr>\n";
		$this->salida .= "								<tr>\n";
		$this->salida .= "									<td class=\"label\">ESTACION DE ENFERMER&Iacute;A</td>\n";
		$this->salida .= "									<td>".$datosPieza[2]."<input type=\"hidden\" name=\"estEnf\" value=\"".$datosPieza[2]."\"></td>\n";
		$this->salida .= "								</tr>\n";
		$this->salida .= "							</table>\n<br>";
		$this->salida .= "						</td>\n";
		$this->salida .= "					</tr>\n";
		$this->salida .= "				</table>\n";
		$this->salida .= "			</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "		<tr>\n";
		$this->salida .= "			<td>\n";
		$this->salida .= "				<table name=\"tabledatosCamas\" cellpadding=\"2\" border=\"1\" align=\"center\">\n";
		$this->salida .= "					<tr class=\"modulo_table_title\"><td colspan=\"6\">DATOS CAMA</td></tr>\n";
		$this->salida .= "<input type=\"hidden\" name=\"cantCamas\" value=\"".$datosPieza[3]."\">\n";
		$this->salida .= "<input type=\"hidden\" name=\"cantCamasPieza\" value=\"".$datosPieza[4]."\">\n";//cantidad de camas de la pieza en la bd
		for($i=0; $i<$datosPieza[3]; $i++)//cantidad de camas a insertar
		{
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"".$this->SetStyle("camaId")."\">ID CAMA ".($i+1)."</td>\n";
			$this->salida .= "					<td><input type=\"text\" name=\"camaId".$i."\" size=\"8\" maxlength=\"8\" class=\"input-text\" value=\"".$this->SetNumeracionCamas($i,$prefijo,$numeracion)."\"></td>\n";
			$this->salida .= "					<td class=\"".$this->SetStyle("estadoCama")."\">ESTADO</td>\n";
			$this->salida .= "					<td><select name=\"estadoCama".$i."\" class=\"select\">\n";
			for($j=0; $j<sizeof($estadosCama); $j++)
			{
				$this->salida .= "							<option value=\"".$estadosCama[$j][0]."\">".$estadosCama[$j][1]."</option>\n";
			}
			$this->salida .= "								</select>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "					<td class=\"".$this->SetStyle("tipoCama")."\">TIPO CAMA</td>\n";
			$this->salida .= "					<td><select name=\"tipoCama".$i."\" class=\"select\">\n";
			for($j=0; $j<sizeof($tiposCamas); $j++)
			{
				$this->salida .= "							<option value=\"".$tiposCamas[$j][0]."\">".$tiposCamas[$j][1]."</option>\n";
			}
			$this->salida .= "								</select>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
		}
		$this->salida .= "				</table>\n";
		$this->salida .= "			</td>\n";
		$this->salida .= "		</tr>\n";
		$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
		$this->salida .= "		<tr align=\"center\" class='modulo_table_list'>\n";
		$this->salida .= "			<td colspan=\"2\"><input type=\"submit\" name=\"submit\" value=\"CREAR CAMAS\" class=\"input-submit\"><BR><BR><div class='normal_10' align='center'><a href='".$href."'>Volver al Menu Estaci&oacute;n</a></td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "	</table>\n<br>";
		$this->salida .= "</form>\n";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/**
	*		SetNumeracionCamas
	*
	*		define la numeraci&oacute;n para las camas de la pieza teniendo en cuenta el prefijo y tipo de numeraci&oacute;n seleccionado por el usuario
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@access Private
	*		@param integer numero de camas
	*		@param string prefijo de numeracion
	*		@param string tipo de numeracion
	*		@return string
	*		@return bool
	*/
	function SetNumeracionCamas($ncamas,$prefijo,$numeracion)
	{
		$numCamas = array();
		$letra=$fijo="";
		if($prefijo && $numeracion)
		{
			if($numeracion == 'L')//numeracion en letras
			{
				$letras = array();
				array_push($letras,"A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");

				$dv = floor($ncamas/sizeof($letras));
				$res = $ncamas%sizeof($letras);
				$fijo = $this->GetSerieLetras($ncamas,$letras);
				if ($fijo=="")
				{
					return ($letra=$prefijo.$letras[$ncamas]);
				}
				else
				{
					return ($letra=$prefijo.$fijo.$letras[$res]);
				}
			}
			else//numeracion en numeros
			{
				$ncamas++;
				return ($prefijo.str_pad($ncamas, 3, "0", STR_PAD_LEFT));
			}
		}
	}


	/**
	*		FrmMantenimientoPltSistemas
	*
	*		Formulario que permita actualizar la plantilla de revision por sistemas
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param array datos de la estacion
	*		@return bool
	*/
	function FrmMantenimientoPltSistemas($datos_estacion)
	{	//$plantillasEstacion[0][descripcion]   $plantillasEstacion[0][hc_ne_plantilla_sistema_id]

		$PlantillaGeneral = $this->GetPlantillaGeneral();//llamo la plantilla General
		$plantillasEstacion = $this->GetPlantillasEstacion($datos_estacion[estacion_id]);
		if(!$plantillasEstacion){// || !$plantillas
			return false;
		}
		$this->salida .= themeAbrirTabla("PLANTILLAS DE LA ESTACION - [ $datos_estacion[descripcion5] ]");
		$this->salida .= "<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\" class='normal_10'>";
		$this->salida .= $this->SetStyle("MensajeError",1);
		/*$action = ModuloGetURL('app','EstacionEnfermeria','admin','InsertarPlantilla',array("datos_estacion"=>$datos_estacion));
		$this->salida .= "	<form name=\"insertaPlantilla\" method=\"POST\" action=\"$action\"><br>\n";
		$this->salida .= "	<tr align='center'><td class='label'>CREAR NUEVA PLANTILLA:&nbsp; <input type='text' name='plantilla' class='input-text' size=30>&nbsp;<input type='submit' name='InsertaPlantilla' value='CREAR' class='input-submit'></td></tr>\n";
		$this->salida .= "	</form>";
		$this->salida .= "	<tr align='center'><td class='label'>&nbsp;</td></tr>\n";*/
		$this->salida .= "	<tr>\n";
		$this->salida .= "		<td>\n";
		$this->salida .= "			<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
		$this->salida .= "				<tr class=\"modulo_table_title\">\n";
		$this->salida .= "					<td colspan='7' align='center'>REVISION POR SISTEMAS</td>\n";
		$this->salida .= "				</tr>\n";
		$action = ModuloGetURL('app','EstacionEnfermeria','admin','MantenimientoPlantilla',array("datos_estacion"=>$datos_estacion));
		$this->salida .= "	<form name=\"MantenimientoPlantilla\" method=\"POST\" action=\"$action\"><br>\n";
		$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "					<td>SISTEMA</td>\n";
		$this->salida .= "					<td>CATEGORIA</td>\n";
		$this->salida .= "					<td>OPCIONES</td>\n";
		$this->salida .= "					<td>TEXTO</td>\n";
		$this->salida .= "					<td colspan='2'>ACCIONES</td>\n";
		$this->salida .= "					<td>ASIGNAR</td>\n";
		$this->salida .= "				</tr>\n";
		if($PlantillaGeneral === "ShowMensaje")
		{
			$hrefAddSist = ModuloGetURL('app','EstacionEnfermeria','admin','CallFrmAddItemsPlantilla',array("add"=>"sistema","datos_estacion"=>$datos_estacion));
			$this->salida .= "					<td colspan='7'>\n";
			$this->salida .= "						<a href=\"$hrefAddSist\">ADD SISTEMA</a>\n";
			$this->salida .= "					</td>\n";
		}
		else
		{
			$i = $contadorSeleccionado = 0;
			foreach ($PlantillaGeneral[datos] as $key => $sistemas)
			{
				$sizeofSistema = $PlantillaGeneral[sizeofSistema];
				$sizeofRevision = $PlantillaGeneral[sizeofRevision];//$sizeofSistema[$key]
				foreach($sizeofRevision[$key] as $kk => $vc){
					$dat_sis[$key][]=$kk;
				}
				//echo "<br><br>Sistema=> ".$key." sizeof ".$sizeofSistema[$key]."<br>"; print_r($sistemas);
				if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
				$this->salida .= "				<tr valign='middle' class='$estilo'>\n";

				$hrefEditar = ModuloGetURL('app','EstacionEnfermeria','admin','CallFrmUpdateRxSdetalle',array("cambiar"=>"sistema","sistema_id"=>$key,"sistema"=>$sistemas[$dat_sis[$key][0]][0][sistema],"datos_estacion"=>$datos_estacion));
				$hrefElim = ModuloGetURL('app','EstacionEnfermeria','admin','ElimRxSdetalle',array("borrar"=>"sistema","plantilla"=>$plantillasEstacion[0][hc_ne_plantilla_sistema_id],"sistema_id"=>$key,"datos_estacion"=>$datos_estacion));
				$hrefAddCat = ModuloGetURL('app','EstacionEnfermeria','admin','CallFrmAddItemsPlantilla',array("add"=>"revision","sistema_id"=>$key,"sistema"=>$sistemas[$dat_sis[$key][0]][0][sistema],"datos_estacion"=>$datos_estacion));
				$hrefAddSist = ModuloGetURL('app','EstacionEnfermeria','admin','CallFrmAddItemsPlantilla',array("add"=>"sistema","datos_estacion"=>$datos_estacion));
				$this->salida .= "					<td rowspan='".$sizeofSistema[$key]."'>\n";
				if($sistemas[$dat_sis[$key][0]][0][sistema] != NULL)
				{
					$this->salida .= "					<b>".$sistemas[$dat_sis[$key][0]][0][sistema]."</b><br>\n";
					$this->salida .= "					<a href=\"$hrefEditar\">EDITAR</a>&nbsp;-&nbsp;\n";
					$this->salida .= "					<a href=\"$hrefElim\">ELIMINAR</a><br>\n";
					$this->salida .= "					<a href=\"$hrefAddCat\">ADD CATEGORIA</a><br>\n";
				}
				$this->salida .= "						<a href=\"$hrefAddSist\">ADD SISTEMA</a>\n";
				$this->salida .= "					</td>\n";
				$j=0;
				foreach ($sistemas as $A => $Categorias)
				{
					if(!$j)//para que haga la primera fila completa, las demas son del rowspan
					{
						$sizeofRevision = $PlantillaGeneral[sizeofRevision];//$sizeofSistema[$key] //echo "<br><br>Revision=> ".$A." sizeof Revision".sizeof($Categorias)."<br>"; print_r($Categorias);
						$hrefEditar = ModuloGetURL('app','EstacionEnfermeria','admin','CallFrmUpdateRxSdetalle',array("cambiar"=>"revision","sistema_id"=>$key,"sistema"=>$sistemas[$dat_sis[$key][0]][0][sistema],"categoria_id"=>$A,"categoria"=>$Categorias[0][revision],"datos_estacion"=>$datos_estacion));//,"detalle_id"=>$Detalle[hc_ne_detalle_id],"detalle"=>$Detalle[detalle],
						$hrefElim = ModuloGetURL('app','EstacionEnfermeria','admin','ElimRxSdetalle',array("borrar"=>"revision","plantilla"=>$plantillasEstacion[0][hc_ne_plantilla_sistema_id],"sistema_id"=>$key,"categoria_id"=>$A,"datos_estacion"=>$datos_estacion));//"detalle_id"=>$Detalle[hc_ne_detalle_id],
						$hrefAddOpcion = ModuloGetURL('app','EstacionEnfermeria','admin','CallFrmAddItemsPlantilla',array("add"=>"opcion","sistema_id"=>$key,"sistema"=>$sistemas[$dat_sis[$key][0]][0][sistema],"categoria_id"=>$A,"categoria"=>$Categorias[0][revision],"datos_estacion"=>$datos_estacion));
						$this->salida .= "					<td rowspan='".$sizeofRevision[$key][$A]."' valign='middle' class='$estilo'>\n";
						if($Categorias[0][revision] != NULL)
						{
							$this->salida .= "					".$Categorias[0][revision]."<br>\n";
							$this->salida .= "					<a href=\"$hrefEditar\">EDITAR</a>&nbsp;\n";
							$this->salida .= "					<a href=\"$hrefElim\">ELIMINAR</a><BR>\n";
							$this->salida .= "					<a href=\"$hrefAddOpcion\">ADD OPCION</a>\n";
						}
						$this->salida .= "					</td>\n";
						foreach ($Categorias as $X => $Detalle)
						{//echo "<br><br>Detalle=> ".$X."<br>"; print_r($Detalle);
							$this->salida .= "					<td class='$estilo'>".$Detalle[detalle]."</td>\n";
							if($Detalle[sw_complemento]){
								$checkedComplemento = "checked";
								$sw_complemento = '1';
							}
							else{
								$checkedComplemento = "";
								$sw_complemento = '0';
							}
							if(!empty($Detalle[hc_ne_detalle_id]))
							{
								if(is_array($plantillasEstacion)){
									$existe = $this->VerificaDetalle($plantillasEstacion[0][hc_ne_plantilla_sistema_id],$key,$A,$Detalle[hc_ne_detalle_id]);
									if($existe === "vacio"){
										$lotiene = "";
									}
									else
									{//if($existe === "lleno"){
										$lotiene = "checked";
										if(($existe == 1)){//if(($checkedComplemento === "checked") && ($existe == 1))
											$checkedComplemento = "checked";
											$sw_complemento = '1';
										}
										elseif(($existe == 0)){//elseif(($checkedComplemento === "checked") && ($existe == 0))
											$checkedComplemento = "";
											$sw_complemento = '0';
										}
										else{
											echo "<br>verificar esto!!!!!!!<br>";
										}
									}
								}//<br>plantilla=> ".$Detalle[sw_complemento]." estacion=> ".$existe."<br>
								$this->salida .= "					<td align='center' class='$estilo'><input type='checkbox' name='sw_complemento[]' $checkedComplemento value='".$key.".-.".$A.".-.".$Detalle[hc_ne_detalle_id]."'></td>\n";
								$hrefEditar = ModuloGetURL('app','EstacionEnfermeria','admin','CallFrmUpdateRxSdetalle',array("cambiar"=>"detalle","sistema_id"=>$key,"sistema"=>$sistemas[$dat_sis[$key][0]][0][sistema],"categoria_id"=>$A,"categoria"=>$Categorias[0][revision],"detalle_id"=>$Detalle[hc_ne_detalle_id],"detalle"=>$Detalle[detalle],"sw_complemento"=>$sw_complemento,"datos_estacion"=>$datos_estacion));
								$this->salida .= "					<td class='$estilo' align='center'><a href=\"$hrefEditar\">EDITAR</a></td>\n";
								$hrefElim = ModuloGetURL('app','EstacionEnfermeria','admin','ElimRxSdetalle',array("borrar"=>"detalle","sistema_id"=>$key,"categoria_id"=>$A,"detalle_id"=>$Detalle[hc_ne_detalle_id],"datos_estacion"=>$datos_estacion));
								$this->salida .= "					<td class='$estilo' align='center'><a href=\"$hrefElim\">ELIMINAR</a></td>\n";
	//							$hrefElim = ModuloGetURL('app','EstacionEnfermeria','admin','ElimRxSdetalle',array("borrar"=>"detalle","sistema_id"=>$key,"categoria_id"=>$A,"detalle_id"=>$Detalle[hc_ne_detalle_id],"datos_estacion"=>$datos_estacion));
								$this->salida .= "					<td align='center' class='$estilo'><input type='checkbox' name='seleccionado[]' $lotiene value='".$key.".-.".$A.".-.".$Detalle[hc_ne_detalle_id]."'></td>\n";
								$contadorSeleccionado ++;
							}
							else
							{
								$this->salida .= "					<td align='center' class='$estilo'>&nbsp;</td>\n";
								$this->salida .= "					<td align='center' class='$estilo'>&nbsp;</td>\n";
								$this->salida .= "					<td align='center' class='$estilo'>&nbsp;</td>\n";
								$this->salida .= "					<td align='center' class='$estilo'>&nbsp;</td>\n";
							}
							$this->salida .= "				</tr>\n";
						}$j++;
					}
					else
					{
						$this->salida.="<tr class=\"$estilo\">\n";
						$sizeofRevision = $PlantillaGeneral[sizeofRevision];//$sizeofSistema[$key]
						$hrefEditar = ModuloGetURL('app','EstacionEnfermeria','admin','CallFrmUpdateRxSdetalle',array("cambiar"=>"revision","sistema_id"=>$key,"sistema"=>$sistemas[$dat_sis[$key][0]][0][sistema],"categoria_id"=>$A,"categoria"=>$Categorias[0][revision],"datos_estacion"=>$datos_estacion));//,"detalle_id"=>$Detalle[hc_ne_detalle_id],"detalle"=>$Detalle[detalle],
						$hrefElim = ModuloGetURL('app','EstacionEnfermeria','admin','ElimRxSdetalle',array("borrar"=>"revision","plantilla"=>$plantillasEstacion[0][hc_ne_plantilla_sistema_id],"sistema_id"=>$key,"categoria_id"=>$A,"datos_estacion"=>$datos_estacion));//"detalle_id"=>$Detalle[hc_ne_detalle_id],
						$hrefAddOpcion = ModuloGetURL('app','EstacionEnfermeria','admin','CallFrmAddItemsPlantilla',array("add"=>"opcion","sistema_id"=>$key,"sistema"=>$sistemas[$dat_sis[$key][0]][0][sistema],"categoria_id"=>$A,"categoria"=>$Categorias[0][revision],"datos_estacion"=>$datos_estacion));
						$this->salida .= "					<td rowspan='".$sizeofRevision[$key][$A]."' valign='middle' class='$estilo'>\n";
						if($Categorias[0][revision] != NULL)
						{
							$this->salida .= "					".$Categorias[0][revision]."<br>\n";
							$this->salida .= "					<a href=\"$hrefEditar\">EDITAR</a>&nbsp;\n";
							$this->salida .= "					<a href=\"$hrefElim\">ELIMINAR</a><BR>\n";
							$this->salida .= "					<a href=\"$hrefAddOpcion\">ADD OPCION</a>\n";
						}
						$this->salida .= "					</td>\n";
						foreach ($Categorias as $X => $Detalle)
						{
							if($Categorias[0][revision] != NULL){
								$tiene = 1;
							}
							else{
								$tiene = 0;
							}
							$this->salida .= "					<td class='$estilo'>".$Detalle[detalle]."</td>\n";
							if($Detalle[sw_complemento]){
								$checkedComplemento = "checked";
								$sw_complemento = '1';
							}
							else {
								$checkedComplemento = "";
								$sw_complemento = '0';
							}
							if(!empty($Detalle[hc_ne_detalle_id]))
							{
								if(is_array($plantillasEstacion))
								{
									$existe = $this->VerificaDetalle($plantillasEstacion[0][hc_ne_plantilla_sistema_id],$key,$A,$Detalle[hc_ne_detalle_id]);
									if($existe === "vacio"){
										$lotiene = "";
									}
									else
									{//if($existe === "lleno"){
										$lotiene = "checked";
										if(($existe == 1)){//if(($checkedComplemento === "checked") && ($existe == 1)){
											$checkedComplemento = "checked";
											$sw_complemento = '1';
										}
										elseif(($existe == 0)){//elseif(($checkedComplemento === "checked") && ($existe == 0)){
											$checkedComplemento = "";
											$sw_complemento = '0';
										}
										else{
											echo "<br><br><br><br>verificar esto!!!!!!!<br>";
										}
									}
								}//<br>plantilla=> ".$Detalle[sw_complemento]." estacion=> ".$existe."<br>
								$this->salida .= "					<td class='$estilo' align='center'><input type='checkbox' name='sw_complemento[]' $checkedComplemento value='".$key.".-.".$A.".-.".$Detalle[hc_ne_detalle_id]."'></td>\n";
								$hrefEditar = ModuloGetURL('app','EstacionEnfermeria','admin','CallFrmUpdateRxSdetalle',array("cambiar"=>"detalle","sistema_id"=>$key,"sistema"=>$sistemas[$dat_sis[$key][0]][0][sistema],"categoria_id"=>$A,"categoria"=>$Categorias[0][revision],"detalle_id"=>$Detalle[hc_ne_detalle_id],"detalle"=>$Detalle[detalle],"sw_complemento"=>$sw_complemento,"datos_estacion"=>$datos_estacion));
								$this->salida .= "					<td class='$estilo' align='center'><a href=\"$hrefEditar\">EDITAR</a></td>\n";
								$hrefElim = ModuloGetURL('app','EstacionEnfermeria','admin','ElimRxSdetalle',array("borrar"=>"detalle","sistema_id"=>$key,"categoria_id"=>$A,"detalle_id"=>$Detalle[hc_ne_detalle_id],"datos_estacion"=>$datos_estacion));
								$this->salida .= "					<td class='$estilo' align='center'><a href=\"$hrefElim\">ELIMINAR</a></td>\n";
								$checkedComplemento = "checked";
								$this->salida .= "					<td align='center' class='$estilo'><input type='checkbox' name='seleccionado[]' $lotiene value='".$key.".-.".$A.".-.".$Detalle[hc_ne_detalle_id]."'></td>\n";
								$contadorSeleccionado ++;
							}
							else
							{
								$this->salida .= "					<td align='center' class='$estilo'>&nbsp;</td>\n";
								$this->salida .= "					<td align='center' class='$estilo'>&nbsp;</td>\n";
								$this->salida .= "					<td align='center' class='$estilo'>&nbsp;</td>\n";
								$this->salida .= "					<td align='center' class='$estilo'>&nbsp;</td>\n";
							}
							$this->salida .= "				</tr>\n";
						}//End forech detalle
						$j++;
					}//end  else
				}//End foreach $revision
				$i++;
			}//end foreach $sistemas
		}//si hay rxs
		##########################
		$this->salida .= "			</table><br>\n";
		$this->salida .= "		</td>\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= "</table>\n";
		if($PlantillaGeneral != "ShowMensaje")
		{
			$this->salida .= "<input type='hidden' name='CantDetalles' value='".$contadorSeleccionado."'>\n";
			$this->salida .= "<input type='hidden' name='Plantilla' value='".$plantillasEstacion[0][hc_ne_plantilla_sistema_id]."'>\n";
			$this->salida .= "<div class='normal_10' align='center'><br><input type='submit' name='ActualizaPlantilla' value='ACTUALIZAR CAMBIOS' class='input-submit'><br></div>\n";
			$this->salida .= "</form>\n";
		}
		$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
		$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br></div>\n";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}//FrmMantenimientoPltSistemas

	/**
	*		FrmUpdateRxSdetalle
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param string sw que define el item a actualizar
	*		@param integer id del sistema
	*		@param string nombre del sistema
	*		@param integer id de la categoria
	*		@param string nombre de la categoria
	*		@param integer id del detalle
	*		@param string nombre del detalle
	*		@param string sw para definir el complemento
	*		@param array datos de la estación
	*		@return bool
	*/
	function FrmUpdateRxSdetalle($cambiar,$sistema_id,$sistema,$categoria_id,$categoria,$detalle_id,$detalle,$sw_complemento,$datos_estacion)
	{
		if($cambiar === "detalle"){
			$Nuevo = $detalle;
		}
		if($cambiar === "revision"){
			$Nuevo = $categoria;
		}
		if($cambiar === "sistema"){
			$Nuevo = $sistema;
		}
		//echo "<br>cambiar=> $cambiar sistemaId=> $sistema_id Sistemas=> $sistema<br>CategoriaId=> $categoria_id Categoria=> $categoria<br>DetalleId=> $detalle_id Detalle=> $detalle comple=> $sw_complemento";
		$action = ModuloGetURL('app','EstacionEnfermeria','admin','UpdatePlantilla',array("cambiar"=>$cambiar,"sistema_id"=>$sistema_id,"categoria_id"=>$categoria_id,"detalle_id"=>$detalle_id,"datos_estacion"=>$datos_estacion));
		$this->salida .= "<form name=\"UpdatePlantilla\" method=\"POST\" action=\"$action\"><br>\n";
		$this->salida .= themeAbrirTabla("EDICI&Oacute;N DE LA PLANTILLA")."<br>";
		$this->salida .= "<table align=\"center\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"normal_10\">\n";
		$this->salida .= "	<tr>\n";// class=\"modulo_table_title\"
		$this->salida .= "		<td class=\"label\">OPCION: </td><td><input type='text' name='Nuevo' value='$Nuevo' size=60 maxlength=255 class='input-text'></td><td>&nbsp;</td>\n";
		$this->salida .= "	</tr>\n";
		if($cambiar === "detalle")
		{
			if($sw_complemento == 1){
				$checkSI = "checked"; $checkNO = "";
			}
			else{
				$checkSI = ""; $checkNO = "checked";
			}
			$this->salida .= "	<tr align='center'>\n";
			$this->salida .= "		<td class=\"label\">COMPLEMENTO: </td><td align='left'><input type='radio' name='sw_complemento' value='0' $checkNO>NO&nbsp;&nbsp;<input type='radio' name='sw_complemento' value='1' $checkSI>SI</td><td>&nbsp;</td>\n";
			$this->salida .= "	</tr>\n";
		}
		$this->salida .= "	<tr align='center'>\n";
		$this->salida .= "		<td colspan='3'><input type='submit'name='ACTUALIZAR' value='ACTUALIZAR' class='input-submit'></td>\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= "</table>\n";
		$this->salida .= "</form>\n";
		$href = ModuloGetURL('app','EstacionEnfermeria','admin','CallFrmMantenimientoPltSistemas',array("datos_estacion"=>$datos_estacion));
		$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver a la plantilla</a><br></div>\n";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}//UpdateRxSdetalle


	/**
	*		FrmAddItemsPlantilla
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param string sw que define el item a adicionar
	*		@param integer id del sistema
	*		@param string nombre del sistema
	*		@param integer id de la categoria
	*		@param string nombre de la categoria
	*		@param string sw para definir el complemento
	*		@param array datos de la estación
	*		@return bool
	*/
	function FrmAddItemsPlantilla($add,$sistema_id,$sistema,$categoria_id,$categoria,$datos_estacion)
	{
		if($add === "sistema"){
			$label = "SISTEMA";
		}
		elseif($add === "revision"){
			$label = "CATEGORIA";
		}
		elseif($add === "opcion"){
			$label = "OPCION";
		}//echo "<br>add=> $add sistemaId=> $sistema_id Sistemas=> $sistema";
		$action = ModuloGetURL('app','EstacionEnfermeria','admin','InsertarItemPlantilla',array("add"=>$add,"sistema_id"=>$sistema_id,"categoria_id"=>$categoria_id,"datos_estacion"=>$datos_estacion));
		$this->salida .= "<form name=\"UpdatePlantilla\" method=\"POST\" action=\"$action\"><br>\n";
		$this->salida .= themeAbrirTabla("ADICIONAR ITEM A LA PLANTILLA GENERAL")."<br>";
		$this->salida .= "<table align=\"center\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"normal_10\">\n";
		if($add != "sistema")
		{
			$this->salida .= "<tr>\n";// class=\"modulo_table_title\"
			$this->salida .= "	<td class=\"label\">SISTEMA: </td><td>$sistema</td><td>&nbsp;</td>\n";
			$this->salida .= "</tr>\n";
		}
		if($add === "opcion")
		{
			$this->salida .= "<tr>\n";// class=\"modulo_table_title\"
			$this->salida .= "	<td class=\"label\">CATEGORIA: </td><td>$categoria</td><td>&nbsp;</td>\n";
			$this->salida .= "</tr>\n";
		}
		$this->salida .= "	<tr>\n";// class=\"modulo_table_title\"
		$this->salida .= "		<td class=\"label\">$label: </td><td><input type='text' name='Nuevo' value='' size=60 maxlength=255 class='input-text'></td><td>&nbsp;</td>\n";
		$this->salida .= "	</tr>\n";
		if($add === "opcion")
		{
			$this->salida .= "<tr>\n";// class=\"modulo_table_title\"
			$this->salida .= "	<td class=\"label\">COMPLEMENTO: </td><td><input type='radio' name='sw_complemento' value='0' checked>NO &nbsp;&nbsp;<input type='radio' name='sw_complemento' value='1'>SI</td><td>&nbsp;</td>\n";
			$this->salida .= "</tr>\n";
		}
		$this->salida .= "	<tr align='center'>\n";
		$this->salida .= "		<td colspan='3'><input type='submit'name='submit' value='INSERTAR' class='input-submit'></td>\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= "</table>\n";
		$this->salida .= "</form>\n";
		$href = ModuloGetURL('app','EstacionEnfermeria','admin','CallFrmMantenimientoPltSistemas',array("datos_estacion"=>$datos_estacion));
		$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver a la plantilla</a><br></div>\n";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}//FrmAddItemsPlantilla

	/*
	*
	*
	*		@Author Arley Vel&aacute;squez C.
	*		@access Private
	*		@return bool
	*/
	function FrmAdminMezclas($datos_estacion)
	{
		$departamentos=array();
		$empresa=array();
		$cont=$contador=0;
		$flag=0;
		$mezcla_medica=array();
		$mezcla_bodega=array();

		if ($_REQUEST["BorraSesion"]){
			unset($_SESSION['mtz_mezclas']);
			unset($_SESSION['mtz_mezclas_bodega']);
		}

		if (!empty($_REQUEST['Enviar'])){
			$href = ModuloGetURL('app','EstacionEnfermeria','admin','InsertarMzclaMedicamentos',array("datos_estacion"=>$datos_estacion,"NombreMezcla"=>$_REQUEST['NombreMezcla']));
			$this->salida.="<script>\n";
			$this->salida.="	location.href=\"$href\";\n";
			$this->salida.="</script>\n";
		}

		if (!empty($_REQUEST['MezclaEliminar'])){
			foreach($_SESSION['mtz_mezclas'] as $key => $value){
				if (in_array($_SESSION['mtz_mezclas'][$key]['codigo'],$_REQUEST['MezclaEliminar'])){
					unset($_SESSION['mtz_mezclas'][$key]);
					unset($_SESSION['mtz_mezclas_bodega'][$key]);
				}
			}
		}

		if (!empty($_REQUEST['FrmMezclaNombreMedicamento'])){
			$mezcla_medica['mezclas']['codigo']=$_REQUEST['FrmMezclaIdMedicamento'];
			$mezcla_medica['mezclas']['nombre']=$_REQUEST['FrmMezclaNombreMedicamento'];
			$mezcla_medica['mezclas']['cantidad']=$_REQUEST['FrmMezclaCantidadMedicamento'];
			$mezcla_medica['mezclas']['ind_suministro']=$_REQUEST['FrmMezclaObsMedicamento'];
			$mezcla_bodegas['bodegas']=$_REQUEST['FrmMezclaBodega'];
			foreach($_SESSION['mtz_mezclas'] as $key => $value){
				if ($_SESSION['mtz_mezclas'][$key]['codigo']==$_REQUEST['FrmMezclaIdMedicamento']){
					$flag=1;
				}
			}
			if (!$flag){
				$_SESSION['mtz_mezclas'][]=$mezcla_medica['mezclas'];
				$_SESSION['mtz_mezclas_bodega'][]=$mezcla_bodegas;
			}
			else{
				$this->salida.="<script>\n";
				$this->salida.="	alert('El c&oacute;digo del medicamento ya se encuentra en la mezcla');\n";
				$this->salida.="</script>\n";
			}
			unset($mezcla_medica);
			unset($mezcla_bodegas);
		}
		$query=$bodegas_estacion=$this->GetMzclaQueryBodegas($datos_estacion['empresa_id'],$datos_estacion['centro_utilidad'],$datos_estacion['estacion_id']);
		$bodegas=$this->GetMzclaBodegasEstacion($datos_estacion['empresa_id'],$datos_estacion['centro_utilidad'],$datos_estacion['estacion_id']);
		$bdgas=urlencode(serialize($bodegas));


		$this->salida .= themeAbrirTabla("CREACION DE MEZCLAS - [ ".$datos_estacion['descripcion5']." ]");
		$action=ModuloGetURL('app','EstacionEnfermeria','admin','CallFrmAdminMezclas',array("datos_estacion"=>$datos_estacion));
		$this->salida.="<form name=\"FrmMezcla\" action=\"$action\" method=\"POST\" onsubmit='return Valida(this);'>\n";
		$this->salida.="<script>\n";
		$this->salida.="function Valida(forma) {\n";
		$this->salida.="	if ((forma.FrmMezclaNombreMedicamento.value!='' && forma.FrmMezclaIdMedicamento.value=='' && forma.FrmMezclaCantidadMedicamento.value=='') || (forma.FrmMezclaNombreMedicamento.value!='' && forma.FrmMezclaIdMedicamento.value!='' && forma.FrmMezclaCantidadMedicamento.value=='')){\n";
		$this->salida.="		alert('Digite la cantidad del medicamento');\n";
		$this->salida.="		forma.FrmMezclaCantidadMedicamento.focus();\n";
		$this->salida.="		return false;\n}\n";
		$this->salida.="	if (forma.FrmMezclaNombreMedicamento.value!='' && forma.FrmMezclaIdMedicamento.value=='' && forma.FrmMezclaCantidadMedicamento.value!=''){\n";
		$this->salida.="		alert('No se puede insertar el medicamento.');\n";
		$this->salida.="		return false;\n}\n";
		$this->salida.="return true;\n }\n\n";

		$this->salida.="function buscaCampos(campo,forma) {\n";
		$this->salida.="var i=0; var j=0;\n";
		$this->salida.="while (!i) { if (window.forma.elements[j].name!=campo) j++; else return(j); } \n";
		$this->salida.="return (-1);\n }\n\n";

		$this->salida.="function Chequeo(forma,cantidad) {\n";
		$this->salida.="	var a=buscaCampos('ChkMedicamentos[]',forma);\n";
		$this->salida.="	var f=buscaCampos('FlagMedicamentos',forma);\n";
		$this->salida.="	var flag=0;\n";
		$this->salida.="	for (i=a;i<=cantidad;i++) {\n";
		$this->salida.="		if (forma.elements[i].checked && forma.elements[(i+2)].value=='') flag=1; }\n";
		$this->salida.="	if (flag) {\n";
		$this->salida.="		forma.elements[f].value=1; }\n";
		$this->salida.="}\n\n";

		$this->salida.="function abrirVentana(forma) {\n";
		$this->salida.="var nombre='';\n";
		$this->salida.="var url2='';\n";
		$this->salida.="var str='';\n";
		$this->salida.="var nombre='Buscador_General';\n";
		$this->salida.="var bTipoQuest='".$query."';\n";
		$this->salida.="var bBodegas='".$bdgas."';\n";
		$this->salida.="var bTipoQuestKey=0;\n";
		$this->salida.="var bTipoUrl=0;\n";
		$this->salida.="var Ancho=screen.width;\n";
		$this->salida.="var Alto=screen.height;\n";
		$this->salida.="var str ='Alto Ancho resizable=no status=no scrollbars=yes';\n";
		$this->salida.="var tTipoCargo='';\n  ";

		$this->salida.="bTipoQuestKey='codigo_producto,descripcion'; \n";
		$this->salida.="url2 ='classes/classbuscador/buscador.php?tipo=planT&key='+bTipoQuestKey+'&forma='+forma.name+'&sql='+bTipoQuest+'&bdgas='+bBodegas; \n";

		$this->salida.="window.open(url2, nombre, str);\n}\n\n";
		$this->salida.="</script>\n";

		$this->salida .= "				<table width='100%' border='0' class='modulo_table_list' align='center'>\n";
		$this->salida .= $this->SetStyle("MensajeError",1);
		$this->salida .= "					<tr>\n";
		$this->salida .= "						<td width='100%' class='modulo_table_list_title'>ADICIONAR MEZCLAS</td>\n";
		$this->salida .= "					</tr>\n";
		if (sizeof($_SESSION['mtz_mezclas']) > 1){
			$this->salida .= "					<tr>\n";
			$this->salida .= "						<td width='100%' class='modulo_list_claro'>\n";
			$this->salida .= "							<table width='100%' border='0' class='".$this->SetStyle($pfj."NombreMezcla",'',2)."'>\n";
			$this->salida .= "								<tr>";
			$this->salida .= "									<td width='50%' align='center'>Nombre de la mezcla</td>\n";
			$this->salida .= "									<td width='50%' align='center'><input type='text' class='input-text' name='NombreMezcla' value='".$_REQUEST['NombreMezcla']."' size='40'></td>\n";
			$this->salida .= "								</tr>";
			$this->salida .= "							</table>\n";
			$this->salida .= "						</td>";
			$this->salida .= "					</tr>\n";
		}
		$this->salida .= "					<tr>\n";
		$this->salida .= "						<td width='100%' align='center'>\n";
		$this->salida .= "							<table width='100%' border='0' class='modulo_table_list'>\n";
		$this->salida .= "								<tr class='modulo_list_claro'>\n";
		$this->salida .= "									<td width='10%' align='center'><input type='button' name='Buscar' value='Buscar' class='input-bottom' onclick='abrirVentana(this.form)'></td>\n";
		$this->salida .= "									<td width='40%' align='justify'><input type='text' class='input-text' name='FrmMezclaNombreMedicamento' value='' size='35' readonly></td>\n";
		$this->salida .= "									<td width='30%' align='justify' class='label'>Cantidad&nbsp;&nbsp;<input type='text' class='input-text' name='FrmMezclaCantidadMedicamento' value='' size='15'></td>\n";
		$this->salida .= "								</tr>";
		$this->salida .= "								<tr class='modulo_list_claro'>\n";
		$this->salida .= "									<td width='20%' align='center' class='label'>Indicaciones de Suministro :</td>";
		$this->salida .= "									<td width='80%' align='center' colspan='2'><textarea name='FrmMezclaObsMedicamento' class='textarea' rows='5' cols='60'></textarea></td>";
		$this->salida .= "								</tr>";
		$this->salida .= "								<tr class='modulo_list_claro'>\n";
		$this->salida .= "									<td width='100%' align='center' colspan='3'><input type='submit' name='Insertar' value='Insertar' class='input-bottom'></td>";
		$this->salida .= "								</tr>";
		$this->salida .= "							</table>\n";

		$mezcla_medicamentos=SessionGetVar('mtz_mezclas');
		if (!empty($mezcla_medicamentos)){
			$this->salida .= "							<table width='100%' border='0' class='modulo_table_list' align='center'>";
			$this->salida .= "								<tr class='modulo_list_claro'>\n";
			$this->salida .= "									<td width='5%' class='modulo_table_list_title'>BORRAR</td>\n";
			$this->salida .= "									<td width='15%' class='modulo_table_list_title'>CODIGO</td>\n";
			$this->salida .= "									<td width='30%' class='modulo_table_list_title'>NOMBRE</td>\n";
			$this->salida .= "									<td width='35%' class='modulo_table_list_title'>INDICACIONES SUMINISTRO</td>\n";
			$this->salida .= "									<td width='15%' class='modulo_table_list_title'>CANTIDAD</td>\n";
			$this->salida .= "								</tr>\n";
			foreach($mezcla_medicamentos as $key => $value){
				$this->salida .= "								<tr ".$this->Lista($key).">\n";
				$this->salida .= "									<td width='5%' align='center'><input type='checkbox' name='MezclaEliminar[]' value='".$value['codigo']."'></td>\n";
				$this->salida .= "									<td width='15%'>".$value['codigo']."</td>\n";
				$this->salida .= "									<td width='30%'>".$value['nombre']."</td>\n";
				$this->salida .= "									<td width='35%'>".$value['ind_suministro']."</td>\n";
				$this->salida .= "									<td width='15%'>".$value['cantidad']."</td>\n";
				$this->salida .= "								</tr>\n";
			}
			$this->salida .= "							</table>\n";
		}
		$this->salida .= "						</td>";
		$this->salida .= "					</tr>\n";
		$this->salida .= "					<tr width='100%'>\n";
		$this->salida .= "						<td align='center'>\n";
		$this->salida .= "							<table width='40%' border='0'>\n";
		$this->salida .= "								<tr>";
		$this->salida .= "									<td width='50%' align='center'><br><input type='submit' class='input-submit' name='Enviar' value='Guardar'></td>\n";
		$this->salida .= "									<td width='50%' align='center'><br><input type='submit' class='input-submit' name='Eliminar' value='Eliminar'></td>\n";
		$this->salida .= "								</tr>";
		$this->salida .= "							</table>\n";
		$this->salida .= "						</td>";
		$this->salida .= "					</tr>\n";
		$this->salida .= "				</table>\n";
		$this->salida .= "	<input type='hidden' name='FrmMezclaEsPos' value=''>\n";
		$this->salida .= "	<input type='hidden' name='FrmMezclaIdMedicamento' value=''>\n";
		$this->salida .= "	<input type='hidden' name='FrmMezclaPresentMedicamento' value=''>\n";
		$this->salida .= "	<input type='hidden' name='FrmMezclaFormFarmMedicamento' value=''>\n";
		$this->salida .= "	<input type='hidden' name='FrmMezclaConcMedicamento' value=''>\n";
		$this->salida .= "	<input type='hidden' name='FrmMezclaPrincipioActivo' value=''>\n";
		$this->salida .= "	<input type='hidden' name='FrmMezclaUnidad' value=''>\n";
		$this->salida .= "	<input type='hidden' name='FrmMezclaBodega' value=''>\n";

		$this->salida .= "<br>";
		$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
		$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br><br></div>";

		$lista_mezcla=$this->GetMzclaLista($datos_estacion['empresa_id'],$datos_estacion['centro_utilidad'],$datos_estacion['estacion_id']);
		if (!empty($lista_mezcla)){
			$this->salida .= "				<table width='100%' border='0' class='modulo_table_list' align='center' cellpadding='3'>\n";
			$this->salida .= "					<tr>\n";
			$this->salida .= "						<td width='100%' class='modulo_table_title'>LISTA DE MEZCLAS</td>\n";
			$this->salida .= "					<tr>\n";
			$this->salida .= "				</table>\n";
			$this->salida .= "				<table width='100%' border='0' class='modulo_table_list' align='center' cellpadding='3'>\n";
			$this->salida .= "					<tr>\n";
			$this->salida .= "						<td width='25%' rowspan='2' class='modulo_table_list_title'>MEZCLA</td>\n";
			$this->salida .= "						<td width='75%' colspan='4' class='modulo_table_list_title'>MEDICAMENTOS</td>\n";
			$this->salida .= "					</tr>\n";
			$this->salida .= "					<tr>\n";
			$this->salida .= "						<td width='20%' class='modulo_table_list_title'>CODIGO</td>\n";
			$this->salida .= "						<td width='30%' class='modulo_table_list_title'>NOMBRE</td>\n";
			$this->salida .= "						<td width='30%' class='modulo_table_list_title'>INDICACIONES SUMINISTRO</td>\n";
			$this->salida .= "						<td width='20%' class='modulo_table_list_title'>CANTIDAD</td>\n";
			$this->salida .= "					</tr>\n";

			foreach($lista_mezcla as $key =>$value){
				if (!$cont){
					$this->salida .= "					<tr ".$this->Lista($contador).">\n";
					$this->salida .= "						<td rowspan='".count($value)."' align='center'>".strtoupper($key)."</td>\n";
				}
				foreach($value as $k1 => $valor){
					if ($cont){
						$this->salida .= "					<tr ".$this->Lista($contador).">\n";
					}
					$this->salida .= "						<td align='justify'>".$valor['medicamento_id']."</td>\n";
					$this->salida .= "						<td align='justify'>".$valor['desc_medicamento']." ".$valor['concentracion']." ".$valor['formfarmnombre']." ".$valor['unidescripcion']."</td>\n";
					$this->salida .= "						<td width='justify'>".$valor['indicaciones_suministro']."</td>\n";
					$this->salida .= "						<td align='right'>".$valor['cantidad']."</td>\n";
					$this->salida .= "					</tr>\n";
					$cont++;
				}
				$cont=0;
				$contador++;
			}
			$this->salida .= "				</table><br><br>\n";
		}

		$this->salida .= "	</form>\n";
		$this->salida .= themeCerrarTabla();
		return true;
	}//End


		/*
		*		 Lista($numero)
		*		$numero es el numero para imprimir la clase de la lista, si el numero es par imprime la clase list_claro
		*		de lo contrario imprime list_oscuro
		*		retorna la cadena con la clase a utilizar
		*
		*		@Author Arley Vel&aacute;squez
		*		@access Private
		*		@param integer
		*/
		function Lista($numero)
		{
			if ($numero%2)
				return ("class='modulo_list_oscuro'");
			return ("class='modulo_list_claro'");
		}//End lISTA


	/*
	*
	*
	*		@Author Arley Vel&aacute;squez Castillo
	*		@access Private
	*		@return bool
	*/
	function PlantillaSuministros($datos_estacion)
	{
		//print_r($datos_estacion);
		$this->salida .= themeAbrirTabla("PLANTILLA DE INSUMOS - [ ".$datos_estacion['descripcion5']." ]");
		$action = ModuloGetURL('app','EstacionEnfermeria','admin','MantenimientoPlantilla',array("datos_estacion"=>$datos_estacion));
		$this->salida .= "	<form name=\"MantenimientoPlantilla\" method=\"POST\" action=\"$action\"><br>\n";
		$this->salida .= "			<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
		$this->salida .= $this->SetStyle("MensajeError",5);
		$this->salida .= "				<tr class=\"modulo_table_title\">\n";
		$this->salida .= "					<td colspan='5' align='center'>INSUMOS DE LA ESTACION</td>\n";
		$this->salida .= "				</tr>\n";
		$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "					<td>TIPO INSUMO</td>\n";
		$this->salida .= "					<td>CODIGO PRODUCTO</td>\n";
		$this->salida .= "					<td>NOMBRE</td>\n";
		$this->salida .= "					<td>ACCI&Oacute;N</td>\n";
		$this->salida .= "				</tr>\n";

		$cont=$contador=0;
		$bodegas=$this->Bodegas($datos_estacion['empresa_id'],$datos_estacion['centro_utilidad'],$datos_estacion['estacion_id']);

		$suministros=$this->GetSuministros($datos_estacion['empresa_id'],$datos_estacion['centro_utilidad'],$bodegas[0]['bodega'],$datos_estacion['estacion_id']);
		foreach ($suministros as $key => $value){
			if (!$cont){
				$this->salida .= "					<tr ".$this->Lista($contador).">\n";
				$this->salida .= "						<td rowspan='".sizeof($value)."' align='center'>\n";
				$this->salida .= "							<table align=\"center\" width=\"100%\" border=\"0\" class=\"normal_10\">\n";
				$this->salida .= "								<tr>\n";
				$href = ModuloGetURL('app','EstacionEnfermeria','admin','CallEditTipoSuministro',array("datos_estacion"=>$datos_estacion,"descripcion"=>$key,"insumo_id"=>$value[0]['insumo_id']));
				$this->salida .= "									<td>".strtoupper($key)."<br><a href=\"$href\">Editar</a>&nbsp;&nbsp;";
				$href = ModuloGetURL('app','EstacionEnfermeria','admin','DelTipoSuministro',array("datos_estacion"=>$datos_estacion,"insumo_id"=>$value[0]['insumo_id']));
				$this->salida .= "									/&nbsp;&nbsp;<a href=\"$href\">Eliminar</a>\n";
				$this->salida .= "									</td>\n";
				$this->salida .= "								</tr>\n";
				$this->salida .= "								<tr>\n";
				$href=ModuloGetURL('app','EstacionEnfermeria','admin','CallAddInsumoProducto',array("datos_estacion"=>$datos_estacion,"tipo_insumo"=>$value[0]['tipo_insumo'],"insumo_id"=>$value[0]['insumo_id']));
				$this->salida .= "									<td><a href=\"$href\">Add Producto</a></td>\n";
				$this->salida .= "								</tr>\n";
				$this->salida .= "								<tr>\n";
				$href=ModuloGetURL('app','EstacionEnfermeria','admin','CallAddInsumoTSuministro',array("datos_estacion"=>$datos_estacion,"tipo_insumo"=>$value[0]['tipo_insumo'],"insumo_id"=>$value[0]['insumo_id']));
				$this->salida .= "									<td><a href=\"$href\">Add Tipo Insumo</a></td>\n";
				$this->salida .= "								</tr>\n";
				$this->salida .= "							</table>\n";
				$this->salida .= "						</td>\n";
			}
			foreach ($value as $key1 => $valor){
				if ($cont){
					$this->salida .= "					<tr ".$this->Lista($contador).">\n";
				}
				if (!is_null($valor['codigo_producto'])){
					$this->salida .= "						<td align='justify'>".$valor['codigo_producto']."</td>\n";
					if ($valor['tipo_insumo']=='M'){
						$this->salida .= "						<td align='justify'>".strtoupper($this->GetNombMedicamentos($valor['codigo_producto'],$datos_estacion['empresa_id']))."</td>\n";
						$href=ModuloGetURL('app','EstacionEnfermeria','admin','EliminarInsumo',array("datos_estacion"=>$datos_estacion,"codigo_producto"=>$valor['codigo_producto'],"tipo_insumo"=>$valor['tipo_insumo'],"insumo_id"=>$valor['insumo_id']));
						$this->salida .= "						<td align='center'><a href=\"$href\">Eliminar</a></td>\n";
					}
					else{
						$this->salida .= "						<td align='justify'>".$valor['descripcion']."</td>\n";
						$href=ModuloGetURL('app','EstacionEnfermeria','admin','EliminarInsumo',array("datos_estacion"=>$datos_estacion,"codigo_producto"=>$valor['codigo_producto'],"tipo_insumo"=>$valor['tipo_insumo'],"insumo_id"=>$valor['insumo_id']));
						$this->salida .= "						<td align='center'><a href=\"$href\">Eliminar</a></td>\n";
					}
				}
				else{
					$this->salida .= "						<td align='center' colspan='4'>--</td>\n";
				}
				$this->salida .= "					</tr>\n";
				$cont++;
			}
			$cont=0;
			$contador++;
		}
		$this->salida .= "			</table>\n";

		$this->salida .= "	<br>";
		$this->salida .= "	</form>\n";
		$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
		$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br><br></div>";
		$this->salida .= themeCerrarTabla();
		return true;
	}

	/**
	*
	*
	*		@Author Arley Velasquez Castillo
	*		@access Private
	*		@param array
	*		@return string
	*/
	function AddInsumoProducto($datos_estacion,$tipo_insumo,$insumo_id)
	{
		$bodegas=$this->Bodegas($datos_estacion['empresa_id'],$datos_estacion['centro_utilidad'],$datos_estacion['estacion_id']);
		$query=$this->GetQueryBodegas($datos_estacion['empresa_id'],$datos_estacion['centro_utilidad'],true,$datos_estacion['estacion_id']);
		$bdgas=urlencode(serialize($bodegas));

		if ($tipo_insumo=='M'){
			$this->salida .= themeAbrirTabla("PLANTILLA DE INSUMOS - [ ".$datos_estacion['descripcion5']." ]");
			$action = ModuloGetURL('app','EstacionEnfermeria','admin','InsertarInsumoProducto',array("datos_estacion"=>$datos_estacion,"tipo_insumo"=>$tipo_insumo,"insumo_id"=>$insumo_id));
			$this->salida .= "	<form name=\"ManPlantilla\" method=\"POST\" action=\"$action\"><br>\n";

			$this->salida.="<script>\n";
			$this->salida.="function abrirVentana(forma) {\n";
			$this->salida.="var nombre='';\n";
			$this->salida.="var url2='';\n";
			$this->salida.="var str='';\n";
			$this->salida.="var nombre='Buscador_General';\n";
			$this->salida.="var bTipoUrl=0;\n";
			$this->salida.="var bTipoQuest='".$query."';\n";
			$this->salida.="var bBodegas='".$bdgas."';\n";
			$this->salida.="var bTipoQuestKey='';\n";
			$this->salida.="var Ancho=screen.width;\n";
			$this->salida.="var Alto=screen.height;\n";
			$this->salida.="var str ='Alto Ancho resizable=no status=no scrollbars=yes';\n";
			$this->salida.="bTipoQuestKey='codigo_producto,descripcion';\n";
			$this->salida.="url2 ='classes/classbuscador/buscador.php?tipo=planT&key='+bTipoQuestKey+'&forma='+forma+'&sql='+bTipoQuest+'&bdgas='+bBodegas; \n";
			$this->salida.="window.open(url2, nombre, str);\n";
			$this->salida.="\n}\n\n";
			$this->salida.="</script>\n";

			$this->salida .= "			<table align=\"center\" width=\"100%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= $this->SetStyle("MensajeError",2);
			$this->salida .= "				<tr class=\"modulo_table_title\">\n";
			$this->salida .= "					<td align='center'>ADICIONAR PRODUCTO</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align='center'>\n";

			$this->salida .= "						<table width='100%' border='0'>\n";
			$this->salida .= "							<tr>\n";
			$this->salida .= "								<td class='".$this->SetStyle("MedicamentoID",2)."'>Codigo: </td>\n";
			$this->salida .= "								<td>\n";
			$this->salida .= "									<table border='0' width='100%'>\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "											<td align='center'><input type='button' name='Buscar' class='input-bottom' value='Buscar' onClick='abrirVentana(this.form.name);'></td>\n";
			$this->salida .= "											<td align='left'><input type='text' name='ManPlantillaIdMedicamento' size='30' class='input-text' readonly='true' value='".$_REQUEST["ManPlantillaIdMedicamento"]."'></td>\n";
			$this->salida .= "										</tr>\n";
			$this->salida .= "									</table>\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "							</tr>\n";
			$this->salida .= "							<tr>\n";
			$this->salida .= "								<td class='".$this->SetStyle("nombreMedicamento",2)."'>Nombre: </td>\n";
			$this->salida .= "								<td><input type='text' name='ManPlantillaNombreMedicamento' size=30 class='input-text' readonly='true' value='".$_REQUEST["ManPlantillaNombreMedicamento"]."'></td>\n";
			$this->salida .= "							</tr>\n";
			$this->salida .= "						</table>\n";

			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";

			$this->salida .= "<input type='hidden' name='ManPlantillaUnidad' value='".$_REQUEST["ManPlantillaUnidad"]."'>\n";
			$this->salida .= "<input type='hidden' name='ManPlantillaPresentMedicamento' value='".$_REQUEST["ManPlantillaPresentMedicamento"]."'>\n";
			$this->salida .= "<input type='hidden' name='ManPlantillaFormFarmMedicamento' value='".$_REQUEST["ManPlantillaFormFarmMedicamento"]."'>\n";
			$this->salida .= "<input type='hidden' name='ManPlantillaConcMedicamento' value='".$_REQUEST["ManPlantillaConcMedicamento"]."'>\n";
			$this->salida .= "<input type='hidden' name='ManPlantillaPrincipioActivo' value='".$_REQUEST["ManPlantillaPrincipioActivo"]."'>\n";
			$this->salida .= "<input type='hidden' name='ManPlantillaBodega' value='".$_REQUEST["ManPlantillaBodega"]."'>\n";
			$this->salida .= "<input type='hidden' name='ManPlantillaFecha' value='".$fecha."'>\n";
			$this->salida .= "<input type='hidden' name='ManPlantillaEsPos' value='".$_REQUEST["ManPlantillaEsPos"]."'>\n";

			$this->salida .= "	<br>";
			$this->salida .= "	<div class='normal_10' align='center'><br><input type='submit' name='ActualizaPlantilla' value='Adicionar Insumo' class='input-submit'><br></div>\n";
			$this->salida .= "	</form>\n";
			$href = ModuloGetURL('app','EstacionEnfermeria','admin','CallPlantillaSuministros',array("datos_estacion"=>$datos_estacion));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver a la Plantilla de Insumos</a><br><br></div>";
			$this->salida .= themeCerrarTabla();
		}
		else{

			$bdgas=$bodegas[0]['bodega'];

			$this->salida .= themeAbrirTabla("PLANTILLA DE INSUMOS - [ ".$datos_estacion['descripcion5']." ]");
			$action = ModuloGetURL('app','EstacionEnfermeria','admin','InsertarInsumoProducto',array("datos_estacion"=>$datos_estacion,"tipo_insumo"=>$tipo_insumo,"insumo_id"=>$insumo_id));
			$this->salida .= "	<form name=\"ManPlantilla\" method=\"POST\" action=\"$action\"><br>\n";
			$this->salida.="<script>\n";
			$this->salida.="function abrirVentana(forma) {\n";
			$this->salida.="var nombre='';\n";
			$this->salida.="var url2='';\n";
			$this->salida.="var str='';\n";
			$this->salida.="var nombre='Buscador_General';\n";
			$this->salida.="var bTipoUrl=0;\n";
			$this->salida.="var bTipoQuest='".$datos_estacion['empresa_id']."';\n";
			$this->salida.="var bTipoQuesta='".$datos_estacion['centro_utilidad']."';\n";
			$this->salida.="var bBodegas='".$bdgas."';\n";
			$this->salida.="var bTipoQuestKey='';\n";
			$this->salida.="var Ancho=screen.width;\n";
			$this->salida.="var Alto=screen.height;\n";
			$this->salida.="var str ='Alto Ancho resizable=no status=no scrollbars=yes';\n";
			$this->salida.="bTipoQuestKey='codigo_producto,descripcion';\n";
			$this->salida.="url2 ='classes/classbuscador/buscador.php?tipo=inventarios&key='+bTipoQuestKey+'&forma='+forma+'&sql='+bTipoQuest+'&sqla='+bTipoQuesta+'&sqlb='+bBodegas; \n";
			$this->salida.="window.open(url2, nombre, str);\n";
			$this->salida.="\n}\n\n";
			$this->salida.="</script>\n";

			$this->salida .= "			<table align=\"center\" width=\"100%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= $this->SetStyle("MensajeError",2);
			$this->salida .= "				<tr class=\"modulo_table_title\">\n";
			$this->salida .= "					<td align='center'>ADICIONAR PRODUCTO</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align='center'>\n";

			$this->salida .= "						<table width='100%' border='0' class='module_table_list'>\n";
			$this->salida .= "							<tr>\n";
			$this->salida .= "								<td class='".$this->SetStyle("codigo",2)."'>Codigo: </td>\n";
			$this->salida .= "								<td>\n";
			$this->salida .= "									<table border='0' width='100%'>\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "											<td align='center'><input type='button' name='Buscar' class='input-bottom' value='Buscar' onClick='abrirVentana(this.form.name);'></td>\n";
			$this->salida .= "											<td align='left'><input type='text' name='codigo' size='30' class='input-text' readonly='true' value='".$_REQUEST["codigo"]."'></td>\n";
			$this->salida .= "										</tr>\n";
			$this->salida .= "									</table>\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "							</tr>\n";
			$this->salida .= "							<tr>\n";
			$this->salida .= "								<td class='".$this->SetStyle("nombreMedicamento",2)."'>Nombre: </td>\n";
			$this->salida .= "								<td><input type='text' name='nombreProducto' size=30 class='input-text' readonly='true' value='".$_REQUEST["nombreProducto"]."'></td>\n";
			$this->salida .= "							</tr>\n";
			$this->salida .= "						</table>\n";

			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";

			$this->salida .= "	<br>";
			$this->salida .= "	<br>";
			$this->salida .= "	<div class='normal_10' align='center'><br><input type='submit' name='ActualizaPlantilla' value='Adicionar Insumo' class='input-submit'><br></div>\n";

			$this->salida .= "	<input type='hidden' name='unidadProducto' value='".$_REQUEST["unidadProducto"]."'>";
			$this->salida .= "	<input type='hidden' name='precioProducto' value='".$_REQUEST["precioProducto"]."'>\n";
			$this->salida .= "	<input type='hidden' name='ExisProducto' value='".$_REQUEST["ExisProducto"]."'>\n";
			$this->salida .= "	<input type='hidden' name='costoProducto' value='".$_REQUEST["costoProducto"]."'>\n";
			$this->salida .= "	</form>\n";
			$href = ModuloGetURL('app','EstacionEnfermeria','admin','CallPlantillaSuministros',array("datos_estacion"=>$datos_estacion));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver a la Plantilla de Insumos</a><br><br></div>";
			$this->salida .= themeCerrarTabla();
		}
		return true;
	}

	/**
	*
	*
	*		@Author Arley Velasquez Castillo
	*		@access Private
	*		@param array
	*		@return string
	*/
	function AddInsumoTSuministro($datos_estacion,$tipo_insumo)
	{
		$this->salida .= themeAbrirTabla("PLANTILLA DE INSUMOS - [ ".$datos_estacion['descripcion5']." ]");
		$action = ModuloGetURL('app','EstacionEnfermeria','admin','InsertarTipoSuministro',array("datos_estacion"=>$datos_estacion,"tipo_insumo"=>$tipo_insumo));
		$this->salida .= "	<form name=\"ManPlantilla\" method=\"POST\" action=\"$action\">\n";

		$this->salida .= "			<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
		$this->salida .= $this->SetStyle("MensajeError",2);
		$this->salida .= "				<tr>\n";
		$this->salida .= "					<td align='center' class='".$this->SetStyle("tipo_suministro",2)."'><br><br>TIPO DE INSUMO</td>\n";
		$this->salida .= "				</tr>\n";
		$this->salida .= "				<tr>\n";
		$this->salida .= "					<td align='center'><input type='text' class='input-text' name='tipo_suministro' value='' size='50' maxlength='80'></td>\n";
		$this->salida .= "				</tr>\n";
		$this->salida .= "			</table>\n";

		$this->salida .= "	<br>";
		$this->salida .= "	<div class='normal_10' align='center'><br><input type='submit' name='ActualizaPlantilla' value='Adicionar Tipo Insumo' class='input-submit'><br></div>\n";
		$this->salida .= "	</form>\n";
		$href = ModuloGetURL('app','EstacionEnfermeria','admin','CallPlantillaSuministros',array("datos_estacion"=>$datos_estacion));
		$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver a la Plantilla de Insumos</a><br><br></div>";
		$this->salida .= themeCerrarTabla();
		return true;
	}

	/**
	*
	*
	*		@Author Arley Velasquez Castillo
	*		@access Private
	*		@param array
	*		@return string
	*/
	function EditTipoSuministro($datos_estacion,$descripcion,$insumo_id)
	{
		$this->salida .= themeAbrirTabla("PLANTILLA DE INSUMOS - [ ".$datos_estacion['descripcion5']." ]");
		$action = ModuloGetURL('app','EstacionEnfermeria','admin','EditarTipoSuministro',array("datos_estacion"=>$datos_estacion,"descripcion"=>$descripcion,"insumo_id"=>$insumo_id));
		$this->salida .= "	<form name=\"ManPlantilla\" method=\"POST\" action=\"$action\">\n";

		$this->salida .= "			<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
		$this->salida .= $this->SetStyle("MensajeError",2);
		$this->salida .= "				<tr>\n";
		$this->salida .= "					<td align='center' class='".$this->SetStyle("descripcion",2)."'><br><br>EDITAR TIPO DE INSUMO</td>\n";
		$this->salida .= "				</tr>\n";
		$this->salida .= "				<tr>\n";
		$this->salida .= "					<td align='center'><input type='text' class='input-text' name='descripcion' value='$descripcion' size='50' maxlength='80'></td>\n";
		$this->salida .= "				</tr>\n";
		$this->salida .= "			</table>\n";

		$this->salida .= "	<br>";
		$this->salida .= "	<div class='normal_10' align='center'><br><input type='submit' name='ActualizaPlantilla' value='Editar Tipo Insumo' class='input-submit'><br></div>\n";
		$this->salida .= "	</form>\n";
		$href = ModuloGetURL('app','EstacionEnfermeria','admin','CallPlantillaSuministros',array("datos_estacion"=>$datos_estacion));
		$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver a la Plantilla de Insumos</a><br><br></div>";
		$this->salida .= themeCerrarTabla();
		return true;
	}

	
	/*
	*
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@return bool
	*/


}//CLASS
?>

