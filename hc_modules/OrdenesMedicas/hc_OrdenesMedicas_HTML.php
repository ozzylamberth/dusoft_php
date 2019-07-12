<?
/* hc_modules/HTML/OrdenesMedicas_HTML.php
* ----------------------------------------------------------------------
* Autor: ARLEY VELÁSQUEZ C.
* Proposito: Manejador de los controles para los pacientes de
* hospitalización.
* Editado Por: Tizziano Perea Ocoro
* ----------------------------------------------------------------------
* $Id: hc_OrdenesMedicas_HTML.php,v 1.12 2006/12/19 21:00:14 jgomez Exp $
*/

class OrdenesMedicas_HTML extends OrdenesMedicas
{
	/**
	*		function OrdenesMedicas_HTML => Constructor de la clase
	*
	*		@Author Arley Velásquez C.
	*		@access Private
	*		@return boolean
	*/
		function OrdenesMedicas_HTML()
		{
          	$this->OrdenesMedicas();//constructor del padre
			return true;
		}//End function


    /**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

    function GetVersion()
    {
      $informacion=array(
      'version'=>'1',
      'subversion'=>'0',
      'revision'=>'0',
      'fecha'=>'01/27/2005',
      'autor'=>'TIZZIANO PEREA OCORO',
      'descripcion_cambio' => '',
      'requiere_sql' => false,
      'requerimientos_adicionales' => '',
      'version_kernel' => '1.0'
      );
      return $informacion;
    }

//////////////////
    
          /**
          *		function SetStyle => Muestra mensajes
          *
          *		crea una fila para poner el mensaje de "Faltan campos por llenar" cambiando a color rojo
          *		el label del campo "obligatorio" sin llenar
          *
          *		@Author Alexander Giraldo
          *		@access Private
          *		@return string
          *		@param string => nombre del input y estilo que qued&oacute; vacio
          */
          function SetStyle($campo,$campo2,$colum)
          {
               if ($this->frmError[$campo] || $this->frmError[$campo2] || $campo=="MensajeError")
               {
                    if ($campo=="MensajeError")   return ("<tr><td colspan='".$colum."' class='label_error'>".$this->frmError["MensajeError"]."</td></tr>");
                    return ("label_error");
               }
               return ("label");
          }//End function


          /**
          *		function FrmForma => Se encarga de armar la vista (HTML) de los controles del paciente
          *
          *		Cuenta con los casos AddCtrlPosicion, InsertCtrlPosicion, EditCtrlPosicion, DelCtrlPosicion
          *		para cada uno de los controles del paciente Ej el Control de Transfusiones seria:
          *		AddCtrlTransfusiones, InsertCtrlTransfusiones, EditCtrlTransfusiones, DelCtrlTransfusiones
          *		Para los controles que cuentan con frecuencia (Terapia Respiratoria,Curva Termica,Tension Arterial,Glucometria,Curaciones,Neurologico)
          *		se crearon los casos :
          *		AddCtrlGral, InsertCtrlGral, EditCtrlGral, DelCtrlGral
          *		Los casos AddCtrlNombreControl muestran la vista(HTML) para insertar cada uno de los controles
          *		Los casos InsertNombreControl insertan el control en su respectiva tabla
          *		Los casos EditCtrlNombreControl permiten editar el control
          *		Los casos DelCtrlNombreControl permiten eliminar el control
          *		El caso GraphCtrlGral grafica algunos controles (CurvaTermica,Glicemia,PresionVenosaCentral,FrecuenciaCardiaca,FrecuenciaRespiratoria)
          *		Default -> Por defecto la funcion FrmForma() que muestra los controles asignados a cada paciente
          *
          *		@Author Arley Velásquez C.
          *		@access Private
          *		@return string
          */
		function FrmForma($action)
		{
			switch ($action)
			{
				case $this->frmPrefijo."AddCtrlPosicion":
							list($dbconn) = GetDBconn();
							if (empty($_REQUEST[$this->frmPrefijo."Posicion"])) {
								$observacion=$_REQUEST[$this->frmPrefijo."CtrlPosicionObs"];
								$control=$this->GetControlPosicion($_REQUEST[$this->frmPrefijo."CtrlPosicion"],1);
							}
							else {
								$datos=$_REQUEST[$this->frmPrefijo."Posicion"];
								$observacion=$datos["observacion"];
								$control=$this->GetControlPosicion($datos["posicion_id"],1);
							}
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."InsertCtrlPosicion"));
							$this->salida .="<form name='".$this->frmPrefijo."posicion' action=\"".$href."\" method='POST'>";
							$this->salida .= "	<table width='100%' align='justify' border='1' class='hc_table_submodulo_list'>";
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td width='80%' class='hc_table_submodulo_list_title'>POSICION DEL PACIENTE</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= $this->SetStyle("MensajeError",'',1);
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td align='center'>\n";
							$this->salida .= "					<table width='99%' border='1' class='hc_table_submodulo_list'>\n";
							$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
							$this->salida .= "							<td width='50%' class='".$this->SetStyle($this->frmPrefijo."Posicion",'',2)."'>Posición</td>\n";
							$this->salida .= "							<td width='50%' align='center'>Observación</td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
							$this->salida .= "							<td width='50%' valign='top'><select class='select' name='".$this->frmPrefijo."CtrlPosicion'><option value='-1'>--</option>\n$control</select></td>\n";
							$this->salida .= "							<td width='50%' align='center'><textarea class='textarea' name='".$this->frmPrefijo."CtrlPosicionObs' cols='45' rows='5'>".$observacion."</textarea></td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "					</table>\n";
							$this->salida .= "			<br><br><input class='input-submit' type='submit' name='".$pj."Save' value='GUARDAR'><br><br>\n";
							$this->salida .= "			</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= "	</table>\n";
							$this->salida .= "	</form>\n";

							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
							$this->salida .= "<form action=\"$href\" name=\"Volver\" method=\"post\">\n";
							$this->salida .= "<br><br>\n";
							$this->salida .= "<div align='center' class='normal_10'><input class='input-submit' type='submit' name='".$this->frmPrefijo."Volver' value='Volver'></div>\n";
							$this->salida .= "</form><br>\n";
							return true;
				break;
				case $this->frmPrefijo."InsertCtrlPosicion":
							$observaciones=$_REQUEST[$this->frmPrefijo."CtrlPosicionObs"];
							$posicion=$_REQUEST[$this->frmPrefijo."CtrlPosicion"];
							$ctrlPosicion=$controles=array();

							$controles=$this->GetControles();
							$ctrlPosicion=$this->FindControles($controles,1,$this->ingreso);
							if ($ctrlPosicion===false){
								return false;
							}

							if ($posicion=='-1')
							{
								$this->frmError[$this->frmPrefijo."Posicion"]=1;
								$this->error=1;
							}
							if (!empty($this->error))
							{
								$this->frmError["MensajeError"]="Verfique los campos en rojo";
								$this->FrmForma($this->frmPrefijo."AddCtrlPosicion");
								return true;
							}

							if (!$this->InsertCtrlPosicion($posicion,$observaciones,$ctrlPosicion,$controles))
								return false;
							return true;
				break;
				case $this->frmPrefijo."EditCtrlPosicion":
							if (!$this->EditCtrlPosicion())
								return false;
							return true;
				break;
				case $this->frmPrefijo."DelCtrlPosicion":
							if (!$this->DelCtrlPosicion())
								return false;
							return true;
				break;
				case $this->frmPrefijo."AddCtrlOxig":
							list($dbconn) = GetDBconn();

							if (empty($_REQUEST[$this->frmPrefijo."Oxig"])) {
								$observacion=$_REQUEST[$this->frmPrefijo."CtlOxigObs"];
								$metodo=$this->GetControlOxiMetodo($_REQUEST[$this->frmPrefijo."CtlOxigMetodo"],1);
								$concentraciones=$this->GetControlOxiConcentraciones($_REQUEST[$this->frmPrefijo."CtlOxigConc"],1);
								$flujo=$this->GetControlOxiFlujo($_REQUEST[$this->frmPrefijo."CtlOxigFlujo"],1);
							}
							else {
								$datos=$_REQUEST[$this->frmPrefijo."Oxig"];
								$observacion=$datos["observacion"];
								$metodo=$this->GetControlOxiMetodo($datos["metodo_id"],1);
								$concentraciones=$this->GetControlOxiConcentraciones($datos["concentracion_id"],1);
								$flujo=$this->GetControlOxiFlujo($datos["flujo_id"],1);
							}
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."InsertCtrlOxig"));
							$this->salida .="<form name='".$this->frmPrefijo."posicion' action=\"".$href."\" method='POST'>";
							$this->salida .= "	<table width='100%' align='justify' border='1' class='hc_table_submodulo_list'>";
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td width='80%' class='hc_table_submodulo_list_title'>OXIGENOTERAPIA</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= $this->SetStyle("MensajeError",'',1);
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td align='center'>\n";
							$this->salida .= "					<table width='99%' border='1' class='hc_table_submodulo_list'>\n";
							$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
							$this->salida .= "							<td width='50%' class='".$this->SetStyle($this->frmPrefijo."Metodo",'',2)."'>Metodo</td>\n";
							$this->salida .= "							<td width='50%' align='center' rowspan='6'>Observación<br><br><textarea class='textarea' name='".$this->frmPrefijo."CtlOxigObs' cols='55' rows='6'>".$observacion."</textarea><br><br></td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
							$this->salida .= "							<td width='50%' valign='top'><select class='select' name='".$this->frmPrefijo."CtlOxigMetodo'><option value='-1'>--</option>\n$metodo</select></td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
							$this->salida .= "							<td width='50%' class='".$this->SetStyle($this->frmPrefijo."Concentraciones",'',2)."'>Concentraciones</td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
							$this->salida .= "							<td width='50%' valign='top'><select class='select' name='".$this->frmPrefijo."CtlOxigConc'>\n$concentraciones</select></td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
							$this->salida .= "							<td width='50%' class='".$this->SetStyle($this->frmPrefijo."Flujo",'',2)."'>Flujo de Oxigeno</td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
							$this->salida .= "							<td width='50%' valign='top'><select class='select' name='".$this->frmPrefijo."CtlOxigFlujo'>\n$flujo</select></td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "					</table>\n";
							$this->salida .= "			<br><br><input class='input-submit' type='submit' name='".$pj."Save' value='GUARDAR'><br><br>\n";
							$this->salida .= "			</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= "	</table>\n";
							$this->salida .= "	</form>\n";

							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
							$this->salida .= "<form action=\"$href\" name=\"Volver\" method=\"post\">\n";
							$this->salida .= "<br><br>\n";
							$this->salida .= "<div align='center' class='normal_10'><input class='input-submit' type='submit' name='".$this->frmPrefijo."Volver' value='Volver'></div>\n";
							$this->salida .= "</form><br>\n";
							return true;
				break;
				case $this->frmPrefijo."InsertCtrlOxig":
							$observaciones=$_REQUEST[$this->frmPrefijo."CtlOxigObs"];
							$metodo=$_REQUEST[$this->frmPrefijo."CtlOxigMetodo"];
							$concentracion=$_REQUEST[$this->frmPrefijo."CtlOxigConc"];
							$flujo=$_REQUEST[$this->frmPrefijo."CtlOxigFlujo"];
							$ctrlOxig=$controles=array();

							if ($metodo=='-1')
							{
								$this->frmError[$this->frmPrefijo."Metodo"]=1;
								$this->error=1;
							}
							if (!empty($this->error))
							{
								$this->frmError["MensajeError"]="Verfique los campos en rojo";
								$this->FrmForma($this->frmPrefijo."AddCtrlOxig");
								return true;
							}

							if (!$this->InsertCtrlOxig($concentracion,$metodo,$flujo,$ctrlOxig,$observaciones))
								return false;
							return true;
				break;
				case $this->frmPrefijo."EditCtrlOxig":
							if (!$this->EditCtrlOxig())
								return false;
							return true;
				break;
				case $this->frmPrefijo."DelCtrlOxig":
							if (!$this->DelCtrlOxig())
								return false;
							return true;
				break;
				case $this->frmPrefijo."AddCtrlReposo":
							list($dbconn) = GetDBconn();

							if (empty($_REQUEST[$this->frmPrefijo."Reposo"])) {
								$observacion=$_REQUEST[$this->frmPrefijo."CtlReposoObs"];
								$ctrlReposo=$_REQUEST[$this->frmPrefijo."CtlReposo"];
								$reposo=$this->GetControlReposo("",2);
							}
							else {
								$datos=$_REQUEST[$this->frmPrefijo."Reposo"];
								$observacion=$datos["observacion"];
								$ctrlReposo=$datos["CtlReposo"];
								$reposo=$this->GetControlReposo("",2);
							}
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."InsertCtrlReposo"));
							$this->salida .="<form name='".$this->frmPrefijo."Reposo' action=\"".$href."\" method='POST'>";
							$this->salida .= "	<table width='100%' align='justify' border='1' class='hc_table_submodulo_list'>";
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td width='80%' class='hc_table_submodulo_list_title'>REPOSO DEL PACIENTE</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= $this->SetStyle("MensajeError",'',1);
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td align='center'>\n";
							$this->salida .= "					<table width='99%' border='1' class='hc_table_submodulo_list'>\n";
							$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
							$this->salida .= "							<td width='50%' class='".$this->SetStyle($this->frmPrefijo."Reposo",'',2)."'>Tipo Reposo</td>\n";
							$this->salida .= "							<td width='50%' align='center'>Observación</td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
							$this->salida .= "							<td width='50%'>\n";
							$this->salida .= "									<table width='100%' border='1' class='hc_table_submodulo_list'>\n";
							$cont=0;
							foreach ($reposo as $key => $value)
							{
								$this->salida .= "									<tr ".$this->Lista($key)."'>\n";
								if (in_array($value['tipo_reposo_id'],$ctrlReposo)) {
									$this->salida .= "										<td width='50%' valign='middle'><input type='checkbox' name='".$this->frmPrefijo."CtlReposo[]' value='".$value['tipo_reposo_id']."' checked>&nbsp;&nbsp;".$value['descripcion']."</td>\n";
								}
								else {
									$this->salida .= "										<td width='50%' valign='middle'><input type='checkbox' name='".$this->frmPrefijo."CtlReposo[]' value='".$value['tipo_reposo_id']."'>&nbsp;&nbsp;".$value['descripcion']."</td>\n";
								}
								$this->salida .= "									</tr>\n";
								$cont++;
							}
							$this->salida .= "									</table>\n</td>\n";
							$this->salida .= "							<td width='50%' align='center'><textarea class='textarea' name='".$this->frmPrefijo."CtlReposoObs' cols='55' rows='6'>".$observacion."</textarea></td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "					</table>\n";
							$this->salida .= "			<br><br><input class='input-submit' type='submit' name='".$pj."Save' value='GUARDAR'><br><br>\n";
							$this->salida .= "			</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= "	</table>\n";
							$this->salida .= "	</form>\n";

							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
							$this->salida .= "<form action=\"$href\" name=\"Volver\" method=\"post\">\n";
							$this->salida .= "<br><br>\n";
							$this->salida .= "<div align='center' class='normal_10'><input class='input-submit' type='submit' name='".$this->frmPrefijo."Volver' value='Volver'></div>\n";
							$this->salida .= "</form><br>\n";
							return true;
				break;
				case $this->frmPrefijo."InsertCtrlReposo":
							$observaciones=$_REQUEST[$this->frmPrefijo."CtlReposoObs"];
							$reposo=$_REQUEST[$this->frmPrefijo."CtlReposo"];

							if (empty($reposo))
							{
								$this->frmError[$this->frmPrefijo."Reposo"]=1;
								$this->error=1;
							}
							if (!empty($this->error))
							{
								$this->frmError["MensajeError"]="Verfique los campos en rojo";
								$this->FrmForma($this->frmPrefijo."AddCtrlReposo");
								return true;
							}

							if (!$this->InsertCtrlReposo($reposo,$observaciones))
								return false;
							return true;
				break;
				case $this->frmPrefijo."EditCtrlReposo":
							if (!$this->EditCtrlReposo())
								return false;
							return true;
				break;
				case $this->frmPrefijo."DelCtrlReposo":
							if (!$this->DelCtrlReposo())
								return false;
							return true;
				break;
				case $this->frmPrefijo."AddCtrlLiquidos":
							list($dbconn) = GetDBconn();

							if (empty($_REQUEST[$this->frmPrefijo."Liquidos"])) {
								$observacion=$_REQUEST[$this->frmPrefijo."CtlLiquidosObs"];
							}
							else {
								$datos=$_REQUEST[$this->frmPrefijo."Liquidos"];
								$observacion=$datos["observacion"];
							}
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."InsertCtrlLiquidos"));
							$this->salida .="<form name='".$this->frmPrefijo."Liquidos' action=\"".$href."\" method='POST'>";
							$this->salida .= "	<table width='100%' align='justify' border='1' class='hc_table_submodulo_list'>";
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td width='80%' class='hc_table_submodulo_list_title'>CONTROL DE LIQUIDOS INGERIDOS Y ELIMINADOS</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= $this->SetStyle("MensajeError",'',1);
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td align='center'>\n";
							$this->salida .= "					<table width='99%' border='1' class='hc_table_submodulo_list'>\n";
							$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
							$this->salida .= "							<td width='100%' class='".$this->SetStyle($this->frmPrefijo."observaciones",'',2)."' align='center'>Observación</td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
							$this->salida .= "							<td width='50%' align='center'><textarea class='textarea' name='".$this->frmPrefijo."CtlLiquidosObs' cols='85' rows='6'>".$observacion."</textarea><br><br></td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "					</table>\n";
							$this->salida .= "			<br><br><input class='input-submit' type='submit' name='".$pj."Save' value='GUARDAR'><br><br>\n";
							$this->salida .= "			</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= "	</table>\n";
							$this->salida .= "	</form>\n";

							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
							$this->salida .= "<form action=\"$href\" name=\"Volver\" method=\"post\">\n";
							$this->salida .= "<br><br>\n";
							$this->salida .= "<div align='center' class='normal_10'><input class='input-submit' type='submit' name='".$this->frmPrefijo."Volver' value='Volver'></div>\n";
							$this->salida .= "</form><br>\n";
							return true;
				break;
				case $this->frmPrefijo."InsertCtrlLiquidos":
							$observaciones=$_REQUEST[$this->frmPrefijo."CtlLiquidosObs"];

							if (empty($observaciones)){
								$observaciones="Control Permanente";
							}

							if (!$this->InsertCtrlLiquidos($observaciones))
								return false;
							return true;
				break;
				case $this->frmPrefijo."EditCtrlLiquidos":
							if (!$this->EditCtrlLiquidos())
								return false;
							return true;
				break;
				case $this->frmPrefijo."DelCtrlLiquidos":
							if (!$this->DelCtrlLiquidos())
								return false;
							return true;
				break;
				case $this->frmPrefijo."AddCtrlPerAbdominal":
							list($dbconn) = GetDBconn();

							if (empty($_REQUEST[$this->frmPrefijo."PerAbdominal"])) {
								$observacion=$_REQUEST[$this->frmPrefijo."CtlPerAbdominalObs"];
							}
							else {
								$datos=$_REQUEST[$this->frmPrefijo."PerAbdominal"];
								$observacion=$datos["observacion"];
							}
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."InsertCtrlPerAbdominal"));
							$this->salida .="<form name='".$this->frmPrefijo."PerAbdominal' action=\"".$href."\" method='POST'>";
							$this->salida .= "	<table width='100%' align='justify' border='1' class='hc_table_submodulo_list'>";
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td width='80%' class='hc_table_submodulo_list_title'>PERIMETRO ABDOMINAL</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= $this->SetStyle("MensajeError",'',1);
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td align='center'>\n";
							$this->salida .= "					<table width='99%' border='1' class='hc_table_submodulo_list'>\n";
							$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
							$this->salida .= "							<td width='100%' class='".$this->SetStyle($this->frmPrefijo."observaciones",'',2)."' align='center'>Observación</td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
							$this->salida .= "							<td width='50%' align='center'><textarea class='textarea' name='".$this->frmPrefijo."CtlPerAbdominalObs' cols='85' rows='6'>".$observacion."</textarea><br><br></td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "					</table>\n";
							$this->salida .= "			<br><br><input class='input-submit' type='submit' name='".$pj."Save' value='GUARDAR'><br><br>\n";
							$this->salida .= "			</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= "	</table>\n";
							$this->salida .= "	</form>\n";

							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
							$this->salida .= "<form action=\"$href\" name=\"Volver\" method=\"post\">\n";
							$this->salida .= "<br><br>\n";
							$this->salida .= "<div align='center' class='normal_10'><input class='input-submit' type='submit' name='".$this->frmPrefijo."Volver' value='Volver'></div>\n";
							$this->salida .= "</form><br>\n";
							return true;
				break;
				case $this->frmPrefijo."InsertCtrlPerAbdominal":
							$observaciones=$_REQUEST[$this->frmPrefijo."CtlPerAbdominalObs"];

							if (empty($observaciones)){
								$observaciones="Perimetro Abdominal";
							}

							if (!$this->InsertCtrlPerAbdominal($observaciones))
								return false;
							return true;
				break;
				case $this->frmPrefijo."EditCtrlPerAbdominal":
							if (!$this->EditCtrlPerAbdominal())
								return false;
							return true;
				break;
				case $this->frmPrefijo."DelCtrlPerAbdominal":
							if (!$this->DelCtrlPerAbdominal())
								return false;
							return true;
				break;
				case $this->frmPrefijo."AddCtrlPerCefalico":
							list($dbconn) = GetDBconn();

							if (empty($_REQUEST[$this->frmPrefijo."PerCefalico"])) {
								$observacion=$_REQUEST[$this->frmPrefijo."CtlPerCefalicoObs"];
							}
							else {
								$datos=$_REQUEST[$this->frmPrefijo."PerCefalico"];
								$observacion=$datos["observacion"];
							}
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."InsertCtrlPerCefalico"));
							$this->salida .="<form name='".$this->frmPrefijo."PerCefalico' action=\"".$href."\" method='POST'>";
							$this->salida .= "	<table width='100%' align='justify' border='1' class='hc_table_submodulo_list'>";
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td width='80%' class='hc_table_submodulo_list_title'>PERIMETRO CEFALICO</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= $this->SetStyle("MensajeError",'',1);
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td align='center'>\n";
							$this->salida .= "					<table width='99%' border='1' class='hc_table_submodulo_list'>\n";
							$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
							$this->salida .= "							<td width='100%' class='".$this->SetStyle($this->frmPrefijo."observaciones",'',2)."' align='center'>Observación</td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
							$this->salida .= "							<td width='50%' align='center'><textarea class='textarea' name='".$this->frmPrefijo."CtlPerCefalicoObs' cols='85' rows='6'>".$observacion."</textarea><br><br></td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "					</table>\n";
							$this->salida .= "			<br><br><input class='input-submit' type='submit' name='".$pj."Save' value='GUARDAR'><br><br>\n";
							$this->salida .= "			</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= "	</table>\n";
							$this->salida .= "	</form>\n";

							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
							$this->salida .= "<form action=\"$href\" name=\"Volver\" method=\"post\">\n";
							$this->salida .= "<br><br>\n";
							$this->salida .= "<div align='center' class='normal_10'><input class='input-submit' type='submit' name='".$this->frmPrefijo."Volver' value='Volver'></div>\n";
							$this->salida .= "</form><br>\n";
							return true;
				break;
				case $this->frmPrefijo."InsertCtrlPerCefalico":
							$observaciones=$_REQUEST[$this->frmPrefijo."CtlPerCefalicoObs"];

							if (empty($observaciones)){
								$observaciones="Perimetro Cefálico";
							}

							if (!$this->InsertCtrlPerCefalico($observaciones))
								return false;
							return true;
				break;
				case $this->frmPrefijo."EditCtrlPerCefalico":
							if (!$this->EditCtrlPerCefalico())
								return false;
							return true;
				break;
				case $this->frmPrefijo."DelCtrlPerCefalico":
							if (!$this->DelCtrlPerCefalico())
								return false;
							return true;
				break;
				case $this->frmPrefijo."AddCtrlPerExtremidades":
							list($dbconn) = GetDBconn();

							if (empty($_REQUEST[$this->frmPrefijo."PerExtremidades"])) {
								$observacion=$_REQUEST[$this->frmPrefijo."CtlPerExtremidadesObs"];
								$ctrlPerExtremidades=$_REQUEST[$this->frmPrefijo."CtlPerExtremidades"];
								$extremidad=$this->GetControlPerExtremidades("",2);
							}
							else {
								$datos=$_REQUEST[$this->frmPrefijo."PerExtremidades"];
								$observacion=$datos["observacion"];
								$ctrlPerExtremidades=$datos["CtlPerExtremidades"];
								$extremidad=$this->GetControlPerExtremidades("",2);
							}
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."InsertCtrlPerExtremidades"));
							$this->salida .="<form name='".$this->frmPrefijo."PerExtremidades' action=\"".$href."\" method='POST'>";
							$this->salida .= "	<table width='100%' align='justify' border='1' class='hc_table_submodulo_list'>";
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td width='80%' class='hc_table_submodulo_list_title'>PERIMETRO DE EXTREMIDADES</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= $this->SetStyle("MensajeError",'',1);
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td align='center'>\n";
							$this->salida .= "					<table width='99%' border='1' class='hc_table_submodulo_list'>\n";
							$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
							$this->salida .= "							<td width='50%' class='".$this->SetStyle($this->frmPrefijo."PerExtremidades",'',2)."'>Tipo Perimetro de Extremidades</td>\n";
							$this->salida .= "							<td width='50%' align='center'>Observación</td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
							$this->salida .= "							<td width='50%'>\n";
							$this->salida .= "									<table width='100%' border='1' class='hc_table_submodulo_list'>\n";
							$cont=0;
							foreach ($extremidad as $key => $value)
							{
								$this->salida .= "									<tr ".$this->Lista($key)."'>\n";
								if (in_array($value['tipo_extremidad_id'],$ctrlPerExtremidades)) {
									$this->salida .= "										<td width='50%' valign='middle'><input type='checkbox' name='".$this->frmPrefijo."CtlPerExtremidades[]' value='".$value['tipo_extremidad_id']."' checked>&nbsp;&nbsp;".$value['descripcion']."</td>\n";
								}
								else {
									$this->salida .= "										<td width='50%'  valign='middle'><input type='checkbox' name='".$this->frmPrefijo."CtlPerExtremidades[]' value='".$value['tipo_extremidad_id']."'>&nbsp;&nbsp;".$value['descripcion']."</td>\n";
								}
								$this->salida .= "									</tr>\n";
								$cont++;
							}
							$this->salida .= "									</table>\n</td>\n";
							$this->salida .= "							<td width='50%' align='center'><textarea class='textarea' name='".$this->frmPrefijo."CtlPerExtremidadesObs' cols='55' rows='6'>".$observacion."</textarea></td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "					</table>\n";
							$this->salida .= "			<br><br><input class='input-submit' type='submit' name='".$pj."Save' value='GUARDAR'><br><br>\n";
							$this->salida .= "			</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= "	</table>\n";
							$this->salida .= "	</form>\n";

							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
							$this->salida .= "<form action=\"$href\" name=\"Volver\" method=\"post\">\n";
							$this->salida .= "<br><br>\n";
							$this->salida .= "<div align='center' class='normal_10'><input class='input-submit' type='submit' name='".$this->frmPrefijo."Volver' value='Volver'></div>\n";
							$this->salida .= "</form><br>\n";
							return true;
				break;
				case $this->frmPrefijo."InsertCtrlPerExtremidades":
							$observaciones=$_REQUEST[$this->frmPrefijo."CtlPerExtremidadesObs"];
							$extremidad=$_REQUEST[$this->frmPrefijo."CtlPerExtremidades"];

							if (empty($extremidad))
							{
								$this->frmError[$this->frmPrefijo."Extremidades"]=1;
								$this->error=1;
							}
							if (!empty($this->error))
							{
								$this->frmError["MensajeError"]="Verfique los campos en rojo";
								$this->FrmForma($this->frmPrefijo."AddCtrlPerExtremidades");
								return true;
							}

							if (!$this->InsertCtrlPerExtremidades($extremidad,$observaciones))
								return false;
							return true;
				break;
				case $this->frmPrefijo."EditCtrlPerExtremidades":
							if (!$this->EditCtrlPerExtremidades())
								return false;
							return true;
				break;
				case $this->frmPrefijo."DelCtrlPerExtremidades":
							if (!$this->DelCtrlPerExtremidades())
								return false;
							return true;
				break;
				case $this->frmPrefijo."AddCtrlParto":
							list($dbconn) = GetDBconn();
							$fecha=date("d-m-Y H:i");

							if (empty($_REQUEST[$this->frmPrefijo."Parto"])) {
								$observacion=$_REQUEST[$this->frmPrefijo."CtlPartoObs"];
							}
							else {
								$datos=$_REQUEST[$this->frmPrefijo."Parto"];
								$observacion=$datos["observacion"];
							}
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."InsertCtrlParto"));
							$this->salida .="<form name='".$this->frmPrefijo."Parto' action=\"".$href."\" method='POST'>";
							$this->salida .= "	<table width='100%' align='justify' border='1' class='hc_table_submodulo_list'>";
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td width='80%' class='hc_table_submodulo_list_title'>CONTROL DE TRABAJO DE PARTO</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= $this->SetStyle("MensajeError",'',1);
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td align='center'>\n";
							$this->salida .= "					<table width='99%' border='1' class='hc_table_submodulo_list'>\n";
							$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
							$this->salida .= "							<td width='100%' class='".$this->SetStyle($this->frmPrefijo."observaciones",'',2)."' align='center'>Observación</td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
							$this->salida .= "							<td width='50%' align='center'><textarea class='textarea' name='".$this->frmPrefijo."CtlPartoObs' cols='85' rows='6'>".$observacion."</textarea><br><br></td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "					</table>\n";
							$this->salida .= "			<br><br><input class='input-submit' type='submit' name='".$pj."Save' value='GUARDAR'><br><br>\n";
							$this->salida .= "			</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= "	</table>\n";
							$this->salida .= "	</form>\n";

							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
							$this->salida .= "<form action=\"$href\" name=\"Volver\" method=\"post\">\n";
							$this->salida .= "<br><br>\n";
							$this->salida .= "<div align='center' class='normal_10'><input class='input-submit' type='submit' name='".$this->frmPrefijo."Volver' value='Volver'></div>\n";
							$this->salida .= "</form><br>\n";
							return true;
				break;
				case $this->frmPrefijo."InsertCtrlParto":
							$observaciones=$_REQUEST[$this->frmPrefijo."CtlPartoObs"];

							if (empty($observaciones)){
								$observaciones="Control Parto";
							}

							if (!$this->InsertCtrlParto($observaciones))
								return false;
							return true;
				break;
				case $this->frmPrefijo."EditCtrlParto":
							if (!$this->EditCtrlParto())
								return false;
							return true;
				break;
				case $this->frmPrefijo."DelCtrlParto":
							if (!$this->DelCtrlParto())
								return false;
							return true;
				break;
				case $this->frmPrefijo."AddCtrlGral":
							list($dbconn) = GetDBconn();

							if (empty($_REQUEST[$this->frmPrefijo."CtrlGral"])) {
								$observacion=$_REQUEST[$this->frmPrefijo."CtrlGralObs"];
								$ctrl_gral=$this->GetAllTipoControles($_REQUEST[$this->frmPrefijo.'tabla_tipo'],$_REQUEST[$this->frmPrefijo."CtrlGral"],1);
							}
							else {
								$datos=$_REQUEST[$this->frmPrefijo."CtrlGral"];
								$observacion=$datos["observacion"];
								$ctrl_gral=$this->GetAllTipoControles($_REQUEST[$this->frmPrefijo.'tabla_tipo'],$datos["frecuencia_id"],1);
							}
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."InsertCtrlGral"));
							$this->salida .="<form name='".$this->frmPrefijo."Control_Gral' action=\"".$href."\" method='POST'>";
							$this->salida .= "	<table width='100%' align='justify' border='1' class='hc_table_submodulo_list'>";
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td width='80%' class='hc_table_submodulo_list_title'>".strtoupper($_REQUEST[$this->frmPrefijo.'control_descripcion'])."</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= $this->SetStyle("MensajeError",'',1);
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td align='center'>\n";
							$this->salida .= "					<table width='99%' border='1' class='hc_table_submodulo_list'>\n";
							$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
							$this->salida .= "							<td width='50%' class='".$this->SetStyle($this->frmPrefijo."frecuencia",'',2)."'>Frecuencia</td>\n";
							$this->salida .= "							<td width='50%' align='center'>Observación</td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
							$this->salida .= "							<td width='50%' valign='top'><select class='select' name='".$this->frmPrefijo."CtrlGralFr'><option value='-1'>--</option>\n$ctrl_gral</select></td>\n";
							$this->salida .= "							<td width='50%' align='center'><textarea class='textarea' name='".$this->frmPrefijo."CtrlGralObs' cols='55' rows='6'>".$observacion."</textarea><br><br></td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "					</table>\n";
							$this->salida .= "			<br><br><input class='input-submit' type='submit' name='".$pj."Save' value='GUARDAR'><br><br>\n";
							$this->salida .= "			</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= "	</table>\n";
							$this->salida .= "	<input type='hidden' name='".$this->frmPrefijo."control_id' value='".$_REQUEST[$this->frmPrefijo.'control_id']."'>\n";
							$this->salida .= "	<input type='hidden' name='".$this->frmPrefijo."tabla' value='".$_REQUEST[$this->frmPrefijo.'tabla']."'>\n";
							$this->salida .= "	<input type='hidden' name='".$this->frmPrefijo."tabla_tipo' value='".$_REQUEST[$this->frmPrefijo.'tabla_tipo']."'>\n";
							$this->salida .= "	<input type='hidden' name='".$this->frmPrefijo."control_descripcion' value='".$_REQUEST[$this->frmPrefijo.'control_descripcion']."'>\n";
							$this->salida .= "	</form>\n";

							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
							$this->salida .= "<form action=\"$href\" name=\"Volver\" method=\"post\">\n";
							$this->salida .= "<br><br>\n";
							$this->salida .= "<div align='center' class='normal_10'><input class='input-submit' type='submit' name='".$this->frmPrefijo."Volver' value='Volver'></div>\n";
							$this->salida .= "</form><br>\n";
							return true;
				break;
				case $this->frmPrefijo."InsertCtrlGral":
							$observaciones=$_REQUEST[$this->frmPrefijo."CtrlGralObs"];
							$frecuencia=$_REQUEST[$this->frmPrefijo."CtrlGralFr"];
							$control_id=$_REQUEST[$this->frmPrefijo.'control_id'];
							$tabla=$_REQUEST[$this->frmPrefijo.'tabla'];

							if ($frecuencia=='-1')
							{
								$this->frmError[$this->frmPrefijo."frecuencia"]=1;
								$this->error=1;
							}
							if (!empty($this->error))
							{
								$this->frmError["MensajeError"]="Verfique los campos en rojo";
								$this->FrmForma($this->frmPrefijo."AddCtrlGral");
								return true;
							}

							if (!$this->InsertCtrlGral($tabla,$control_id,$frecuencia,$observaciones))
								return false;
							return true;
				break;
				case $this->frmPrefijo."EditCtrlGral":
							if (!$this->EditCtrlGral())
								return false;
							return true;
				break;
				case $this->frmPrefijo."DelCtrlGral":
							if (!$this->DelCtrlGral())
								return false;
							return true;
				break;
				case $this->frmPrefijo."GraphCtrlGral":

							$hora_inicio_turno=ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
							$rango_turno=ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
							list($dbconn) = GetDBconn();
							$control_id=$_REQUEST[$this->frmPrefijo.'control_id'];

							$datos_hc=GetDatosPaciente("","",$this->ingreso,"","");
							$datos_paciente=array("edad"=>CalcularEdad($datos_hc["fecha_nacimiento"],date("Y-m-d")),"sexo"=>$datos_hc["sexo_id"]);

							$rango_control=$this->GetRangoControl($control_id,$datos_paciente);
							if ($rango_control===false && !empty($this->error)){
								return false;
							}

							list($h,$m,$s)=explode(":",$hora_inicio_turno);
							$datos=array();
							$datos_d=array();

							$datos_fecha=$this->GetListFechas($h,$control_id);
							if (is_array($datos_fecha) && !empty($datos_fecha) && !empty($rango_control)){
								$this->salida.= ThemeAbrirTablaSubModulo(strtoupper($_REQUEST[$this->frmPrefijo.'control_descripcion']));
								$this->salida .= "<form name=\"formaGraphCtrl\" action=\"$href\" method=\"post\">";
								$this->salida.= "		<script>\n";
								$this->salida.= "			function buscaCampos(campo,forma) {\n";
								$this->salida.= "				var i=0; var j=0;";
								$this->salida.= "				while (!i) { if (forma.elements[j].name!=campo) j++; else return(j); } \n";
								$this->salida.= "				return (-1);\n";
								$this->salida.= "			}\n\n";

								$this->salida .= "			function CargarPagina(href,valor) {\n";
								$this->salida.= "				var url=href;\n";
								$this->salida.= "				location.href=url+'&".$this->frmPrefijo."GraphControl='+valor;\n";
								$this->salida.= "			}\n\n";
								$this->salida.= "		</script>\n\n";

								$this->salida.= "<table width='100%' border='0'>";
								$url=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."GraphCtrlGral",$this->frmPrefijo."control_id"=>$control_id,$this->frmPrefijo."control_descripcion"=>$_REQUEST[$this->frmPrefijo.'control_descripcion']));
								$this->salida.= "		<tr><td align='center'><select class='select' name='".$this->frmPrefijo."GraphControl' onchange=\"CargarPagina('$url',this.options[selectedIndex].value);\">\n";
								foreach($datos_fecha as $key => $value){
									if ($_REQUEST[$this->frmPrefijo."GraphControl"]==$value['fechas']){
										if ($value['fechas']==date("Y-m-d")){
											$this->salida.= "		<option value='".$value['fechas']."' selected>Hoy</option>\n";
										}
										elseif ($value['fechas']==date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")))){
											$this->salida.= "		<option value='".$value['fechas']."' selected>Ayer</option>\n";
										}
										else{
											$this->salida.= "		<option value='".$value['fechas']."' selected>".$value['fechas']."</option>\n";
										}
									}
									else{
										if ($value['fechas']==date("Y-m-d")){
											$this->salida.= "		<option value='".$value['fechas']."'>Hoy</option>\n";
										}
										elseif ($value['fechas']==date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")))){
											$this->salida.= "		<option value='".$value['fechas']."'>Ayer</option>\n";
										}
										else{
											$this->salida.= "		<option value='".$value['fechas']."'>".$value['fechas']."</option>\n";
										}
									}
								}
								if ($_REQUEST[$this->frmPrefijo."GraphControl"]=="todos"){
									$this->salida.= "		<option value='todos' selected>Todos</option>\n";
								}
								else{
									$this->salida.= "		<option value='todos'>Todos</option>\n";
								}
								$this->salida.= "		</select></td></tr>\n";

								if ($_REQUEST[$this->frmPrefijo."GraphControl"]!="todos"){
									if (empty($_REQUEST[$this->frmPrefijo."GraphControl"]))
										$fecha=$datos_fecha[0]['fechas'];
									else
										$fecha=$_REQUEST[$this->frmPrefijo."GraphControl"];
									$datos=$this->GetFechas($fecha,$hora_inicio_turno,$rango_turno,$control_id);
									if (!is_array($datos)){
										return false;
									}
								}
								elseif ($_REQUEST[$this->frmPrefijo."GraphControl"]=="todos"){
									$fecha=$_REQUEST[$this->frmPrefijo."GraphControl"];
									$datos_d=$this->GetAllFechas($hora_inicio_turno,$rango_turno,$control_id);
									if (!is_array($datos_d)){
										return false;
									}
								}

								$this->salida.= "		<tr><td>\n";
								$this->salida.= "			<table width='100%' border='0'>";
								if ($fecha=='todos'){
									$this->salida.= "				<tr><td align='center'><br>";
									switch($control_id){
										case 5 :
															IncludeLib("jpgraph/CurvaTermica");
															$this->salida.="<img src='".GraficarCurvaTermica($rango_control['rango_max'],$rango_control['rango_min'],$datos_d,1,"","")."'>";
															$this->salida.= "				<tr><td align='center'><br>\n";
															$this->FrmResumenCurvaTermica($this->ingreso,$hora_inicio_turno,$rango_turno,$fecha,$rango_control['rango_max'],$rango_control['rango_min'],true);
															$this->salida.= "				</td></tr>";
										break;
										case 7 :
															$this->salida.="<img src='".GraficarGlucometria($rango_control['rango_max'],$rango_control['rango_min'],$datos_d,1,"","")."'>";
										break;
										case 8 :
															IncludeLib("jpgraph/Glicemia");
															$this->salida.="<img src='".GraficarGlucometria($rango_control['rango_max'],$rango_control['rango_min'],$datos_d,1,"","")."'>";
															$this->salida.= "				<tr><td align='center'><br>\n";
															$this->FrmResumenGlucometria($this->ingreso,$hora_inicio_turno,$rango_turno,$fecha,$rango_control['rango_max'],$rango_control['rango_min'],true);
															$this->salida.= "				</td></tr>";
										break;
										case 18 :
															IncludeLib("jpgraph/PresionVenosaCentral");
															$this->salida.="<img src='".GraficarPresionVenosaCentral($rango_control['rango_max'],$rango_control['rango_min'],$datos_d,1,"","")."'>";
															$this->salida.= "				<tr><td align='center'><br>\n";
															$this->FrmResumenPresionVenosaCentral($this->ingreso,$hora_inicio_turno,$rango_turno,$fecha,$rango_control['rango_max'],$rango_control['rango_min'],true);
															$this->salida.= "				</td></tr>";
										break;
										case 21 :
															IncludeLib("jpgraph/FrecuenciaCardiaca");
															$this->salida.="<img src='".GraficarFrecuenciaCardiaca($rango_control['rango_max'],$rango_control['rango_min'],$datos_d,1,"","")."'>";
															$this->salida.= "				<tr><td align='center'><br>\n";
															$this->FrmResumenFrecuenciaCardiaca($this->ingreso,$hora_inicio_turno,$rango_turno,$fecha,$rango_control['rango_max'],$rango_control['rango_min'],true);
															$this->salida.= "				</td></tr>";
										break;
										case 22 :
															IncludeLib("jpgraph/FrecuenciaRespiratoria");
															$this->salida.="<img src='".GraficarFrecuenciaRespiratoria($rango_control['rango_max'],$rango_control['rango_min'],$datos_d,1,"","")."'>";
															$this->salida.= "				<tr><td align='center'><br>\n";
															$this->FrmResumenFrecuenciaRespiratoria($this->ingreso,$hora_inicio_turno,$rango_turno,$fecha,$rango_control['rango_max'],$rango_control['rango_min'],true);
															$this->salida.= "				</td></tr>";
										break;
										$this->salida.= "				</td></tr>";
									}
								}
								else{
									$this->salida.= "				<tr><td align='center'><br>";
										switch($control_id){
											case 5 :
																	IncludeLib("jpgraph/CurvaTermica");
																	$this->salida.="<img src=\"".GraficarCurvaTermica($rango_control['rango_max'],$rango_control['rango_min'],"",0,$fecha,$datos)."\">";
																	$this->salida.= "				<tr><td align='center'><br>\n";
																	$this->FrmResumenCurvaTermica($this->ingreso,$hora_inicio_turno,$rango_turno,$fecha,$rango_control['rango_max'],$rango_control['rango_min'],false);
																	$this->salida.= "				</td></tr>";
											break;
											case 7 :
																$this->salida.="<img src=\"".GraficarGlucometria($rango_control['rango_max'],$rango_control['rango_min'],"",0,$fecha,$datos)."\">";
											break;
											case 8 :
																IncludeLib("jpgraph/Glicemia");
																$this->salida.="<img src=\"".GraficarGlucometria($rango_control['rango_max'],$rango_control['rango_min'],"",0,$fecha,$datos)."\">";
																$this->salida.= "				<tr><td align='center'><br>\n";
																$this->FrmResumenGlucometria($this->ingreso,$hora_inicio_turno,$rango_turno,$fecha,$rango_control['rango_max'],$rango_control['rango_min'],false);
																$this->salida.= "				</td></tr>";
											break;
											case 18 :
																	IncludeLib("jpgraph/PresionVenosaCentral");
																	$this->salida.="<img src='".GraficarPresionVenosaCentral($rango_control['rango_max'],$rango_control['rango_min'],"",0,$fecha,$datos)."'>";
																	$this->salida.= "				<tr><td align='center'><br>\n";
																	$this->FrmResumenPresionVenosaCentral($this->ingreso,$hora_inicio_turno,$rango_turno,$fecha,$rango_control['rango_max'],$rango_control['rango_min'],false);
																	$this->salida.= "				</td></tr>";
											break;
											case 21 :
																	IncludeLib("jpgraph/FrecuenciaCardiaca");
																	$this->salida.="<img src='".GraficarFrecuenciaCardiaca($rango_control['rango_max'],$rango_control['rango_min'],"",0,$fecha,$datos)."'>";
																	$this->salida.= "				<tr><td align='center'><br>\n";
																	$this->FrmResumenFrecuenciaCardiaca($this->ingreso,$hora_inicio_turno,$rango_turno,$fecha,$rango_control['rango_max'],$rango_control['rango_min'],false);
																	$this->salida.= "				</td></tr>";
											break;
											case 22 :
																IncludeLib("jpgraph/FrecuenciaRespiratoria");
																$this->salida.="<img src='".GraficarFrecuenciaRespiratoria($rango_control['rango_max'],$rango_control['rango_min'],"",0,$fecha,$datos)."'>";
																$this->salida.= "				<tr><td align='center'><br>\n";
																$this->FrmResumenFrecuenciaRespiratoria($this->ingreso,$hora_inicio_turno,$rango_turno,$fecha,$rango_control['rango_max'],$rango_control['rango_min'],false);
																$this->salida.= "				</td></tr>";
											break;
										}
									$this->salida.= "				</td></tr>";
								}
								$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>""));
								$this->salida.= "				<tr><td align='center' class='normal_10'><br><a href='$href'>Volver Controles Paciente</a></td></tr>";
								$this->salida.= "			</table>\n";
								$this->salida.= "		</td></tr>\n";
								$this->salida.= "	</table>\n";
								$this->salida.= "	</form>\n";
								$this->salida.= ThemeCerrarTablaSubModulo();
							}
							elseif ((is_array($datos_fecha) && empty($datos_fecha)) || empty($rango_control)){
								$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>""));
								//$this->salida.= ThemeAbrirTabla("MENSAJE DEL SISTEMA","60%");
								$this->salida.= "<br><br><br><table width='100%' border='0'>";
								$this->salida.= "		<tr>\n";
								$this->salida.= "			<td align='center' class='label_mark'>EL PACIENTE NO CUENTA CON DATOS PARA REALIZAR LA GRÁFICA.</td>\n";
								$this->salida.= "		</tr>\n";
								$this->salida.= "		<tr>\n";
								$this->salida.= "			<td align='center' class='normal_10'><br><a href='$href'>Volver Controles Paciente</a></td>\n";
								$this->salida.= "		</tr>\n";
								$this->salida.= "</table><br><br>\n\n";
								//$this->salida.= ThemeCerrarTabla();
							}
							else{
								$this->error = "Error en el Submodulo de Controles del Paciente.<br>";
								$this->mensajeDeError = "Se ha generado una exepción en la ejecución de las consultas<br>Verificar con el Administrador.";
								return false;
							}
							return true;
				break;
				case $this->frmPrefijo."AddCtrlDietas":
							list($dbconn) = GetDBconn();
							if (empty($_REQUEST[$this->frmPrefijo."Dietas"])) {
								$observacion=$_REQUEST[$this->frmPrefijo."CtlDietasObs"];
								$ctrlDietas=$_REQUEST[$this->frmPrefijo."CtlDietas"];
								$hora=$_REQUEST[$this->frmPrefijo."hora"];
								$hora_inicio=$_REQUEST[$this->frmPrefijo."horainicio"];
								$observacion1=$_REQUEST[$this->frmPrefijo."CtlDietasObsA"];
								if($_REQUEST[$this->frmPrefijo."CtlAyuno"])
								{$check='checked';}else{$check='';}
								$dietas=$this->GetControlDietas();
							}
							else {
								$datos=$_REQUEST[$this->frmPrefijo."Dietas"];
								$observacion=$datos["observacion"];
								$_REQUEST[$this->frmPrefijo.'tipodieta'] = $datos["CtlDietas"];
								$_REQUEST[$this->frmPrefijo.'fraccionada'] = $datos["fraccionada"];
								$caracteristicas_vec = $this->GetCaracteristicas_Info();
								$dietas=$this->GetControlDietas();
								$informacion=$this->TraerInformacionAyuno();
								if($informacion!='')
								{$check='checked';}else{$check='';}
								$observacion1=$informacion[0];
								$hora=$informacion[1];
								$hora_inicio=$informacion[2];
							}
							$pfj = $this->frmPrefijo;
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."InsertCtrlDietas"));
							$this->salida .="<form name='".$this->frmPrefijo."Dietas' action=\"".$href."\" method='POST'>";
                                   $this->salida .= "		<table align='center'>\n";
							$this->salida .= $this->SetStyle("MensajeError",'',1);
							$this->salida .= "		</table>\n";

                                   $java = "\n<script language='javascript'>\n";
                                   $java .="function Caracteristica(valor){\n";
                                   $java .="window.location.href='".ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddCtrlDietas"))."&".$this->frmPrefijo."tipodieta='+valor;\n";
                                   $java .="}\n";
                                   
                                   $java .="function Desabilitar(obj_valor){\n";
                                   $java .="window.location.href='".ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddCtrlDietas"))."&".$this->frmPrefijo."nada_oral='+obj_valor;\n";
                                   $java .="}\n";
                                   
		                         $java .="function Desabilitar2(formaH,i){\n";
                                   $java .="for(j=0;j<=i;j++){\n";
                                   $java .="if(formaH.radio[j].disabled == false){\n";
                                   $java .="formaH.radio[j].disabled = true}else{\n";
                                   $java .="formaH.radio[j].disabled = false}}\n";
                                   $java .="}\n";

                                   $java .="</script>\n";
                                   $this->salida .= $java;
                                   $this->salida .= "<br><table width='60%' align='center' border='1' class='modulo_table_list'>";
							$this->salida .= "   <tr>\n";
							$this->salida .= "			<td width='100%' colspan=\"2\" class='modulo_table_list_title'>DIETAS DEL PACIENTE</td>\n";

                              	$NVO=$this->GetNadaViaOral();
                                   if($_REQUEST[$pfj.'nada_oral'] == 'nada' OR ($_REQUEST[$this->frmPrefijo.'tipodieta'] == $NVO AND $NVO != $_REQUEST[$pfj.'nada_oral']))
                                   { 
                                        $enabled = 'disabled';
                                        $checked = 'checked';
                                   }else
                                   {
                                        $enabled = 'enabled';
                                   }
                                   
                                   $this->salida .= "<tr class=\"hc_list_oscuro\">\n";
                                   if($_REQUEST[$pfj.'nada_oral'] == 'nada' OR ($_REQUEST[$this->frmPrefijo.'tipodieta'] == $NVO AND $NVO != $_REQUEST[$pfj.'nada_oral']))
                                   {
	                                   $this->salida .= "<td width='50%' class=\"label\"><input type=\"checkbox\" name=\"".$pfj."nada_oral\" value=\"".$NVO."\" onclick=\"Desabilitar(this.value)\" $checked>&nbsp;NADA VIA ORAL</td>\n";
                                   }else
                                   {
	                                   $this->salida .= "<td width='50%' class=\"label\"><input type=\"checkbox\" name=\"".$pfj."nada_oral\" value=\"nada\" onclick=\"Desabilitar(this.value)\">&nbsp;NADA VIA ORAL</td>\n";
                                   }
                                                                           
                                   if($_REQUEST[$pfj.fraccionada])
                                   { $this->salida .= "<td class=\"label\"><input type=\"checkbox\" name=\"".$pfj."fraccionada\" value=\"1\" checked $enabled>&nbsp;FRACCIONADA (6 Porciones)";}else
                                   { $this->salida .= "<td class=\"label\"><input type=\"checkbox\" name=\"".$pfj."fraccionada\" value=\"1\" $enabled>&nbsp;FRACCIONADA (6 Porciones)";}
                                   $this->salida .= "</td>";
                                   $this->salida .= "</tr>";
                                   
                                   $this->salida .= "<tr class=\"hc_list_oscuro\">\n";
		                       	$this->salida .= "<td width='50%' class=\"label\">TIPOS DE DIETAS:&nbsp;&nbsp;&nbsp;";
                                   $this->salida .= "&nbsp;&nbsp;&nbsp;<select name=\"".$pfj."tipodieta\" class=\"select\" onchange=\"Caracteristica(this.value)\" $enabled>";
                                   $this->GetHtmlDietas($dietas,$_REQUEST[$pfj.'tipodieta']);
                                   $this->salida .= "</select>";
                                   $this->salida .= "</td>\n";
                                   
							$this->salida .= "<td width='20%' align='left'>";
							$this->salida .= "<input type='checkbox' value=\"1\" $check name='".$this->frmPrefijo."CtlAyuno' $enabled>&nbsp;&nbsp;<label class='label'>AYUNO</label>";
							$this->salida .= "</td>";
                                   $this->salida .= "</tr>\n";
							
                                   $this->salida .= "<tr class=\"hc_list_oscuro\">\n";
							if(empty($_REQUEST[$pfj.'tipodieta']))
                                   { $valor = $dietas[0][hc_dieta_id];}
                                   else
                                   { $valor = $_REQUEST[$pfj.'tipodieta'];}
							
                                   $dietas_Caracteristicas = $this->GetDietas_Caracteristicas($valor,1);
        						$this->salida .= "<td width='50%' class=\"label\">";
                                   if(!empty($dietas_Caracteristicas))
                                   {
                                        $this->salida .= "<table class=\"hc_list_oscuro\" width='100%' border=\"1\">";
                                        for($j=0;$j<sizeof($dietas_Caracteristicas);$j++)
                                        {    
                                             $checked = '';
                      					if(!empty($caracteristicas_vec))
                                             {
                                                  for($count=0;$count<sizeof($caracteristicas_vec); $count++)
                                                  { 
                                                       if($caracteristicas_vec[$count][caracteristica_id] == $dietas_Caracteristicas[$j][caracteristica_id])
                                                       {$checked = 'checked';}
                                                  }
                                             }
                                             $this->salida .= "<tr class=\"hc_list_oscuro\">";
                                             $this->salida .= "<td class=\"label\"><input type=\"checkbox\" name=\"".$pfj."caracteristica_dieta[]\" value=\"".$dietas_Caracteristicas[$j][caracteristica_id]."\" $checked $enabled>&nbsp;".$dietas_Caracteristicas[$j][descripcion]."";
                                             $this->salida .= "</td>";
                                             $this->salida .= "</tr>";
                                        }
                                        $this->salida .= "</table>";
                                   }
                                   $this->salida .= "</td>\n";

                                   $only_Caracteristicas = $this->GetDietas_Caracteristicas($valor,2);
                                   $this->salida .= "<td width='50%' class=\"label\">";
                                   if(!empty($only_Caracteristicas))
                                   {
                                   	$aa = 0;
                                        $this->salida .= "<table class=\"hc_list_oscuro\">";
                                        for($x=0;$x<sizeof($only_Caracteristicas);$x++)
                                        {
                                        	$checked = '';
                                             if(!empty($caracteristicas_vec))
                                             {
                                                  $habilitar = '';
                                                  for($count=0;$count<sizeof($caracteristicas_vec); $count++)
                                                  { 
                                                       if($caracteristicas_vec[$count][caracteristica_id] == $only_Caracteristicas[$x][caracteristica_id])
                                                       {$checked = 'checked';}
                                                  }
                                             }else
                                             {$habilitar = 'disabled';}
                                             if(!empty($only_Caracteristicas[$x][codigo_agrupamiento]))
                                             {
                                             	$aa++;
                                                  if($only_Caracteristicas[$x][codigo_agrupamiento] != $only_Caracteristicas[$x -1][codigo_agrupamiento])
                                                  {
                                                       $this->salida .= "<tr class=\"hc_list_oscuro\">";
                                                       $this->salida .= "<td class=\"label\"><input type=\"checkbox\" name=\"".$pfj."sel[]\" onclick=\"Desabilitar2(document.".$pfj."Dietas,$aa)\" $checked $enabled>".$only_Caracteristicas[$x][descripcion]."";
                                                       $this->salida .= "</td>";
                                                       $this->salida .= "</tr>";
                                                  }
                               
                                                  $this->salida .= "<tr class=\"hc_list_oscuro\">";
                                                  $this->salida .= "<td class=\"label\">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" id=\"radio\" name=\"".$pfj."caracteristica_dieta[]\" value=\"".$only_Caracteristicas[$x][caracteristica_id]."\" $habilitar $checked $enabled>&nbsp;".$only_Caracteristicas[$x][descripcion_agrupamiento]."";
                                                  $this->salida .= "</td>";
                                                  $this->salida .= "</tr>";
                                             }
                                             else
                                             {
                                                  $this->salida .= "<tr class=\"hc_list_oscuro\">";
                                                  $this->salida .= "<td class=\"label\"><input type=\"checkbox\" name=\"".$pfj."caracteristica_dieta[]\" value=\"".$only_Caracteristicas[$x][caracteristica_id]."\" $checked $enabled>&nbsp;".$only_Caracteristicas[$x][descripcion]."";
                                                  $this->salida .= "</td>";
                                                  $this->salida .= "</tr>";
                                             }
                                        }
                                        $this->salida .= "</table>";
                                   }
                                   $this->salida .= "</td>\n";
							
                                   $this->salida .= "</tr>\n";
                                   
                                   $this->salida .= "<tr class=\"hc_list_claro\">\n";
                                   $this->salida .= "<td class=\"label\" align=\"center\" colspan=\"2\">OSERVACION GENERAL DE LA DIETA";
                                   $this->salida .= "</td>\n";
                                   $this->salida .= "</tr>\n";
                                   $this->salida .= "<tr>\n";
                                   $this->salida .= "<td class=\"hc_list_claro\" colspan=\"2\" align=\"center\">";
                                   $this->salida .= "<textarea class='textarea' name='".$this->frmPrefijo."CtlDietasObs' cols='55' style=\"width:100%\"rows='6' $enabled>".$observacion."</textarea>";
                                   $this->salida .= "</td>\n";
                                   $this->salida .= "</tr>\n";
                                   
                                   $this->salida .= "<tr>\n";
                                   $this->salida .= "<td class=\"hc_list_claro\" colspan=\"2\" align=\"center\">";
                                   $this->salida .= "<input class='input-submit' type='submit' name='".$pj."Save' value='GUARDAR'>";
                                   $this->salida .= "</td>\n";
                                   $this->salida .= "</tr>\n";
                                   
							$this->salida .= "	</table>\n";
                                   
							$this->salida .= "	</form>\n";

							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
							$this->salida .= "<form action=\"$href\" name=\"Volver\" method=\"post\">\n";
							$this->salida .= "<br><br>\n";
							$this->salida .= "<div align='center' class='normal_10'><input class='input-submit' type='submit' name='".$this->frmPrefijo."Volver' value='Volver'></div>\n";
							$this->salida .= "</form><br>\n";
							return true;
				break;
				case $this->frmPrefijo."InsertCtrlDietas":
							$tipo_dieta = $_REQUEST[$this->frmPrefijo.'tipodieta'];
                                   $fraccionada = $_REQUEST[$this->frmPrefijo.'fraccionada'];
							$observaciones = $_REQUEST[$this->frmPrefijo."CtlDietasObs"];
                                   $caracteristicas = $_REQUEST[$this->frmPrefijo.'caracteristica_dieta'];
                                   //CORRESPONDIENTE AL AYUNO
                                   $ayuno = $_REQUEST[$this->frmPrefijo."CtlAyuno"];                                   
							$horafin = $_REQUEST[$this->frmPrefijo."horafin"];
							$observacion_Ayuno = $_REQUEST[$this->frmPrefijo."CtlDietasObsA"];
							$hora_inicio = $_REQUEST[$this->frmPrefijo."horainicio"];
							$nada_via_oral = $_REQUEST[$this->frmPrefijo.'nada_oral'];
                                   if(empty($fraccionada))
                                   { $fraccionada = '0';}
                                   if(empty($ayuno))
                                   { $ayuno = '0';}
                                   if(empty($observaciones))
                                   { $observaciones = 'NULL';}else{ $observaciones = "'".$observaciones."'";}
                                   
							if (!$this->InsertCtrlDietas($tipo_dieta,$fraccionada,$observaciones,$caracteristicas,$ayuno,$horafin,$observacion_Ayuno,$hora_inicio,$nada_via_oral))
								return false;

							return true;
				break;
				case $this->frmPrefijo."EditCtrlDietas":
							if (!$this->EditCtrlDietas())
								return false;
							return true;
				break;
				case $this->frmPrefijo."DelCtrlDietas":
							if (!$this->DelCtrlDietas())
								return false;
							return true;
				break;
				case $this->frmPrefijo."AddCtrlTransfusiones":
							list($dbconn) = GetDBconn();

							if (empty($_REQUEST[$this->frmPrefijo."Transfusiones"])) {
								$observacion=$_REQUEST[$this->frmPrefijo."CtlTransfusionesObs"];
							}
							else {
								$datos=$_REQUEST[$this->frmPrefijo."Transfusiones"];
								$observacion=$datos["observaciones"];
							}
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."InsertCtrlTransfusiones"));
							$this->salida .="<form name='".$this->frmPrefijo."Liquidos' action=\"".$href."\" method='POST'>";
							$this->salida .= "	<table width='100%' align='justify' border='1' class='hc_table_submodulo_list'>";
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td width='80%' class='hc_table_submodulo_list_title'>CONTROL DE TRANSFUSIONES</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= $this->SetStyle("MensajeError",'',1);
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td align='center'>\n";
							$this->salida .= "					<table width='99%' border='1' class='hc_table_submodulo_list'>\n";
							$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
							$this->salida .= "							<td width='100%' class='".$this->SetStyle($this->frmPrefijo."observaciones",'',2)."' align='center'>Observación</td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
							$this->salida .= "							<td width='50%' align='center'><textarea class='textarea' name='".$this->frmPrefijo."CtlTransfusionesObs' cols='85' rows='6'>".$observacion."</textarea><br><br></td>\n";
							$this->salida .= "						</tr>\n";
							$this->salida .= "					</table>\n";
							$this->salida .= "			<br><br><input class='input-submit' type='submit' name='".$pj."Save' value='GUARDAR'><br><br>\n";
							$this->salida .= "			</td>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= "	</table>\n";
							$this->salida .= "	</form>\n";

							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
							$this->salida .= "<form action=\"$href\" name=\"Volver\" method=\"post\">\n";
							$this->salida .= "<br><br>\n";
							$this->salida .= "<div align='center' class='normal_10'><input class='input-submit' type='submit' name='".$this->frmPrefijo."Volver' value='Volver'></div>\n";
							$this->salida .= "</form><br>\n";
							return true;
				break;
				case $this->frmPrefijo."InsertCtrlTransfusiones":
							$observaciones=$_REQUEST[$this->frmPrefijo."CtlTransfusionesObs"];

							if (empty($observaciones)){
								$observaciones="Control de Transfusiones";
							}

							if (!$this->InsertCtrlTransfusiones($observaciones))
								return false;
							return true;
				break;
				case $this->frmPrefijo."EditCtrlTransfusiones":
							if (!$this->EditCtrlTransfusiones())
								return false;
							return true;
				break;
				case $this->frmPrefijo."DelCtrlTransfusiones":
							if (!$this->DelCtrlTransfusiones())
								return false;
							return true;
				break;


				//consultas de las transfusiones segun el ingreso.
				case $this->frmPrefijo."ConsCtrlTranfusiones":

			$transfusionesPaciente = $this->GetTransfusiones($this->ingreso);
			if(!$transfusionesPaciente){
				$mensaje = "NO SE ENCONTRARON REGISTROS DE TRANSFUSIONES";
				$this->salida.= "<br><br><br><table width='100%' border='0'>";
				$this->salida.= "		<tr>\n";
				$this->salida.= "			<td align='center' class='label_mark'>$mensaje.</td>\n";
				$this->salida.= "		</tr>\n";
				$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>""));
				$this->salida.= "		<tr>\n";
				$this->salida.= "			<td align='center' class='normal_10'><br><a href='$href'>Volver Controles Paciente</a></td>\n";
				$this->salida.= "		</tr>\n";
				$this->salida.= "</table><br><br>\n\n";
				return false;
			}
			elseif($transfusionesPaciente != "ShowMensaje")
			{
				if(empty($contador)){
					$contador = sizeof($transfusionesPaciente);
				}
				$this->salida .= "<form name='frmShowTransfusiones' action='".$action."' method='POST'><br>\n";
				$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border=\"0\">\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td>FECHA</td>\n";
				$this->salida .= "			<td>BOLSAS</td>\n";
				$this->salida .= "			<td># SELLO<BR>CALIDAD</td>\n";
				$this->salida .= "			<td>FECHA DE<br>VENCIMIENTO</td>\n";
				$this->salida .= "			<td>G.S.</td>\n";
				$this->salida .= "			<td>RH</td>\n";
				$this->salida .= "			<td>FECHA FINAL<br>TRANSFUSION</td>\n";
				$this->salida .= "			<td>REACCIONES<BR>ADVERSAS</td>\n";
				$this->salida .= "		</tr>\n";
				$cont=1;

				while ($cont <= sizeof($transfusionesPaciente) && $cont <= $contador)
				{
					list($fecha,$hora) = explode(" ",$transfusionesPaciente[$cont-1][fecha]);//substr(,0,10);
					$this->salida .= "		<tr ".$this->Lista($cont)."' align='center' valign='middle'>\n";
					if($fecha == date("Y-m-d")) {
						$fecha = "HOY $hora";
					}
					elseif($fecha == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
						$fecha = "AYER $hora";
					}
					else {
						$fecha = $fecha;
					}
					$this->salida .= "			<td>".$fecha."</td>\n";
					$this->salida .= "			<td>".$transfusionesPaciente[$cont-1][numero_bolsas]."</td>\n";
					$this->salida .= "			<td>".$transfusionesPaciente[$cont-1][numero_sello_calidad]."</td>\n";
					$this->salida .= "			<td>".$transfusionesPaciente[$cont-1][fecha_vencimiento]."</td>\n";
					$this->salida .= "			<td>".$transfusionesPaciente[$cont-1][grupo_sanguineo]."</td>\n";
					$this->salida .= "			<td>".$transfusionesPaciente[$cont-1][rh]."</td>\n";
					$this->salida .= "			<td valign='middle'>\n";
					if(empty($transfusionesPaciente[$cont-1][fecha_final]))
					{
						$this->salida .= "			<form name='FechaFin' action=\"$action\" method='post'>\n";
						$this->salida .= "				<input type='text' class='input-text' name='fechaFin' value='".$_REQUEST['fechaFin']."' size='10' maxlength='10' readonly='yes'>".ReturnOpenCalendario('frmShowTransfusiones','fechaFin','-')."\n";
						$this->salida .= "				<select name='Horas' class='select'>\n";
						for($i=0; $i<24; $i++)
						{
							$hora = date("H", mktime($i,0,0,date("m"),date("d"),date("Y")));
							if($hora == date("H")){
								$selected = 'selected="yes"';
							}
							else { $selected = ""; }
							$this->salida .= "				<option value='$hora' $selected>$hora</option>\n";
						}
						$this->salida .= "				</select>\n";
						$this->salida .= "				<select name='Minutos' class='select'>\n";
						for($i=0; $i<60; $i++)
						{
							$min = date("i",mktime(date("H"),$i,date("s"),date("m"),date("d"),date("Y")));
							if(date("i") == $min){
								$selected = 'selected="yes"';
							}
							else { $selected = ""; }
							$this->salida .= "				<option value='".$min.":".date("s")."' $selected>$min</option>\n";
						}
						$this->salida .= "				</select>\n";
						$this->salida .= "				<input type='hidden' name='ingreso' value='".$datos_estacion['ingreso']."'>\n";
						$this->salida .= "				<input type='hidden' name='fechaInicio' value='".$transfusionesPaciente[$cont-1][fecha]."'>\n";
						$this->salida .= "				<input type='image' name='submit' src='".GetThemePath()."/images/EstacionEnfermeria/guarda.png' border=0 alt='GUARDAR'>\n";//<input type='submit' name='submit' value='s'>
						$this->salida .= "			</form>\n";
					}
					else{
						$this->salida .= "			".$transfusionesPaciente[$cont-1][fecha_final]."\n";
					}
					$this->salida .= "			</td>\n";
					$this->salida .= "			<td>\n";
					if(empty($transfusionesPaciente[$cont-1][reaccion_adversa])){
						//$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmInsertarReaccionAdversa',array("ingreso"=>$datos_estacion['ingreso'],"datos"=>$transfusionesPaciente[$cont-1],"estacion"=>$estacion,"datos_estacion"=>$datos_estacion));
						$this->salida .= "			--";
					}
					else{
						$this->salida .= "			".$transfusionesPaciente[$cont-1][reaccion_adversa]."\n";
					}
					$this->salida .= "			</td>\n";
					$this->salida .= "		</tr>\n";
					$cont++;
				}
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n\n";

				$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>""));
				$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
				$this->salida.= "		<tr>\n";
				$this->salida.= "			<td align='center' class='normal_10'><br><a href='$href'>Volver Controles Paciente</a></td>\n";
				$this->salida.= "		</tr>\n";
				$this->salida .= "</table>\n";
			}

			break;
               
               // Nuevo caso de Controles Adicionales (Otros Controles).
               case $this->frmPrefijo."AddCtrlAdicionales":
                    list($dbconn) = GetDBconn();
               
                    if (empty($_REQUEST[$this->frmPrefijo."Adicionales"])) {
                         $observacion=$_REQUEST[$this->frmPrefijo."CtlAdicionalesObs"];
                    }
                    else {
                         $datos=$_REQUEST[$this->frmPrefijo."Adicionales"];
                         $observacion=$datos["observaciones"];
                    }
                    $href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."InsertCtrlAdicionales"));
                    $this->salida .="<form name='".$this->frmPrefijo."Adicionales' action=\"".$href."\" method='POST'>";
                    $this->salida .= "	<table width='100%' align='justify' border='1' class='hc_table_submodulo_list'>";
                    $this->salida .= "		<tr>\n";
                    $this->salida .= "			<td width='80%' class='hc_table_submodulo_list_title'>CONTROLES ADICIONALES</td>\n";
                    $this->salida .= "		</tr>\n";
                    $this->salida .= $this->SetStyle("MensajeError",'',1);
                    $this->salida .= "		<tr>\n";
                    $this->salida .= "			<td align='center'>\n";
                    $this->salida .= "					<table width='99%' border='1' class='hc_table_submodulo_list'>\n";
                    $this->salida .= "						<tr ".$this->Lista(1)."'>\n";
                    $this->salida .= "							<td width='100%' class='".$this->SetStyle($this->frmPrefijo."observaciones",'',2)."' align='center'>Observación</td>\n";
                    $this->salida .= "						</tr>\n";
                    $this->salida .= "						<tr ".$this->Lista(2)."'>\n";
                    $this->salida .= "							<td width='50%' align='center'><textarea class='textarea' name='".$this->frmPrefijo."CtlAdicionalesObs' cols='85' rows='6'>".$observacion."</textarea><br><br></td>\n";
                    $this->salida .= "						</tr>\n";
                    $this->salida .= "					</table>\n";
                    $this->salida .= "			<br><br><input class='input-submit' type='submit' name='".$pj."Save' value='GUARDAR'><br><br>\n";
                    $this->salida .= "			</td>\n";
                    $this->salida .= "		</tr>\n";
                    $this->salida .= "	</table>\n";
                    $this->salida .= "	</form>\n";
               
                    $href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
                    $this->salida .= "<form action=\"$href\" name=\"Volver\" method=\"post\">\n";
                    $this->salida .= "<br><br>\n";
                    $this->salida .= "<div align='center' class='normal_10'><input class='input-submit' type='submit' name='".$this->frmPrefijo."Volver' value='Volver'></div>\n";
                    $this->salida .= "</form><br>\n";
                    return true;
               break;
               
               case $this->frmPrefijo."InsertCtrlAdicionales":
                    $observaciones=$_REQUEST[$this->frmPrefijo."CtlAdicionalesObs"];

                    if (empty($observaciones)){
                         $observaciones="Controles Adicionales";
                    }

                    if (!$this->InsertCtrlAdicionales($observaciones))
                         return false;
                    return true;
               break;               
               
               case $this->frmPrefijo."EditCtrlAdicionales":
                    if (!$this->EditCtrlAdicionales())
                         return false;
                    return true;
               break;

               case $this->frmPrefijo."DelCtrlAdicionales":
                    if (!$this->DelCtrlAdicionales())
                         return false;
                    return true;
               break;
               
               case $this->frmPrefijo."AddCtrlDrenajes":
                    list($dbconn) = GetDBconn();
               
                    if (empty($_REQUEST[$this->frmPrefijo."Drenajes"])) {
                         $observacion = $_REQUEST[$this->frmPrefijo."CtlDrenajesObs"];
                    }
                    else {
                         $datos = $_REQUEST[$this->frmPrefijo."Drenajes"];
                         $observacion = $datos["observaciones"];
                         $_REQUEST[$this->frmPrefijo."tipodrenaje"] = $datos["tipo_drenaje"];
                    }
                    $href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."InsertCtrlDrenajes"));
                    $this->salida .="<form name='".$this->frmPrefijo."Drenaje' action=\"".$href."\" method='POST'>";
                    $this->salida .= "	<table width='100%' align='justify' border='1' class='hc_table_submodulo_list'>";
                    $this->salida .= "		<tr>\n";
                    $this->salida .= "			<td width='80%' class='hc_table_submodulo_list_title'>CONTROL DE DRENAJES</td>\n";
                    $this->salida .= "		</tr>\n";
                    $this->salida .= $this->SetStyle("MensajeError",'',1);
                    $this->salida .= "		<tr>\n";
                    $this->salida .= "			<td align='center'>\n";
                    $this->salida .= "					<table width='100%' border='1' class='hc_table_submodulo_list'>\n";
                    $this->salida .= "						<tr ".$this->Lista(1)."'>\n";
                    $this->salida .= "							<td width='50%' class='".$this->SetStyle($this->frmPrefijo."observaciones",'',2)."' align='center'>Tipo de Drenaje</td>\n";
                    $this->salida .= "							<td width='50%' class='".$this->SetStyle($this->frmPrefijo."observaciones",'',2)."' align='center'>Observación</td>\n";                    
                    $this->salida .= "						</tr>\n";
                    $this->salida .= "						<tr ".$this->Lista(2)."'>\n";
                    $this->salida .= "							<td width='50%' align='center'>\n";
				
                    $drenajes = $this->GetControlTipoDrenajes();
                    
	               $this->salida .= "&nbsp;&nbsp;&nbsp;<select name=\"".$this->frmPrefijo."tipodrenaje\" class=\"select\">";
                    $this->GetTiposDrenajes($drenajes,$_REQUEST[$this->frmPrefijo.'tipodrenaje']);
                    $this->salida .= "</select>";                   
                    $this->salida .= "							</td>";
                    
                    $this->salida .= "							<td width='50%' align='center'><textarea class='textarea' name='".$this->frmPrefijo."CtlDrenajesObs' cols='50' rows='4'>".$observacion."</textarea><br><br></td>\n";                    
                    $this->salida .= "						</tr>\n";
                    $this->salida .= "					</table>\n";
                    $this->salida .= "			<br><br><input class='input-submit' type='submit' name='".$pj."Save' value='GUARDAR'><br><br>\n";
                    $this->salida .= "			</td>\n";
                    $this->salida .= "		</tr>\n";
                    $this->salida .= "	</table>\n";
                    $this->salida .= "	</form>\n";
               
                    $href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
                    $this->salida .= "<form action=\"$href\" name=\"Volver\" method=\"post\">\n";
                    $this->salida .= "<br><br>\n";
                    $this->salida .= "<div align='center' class='normal_10'><input class='input-submit' type='submit' name='".$this->frmPrefijo."Volver' value='Volver'></div>\n";
                    $this->salida .= "</form><br>\n";
                    return true;
               break;
               
               case $this->frmPrefijo."InsertCtrlDrenajes":
                    $observaciones = $_REQUEST[$this->frmPrefijo."CtlDrenajesObs"];
				$tipo_drenaje = $_REQUEST[$this->frmPrefijo."tipodrenaje"];
                    
                    if (empty($observaciones)){
                         $observaciones="Control de Drenajes";
                    }

                    if (!$this->InsertCtrlDrenajes($observaciones, $tipo_drenaje))
                         return false;
                    return true;
               break;               
               
               case $this->frmPrefijo."EditCtrlDrenajes":
                    if (!$this->EditCtrlDrenajes())
                         return false;
                    return true;
               break;

               case $this->frmPrefijo."DelCtrlDrenajes":
                    if (!$this->DelCtrlDrenajes())
                         return false;
                    return true;
               break;
               
               //Consulta de los liquidos administrados y eliminados de la persona..
			case $this->frmPrefijo."ConsCtrlLiquidos":
                         $hora_inicio_turno = ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
                         $rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
                         list($hh,$mm, $ss) = explode(" ",$hora_inicio_turno);
          
                         if($datosAlternos)
                         {//viene desde la forma de RESUMEN ACUMULADOS y se muestran los liquidos del turno de una fecha espec&iacute;fica
                              list($yy,$mm,$dd) = explode("-",$datosAlternos[fecha]);
                              //OJO!! si el turno empieza a las 2004-01-30 08:00:00 => nextDay = 2004-01-31 07:59:59'
                              $NextDay = date("Y-m-d H:i:s", mktime(date(($hh)), date(($mm)-1), date(($ss)-1), date($mm),(date($dd)+1),date($yy)));
                              $vLiquidoA = $this->GetLiquidosAdministrados($this->ingreso,date("$datosAlternos[fecha] $hora_inicio_turno"),$NextDay);
                         }
                         else
                         {//se muestran los liquidos del turno actual
                              //OJO!! si el turno empieza a las 2004-01-30 08:00:00 => nextDay = 2004-01-31 07:59:59'
                              $NextDay = date("Y-m-d H:i:s", mktime(date(($hh)), date(($mm)), date(($ss)-1), date("m"),(date("d")+1),date("Y")));
                              $vLiquidoA = $this->GetLiquidosAdministrados($this->ingreso,date("Y-m-d $hora_inicio_turno"),$NextDay);
                         }
          
          
                         if(!empty($vLiquidoA))
                         {
                              $this->salida .= " <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
                              $this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
                              $this->salida .= "		<td colspan='3'>LIQUIDOS ADMINISTRADOS</td>\n";
                              $this->salida .= "	</tr>\n";
                              $this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
                              $this->salida .= "		<td>FECHA</td>\n";
                              $this->salida .= "		<td>LIQUIDO</td>\n";
                              $this->salida .= "		<td>CANTIDAD</td>\n";
                              $this->salida .= "	</tr>\n";
                              $this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
                              foreach($vLiquidoA as $key => $value)
                              {
                                   $colspan = sizeof($value);
                                   foreach($value as $A => $B)
                                   {
                                        if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                                        $this->salida .= "	<tr class=".$estilo.">\n";
                                        if($colspan == sizeof($value))
                                        {
                                             if($B[fechas] == date("Y-m-d")) {
                                                  $fecha = "HOY";
                                             }
                                             elseif($B[fechas] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
                                                  $fecha = "AYER ";
                                             }
                                             elseif($B[fechas] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")+1), date("Y")))){
                                                  $fecha = "MAÑANA ";
                                             }
                                             else {
                                                  $fecha = $B[fechas];
                                             }
                                             $this->salida .= "		<td align='center' rowspan=".$colspan." width='20%'>".$fecha." ".date("H:i:s",mktime($key,0,0,date("m"),date("d"),date("Y")))."</td>\n";
                                        }
                                        $this->salida .= "		<td>".$B[descripcion]."</td>\n";
                                        $this->salida .= "		<td align='center'>".$B[sumas]."</td>\n";
                                        $this->salida .= "	</tr>\n";
                                        $colspan--;
                                        $TotalAdmin +=$B[sumas] ;
                                   }
                                   $i++;
                              }
                              $this->salida .= "<tr align='center' class='modulo_table_title'><td colspan='2' align='center'>TOTAL LIQUIDOS ADMINISTRADOS</td><td align='center'>".number_format($TotalAdmin,2,',','.')."</td></tr>\n";
                              $this->salida .= "<tr align='center' class='modulo_table_title'><td colspan='2' align='center'>TOTAL AGUA END&Oacute;GENA</td><td align='center'>".number_format(($TotalAdmin+(5*$this->GetPesoPaciente($this->ingreso))),2,',','.')."</td></tr>\n";
                              $this->salida .= "</table><br>\n";
          
                         }
                         
                         $hora_inicio_turno = ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
                         $rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
                         list($hh,$mms, $ss) = explode(" ",$hora_inicio_turno);
          
                         if($datosAlternos)
                         {//viene desde la forma de RESUMEN ACUMULADOS y se muestran los liquidos del turno de una fecha espec&iacute;fica
                              list($yy,$mm,$dd) = explode("-",$datosAlternos[fecha]);
                              $NextDay = date("Y-m-d H:i:s", mktime(date($hh), date($mms)-1, date($ss), date($mm),(date($dd)+1),date($yy)));
                              $vLiquido = $this->GetLiquidosEliminados($this->ingreso,date("$datosAlternos[fecha] $hora_inicio_turno"),$NextDay);
                         }
                         else
                         {//se muestran los liquidos del turno actual
                              $NextDay = date("Y-m-d H:i:s", mktime(date($hh), date($mms)-1, date($ss), date("m"),(date("d")+1),date("Y")));
                              $vLiquido = $this->GetLiquidosEliminados($this->ingreso,date("Y-m-d $hora_inicio_turno"),$NextDay);
                         }
          
                         if(!empty($vLiquido))
                         {
                              $this->salida .= " <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
                              $this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
                              $this->salida .= "		<td colspan='3'>LIQUIDOS ELIMINADOS</td>\n";
                              $this->salida .= "	</tr>\n";
          
                              $this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
                              $this->salida .= "		<td>FECHA</td>\n";
                              $this->salida .= "		<td>LIQUIDO</td>\n";
                              $this->salida .= "		<td>CANTIDAD</td>\n";
                              $this->salida .= "	</tr>\n";
                              $this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
          
                              foreach($vLiquido as $key => $value)
                              {
                                   $colspan = sizeof($value);
                                   foreach($value as $A => $B)
                                   { 
                                        if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                                        $this->salida .= "	<tr class=".$estilo.">\n";
                                        if($colspan == sizeof($value))
                                        {
                                             if($B[fechas] == date("Y-m-d")) {
                                                  $fecha = "HOY";
                                             }
                                             elseif($B[fechas] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
                                                  $fecha = "AYER ";
                                             }
                                             elseif($B[fechas] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")+1), date("Y")))){
                                                  $fecha = "MAÑANA ";
                                             }
                                             else {
                                                  $fecha = $B[fechas];
                                             }
                                             $this->salida .= "		<td align='center' rowspan=".$colspan." width='20%'>".$fecha." ".date("H:i:s",mktime($key,0,0,date("m"),date("d"),date("Y")))."</td>\n";
                                        }
                                        $this->salida .= "		<td>".$B[descripcion]."</td>\n";
          
                                        if($B[sumas] != 0.00){//ojo, luego cambiar la condicion por if campo tipo_liquido_eliminado.deposicion == 0
                                             $this->salida .= "		<td align='center'>".$B[sumas]."</td>\n";
                                        }
                                        else{
                                             $this->salida .= "		<td align='center'>".$B[deposicion]."</td>\n";
                                        }
                                        $this->salida .= "	</tr>\n";
                                        $colspan--;
                                        $TotalElim+=$B[sumas];
                                   }
                                   $i++;
                              }
                              $this->salida .= "<tr class='modulo_table_title'><td colspan='2' align='center'>TOTAL LIQUIDOS ELIMINADOS</td><td align='center'>".number_format($TotalElim,2,',','.')."</td></tr>\n";
                              $this->salida .= "<tr align='center' class='modulo_table_title'><td colspan='2' align='center'>TOTAL P&Eacute;RDIDA INSENSIBLE</td><td align='center'>".number_format(($TotalElim+(14*$this->GetPesoPaciente($this->ingreso))),2,',','.')."</td></tr>\n";
                              $href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>""));
                              $this->salida .= "</table>\n";
          
                         }
                         elseif(empty($vLiquidoA) AND empty($vLiquido))
                         {
                              $mensaje = "NO SE ENCONTRARON REGISTROS DE LIQUIDOS ELIMINADOS";
                              $this->salida.= "<br><br><br><table width='100%' border='0'>";
                              $this->salida.= "		<tr>\n";
                              $this->salida.= "			<td align='center' class='label_mark'>$mensaje.</td>\n";
                              $this->salida.= "		</tr>\n";
                              $href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>""));
                              $this->salida.= "		<tr>\n";
                              $this->salida.= "			<td align='center' class='normal_10'><br><a href='$href'>Volver Controles Paciente</a></td>\n";
                              $this->salida.= "		</tr>\n";
                              $this->salida.= "</table><br><br>\n\n";
                         }
          
                         if(!empty($vLiquidoA) OR !empty($vLiquido))
                         {
                              $this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
                              $this->salida.= "		<tr>\n";
                              $this->salida.= "			<td align='center' class='normal_10'><br><a href='$href'>Volver Controles Paciente</a></td>\n";
                              $this->salida.= "		</tr>\n";
                              $this->salida .= "</table>\n";
                         }
				break;
				//caso de consulta neurologica, los resultados de los controles
				case $this->frmPrefijo."ConsCtrlNeurologica":

				$limit = 4; $offset = 0;
				$VectorControl = $this->Listar_ControlesNeurologicos($this->ingreso);
			if(!$VectorControl)
			{
				$mensaje = "NO SE ENCONTRARON REGISTROS DE CONTROLES NEUROLOGICOS DEL PACIENTE";
				$this->salida.= "<br><br><br><table width='100%' border='0'>";
				$this->salida.= "		<tr>\n";
				$this->salida.= "			<td align='center' class='label_mark'>$mensaje.</td>\n";
				$this->salida.= "		</tr>\n";
               	$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>""));
				$this->salida.= "		<tr>\n";
				$this->salida.= "			<td align='center' class='normal_10'><br><a href='$href'>Volver Controles Paciente</a></td>\n";
				$this->salida.= "		</tr>\n";
				$this->salida.= "</table><br><br>\n\n";
			}
			else
			{
				$this->ShowControl_Neurologico($VectorControl);
			}
               break;
               
               default:
                    $salida=$this->frmConsulta();
                    $salida=$this->frmReporte();							
                    if ($salida===false){
                         return false;
                    }
                    if(empty($this->titulo))
                    {
                         $this->salida = ThemeAbrirTabla("ORDENES MEDICAS");
                    }
                    else
                    {
                         $this->salida  = ThemeAbrirTabla($this->titulo);
                    }

                    $this->salida.= "<table width='100%' border='0' class='module_table_list'>";
                    $this->salida.= "<tr>\n";
                    $this->salida.= "<td align='center'>\n";
                    $this->salida.= $salida;
                    $this->salida.= "</td>\n";
                    $this->salida.= "</tr>";
                    $this->salida.= "</table>\n\n";
                    $this->salida.= ThemeCerrarTabla();
                    return true;
			}
		}
     
          //funcion de tizziano para revisar las pasadas hojas neurologicas.
          // los resumen de hojas neurologicas.
          function ShowControl_Neurologico($VectorControl)
          {
               $accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Listar_ControlesNeurologicos'));
               $this->salida.= "<form name=\"neuro$pfj\" action=\"$accionI\" method=\"post\">";
     
               if (empty($contador)){
                    $contador=sizeof($VectorControl);
               }
          
               $this->salida .="<br><table align=\"center\" width=\"100%\" border='0'>";
               $this->salida .="<tr class=\"modulo_table_list_title\">";
               $this->salida .="<td rowspan='2'>FECHA</td>";
               $this->salida .="<td rowspan='2'>HORA</td>";
               $this->salida .="<td colspan='2'>PUPILA DERECHA</td>";
               $this->salida .="<td colspan='2'>PUPILA IZQUIDA.</td>";
               $this->salida .="<td rowspan='2'>CONCIENCIA</td>";
               $this->salida .="<td colspan='4'> FUERZA </td>";
               $this->salida .="<td colspan='4'> ESCALA DE GLASGOW </td>";
               $this->salida .="<td rowspan='2'>USUARIO</td>";
               $this->salida .="</tr>";
               $this->salida .="<tr class='hc_table_submodulo_list_title'>";
               $this->salida .="<td align=\"center\"> TALLA </td>";
               $this->salida .="<td align=\"center\"> REACCION</td>";
               $this->salida .="<td align=\"center\"> TALLA </td>";
               $this->salida .="<td align=\"center\"> REACCION </td>";
               $this->salida .="<td align=\"center\"> B. DER. </td>";
               $this->salida .="<td align=\"center\"> B. IZQ. </td>";
               $this->salida .="<td align=\"center\"> P. DER. </td>";
               $this->salida .="<td align=\"center\"> P. IZQ. </td>";
               $this->salida .="<td align=\"center\"> A. OCULAR </td>";
               $this->salida .="<td align=\"center\"> R. VERBAL </td>";
               $this->salida .="<td align=\"center\"> R. MOTORA </td>";
               $this->salida .="<td align=\"center\"> E.G. </td>";
               $this->salida .="</tr>";
               $cont=1;
               $spy=0;
               while ($cont <= sizeof($VectorControl) && $cont <= $contador)
               {
                    list($fecha,$hora) = explode(" ",$VectorControl[$cont-1][fecha]);
                    list($ano,$mes,$dia) = explode("-",$fecha);
                    list($hora,$min) = explode(":",$hora);
                    $hora=$hora.":".$min;
                    //$this->salida .= "<tr align='center'>\n";
                    if($fecha == date("Y-m-d"))
                    {
                         $fecha = "HOY";
                    }
                    elseif($fecha == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y"))))
                    {
                         $fecha = "AYER";
                    }
                    else
                    {
                         $fecha = $fecha;
                    }
          
                    if($spy==0)
                    {
                         $this->salida.="<tr class=\"modulo_list_oscuro\">";
                         $spy=1;
                    }
                    else
                    {
                         $this->salida.="<tr class=\"modulo_list_claro\">";
                         $spy=0;
                    }
          
                    if($VectorControl[$cont-1][pupila_talla_d] == 0) $ptallad = "--"; else $ptallad = $VectorControl[$cont-1][pupila_talla_d];
                    if($VectorControl[$cont-1][pupila_reaccion_d] == ' ') $preacciond = "--"; else $preacciond = $VectorControl[$cont-1][pupila_reaccion_d];
                    if($VectorControl[$cont-1][pupila_talla_i] == 0) $ptallai = "--"; else $ptallai = $VectorControl[$cont-1][pupila_talla_i];
                    if($VectorControl[$cont-1][pupila_reaccion_i] == ' ') $preaccioni = "--"; else $preaccioni = $VectorControl[$cont-1][pupila_reaccion_i];
                    if($VectorControl[$cont-1][descripcion] == ' ') $conciencia = "--"; else $conciencia = $VectorControl[$cont-1][descripcion];
                    if($VectorControl[$cont-1][fuerza_brazo_d] == ' ') $brazod = "--"; else $brazod = $VectorControl[$cont-1][fuerza_brazo_d];
                    if($VectorControl[$cont-1][fuerza_brazo_i] == ' ') $brazoi = "--"; else $brazoi = $VectorControl[$cont-1][fuerza_brazo_i];
                    if($VectorControl[$cont-1][fuerza_pierna_d] == ' ') $piernad = "--"; else $piernad = $VectorControl[$cont-1][fuerza_pierna_d];
                    if($VectorControl[$cont-1][fuerza_pierna_i] == ' ') $piernai = "--"; else $piernai = $VectorControl[$cont-1][fuerza_pierna_i];
                    if($VectorControl[$cont-1][tipo_apertura_ocular_id] == 0 ) $AO = "--"; else $AO = $VectorControl[$cont-1][tipo_apertura_ocular_id];
                    if($VectorControl[$cont-1][tipo_respuesta_verbal_id] == 0 ) $RV = "--"; else $RV = $VectorControl[$cont-1][tipo_respuesta_verbal_id];
                    if($VectorControl[$cont-1][tipo_respuesta_motora_id] == 0 ) $RM = "--"; else $RM = $VectorControl[$cont-1][tipo_respuesta_motora_id];
                    if($VectorControl[$cont-1][usuario] == ' ') $user = "--"; else $user = $VectorControl[$cont-1][usuario];
                    $EG = $AO + $RV + $RM;
                    if($EG == 0) $EG = "--"; else $EG = $EG;
          
                    $this->salida .="<td align=\"center\">" .$fecha. "</td>";
                    $this->salida .="<td align=\"center\">" .$hora. "</td>";
                    $this->salida .="<td align=\"center\">" .$ptallad. "</td>";
                    $this->salida .="<td align=\"center\">" .$preacciond. "</td>";
                    $this->salida .="<td align=\"center\">" .$ptallai. "</td>";
                    $this->salida .="<td align=\"center\">" .$preaccioni. "</td>";
                    $this->salida .="<td align=\"center\">" .$conciencia. "</td>";
                    $this->salida .="<td align=\"center\">" .$brazod. "</td>";
                    $this->salida .="<td align=\"center\">" .$brazoi. "</td>";
                    $this->salida .="<td align=\"center\">" .$piernad. "</td>";
                    $this->salida .="<td align=\"center\">" .$piernai. "</td>";
                    $this->salida .="<td align=\"center\">" .$AO. "</td>";
                    $this->salida .="<td align=\"center\">" .$RV. "</td>";
                    $this->salida .="<td align=\"center\">" .$RM. "</td>";
                    if ($EG < 8)
                    {
                         $this->salida .="<td align=\"center\" class ='GlasgowBajo'>" .$EG. "</td>";
                    }
     
                    if ($EG >= 8 && $EG < 12)
                    {
                         $this->salida .="<td align=\"center\" class ='GlasgowIntermedio'>" .$EG. "</td>";
                    }
     
                    if ($EG >= 12)
                    {
                         $this->salida .="<td align=\"center\" class ='GlasgowAlto'>" .$EG. "</td>";
                    }
     
                    $fechareg =$VectorControl[$cont-1][fecha_registro];
                    $user=$this->GetDatosUsuarioSistema($VectorControl[$cont-1][usuario_id]);
                    $this->salida .="<td align=\"center\">" .$user[0][usuario]. "</td>";
                    $this->salida .="</tr>";
                    $cont++;
               }
     
               $this->salida .="</table>";
               $this->salida .="<table align=\"center\">";
               $href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>""));
               $this->salida.= "		<tr>\n";
               $this->salida.= "			<td align='center' class='normal_10'><br><a href='$href'>Volver Controles Paciente</a></td>\n";
               $this->salida.= "		</tr>\n";
               $this->salida .="</table>";
               $this->salida .= "</form>";
               return true;
          }


          /**
          *		function FrmResumenGlucometria => Se encarga de armar la vista (HTML) del control de glucometria para realizar las graficas
          *
          *		Llama al metodo GetResumenGlucometria() El cual se encarga de traer los datos del control de glucometria del paciente
          *		@Author Rosa Maria Angel.
          *		@access Private
          *		@return string
          *
          */
		function FrmResumenGlucometria($ingreso,$hora_inicio_turno,$rango_turno,$fecha,$rango_max,$rango_min,$completo=false)
		{
			$cont=0;
			$Resumen = $this->GetResumenGlucometria($ingreso,$hora_inicio_turno,$rango_turno,$fecha);
			if(!$Resumen){
				return false;
			}
			else
			{
				if ($completo){

					$this->salida .= "<table width='100%' border='0' class='tabla'>\n";
					$this->salida .= "	<tr>\n";
					$this->salida .= "		<td width=\"100%\">\n";
					$this->salida .= "			<table width='100%' border='0' class='modulo_table_list' align='center'>\n";
					$this->salida .= "				<tr class=\"modulo_table_list_title\" align='center'>\n";
					$this->salida .= "					<td rowspan='2'>FECHA</td>\n";
					$this->salida .= "					<td rowspan='2'>GLUCOMETRIA</td>\n";
					$this->salida .= "					<td colspan='2'>INSULINA CRISTALINA</td>\n";
					$this->salida .= "					<td colspan='2'>INSULINA NHP</td>\n";
					$this->salida .= "				</tr>\n";
					$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "					<td width='13%'>CANTIDAD</td>\n";
					$this->salida .= "					<td width='13%' >VIA</td>\n";
					$this->salida .= "					<td width='13%'>CANTIDAD</td>\n";
					$this->salida .= "					<td width='13%'>VIA</td>\n";
					$this->salida .= "				</tr>\n";

					foreach($Resumen as $key => $valor)
					{
						foreach($valor as $key1 => $value)
						{
							if(!empty($value[glucometria]))			{ $gluco = number_format($value[glucometria], 0, ',', '.');} else { $gluco = "--"; }
							if(!empty($value[valor_cristalina]))	{ $valCristalina = number_format($value[valor_cristalina], 0, ',', '');} else { $valCristalina = "--"; }
							if(!empty($value[valor_nph]))				{ $valNPH = number_format($value[valor_nph], 0, ',', '');} else { $valNPH = "--"; }
							if(!empty($value[via_cristalina]))		{ $via_cristalina = $value[viacristalina];} else { $via_cristalina = "--"; }
							if(!empty($value[via_nph]))					{ $via_nph = $value[vianph];} else { $via_nph = "--"; }

							$this->salida .= "				<tr ".$this->Lista($cont)." align='center'>\n";
							list($date,$time) = explode (" ",$key);
							if($date == date("Y-m-d")) {
								$fecha = "HOY ".$time;
							}
							elseif($date == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
								$fecha = "AYER ".$time;
							}
							else{
								$fecha = $key;
							}
							$this->salida .= "					<td>".$fecha."</td>\n";//number_format($vc[$j][3], 2, ',', '.')
							if($gluco >= $Rangos[rango_max] || $gluco<= $Rangos[rango_min]){
								$estilo = "alerta";
							}
							else{
								$estilo = "";
							}
							$this->salida .= "					<td class='$estilo' >".$gluco."</td>\n";
							$this->salida .= "					<td>".$valCristalina."</td>\n";
							$this->salida .= "					<td>".$via_cristalina."</td>\n";
							$this->salida .= "					<td>".$valNPH."</td>\n";
							$this->salida .= "					<td>".$via_nph."</td>\n";
							$this->salida .= "				</tr>\n";
							$cont++;
						}
					}
				}//Fin if
				else{
					$this->salida .= "<table width='100%' border='0' class='tabla'>\n";
					$this->salida .= "	<tr>\n";
					$this->salida .= "		<td width=\"100%\">\n";
					$this->salida .= "			<table width='100%' border='0' class='modulo_table_list' align='center'>\n";
					$this->salida .= "				<tr class=\"modulo_table_list_title\" align='center'>\n";
					$this->salida .= "					<td rowspan='2'>FECHA</td>\n";
					$this->salida .= "					<td rowspan='2'>GLUCOMETRIA</td>\n";
					$this->salida .= "					<td colspan='2'>INSULINA CRISTALINA</td>\n";
					$this->salida .= "					<td colspan='2'>INSULINA NHP</td>\n";
					$this->salida .= "				</tr>\n";
					$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "					<td width='13%'>CANTIDAD</td>\n";
					$this->salida .= "					<td width='13%' >VIA</td>\n";
					$this->salida .= "					<td width='13%'>CANTIDAD</td>\n";
					$this->salida .= "					<td width='13%'>VIA</td>\n";
					$this->salida .= "				</tr>\n";

					foreach($Resumen as $key => $value)
					{
						if(!empty($value[0][glucometria]))			{ $gluco = number_format($value[0][glucometria], 0, ',', '.');} else { $gluco = "--"; }
						if(!empty($value[0][valor_cristalina]))	{ $valCristalina = number_format($value[0][valor_cristalina], 0, ',', '');} else { $valCristalina = "--"; }
						if(!empty($value[0][valor_nph]))				{ $valNPH = number_format($value[0][valor_nph], 0, ',', '');} else { $valNPH = "--"; }
						if(!empty($value[0][via_cristalina]))		{ $via_cristalina = $value[0][viacristalina];} else { $via_cristalina = "--"; }
						if(!empty($value[0][via_nph]))					{ $via_nph = $value[0][vianph];} else { $via_nph = "--"; }

						$this->salida .= "				<tr ".$this->Lista($cont)." align='center'>\n";
						list($date,$time) = explode (" ",$key);
						if($date == date("Y-m-d")) {
							$fecha = "HOY ".$time;
						}
						elseif($date == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
							$fecha = "AYER ".$time;
						}
						else{
							$fecha = $key;
						}
						$this->salida .= "					<td>".$fecha."</td>\n";//number_format($vc[$j][3], 2, ',', '.')
						if($gluco >= $Rangos[rango_max] || $gluco<= $Rangos[rango_min]){
							$estilo = "alerta";
						}
						else{
							$estilo = "";
						}
						$this->salida .= "					<td class='$estilo' >".$gluco."</td>\n";
						$this->salida .= "					<td>".$valCristalina."</td>\n";
						$this->salida .= "					<td>".$via_cristalina."</td>\n";
						$this->salida .= "					<td>".$valNPH."</td>\n";
						$this->salida .= "					<td>".$via_nph."</td>\n";
						$this->salida .= "				</tr>\n";
						$cont++;
					}
				}
				$this->salida .= "			</table>\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "</table>\n";
			}
			return true;
		}//FrmResumenGlucometria



	/**
	*		function FrmResumenCurvaTermica => Se encarga de armar la vista (HTML) del control de curva termica para realizar las graficas
	*
	*		Llama al metodo GetResumenCurvaTermica() El cual se encarga de traer los datos del control de curva termica del paciente
	*		@Author Rosa Maria Angel.
	*		@access Private
	*		@return string
	*/
		function FrmResumenCurvaTermica($ingreso,$hora_inicio_turno,$rango_turno,$fecha,$rango_max,$rango_min,$completo=false)
		{
			$cont=0;
			$Resumen = $this->GetResumenCurvaTermica($ingreso,$hora_inicio_turno,$rango_turno,$fecha);
			if(!$Resumen){
				return false;
			}
			else
			{
				if ($completo){
					$this->salida .= "<table width='100%' border='0' class='tabla'>\n";
					$this->salida .= "	<tr>\n";
					$this->salida .= "		<td width=\"100%\">\n";
					$this->salida .= "			<table width='100%' border='0' class='modulo_table_list'>\n";
					$this->salida .= "				<tr class=\"modulo_table_title\"><td colspan='2'>RESUMEN CONTROL DE CURVA TERMICA</td></tr>\n";
					$this->salida .= "				<tr class=\"modulo_table_list_title\" align='center'>\n";
					$this->salida .= "					<td>FECHA</td>\n";
					$this->salida .= "					<td>MEDIA TEMPERATURA</td>\n";
					$this->salida .= "				</tr>\n";
					foreach($Resumen as $key => $vect)
					{
						for ($i=0;$i<sizeof($vect);$i++){
							$value=$vect[$i];
							if(!empty($value['temp_piel'])){ $temp = number_format($value['temp_piel'], 1, '.', '.');} else { $temp = "--"; }

							$media=sscanf (number_format($value['media'], 2, '.', ','),"%f");

							$this->salida .= "				<tr ".$this->Lista($cont)." align='center'>\n";
							list($date,$time) = explode (" ",$key);
							if($date == date("Y-m-d")) {
								$fecha = "HOY ".$time;
							}
							elseif($date == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
								$fecha = "AYER ".$time;
							}
							else{
								$fecha = $key;
							}

							if (!$i){
								$this->salida .= "					<td rowspan='".sizeof($vect)."'>".$fecha."</td>\n";//number_format($vc[$j][3], 2, ',', '.')
								if ($media[0] >= $rango_max || $media[0] <= $rango_min){
									$this->salida .= "					<td rowspan='".sizeof($vect)."' class='rango_max_min'>".$media[0]."</td>\n";
								}
								else{
									$this->salida .= "					<td rowspan='".sizeof($vect)."'>".$media[0]."</td>\n";
								}
								$this->salida .= "				</tr>\n";
							}
						}//Fin for
					}//Fin foreach
				}//Fin if
				else{
					$this->salida .= "<table width='100%' border='0' class='tabla'>\n";
					$this->salida .= "	<tr>\n";
					$this->salida .= "		<td width=\"100%\">\n";
					$this->salida .= "			<table width='100%' border='0' class='modulo_table_list'>\n";
					$this->salida .= "				<tr class=\"modulo_table_title\"><td colspan='2'>RESUMEN CONTROL DE CURVA TERMICA</td></tr>\n";
					$this->salida .= "				<tr class=\"modulo_table_list_title\" align='center'>\n";
					$this->salida .= "					<td>FECHA</td>\n";
					$this->salida .= "					<td>TEMPERATURA</td>\n";
					$this->salida .= "				</tr>\n";
					foreach($Resumen as $key => $value)
					{
						if(!empty($value[0]['temp_piel'])){ $temp = number_format($value[0]['temp_piel'], 1, '.', ',');} else { $temp = "--"; }

						$this->salida .= "				<tr ".$this->Lista($cont)." align='center'>\n";
						list($date,$time) = explode (" ",$key);
						if($date == date("Y-m-d")) {
							$fecha = "HOY ".$time;
						}
						elseif($date == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
							$fecha = "AYER ".$time;
						}
						else{
							$fecha = $key;
						}
						$this->salida .= "					<td>".$fecha."</td>\n";//number_format($vc[$j][3], 2, ',', '.')
						if ($temp >= $rango_max || $temp <= $rango_min){
							$this->salida .= "					<td class='rango_max_min'>".$temp."</td>\n";
						}
						else{
							$this->salida .= "					<td>".$temp."</td>\n";
						}
						$this->salida .= "				</tr>\n";
						$cont++;
					}
				}
				$this->salida .= "			</table>\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "</table>\n";
			}
			return true;
		}//FrmResumenCurva Termica



	/**
	*		function FrmResumenFrecuenciaCardiaca => Se encarga de armar la vista (HTML) del control de frecuencia cardiaca para realizar las graficas
	*
	*		Llama al metodo GetResumenFrecuenciaCardiaca() El cual se encarga de traer los datos del control de frecuencia cardiaca del paciente
	*		@Author Rosa Maria Angel.
	*		@access Private
	*		@return string
	*/
		function FrmResumenFrecuenciaCardiaca($ingreso,$hora_inicio_turno,$rango_turno,$fecha,$rango_max,$rango_min,$completo=false)
		{
			$cont=0;
			$Resumen = $this->GetResumenFrecuenciaCardiaca($ingreso,$hora_inicio_turno,$rango_turno,$fecha);
			if(!$Resumen){
				return false;
			}
			else
			{
				if ($completo){
					$this->salida .= "<table width='100%' border='0' class='tabla'>\n";
					$this->salida .= "	<tr>\n";
					$this->salida .= "		<td width=\"100%\">\n";
					$this->salida .= "			<table width='100%' border='0' class='modulo_table_list'>\n";
					$this->salida .= "				<tr class=\"modulo_table_title\"><td colspan='2'>RESUMEN CONTROL DE FRECUENCIA CARDIACA</td></tr>\n";
					$this->salida .= "				<tr class=\"modulo_table_list_title\" align='center'>\n";
					$this->salida .= "					<td>FECHA</td>\n";
					$this->salida .= "					<td>MEDIA FRECUENCIA CARDIACA</td>\n";
					$this->salida .= "				</tr>\n";
					foreach($Resumen as $key => $vect)
					{
						for ($i=0;$i<sizeof($vect);$i++){
							$value=$vect[$i];
							if(!empty($value['fc'])){ $fc = number_format($value['fc'], 1, '.', '.');} else { $fc = "--"; }

							$media=sscanf (number_format($value['media'], 2, '.', ','),"%f");

							$this->salida .= "				<tr ".$this->Lista($cont)." align='center'>\n";
							list($date,$time) = explode (" ",$key);
							if($date == date("Y-m-d")) {
								$fecha = "HOY ".$time;
							}
							elseif($date == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
								$fecha = "AYER ".$time;
							}
							else{
								$fecha = $key;
							}

							if (!$i){
								$this->salida .= "					<td rowspan='".sizeof($vect)."'>".$fecha."</td>\n";//number_format($vc[$j][3], 2, ',', '.')
								if ($media[0] >= $rango_max || $media[0] <= $rango_min){
									$this->salida .= "					<td rowspan='".sizeof($vect)."' class='rango_max_min'>".$media[0]."</td>\n";
								}
								else{
									$this->salida .= "					<td rowspan='".sizeof($vect)."'>".$media[0]."</td>\n";
								}
								$this->salida .= "				</tr>\n";
							}
						}//Fin for
					}//Fin foreach
				}//Fin if
				else{
					$this->salida .= "<table width='100%' border='0' class='tabla'>\n";
					$this->salida .= "	<tr>\n";
					$this->salida .= "		<td width=\"100%\">\n";
					$this->salida .= "			<table width='100%' border='0' class='modulo_table_list'>\n";
					$this->salida .= "				<tr class=\"modulo_table_title\"><td colspan='2'>RESUMEN CONTROL DE FRECUENCIA CARDIACA</td></tr>\n";
					$this->salida .= "				<tr class=\"modulo_table_list_title\" align='center'>\n";
					$this->salida .= "					<td>FECHA</td>\n";
					$this->salida .= "					<td>FRECUENCIA CARDIACA</td>\n";
					$this->salida .= "				</tr>\n";
					foreach($Resumen as $key => $value)
					{
						if(!empty($value[0]['fc'])){ $fc = number_format($value[0]['fc'], 1, '.', ',');} else { $fc = "--"; }

						$this->salida .= "				<tr ".$this->Lista($cont)." align='center'>\n";
						list($date,$time) = explode (" ",$key);
						if($date == date("Y-m-d")) {
							$fecha = "HOY ".$time;
						}
						elseif($date == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
							$fecha = "AYER ".$time;
						}
						else{
							$fecha = $key;
						}
						$this->salida .= "					<td>".$fecha."</td>\n";//number_format($vc[$j][3], 2, ',', '.')
						if ($fc >= $rango_max || $fc <= $rango_min){
							$this->salida .= "					<td class='rango_max_min'>".$fc."</td>\n";
						}
						else{
							$this->salida .= "					<td>".$fc."</td>\n";
						}
						$this->salida .= "				</tr>\n";
						$cont++;
					}
				}
				$this->salida .= "			</table>\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "</table>\n";
			}
			return true;
		}//FrmResumenFC


          /**
          *		function FrmResumenFrecuenciaRespiratoria => Se encarga de armar la vista (HTML) del control de frecuencia respitatoria para realizar las graficas
          *
          *		Llama al metodo GetResumenFrecuenciaRespiratoria() El cual se encarga de traer los datos del control de frecuencia respiratoria del paciente
          *		@Author Rosa Maria Angel.
          *		@access Private
          *		@return string
          */
		function FrmResumenFrecuenciaRespiratoria($ingreso,$hora_inicio_turno,$rango_turno,$fecha,$rango_max,$rango_min,$completo=false)
		{
			$cont=0;
			$Resumen = $this->GetResumenFrecuenciaRespiratoria($ingreso,$hora_inicio_turno,$rango_turno,$fecha);
			if(!$Resumen){
				return false;
			}
			else
			{
				if ($completo){
					$this->salida .= "<table width='100%' border='0' class='tabla'>\n";
					$this->salida .= "	<tr>\n";
					$this->salida .= "		<td width=\"100%\">\n";
					$this->salida .= "			<table width='100%' border='' class='modulo_table_list'>\n";
					$this->salida .= "				<tr class=\"modulo_table_title\"><td colspan='2'>RESUMEN CONTROL DE FRECUENCIA RESPIRATORIA</td></tr>\n";
					$this->salida .= "				<tr class=\"modulo_table_list_title\" align='center'>\n";
					$this->salida .= "					<td>FECHA</td>\n";
					$this->salida .= "					<td>MEDIA FRECUENCIA RESPIRATORIA</td>\n";
					$this->salida .= "				</tr>\n";
					foreach($Resumen as $key => $vect)
					{
						for ($i=0;$i<sizeof($vect);$i++){
							$value=$vect[$i];
							if(!empty($value['fr_respiratoria'])){ $f_r = number_format($value['fr_respiratoria'], 1, '.', '.');} else { $f_r = "--"; }

							$media=sscanf (number_format($value['media'], 2, '.', ','),"%f");

							$this->salida .= "				<tr ".$this->Lista($cont)." align='center'>\n";
							list($date,$time) = explode (" ",$key);
							if($date == date("Y-m-d")) {
								$fecha = "HOY ".$time;
							}
							elseif($date == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
								$fecha = "AYER ".$time;
							}
							else{
								$fecha = $key;
							}

							if (!$i){
								$this->salida .= "					<td rowspan='".sizeof($vect)."'>".$fecha."</td>\n";//number_format($vc[$j][3], 2, ',', '.')
								if ($media[0] >= $rango_max || $media[0] <= $rango_min){
									$this->salida .= "					<td rowspan='".sizeof($vect)."' class='rango_max_min'>".$media[0]."</td>\n";
								}
								else{
									$this->salida .= "					<td rowspan='".sizeof($vect)."'>".$media[0]."</td>\n";
								}
								$this->salida .= "				</tr>\n";
							}
						}//Fin for
					}//Fin foreach
				}//Fin if
				else{
					$this->salida .= "<table width='100%' border='0' class='tabla'>\n";
					$this->salida .= "	<tr>\n";
					$this->salida .= "		<td width=\"100%\">\n";
					$this->salida .= "			<table width='100%' border='0' class='modulo_table_list'>\n";
					$this->salida .= "				<tr class=\"modulo_table_title\"><td colspan='2'>RESUMEN CONTROL DE FRECUENCIA RESPIRATORIA</td></tr>\n";
					$this->salida .= "				<tr class=\"modulo_table_list_title\" align='center'>\n";
					$this->salida .= "					<td>FECHA</td>\n";
					$this->salida .= "					<td>FRECUENCIA RESPIRATORIA</td>\n";
					$this->salida .= "				</tr>\n";
					foreach($Resumen as $key => $value)
					{
						if(!empty($value[0]['fr_respiratoria'])){ $f_r = number_format($value[0]['fr_respiratoria'], 1, '.', ',');} else { $f_r = "--"; }

						$this->salida .= "				<tr ".$this->Lista($cont)." align='center'>\n";
						list($date,$time) = explode (" ",$key);
						if($date == date("Y-m-d")) {
							$fecha = "HOY ".$time;
						}
						elseif($date == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
							$fecha = "AYER ".$time;
						}
						else{
							$fecha = $key;
						}
						$this->salida .= "					<td>".$fecha."</td>\n";//number_format($vc[$j][3], 2, ',', '.')
						if ($f_r >= $rango_max || $f_r <= $rango_min){
							$this->salida .= "					<td class='rango_max_min'>".$f_r."</td>\n";
						}
						else{
							$this->salida .= "					<td>".$f_r."</td>\n";
						}
						$this->salida .= "				</tr>\n";
						$cont++;
					}
				}
				$this->salida .= "			</table>\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "</table>\n";
			}
			return true;
		}//FrmResumenFC


          /**
          *		function FrmResumenPresionVenosaCentral => Se encarga de armar la vista (HTML) del control de Presion Venosa Central para realizar las graficas
          *
          *		Llama al metodo GetResumenPresionVenosaCentral() El cual se encarga de traer los datos del control de Presion Venosa Central del paciente
          *		@Author Rosa Maria Angel.
          *		@access Private
          *		@return string
          */
		function FrmResumenPresionVenosaCentral($ingreso,$hora_inicio_turno,$rango_turno,$fecha,$rango_max,$rango_min,$completo=false)
		{
			$cont=0;
			$Resumen = $this->GetResumenPresionVenosaCentral($ingreso,$hora_inicio_turno,$rango_turno,$fecha);
			if(!$Resumen){
				return false;
			}
			else
			{
				if ($completo){
					$this->salida .= "<table width='100%' border='0' class='tabla'>\n";
					$this->salida .= "	<tr>\n";
					$this->salida .= "		<td width=\"100%\">\n";
					$this->salida .= "			<table width='100%' border='0' class='modulo_table_list'>\n";
					$this->salida .= "				<tr class=\"modulo_table_title\"><td colspan='2'>RESUMEN CONTROL DE FRECUENCIA RESPIRATORIA</td></tr>\n";
					$this->salida .= "				<tr class=\"modulo_table_list_title\" align='center'>\n";
					$this->salida .= "					<td>FECHA</td>\n";
					$this->salida .= "					<td>MEDIA PRESION VENOSA CENTRAL</td>\n";
					$this->salida .= "				</tr>\n";
					foreach($Resumen as $key => $vect)
					{
						for ($i=0;$i<sizeof($vect);$i++){
							$value=$vect[$i];
							if(!empty($value['pvc'])){ $f_r = number_format($value['pvc'], 1, '.', '.');} else { $f_r = "--"; }

							$media=sscanf (number_format($value['media'], 2, '.', ','),"%f");

							$this->salida .= "				<tr ".$this->Lista($cont)." align='center'>\n";
							list($date,$time) = explode (" ",$key);
							if($date == date("Y-m-d")) {
								$fecha = "HOY ".$time;
							}
							elseif($date == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
								$fecha = "AYER ".$time;
							}
							else{
								$fecha = $key;
							}

							if (!$i){
								$this->salida .= "					<td rowspan='".sizeof($vect)."'>".$fecha."</td>\n";//number_format($vc[$j][3], 2, ',', '.')
								if ($media[0] >= $rango_max || $media[0] <= $rango_min){
									$this->salida .= "					<td rowspan='".sizeof($vect)."' class='rango_max_min'>".$media[0]."</td>\n";
								}
								else{
									$this->salida .= "					<td rowspan='".sizeof($vect)."'>".$media[0]."</td>\n";
								}
								$this->salida .= "				</tr>\n";
							}
						}//Fin for
					}//Fin foreach
				}//Fin if
				else{
					$this->salida .= "<table width='100%' border='0' class='tabla'>\n";
					$this->salida .= "	<tr>\n";
					$this->salida .= "		<td width=\"100%\">\n";
					$this->salida .= "			<table width='100%' border='0' class='modulo_table_list'>\n";
					$this->salida .= "				<tr class=\"modulo_table_title\"><td colspan='2'>RESUMEN CONTROL DE FRECUENCIA RESPIRATORIA</td></tr>\n";
					$this->salida .= "				<tr class=\"modulo_table_list_title\" align='center'>\n";
					$this->salida .= "					<td>FECHA</td>\n";
					$this->salida .= "					<td>PRESION VENOSA CENTRAL</td>\n";
					$this->salida .= "				</tr>\n";
					foreach($Resumen as $key => $value)
					{
						if(!empty($value[0]['pvc'])){ $pvc = number_format($value[0]['pvc'], 1, '.', ',');} else { $pvc = "--"; }

						$this->salida .= "				<tr ".$this->Lista($cont)." align='center'>\n";
						list($date,$time) = explode (" ",$key);
						if($date == date("Y-m-d")) {
							$fecha = "HOY ".$time;
						}
						elseif($date == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
							$fecha = "AYER ".$time;
						}
						else{
							$fecha = $key;
						}
						$this->salida .= "					<td>".$fecha."</td>\n";//number_format($vc[$j][3], 2, ',', '.')
						if ($pvc >= $rango_max || $pvc <= $rango_min){
							$this->salida .= "					<td class='rango_max_min'>".$pvc."</td>\n";
						}
						else{
							$this->salida .= "					<td>".$pvc."</td>\n";
						}
						$this->salida .= "				</tr>\n";
						$cont++;
					}
				}
				$this->salida .= "			</table>\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "</table>\n";
			}
			return true;
		}//FrmResumenFC

          /**
          *		function frmReporte => Imprime en modo consulta (HTML) los controles del paciente
          *
          *		@Author Arley Velásquez C.
          *		@access Private
          *		@return boolean
          */
		function frmReporte()
		{
			$ctrlPosicion=array();
			$href_add="AddCtrlGral";
			$href_edit="EditCtrlGral";
			$href_del="DelCtrlGral";
			$controles=$this->GetControles();

			if (!IncludeLib('datospaciente')){
				$this->error = "Error al cargar la libreria [datospaciente].";
				$this->mensajeDeError = "datospaciente";
				return false;
			}

			$ctrlPosicion=$this->FindControles($controles,1,$this->ingreso);
			if ($ctrlPosicion===false){
				return false;
			}
			if ($this->ControlPosicion($ctrlPosicion)===false){
				return false;
			}
			else{
			$salida.=$this->ControlPosicion($ctrlPosicion);
			}
			$ctrlOxig=$this->FindControles($controles,2,$this->ingreso);
			if ($ctrlOxig===false){
				return false;
			}
			if ($this->ControlOxig($ctrlOxig)===false){
				return false;
			}
			else{
				$salida.=$this->ControlOxig($ctrlOxig);
			}

			$ctrlReposo=$this->FindControles($controles,3,$this->ingreso);
			if ($ctrlReposo===false){
				return false;
			}
			if ($this->ControlReposo($ctrlReposo)===false){
				return false;
			}
			else{
				$salida.=$this->ControlReposo($ctrlReposo);
			}
			$control=$this->FindControles($controles,4,$this->ingreso);
			if ($control===false){
				return false;
			}
			$datos=$this->GetAllControles("hc_terapias_respiratorias",$control);
			if (is_array($control) && is_array($datos)){
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_terapia_respiratoria","hc_terapias_respiratorias",$href_add,$href_edit,$href_del,false);
			}
			else{
				return false;
			}

			$control=$this->FindControles($controles,5,$this->ingreso);
			if ($control===false){
				return false;
			}
			$datos=$this->GetAllControles("hc_curvas_termicas",$control);
			if (is_array($control) && is_array($datos)){
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_curva_termica","hc_curvas_termicas",$href_add,$href_edit,$href_del,true);
			}
			else{
				return false;
			}
			$ctrlLiquidos=$this->FindControles($controles,6,$this->ingreso);
			if ($ctrlLiquidos===false){
				return false;
			}
			if ($this->ControlLiquidos($ctrlLiquidos)===false){
				return false;
			}
			else{
				$salida.=$this->ControlLiquidos($ctrlLiquidos);
			}
			$control=$this->FindControles($controles,7,$this->ingreso);
			if ($control===false){
				return false;
			}
			$datos=$this->GetAllControles("hc_control_tension_arterial",$control);
			if (is_array($control) && is_array($datos)){
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_ta","hc_control_tension_arterial",$href_add,$href_edit,$href_del,false);
			}
			else{
				return false;
			}
			$control=$this->FindControles($controles,8,$this->ingreso);
			if ($control===false){
				return false;
			}
			$datos=$this->GetAllControles("hc_control_glucometria",$control);
			if (is_array($control) && is_array($datos)){
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_glucometrias","hc_control_glucometria",$href_add,$href_edit,$href_del,true);
			}
			else{
				return false;
			}
			$control=$this->FindControles($controles,9,$this->ingreso);
			if ($control===false){
				return false;
			}
			$datos=$this->GetAllControles("hc_control_curaciones",$control);
			if (is_array($control) && is_array($datos)){
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_curaciones","hc_control_curaciones",$href_add,$href_edit,$href_del,false);
			}
			else{
				return false;
			}
			$control=$this->FindControles($controles,10,$this->ingreso);
			if ($control===false){
				return false;
			}
			$datos=$this->GetAllControles("hc_control_neurologico",$control);
			if (is_array($control) && is_array($datos)){
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_control_neurologico","hc_control_neurologico",$href_add,$href_edit,$href_del,false);
			}
			else{
				return false;
			}

			$datos_hc=GetDatosPaciente("","",$this->ingreso,"","");
			$data=$this->Gestacion($datos_hc);

			if ($data->estado) {
				$ctrlParto=$this->FindControles($controles,11,$this->ingreso);
				if ($ctrlParto===false){
					return false;
				}
				if ($this->ControlParto($ctrlParto)===false){
					return false;
				}
				else{
					$salida.=$this->ControlParto($ctrlParto);
				}
			}

			$ctrlPerAbdominal=$this->FindControles($controles,12,$this->ingreso);
			if ($ctrlPerAbdominal===false){
				return false;
			}
			if ($this->ControlPerAbdominal($ctrlPerAbdominal)===false){
				return false;
			}
			else{
				$salida.=$this->ControlPerAbdominal($ctrlPerAbdominal);
			}
			$ctrlPerCefalico=$this->FindControles($controles,13,$this->ingreso);
			if ($ctrlPerCefalico===false){
				return false;
			}
			if ($this->ControlPerCefalico($ctrlPerCefalico)===false){
				return false;
			}
			else{
				$salida.=$this->ControlPerCefalico($ctrlPerCefalico);
			}

			$ctrlPerExtremidades=$this->FindControles($controles,14,$this->ingreso);
			if ($ctrlPerExtremidades===false){
				return false;
			}
			if ($this->ControlPerExtremidades($ctrlPerExtremidades)===false){
				return false;
			}
			else{
				$salida.=$this->ControlPerExtremidades($ctrlPerExtremidades);
			}

			$ctrlDietas=$this->FindControles($controles,25,$this->ingreso);
			if ($ctrlDietas===false){
				return false;
			}
			if ($this->ControlDietas($ctrlDietas)===false){
				return false;
			}
			else{
				$salida.=$this->ControlDietas($ctrlDietas);
			}

			$ctrlTransfusiones=$this->FindControles($controles,24,$this->ingreso);
			if ($ctrlTransfusiones===false){
				return false;
			}
			if ($this->ControlTransfusiones($ctrlTransfusiones)===false){
				return false;
			}
			else{
				$salida.=$this->ControlTransfusiones($ctrlTransfusiones);
			}

			$ctrlDrenajes=$this->FindControles($controles,26,$this->ingreso);
			if ($ctrlDrenajes===false){
				return false;
			}
			if ($this->ControlDrenajes($ctrlDrenajes)===false){
				return false;
			}
			else{
				$salida.=$this->ControlDrenajes($ctrlDrenajes);
			}
			
               $ctrlOtrosCtrl=$this->FindControles($controles,27,$this->ingreso);
               if ($ctrlOtrosCtrl===false){
				return false;
			}
			if ($this->ControlOtrosControles($ctrlOtrosCtrl)===false){
				return false;
			}
			else{
				$salida.=$this->ControlOtrosControles($ctrlOtrosCtrl);
			}

               $salida.="<div align='left' class='label'>";
			$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."GraphCtrlGral",$this->frmPrefijo."control_id"=>21,$this->frmPrefijo."control_descripcion"=>"FRECUENCIA CARDIACA"));
			$salida.="<br><br><a href=\"".$href."\">Frecuencia Cardiaca</a>";
			$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."GraphCtrlGral",$this->frmPrefijo."control_id"=>22,$this->frmPrefijo."control_descripcion"=>"FRECUENCIA RESPIRATORIA"));
			$salida.="<br><br><a href=\"".$href."\">Frecuencia Respiratoria</a>";
			$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."GraphCtrlGral",$this->frmPrefijo."control_id"=>18,$this->frmPrefijo."control_descripcion"=>"PRESION VENOSA CENTRAL"));
			$salida.="<br><br><a href=\"".$href."\">PVC</a>";
			$salida.="<br><br>";
			$salida.="</div>";
			return $salida;
		}//End function


          /**
          *		function frmConsulta => Imprime en modo consulta (HTML) los controles del paciente
          *
          *		@Author Arley Velásquez C.
          *		@access Private
          *		@return boolean
          */
		function frmConsulta()
		{
			$ctrlPosicion=array();
			$href_add="AddCtrlGral";
			$href_edit="EditCtrlGral";
			$href_del="DelCtrlGral";
			$controles=$this->GetControles();
			if (!IncludeLib('datospaciente')){
				$this->error = "Error al cargar la libreria [datospaciente].";
				$this->mensajeDeError = "datospaciente";
				return false;
			}

			if (!empty ($controles))
			{
				$salida .= "<br><table align=\"center\" width=\"100%\" class=\"modulo_table_list_title\" border=\"0\" >\n";
				$salida .= "<tr>";
				$salida .= "<td align=\"center\">LISTADOS GENERALES DE CONTROLES DE PACIENTES";
				$salida .= "</td>";
				$salida .= "</tr>";
				$salida .= "</table>";
			}

			$ctrlPosicion=$this->FindControles($controles,1,$this->ingreso);
			if ($ctrlPosicion===false){
				return false;
			}
			if ($this->ControlPosicion($ctrlPosicion)===false){
				return false;
			}
			else{
			$salida.=$this->ControlPosicion($ctrlPosicion);
			}
			$ctrlOxig=$this->FindControles($controles,2,$this->ingreso);
			if ($ctrlOxig===false){
				return false;
			}
			if ($this->ControlOxig($ctrlOxig)===false){
				return false;
			}
			else{
				$salida.=$this->ControlOxig($ctrlOxig);
			}

			$ctrlReposo=$this->FindControles($controles,3,$this->ingreso);
			if ($ctrlReposo===false){
				return false;
			}
			if ($this->ControlReposo($ctrlReposo)===false){
				return false;
			}
			else{
				$salida.=$this->ControlReposo($ctrlReposo);
			}
			$control=$this->FindControles($controles,4,$this->ingreso);
			if ($control===false){
				return false;
			}
			$datos=$this->GetAllControles("hc_terapias_respiratorias",$control);
			if (is_array($control) && is_array($datos)){
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_terapia_respiratoria","hc_terapias_respiratorias",$href_add,$href_edit,$href_del,false);
			}
			else{
				return false;
			}

			$control=$this->FindControles($controles,5,$this->ingreso);
			if ($control===false){
				return false;
			}
			$datos=$this->GetAllControles("hc_curvas_termicas",$control);
			if (is_array($control) && is_array($datos)){
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_curva_termica","hc_curvas_termicas",$href_add,$href_edit,$href_del,true);
			}
			else{
				return false;
			}
			$ctrlLiquidos=$this->FindControles($controles,6,$this->ingreso);
			if ($ctrlLiquidos===false){
				return false;
			}
			if ($this->ControlLiquidos($ctrlLiquidos)===false){
				return false;
			}
			else{
				$salida.=$this->ControlLiquidos($ctrlLiquidos);
			}
			$control=$this->FindControles($controles,7,$this->ingreso);
			if ($control===false){
				return false;
			}
			$datos=$this->GetAllControles("hc_control_tension_arterial",$control);
			if (is_array($control) && is_array($datos)){
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_ta","hc_control_tension_arterial",$href_add,$href_edit,$href_del,false);
			}
			else{
				return false;
			}
			$control=$this->FindControles($controles,8,$this->ingreso);
			if ($control===false){
				return false;
			}
			$datos=$this->GetAllControles("hc_control_glucometria",$control);
			if (is_array($control) && is_array($datos)){
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_glucometrias","hc_control_glucometria",$href_add,$href_edit,$href_del,true);
			}
			else{
				return false;
			}
			$control=$this->FindControles($controles,9,$this->ingreso);
			if ($control===false){
				return false;
			}
			$datos=$this->GetAllControles("hc_control_curaciones",$control);
			if (is_array($control) && is_array($datos)){
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_curaciones","hc_control_curaciones",$href_add,$href_edit,$href_del,false);
			}
			else{
				return false;
			}
			$control=$this->FindControles($controles,10,$this->ingreso);
			if ($control===false){
				return false;
			}
			$datos=$this->GetAllControles("hc_control_neurologico",$control);
			if (is_array($control) && is_array($datos)){
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_control_neurologico","hc_control_neurologico",$href_add,$href_edit,$href_del,false);
			}
			else{
				return false;
			}

			$datos_hc=GetDatosPaciente("","",$this->ingreso,"","");
			$data=$this->Gestacion($datos_hc);

			if ($data->estado) {
				$ctrlParto=$this->FindControles($controles,11,$this->ingreso);
				if ($ctrlParto===false){
					return false;
				}
				if ($this->ControlParto($ctrlParto)===false){
					return false;
				}
				else{
					$salida.=$this->ControlParto($ctrlParto);
				}
			}

			$ctrlPerAbdominal=$this->FindControles($controles,12,$this->ingreso);
			if ($ctrlPerAbdominal===false){
				return false;
			}
			if ($this->ControlPerAbdominal($ctrlPerAbdominal)===false){
				return false;
			}
			else{
				$salida.=$this->ControlPerAbdominal($ctrlPerAbdominal);
			}
			$ctrlPerCefalico=$this->FindControles($controles,13,$this->ingreso);
			if ($ctrlPerCefalico===false){
				return false;
			}
			if ($this->ControlPerCefalico($ctrlPerCefalico)===false){
				return false;
			}
			else{
				$salida.=$this->ControlPerCefalico($ctrlPerCefalico);
			}

			$ctrlPerExtremidades=$this->FindControles($controles,14,$this->ingreso);
			if ($ctrlPerExtremidades===false){
				return false;
			}
			if ($this->ControlPerExtremidades($ctrlPerExtremidades)===false){
				return false;
			}
			else{
				$salida.=$this->ControlPerExtremidades($ctrlPerExtremidades);
			}

			$ctrlDietas=$this->FindControles($controles,25,$this->ingreso);
			if ($ctrlDietas===false){
				return false;
			}
			if ($this->ControlDietas($ctrlDietas)===false){
				return false;
			}
			else{
				$salida.=$this->ControlDietas($ctrlDietas);
			}

			$ctrlTransfusiones=$this->FindControles($controles,24,$this->ingreso);
			if ($ctrlTransfusiones===false){
				return false;
			}
			if ($this->ControlTransfusiones($ctrlTransfusiones)===false){
				return false;
			}
			else{
				$salida.=$this->ControlTransfusiones($ctrlTransfusiones);
			}
			
               $ctrlDrenajes=$this->FindControles($controles,26,$this->ingreso);
			if ($ctrlDrenajes===false){
				return false;
			}
			if ($this->ControlDrenajes($ctrlDrenajes)===false){
				return false;
			}
			else{
				$salida.=$this->ControlDrenajes($ctrlDrenajes);
			}
			
               $ctrlOtrosCtrl=$this->FindControles($controles,27,$this->ingreso);
			if ($ctrlOtrosCtrl===false){
				return false;
			}
			if ($this->ControlOtrosControles($ctrlOtrosCtrl)===false){
				return false;
			}
			else{
				$salida.=$this->ControlOtrosControles($ctrlOtrosCtrl);
			}

			$salida.="<br>";
			$img="<img src='".GetThemePath()."/images/folder_vacio.png' border='0'>";
			$img2="<img src='".GetThemePath()."/images/folder_lleno.png' border='0'>";
			$salida=str_replace("$img","",$salida);
			$salida=str_replace("$img2","",$salida);

			$this->salida = $salida;
			return $salida;//true;
		}//End function



/******************************************************************/

		function frmHistoria()
		{
			$ctrlPosicion=array();
			$href_add="AddCtrlGral";
			$href_edit="EditCtrlGral";
			$href_del="DelCtrlGral";
			$controles=$this->GetControles();

			if (!IncludeLib('datospaciente')){
				$this->error = "Error al cargar la libreria [datospaciente].";
				$this->mensajeDeError = "datospaciente";
				return false;
			}

			if (!empty ($controles))
			{
				$salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" >\n";
				$salida .= "<tr>";
				$salida .= "<td align=\"center\">LISTADOS GENERALES DE CONTROLES DE PACIENTES";
				$salida .= "</td>";
				$salida .= "</tr>";
				$salida .= "</table>";
			}else
               {
               	return $salida;
               }

			$ctrlPosicion=$this->FindControles($controles,1,$this->ingreso);
			if ($ctrlPosicion===false){
				return false;
			}
			if ($this->ControlPosicion($ctrlPosicion)===false){
				return false;
			}

			if ($this->ControlPosicion($ctrlPosicion)==''){$salida.=$this->ControlPosicion($ctrlPosicion);}

               else{
				$salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
				$salida .= "<tr>";
				$salida .= "<td>";
				$salida.=$this->ControlPosicion($ctrlPosicion);
				$salida .= "</td>";
				$salida .= "</tr>";
				$salida .= "</table>";

			}
			$ctrlOxig=$this->FindControles($controles,2,$this->ingreso);
			if ($ctrlOxig===false){
				return false;
			}
			if ($this->ControlOxig($ctrlOxig)===false){
				return false;
			}

			if ($this->ControlOxig($ctrlOxig)==''){$salida.=$this->ControlOxig($ctrlOxig);}

			else{

				$salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
				$salida .= "<tr>";
				$salida .= "<td>";
				$salida.=$this->ControlOxig($ctrlOxig);
				$salida .= "</td>";
				$salida .= "</tr>";
				$salida .= "</table>";

			}

			$ctrlReposo=$this->FindControles($controles,3,$this->ingreso);
			if ($ctrlReposo===false){
				return false;
			}
			if ($this->ControlReposo($ctrlReposo)===false){
				return false;
			}

			if ($this->ControlReposo($ctrlReposo)==''){$salida.=$this->ControlReposo($ctrlReposo);}

			else{

				$salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
				$salida .= "<tr>";
				$salida .= "<td>";
				$salida.=$this->ControlReposo($ctrlReposo);
				$salida .= "</td>";
				$salida .= "</tr>";
				$salida .= "</table>";
			}

			$control=$this->FindControles($controles,4,$this->ingreso);
			if ($control===false){
				return false;
			}
			$datos=$this->GetAllControles("hc_terapias_respiratorias",$control);

			//if (is_array($control) && is_array($datos))

			if (empty ($datos))
			{
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_terapia_respiratoria","hc_terapias_respiratorias",$href_add,$href_edit,$href_del,false);
			}
			else{

				$salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
				$salida .= "<tr>";
				$salida .= "<td>";
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_terapia_respiratoria","hc_terapias_respiratorias",$href_add,$href_edit,$href_del,false);
				$salida .= "</td>";
				$salida .= "</tr>";
				$salida .= "</table>";

			}

			$control=$this->FindControles($controles,5,$this->ingreso);
			if ($control===false){
				return false;
			}
			$datos=$this->GetAllControles("hc_curvas_termicas",$control);

			if (empty ($datos))
			{
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_curva_termica","hc_curvas_termicas",$href_add,$href_edit,$href_del,true);
			}
			else{

				$salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
				$salida .= "<tr>";
				$salida .= "<td>";
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_curva_termica","hc_curvas_termicas",$href_add,$href_edit,$href_del,true);
				$salida .= "</td>";
				$salida .= "</tr>";
				$salida .= "</table>";

			}

			$ctrlLiquidos=$this->FindControles($controles,6,$this->ingreso);
			if ($ctrlLiquidos===false){
				return false;
			}
			if ($this->ControlLiquidos($ctrlLiquidos)===false){
				return false;
			}

			if ($this->ControlLiquidos($ctrlLiquidos)==''){$salida.=$this->ControlLiquidos($ctrlLiquidos);}

			else{

				$salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
				$salida .= "<tr>";
				$salida .= "<td>";
				$salida.=$this->ControlLiquidos($ctrlLiquidos);
				$salida .= "</td>";
				$salida .= "</tr>";
				$salida .= "</table>";

			}
			$control=$this->FindControles($controles,7,$this->ingreso);
			if ($control===false){
				return false;
			}
			$datos=$this->GetAllControles("hc_control_tension_arterial",$control);

			if (empty ($datos))
			{
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_ta","hc_control_tension_arterial",$href_add,$href_edit,$href_del,false);
			}
			else{

				$salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
				$salida .= "<tr>";
				$salida .= "<td>";
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_ta","hc_control_tension_arterial",$href_add,$href_edit,$href_del,false);
				$salida .= "</td>";
				$salida .= "</tr>";
				$salida .= "</table>";

			}

			$control=$this->FindControles($controles,8,$this->ingreso);
			if ($control===false){
				return false;
			}
			$datos=$this->GetAllControles("hc_control_glucometria",$control);

			if (empty ($datos))
			{
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_glucometrias","hc_control_glucometria",$href_add,$href_edit,$href_del,true);
			}
			else{

				$salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
				$salida .= "<tr>";
				$salida .= "<td>";
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_glucometrias","hc_control_glucometria",$href_add,$href_edit,$href_del,true);
				$salida .= "</td>";
				$salida .= "</tr>";
				$salida .= "</table>";

			}

			$control=$this->FindControles($controles,9,$this->ingreso);
			if ($control===false){
				return false;
			}
			$datos=$this->GetAllControles("hc_control_curaciones",$control);

			if (empty ($datos))
			{
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_curaciones","hc_control_curaciones",$href_add,$href_edit,$href_del,false);
			}
			else{

				$salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
				$salida .= "<tr>";
				$salida .= "<td>";
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_curaciones","hc_control_curaciones",$href_add,$href_edit,$href_del,false);
				$salida .= "</td>";
				$salida .= "</tr>";
				$salida .= "</table>";

			}

			$control=$this->FindControles($controles,10,$this->ingreso);
			if ($control===false){
				return false;
			}
			$datos=$this->GetAllControles("hc_control_neurologico",$control);

			if (empty ($datos))
			{
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_control_neurologico","hc_control_neurologico",$href_add,$href_edit,$href_del,false);
			}
			else{

				$salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
				$salida .= "<tr>";
				$salida .= "<td>";
				$salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_control_neurologico","hc_control_neurologico",$href_add,$href_edit,$href_del,false);
				$salida .= "</td>";
				$salida .= "</tr>";
				$salida .= "</table>";

			}

			$datos_hc=GetDatosPaciente("","",$this->ingreso,"","");
			$data=$this->Gestacion($datos_hc);

			if ($data->estado) {
				$ctrlParto=$this->FindControles($controles,11,$this->ingreso);
				if ($ctrlParto===false){
					return false;
				}
				if ($this->ControlParto($ctrlParto)===false){
					return false;
				}

				if ($this->ControlParto($ctrlParto)==''){$salida.=$this->ControlParto($ctrlParto);}

				else{

					$salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
					$salida .= "<tr>";
					$salida .= "<td>";
					$salida.=$this->ControlParto($ctrlParto);
					$salida .= "</td>";
					$salida .= "</tr>";
					$salida .= "</table>";

				}
			}

			$ctrlPerAbdominal=$this->FindControles($controles,12,$this->ingreso);
			if ($ctrlPerAbdominal===false){
				return false;
			}
			if ($this->ControlPerAbdominal($ctrlPerAbdominal)===false){
				return false;
			}

			if ($this->ControlPerAbdominal($ctrlPerAbdominal)==''){$salida.=$this->ControlPerAbdominal($ctrlPerAbdominal);}

			else{

					$salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
					$salida .= "<tr>";
					$salida .= "<td>";
					$salida.=$this->ControlPerAbdominal($ctrlPerAbdominal);
					$salida .= "</td>";
					$salida .= "</tr>";
					$salida .= "</table>";

			}
			$ctrlPerCefalico=$this->FindControles($controles,13,$this->ingreso);
			if ($ctrlPerCefalico===false){
				return false;
			}
			if ($this->ControlPerCefalico($ctrlPerCefalico)===false){
				return false;
			}

			if ($this->ControlPerCefalico($ctrlPerCefalico)==''){$salida.=$this->ControlPerCefalico($ctrlPerCefalico);}

			else{

					$salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
					$salida .= "<tr>";
					$salida .= "<td>";
					$salida.=$this->ControlPerCefalico($ctrlPerCefalico);
					$salida .= "</td>";
					$salida .= "</tr>";
					$salida .= "</table>";

			}

			$ctrlPerExtremidades=$this->FindControles($controles,14,$this->ingreso);
			if ($ctrlPerExtremidades===false){
				return false;
			}
			if ($this->ControlPerExtremidades($ctrlPerExtremidades)===false){
				return false;
			}

			if ($this->ControlPerExtremidades($ctrlPerExtremidades)==''){$salida.=$this->ControlPerExtremidades($ctrlPerExtremidades);}

			else{

					$salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
					$salida .= "<tr>";
					$salida .= "<td>";
					$salida.=$this->ControlPerExtremidades($ctrlPerExtremidades);
					$salida .= "</td>";
					$salida .= "</tr>";
					$salida .= "</table>";

			}

			$ctrlDietas=$this->FindControles($controles,25,$this->ingreso);
			if ($ctrlDietas===false){
				return false;
			}
			if ($this->ControlDietas($ctrlDietas)===false){
				return false;
			}

			if ($this->ControlDietas($ctrlDietas)==''){$salida.=$this->ControlDietas($ctrlDietas);}

			else{

					$salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
					$salida .= "<tr>";
					$salida .= "<td>";
					$salida.=$this->ControlDietas($ctrlDietas);
					$salida .= "</td>";
					$salida .= "</tr>";
					$salida .= "</table>";

			}

			$ctrlTransfusiones=$this->FindControles($controles,24,$this->ingreso);
			if ($ctrlTransfusiones===false){
				return false;
			}
			if ($this->ControlTransfusiones($ctrlTransfusiones)===false){
				return false;
			}

			if ($this->ControlTransfusiones($ctrlTransfusiones)==''){$salida.=$this->ControlTransfusiones($ctrlTransfusiones);}

			else{

					$salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
					$salida .= "<tr>";
					$salida .= "<td>";
					$salida.=$this->ControlTransfusiones($ctrlTransfusiones);
					$salida .= "</td>";
					$salida .= "</tr>";
					$salida .= "</table>";

			}
               
               $ctrlDrenajes=$this->FindControles($controles,26,$this->ingreso);
			if ($ctrlDrenajes===false){
				return false;
			}
			if ($this->ControlDrenajes($ctrlDrenajes)===false){
				return false;
			}
               
               if ($this->ControlDrenajes($ctrlDrenajes)==''){$salida.=$this->ControlDrenajes($ctrlDrenajes);}

			else{

					$salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
					$salida .= "<tr>";
					$salida .= "<td>";
					$salida.=$this->ControlDrenajes($ctrlDrenajes);
					$salida .= "</td>";
					$salida .= "</tr>";
					$salida .= "</table>";

			}
			
               $ctrlOtrosCtrl=$this->FindControles($controles,27,$this->ingreso);
			if ($ctrlOtrosCtrl===false){
				return false;
			}
			if ($this->ControlOtrosControles($ctrlOtrosCtrl)===false){
				return false;
			}
               
               if ($this->ControlOtrosControles($ctrlOtrosCtrl)==''){$salida.=$this->ControlOtrosControles($ctrlOtrosCtrl);}

			else{

					$salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
					$salida .= "<tr>";
					$salida .= "<td>";
					$salida.=$this->ControlOtrosControles($ctrlOtrosCtrl);
					$salida .= "</td>";
					$salida .= "</tr>";
					$salida .= "</table>";

			}

			$salida.="<br>";
			$img="<img src='".GetThemePath()."/images/folder_vacio.png' border='0'>";
			$img2="<img src='".GetThemePath()."/images/folder_lleno.png' border='0'>";
			$salida=str_replace("$img","",$salida);
			$salida=str_replace("$img2","",$salida);
			return $salida;

		}//End function

/********************************************************************/

	/**
	*		function FindControles => Se encarga de verificar de la tabla controles del paciente
	*		si existe  armar la vista (HTML) de los controles del paciente
	*
	*		Llama al metodo GetExamen() El cual se encarga de traer los controles que se le han ordenado al paciente
	*		@Author Arley Velásquez C.
	*		@access Private
	*		@param array Id del control
	*		@paran
	*		@return array Los datos del control
	*/
		function FindControles($control,$control_id,$ingreso)
		{
			list($dbconn) = GetDBconn();
			$controles=array();
			$flag=0;

			foreach($control as $key =>$value){
				if ($value['control_id']==$control_id && $value['ingreso']==$ingreso){
					$flag=1;
					return $value;
				}
			}
			if (!$falg){
				return $this->GetFindControles($control_id);
			}
		}


		function GetAllTipoControlesOpt($resultado,$frecuencia_id)
		{
			$option="";
			while ($data = $resultado->FetchNextObject($toUpper=false))
			{
				if ($data->frecuencia_id==$frecuencia_id)
					$option.="<option value='".$data->frecuencia_id."' selected>".$data->descripcion."</option>\n";
				else
					$option.="<option value='".$data->frecuencia_id."'>".$data->descripcion."</option>\n";
			}
			return $option;
		}


		function FrmControles($control,$datos_controles,$tabla_tipo,$tabla,$href_add,$href_edit,$href_del,$imagen=false)
		{
			$salida="";

			if (!is_array($datos_controles) || empty($datos_controles['frecuencia_id']))
			{
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
					$salida .= "		<tr>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo.$href_add,$this->frmPrefijo."control_id"=>$control['control_id'],$this->frmPrefijo."control_descripcion"=>$control['descripcion'],$this->frmPrefijo."tabla_tipo"=>$tabla_tipo,$this->frmPrefijo."tabla"=>$tabla));
					$salida .= "			<td width='90%' class='normal_10N'><a href='".$href."'><img src='".GetThemePath()."/images/folder_vacio.png' border='0'>&nbsp;&nbsp;".strtoupper($control['descripcion'])."</a></td>\n";
					$salida .= "			<td width='10%' class='normal_10N'><a href='".$href."'>Adicionar</a></td>\n";
					$salida .= "		</tr>\n";
					$salida .= "	</table>\n";
				}
			}
			else
			{
				$controles=$this->GetAllTipoControles($tabla_tipo,$datos_controles['frecuencia_id'],0);
				if ($controles===false){
					return false;
				}

				$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
				$salida .= "		<tr>\n";
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "			<td width='85%' class='normal_10N'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;".strtoupper($control['descripcion'])."</td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo.$href_edit,$this->frmPrefijo."control_id"=>$control['control_id'],$this->frmPrefijo."control_descripcion"=>$control['descripcion'],$this->frmPrefijo."tabla"=>$tabla,$this->frmPrefijo."tabla_tipo"=>$tabla_tipo));
					$salida .= "			<td width='5%' align='center'><a href=\"".$href."\"><img src='".GetThemePath()."/images/modificar.png'  width='17' heigth='15' border='0' alt='Editar Control'></a></td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo.$href_del,$this->frmPrefijo."control_id"=>$control['control_id'],$this->frmPrefijo."control_descripcion"=>$control['descripcion'],$this->frmPrefijo."tabla"=>$tabla,$this->frmPrefijo."tabla_tipo"=>$tabla_tipo));
					$salida .= "			<td width='5%' align='center'><a href=\"".$href."\"><img src='".GetThemePath()."/images/elimina.png' width='17' heigth='15' border='0' alt='Eliminar Control'></a></td>\n";

						//este control es si es neurologico.
					if($control['control_id']==10)
					{
						$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo.'ConsCtrlNeurologica',$this->frmPrefijo."control_id"=>$control['control_id'],$this->frmPrefijo."control_descripcion"=>$control['descripcion'],$this->frmPrefijo."tabla"=>$tabla,$this->frmPrefijo."tabla_tipo"=>$tabla_tipo));
						$salida .= "			<td width='5%' class='normal_10N'><a href=\"".$href."\"><img src='".GetThemePath()."/images/resultado.png' width='17' height='17' border='0'></a></td>\n";
					}
					//este control es si es neurologico.

				if ($imagen){
						$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."GraphCtrlGral",$this->frmPrefijo."control_id"=>$control['control_id'],$this->frmPrefijo."control_descripcion"=>$control['descripcion']));
						$salida .= "			<td width='5%' align='center'><a href=\"".$href."\"><img src='".GetThemePath()."/images/estadistica.gif' border='0' alt='Grafica'></a></td>\n";
					}
					$salida .= "			<td width='5%' align='center'>&nbsp;</td>\n";
				}
				else {
					$salida .= "			<td width='100%' class='normal_10N' colspan='3'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;".strtoupper($control['descripcion'])."</td>\n";
				}
				$salida .= "		</tr>\n";
				$salida .= "		<tr>\n";
				$salida .= "			<td align='center' colspan='4'>\n";
				$salida .= "					<table width='100%' border='0' class='modulo_table_list'>\n";
				if (!empty($datos_controles['frecuencia_id']))
				{
					$salida .= "						<tr ".$this->Lista(1).">\n";
					$salida .= "							<td width='20%'>Frecuencia</td>\n";
					$salida .= "							<td width='80%'>".$controles['descripcion']."</td>\n";
					$salida .= "						</tr>\n";
				}
				if (!empty($datos_controles['observaciones'])) {
					$salida .= "						<tr ".$this->Lista(2).">\n";
					$salida .= "							<td width='20%'>Observación</td>\n";
					$salida .= "							<td width='80%' align='justify'>".$datos_controles['observaciones']."</td>\n";
					$salida .= "						</tr>\n";
				}
				$salida .= "					</table>\n";
                    $salida .= $this->ImpresionControles($control);
                    $salida .= "			</td>\n";
				$salida .= "		</tr>\n";
				$salida .= "	</table>\n";
                    
                                        
			}
			return $salida;
		}


		function ControlPosicion($control)
		{
			$salida="";
			if(empty($control['evolucion_id']))
			{
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
					$salida .= "		<tr>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddCtrlPosicion"));
					$salida .= "			<td width='90%' class='normal_10N'><a href='".$href."'><img src='".GetThemePath()."/images/folder_vacio.png' border='0'>&nbsp;&nbsp;POSICION DEL PACIENTE</a></td>\n";
					$salida .= "			<td width='10%' class='normal_10N'><a href='".$href."'>Adicionar</a></td>\n";
					$salida .= "		</tr>\n";
					$salida .= "	</table>\n";
				}
			}
			else
			{
				$data=$this->GetCControlPosicion($control);
				if (!$data)
				 return false;
				$controles=$this->GetControlPosicion($data->posicion_id,0);

				$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
				$salida .= "		<tr>\n";
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "			<td width='85%' class='normal_10N'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;POSICION DEL PACIENTE</td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."EditCtrlPosicion"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/modificar.png' width='17' heigth='15' border='0' alt='Editar Control'></a></td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlPosicion"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/elimina.png'  width='17' heigth='15' border='0' alt='Elimiar Control'></a></td>\n";
					//$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlPosicion"));
					//$salida .= "			<td width='5%' align='center' valign='middle'><a href=\"".$href."\"><img src='".GetThemePath()."/images/estadistica.gif' border='0' alt='Grafica'></a></td>\n";
					$salida .= "			<td width='5%' align='center' valign='middle'></td>\n";
				}
				else {
					$salida .= "			<td width='100%' class='normal_10N' colspan='3'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;POSICION DEL PACIENTE</td>\n";
				}
				$salida .= "		</tr>\n";
				$salida .= "		<tr>\n";
				$salida .= "			<td align='center' colspan='4'>\n";
				if (!empty($data->posicion_id))
				{
					$salida .= "					<table width='100%' border='0' class='modulo_table_list'>\n";
					$salida .= "						<tr ".$this->Lista(1)."'>\n";
					$salida .= "							<td width='20%'>Posición</td>\n";
					$salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
					$salida .= "						</tr>\n";
					if (!empty($data->observaciones)) {
						$salida .= "						<tr ".$this->Lista(2)."'>\n";
						$salida .= "							<td width='20%'>Observación</td>\n";
						$salida .= "							<td width='80%' align='justify'>".$data->observaciones."</td>\n";
						$salida .= "						</tr>\n";
					}
					$salida .= "					</table>\n";
				}
				$salida .= "			</td>\n";
				$salida .= "		</tr>\n";
				$salida .= "	</table>\n";
			}
			return $salida;
		}


		function ControlOxig($control)
		{
			$salida="";
			if (empty($control['evolucion_id']))
			{
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
					$salida .= "		<tr>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddCtrlOxig"));
					$salida .= "			<td width='90%' class='normal_10N'><a href='".$href."'><img src='".GetThemePath()."/images/folder_vacio.png' border='0'>&nbsp;&nbsp;OXIGENOTERAPIA</a></td>\n";
					$salida .= "			<td width='10%' class='normal_10N'><a href='".$href."'>Adicionar</a></td>\n";
					$salida .= "		</tr>\n";
					$salida .= "	</table>\n";
				}
			}
			else
			{
				$data=$this->GetCOxigenoterapia($control);
				if (!$data)
					return false;
				$metodo=$this->GetControlOxiMetodo($data->metodo_id,0);
				$concentracion=$this->GetControlOxiConcentraciones($data->concentracion_id,0);
				$flujo=$this->GetControlOxiFlujo($data->flujo_id,0);
				$contador=1;

				$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
				$salida .= "		<tr>\n";
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "			<td width='85%' class='normal_10N'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;OXIGENOTERAPIA</td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."EditCtrlOxig"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/modificar.png'  width='17' heigth='15' border='0' alt='Editar Control'></a></td>\n";
					$href1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlOxig"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href1."'><img src='".GetThemePath()."/images/elimina.png'  width='17' heigth='15' border='0' alt='Eliminar Control'></a></td>\n";
					//$href1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlOxig"));
					//$salida .= "			<td width='5%' align='center' valign='middle'><a href=\"".$href."\"><img src='".GetThemePath()."/images/estadistica.gif' border='0' alt='Grafica'></a></td>\n";
					$salida .= "			<td width='5%' align='center' valign='middle'></td>\n";
				}
				else {
					$salida .= "			<td width='100%' class='normal_10N' colspan='3'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;OXIGENOTERAPIA</td>\n";
				}
				$salida .= "		</tr>\n";
				$salida .= "		<tr>\n";
				$salida .= "			<td align='center' colspan='4'>\n";
				$salida .= "					<table width='100%' border='0' class='modulo_table_list'>\n";
				if (!empty($data->metodo_id))
				{
					$salida .= "						<tr ".$this->Lista($contador)."'>\n";
					$salida .= "							<td width='20%'>Método</td>\n";
					$salida .= "							<td width='80%'>".$metodo[0]['descripcion']."</td>\n";
					$salida .= "						</tr>\n";
					$contador++;
				}
				if (!empty($data->concentracion_id))
				{
					$salida .= "						<tr ".$this->Lista($contador)."'>\n";
					$salida .= "							<td width='20%'>Concentración</td>\n";
					$salida .= "							<td width='80%'>".$concentracion[0]['descripcion']."</td>\n";
					$salida .= "						</tr>\n";
					$contador++;
				}
				if (!empty($data->flujo_id))
				{
					$salida .= "						<tr ".$this->Lista($contador)."'>\n";
					$salida .= "							<td width='20%'>Flujo</td>\n";
					$salida .= "							<td width='80%'>".$flujo[0]['descripcion']."</td>\n";
					$salida .= "						</tr>\n";
					$contador++;
				}
				if (!empty($data->observaciones))
				{
					$salida .= "						<tr ".$this->Lista($contador)."'>\n";
					$salida .= "							<td width='20%'>Observación</td>\n";
					$salida .= "							<td width='80%' align='justify'>".$data->observaciones."</td>\n";
					$salida .= "						</tr>\n";
				}
				$salida .= "					</table>\n";
				$salida .= "			</td>\n";
				$salida .= "		</tr>\n";
				$salida .= "	</table>\n";
			}
			return $salida;
		}


		function ControlReposo($control)
		{
			$salida="";

			if (empty($control['evolucion_id']))
			{
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
					$salida .= "		<tr>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddCtrlReposo"));
					$salida .= "			<td width='90%' class='normal_10N'><a href='".$href."'><img src='".GetThemePath()."/images/folder_vacio.png' border='0'>&nbsp;&nbsp;REPOSO DEL PACIENTE</a></td>\n";
					$salida .= "			<td width='10%' class='normal_10N'><a href='".$href."'>Adicionar</a></td>\n";
					$salida .= "		</tr>\n";
					$salida .= "	</table>\n";
				}
			}
			else
			{
				$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
				$salida .= "		<tr>\n";
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "			<td width='85%' class='normal_10N'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;REPOSO DEL PACIENTE</td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."EditCtrlReposo"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/modificar.png' width='17' heigth='15' border='0' alt='Editar Control'></a></td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlReposo"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/elimina.png'  width='17' heigth='15' border='0' alt='Eliminar Control'></a></td>\n";
					//$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlOxig"));
					//$salida .= "			<td width='5%' align='center' valign='middle'><a href=\"".$href."\"><img src='".GetThemePath()."/images/estadistica.gif' border='0' alt='Grafica'></a></td>\n";
					$salida .= "			<td width='5%' align='center' valign='middle'></td>\n";
				}
				else {
					$salida .= "			<td width='100%' class='normal_10N' colspan='3'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;REPOSO DEL PACIENTE</td>\n";
				}
				$salida .= "		</tr>\n";
				$salida .= "		<tr>\n";
				$salida .= "			<td align='center' colspan='4'>\n";
				$salida .= "					<table width='100%' border='0' class='modulo_table_list'>\n";

				$reposo_d=$this->GetCControlReposoDetalle($control);
				if ($reposo_d===false || !is_array($reposo_d))
				 return false;

				$salida .= "						<tr ".$this->Lista(1)."'>\n";
				$salida .= "							<td width='100%' align='center' colspan='2'>Tipo de Reposo</td>\n";
				$salida .= "						</tr>\n";

				foreach ($reposo_d as $key => $value)
				{
					$salida .= "						<tr ".$this->Lista($key)."'>\n";
					$salida .= "							<td width='100%' colspan='2'>".$value['descripcion']."</td>\n";
					$salida .= "						</tr>\n";
				}

				$data=$this->GetCControlReposo($control);
				if (!$data)
					return false;

				if (!empty($data->observaciones)) {
					$salida .= "						<tr ".$this->Lista(1)."'>\n";
					$salida .= "							<td width='20%'>Observación</td>\n";
					$salida .= "							<td width='80%' align='justify'>".$data->observaciones."</td>\n";
					$salida .= "						</tr>\n";
				}
				$salida .= "					</table>\n";
				$salida .= "			</td>\n";
				$salida .= "		</tr>\n";
				$salida .= "	</table>\n";
			}
			return $salida;
		}


		function ControlLiquidos($control)
		{
			$salida="";

			if (empty($control['evolucion_id']))
			{
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
					$salida .= "		<tr>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddCtrlLiquidos"));
					$salida .= "			<td width='90%' class='normal_10N'><a href='".$href."'><img src='".GetThemePath()."/images/folder_vacio.png' border='0'>&nbsp;&nbsp;CONTROL DE LIQUIDOS INGERIDOS Y ELIMINADOS</a></td>\n";
					$salida .= "			<td width='10%' class='normal_10N'><a href='".$href."'>Adicionar</a></td>\n";
					$salida .= "		</tr>\n";
					$salida .= "	</table>\n";
				}
			}
			else
			{
				$data=$this->GetCControlLiquidos($control);
				if (!$data)
					return false;

				$controles=$this->GetControlLiquidos($control['evolucion_id']);

				$salida .= "	<table width='100%' align='justify' border='0' class='modulo_table_list'>";
				$salida .= "		<tr>\n";
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "			<td width='85%' class='normal_10N'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;CONTROL DE LIQUIDOS INGERIDOS Y ELIMINADOS</td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."EditCtrlLiquidos"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/modificar.png'  width='17' heigth='15' border='0' alt='Editar Control'></a></td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlLiquidos"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/elimina.png'  width='17' heigth='15' border='0' alt='Eliminar Control'></a></td>\n";

					//LINK de revisar resultados hecho por <duvan>
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo.'ConsCtrlLiquidos'));
					$salida .= "			<td width='5%' class='normal_10N'><a href=\"".$href."\"><img src='".GetThemePath()."/images/resultado.png' width='17' height='17' border='0'></a></td>\n";

					//$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlReposo"));
					//$salida .= "			<td width='5%' align='center' valign='middle'><a href=\"".$href."\"><img src='".GetThemePath()."/images/estadistica.gif' border='0' alt='Grafica'></a></td>\n";
					$salida .= "			<td width='5%' align='center' valign='middle'>&nbsp;</td>\n";
				}
				else {
					$salida .= "			<td width='100%' class='normal_10N' colspan='3'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;CONTROL DE LIQUIDOS INGERIDOS Y ELIMINADOS</td>\n";
				}
				$salida .= "		</tr>\n";
				$salida .= "		<tr>\n";
				$salida .= "			<td align='center' colspan='4'>\n";
				if (!empty($controles[0]['observaciones']))
				{
					$salida .= "					<table width='100%' border='0' class='modulo_table_list'>\n";
					$salida .= "						<tr ".$this->Lista(1)."'>\n";
					$salida .= "							<td width='20%'>Descripción</td>\n";
					$salida .= "							<td width='80%'>".$controles[0]['observaciones']."</td>\n";
					$salida .= "						</tr>\n";
					$salida .= "					</table>\n";
				}
				$salida .= "			</td>\n";
				$salida .= "		</tr>\n";
				$salida .= "	</table>\n";
			}
			return $salida;
		}


		function ControlPerAbdominal($control)
		{
			$salida="";

			if (empty($control['evolucion_id']))
			{
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
					$salida .= "		<tr>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddCtrlPerAbdominal"));
					$salida .= "			<td width='90%' class='normal_10N'><a href='".$href."'><img src='".GetThemePath()."/images/folder_vacio.png' border='0'>&nbsp;&nbsp;PERIMETRO ABDOMINAL</a></td>\n";
					$salida .= "			<td width='10%' class='normal_10N'><a href='".$href."'>Adicionar</a></td>\n";
					$salida .= "		</tr>\n";
					$salida .= "	</table>\n";
				}
			}
			else
			{
				$data=$this->GetCPerimetroAbdominal($control);
				if (!$data)
					return false;

				$controles=$this->GetControlPerAbdominal($control['evolucion_id']);

				$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
				$salida .= "		<tr>\n";
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "			<td width='85%' class='normal_10N'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;PERIMETRO ABDOMINAL</td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."EditCtrlPerAbdominal"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/modificar.png' width='17' heigth='15' border='0' alt='Editar Control'></a></td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlPerAbdominal"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/elimina.png' width='17' heigth='15'  border='0' alt='Eliminar Control'></a></td>\n";
					//$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlReposo"));
					//$salida .= "			<td width='5%' align='center' valign='middle'><a href=\"".$href."\"><img src='".GetThemePath()."/images/estadistica.gif' border='0' alt='Grafica'></a></td>\n";
					$salida .= "			<td width='5%' align='center' valign='middle'>&nbsp;</td>\n";
				}
				else {
					$salida .= "			<td width='100%' class='normal_10N' colspan='3'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;PERIMETRO ABDOMINAL</td>\n";
				}
				$salida .= "		</tr>\n";
				$salida .= "		<tr>\n";
				$salida .= "			<td align='center' colspan='4'>\n";
				if (!empty($controles[0]['observaciones']))
				{
					$salida .= "					<table width='100%' border='0' class='modulo_table_list'>\n";
					$salida .= "						<tr ".$this->Lista(1)."'>\n";
					$salida .= "							<td width='20%'>Descripción</td>\n";
					$salida .= "							<td width='80%'>".$controles[0]['observaciones']."</td>\n";
					$salida .= "						</tr>\n";
					$salida .= "					</table>\n";
				}
				$salida .= "			</td>\n";
				$salida .= "		</tr>\n";
				$salida .= "	</table>\n";
			}
			return $salida;
		}


		function ControlPerCefalico($control)
		{
			$salida="";

			if (empty($control['evolucion_id']))
			{
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
					$salida .= "		<tr>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddCtrlPerCefalico"));
					$salida .= "			<td width='90%' class='normal_10N'><a href='".$href."'><img src='".GetThemePath()."/images/folder_vacio.png' border='0'>&nbsp;&nbsp;PERIMETRO CEFALICO</a></td>\n";
					$salida .= "			<td width='10%' class='normal_10N'><a href='".$href."'>Adicionar</a></td>\n";
					$salida .= "		</tr>\n";
					$salida .= "	</table>\n";
				}
			}
			else
			{
				$data=$this->GetCPerimetroCefalico($control);
				if (!$data)
					return false;

				$controles=$this->GetControlPerCefalico($control['evolucion_id']);

				$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
				$salida .= "		<tr>\n";
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "			<td width='85%' class='normal_10N'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;PERIMETRO CEFALICO</td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."EditCtrlPerCefalico"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/modificar.png'  width='17' heigth='15' border='0' alt='Editar Control'></a></td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlPerCefalico"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/elimina.png'  width='17' heigth='15' border='0' alt='Eliminar Control'></a></td>\n";
					//$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlReposo"));
					//$salida .= "			<td width='5%' align='center' valign='middle'><a href=\"".$href."\"><img src='".GetThemePath()."/images/estadistica.gif' border='0' alt='Grafica'></a></td>\n";
					$salida .= "			<td width='5%' align='center' valign='middle'>&nbsp;</td>\n";
				}
				else {
					$salida .= "			<td width='100%' class='normal_10N' colspan='3'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;PERIMETRO CEFALICO</td>\n";
				}
				$salida .= "		</tr>\n";
				$salida .= "		<tr>\n";
				$salida .= "			<td align='center' colspan='4'>\n";
				if (!empty($controles[0]['observaciones']))
				{
					$salida .= "					<table width='100%' border='0' class='modulo_table_list'>\n";
					$salida .= "						<tr ".$this->Lista(1)."'>\n";
					$salida .= "							<td width='20%'>Descripción</td>\n";
					$salida .= "							<td width='80%'>".$controles[0]['observaciones']."</td>\n";
					$salida .= "						</tr>\n";
					$salida .= "					</table>\n";
				}
				$salida .= "			</td>\n";
				$salida .= "		</tr>\n";
				$salida .= "	</table>\n";
			}
			return $salida;
		}


		function ControlPerExtremidades($control)
		{
			$salida="";

			if (empty($control['evolucion_id']))
			{
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
					$salida .= "		<tr>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddCtrlPerExtremidades"));
					$salida .= "			<td width='90%' class='normal_10N'><a href='".$href."'><img src='".GetThemePath()."/images/folder_vacio.png' border='0'>&nbsp;&nbsp;PERIMETRO DE EXTREMIDADES</a></td>\n";
					$salida .= "			<td width='10%' class='normal_10N'><a href='".$href."'>Adicionar</a></td>\n";
					$salida .= "		</tr>\n";
					$salida .= "	</table>\n";
				}
			}
			else
			{
				$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
				$salida .= "		<tr>\n";
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "			<td width='85%' class='normal_10N'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;PERIMETRO DE EXTREMIDADES</td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."EditCtrlPerExtremidades"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/modificar.png'  width='17' heigth='15' border='0' alt='Editar Control'></a></td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlPerExtremidades"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/elimina.png'  width='17' heigth='15' border='0' alt='Eliminar Control'></a></td>\n";
					//$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlReposo"));
					//$salida .= "			<td width='5%' align='center' valign='middle'><a href=\"".$href."\"><img src='".GetThemePath()."/images/estadistica.gif' border='0' alt='Grafica'></a></td>\n";
					$salida .= "			<td width='5%' align='center' valign='middle'>&nbsp;</td>\n";
				}
				else {
					$salida .= "			<td width='100%' class='normal_10N' colspan='3'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;PERIMETRO DE EXTREMIDADES</td>\n";
				}
				$salida .= "		</tr>\n";
				$salida .= "		<tr>\n";
				$salida .= "			<td align='center' colspan='4'>\n";
				$salida .= "					<table width='100%' border='0' class='modulo_table_list'>\n";

				$resultado=$this->GetCPerimetroExtremidadesDetalle($control);
				if ($resultado===false || !is_array($resultado))
					return false;

				$salida .= "						<tr ".$this->Lista(1)."'>\n";
				$salida .= "							<td width='100%' align='center' colspan='2'>Tipo de Perimetro de extremidad</td>\n";
				$salida .= "						</tr>\n";

				foreach ($resultado as $key => $value)
				{
					$salida .= "						<tr ".$this->Lista($key)."'>\n";
					$salida .= "							<td width='100%' colspan='2'>".$value['descripcion']."</td>\n";
					$salida .= "						</tr>\n";
				}

				$data=$this->GetCPerimetroExtremidades($control);
				if ($data===false)
					return false;

				if (!empty($data->observaciones)) {
					$salida .= "						<tr ".$this->Lista(1)."'>\n";
					$salida .= "							<td width='20%'>Observación</td>\n";
					$salida .= "							<td width='80%' align='justify'>".$data->observaciones."</td>\n";
					$salida .= "						</tr>\n";
				}
				$salida .= "					</table>\n";
				$salida .= "			</td>\n";
				$salida .= "		</tr>\n";
				$salida .= "	</table>\n";
			}
			return $salida;
		}


		function ControlParto($control)
		{
			$salida="";

			if (empty($control['evolucion_id']))
			{
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
					$salida .= "		<tr>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddCtrlParto"));
					$salida .= "			<td width='90%' class='normal_10N'><a href='".$href."'><img src='".GetThemePath()."/images/folder_vacio.png' border='0'>&nbsp;&nbsp;CONTROL DE TRABAJO DE PARTO</a></td>\n";
					$salida .= "			<td width='10%' class='normal_10N'><a href='".$href."'>Adicionar</a></td>\n";
					$salida .= "		</tr>\n";
					$salida .= "	</table>\n";
				}
			}
			else
			{
				$data=$this->GetCControlParto($control);
				if (!$data)
					return false;
				$controles=$this->GetControlParto($control['evolucion_id']);

				$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
				$salida .= "		<tr>\n";
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "			<td width='85%' class='normal_10N'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;CONTROL DE TRABAJO DE PARTO</td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."EditCtrlParto"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/modificar.png'  width='17' heigth='15' border='0' alt='Editar Control'></a></td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlParto"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/elimina.png'  width='17' heigth='15' border='0' alt='Eliminar Control'></a></td>\n";
					//$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlReposo"));
					//$salida .= "			<td width='5%' align='center' valign='middle'><a href=\"".$href."\"><img src='".GetThemePath()."/images/estadistica.gif' border='0' alt='Grafica'></a></td>\n";
					$salida .= "			<td width='5%' align='center' valign='middle'>&nbsp;</td>\n";
				}
				else {
					$salida .= "			<td width='100%' class='normal_10N' colspan='3'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;CONTROL DE TRABAJO DE PARTO</td>\n";
				}
				$salida .= "		</tr>\n";
				$salida .= "		<tr>\n";
				$salida .= "			<td align='center' colspan='4'>\n";
				if (!empty($controles[0]['observaciones']))
				{
					$salida .= "					<table width='100%' border='0' class='modulo_table_list'>\n";
					$salida .= "						<tr ".$this->Lista(1)."'>\n";
					$salida .= "							<td width='20%'>Descripción</td>\n";
					$salida .= "							<td width='80%'>".$controles[0]['observaciones']."</td>\n";
					$salida .= "						</tr>\n";
					$salida .= "					</table>\n";
				}
				$salida .= "			</td>\n";
				$salida .= "		</tr>\n";
				$salida .= "	</table>\n";
			}
			return $salida;
		}


		function ControlDietas($control)
		{
			$salida="";

			if (empty($control['evolucion_id']))
			{
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
					$salida .= "		<tr>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddCtrlDietas"));
					$salida .= "			<td width='90%' class='normal_10N'><a href='".$href."'><img src='".GetThemePath()."/images/folder_vacio.png' border='0'>&nbsp;&nbsp;DIETAS DEL PACIENTE</a></td>\n";
					$salida .= "			<td width='10%' class='normal_10N'><a href='".$href."'>Adicionar</a></td>\n";
					$salida .= "		</tr>\n";
					$salida .= "	</table>\n";
				}
			}
			else
			{
				$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
				$salida .= "		<tr>\n";
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "			<td width='85%' class='normal_10N'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;DIETAS DEL PACIENTE</td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."EditCtrlDietas"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/modificar.png'  width='17' heigth='15' border='0' alt='Editar Control'></a></td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlDietas"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/elimina.png'  width='17' heigth='15' border='0' alt='Eliminar Control'></a></td>\n";
					$salida .= "			<td width='5%' align='center' valign='middle'></td>\n";
				}
				else {
					$salida .= "			<td width='100%' class='normal_10N' colspan='3'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;DIETAS DEL PACIENTE</td>\n";
				}
				$salida .= "		</tr>\n";
				$salida .= "		<tr>\n";
				$salida .= "			<td align='center' colspan='4'>\n";
				$salida .= "					<table width='100%' border='0' class='modulo_table_list'>\n";
				$dietas_d=$this->GetCControlDietasDetalle($control);

				if(sizeof($dietas_d)>1)
				{
                         foreach ($dietas_d as $key => $value)
                         {
                              $datos.=$value['descripcion'].",";
                         }
                         $salida .= "<tr ".$this->Lista($key)."'>\n";
                         $salida .= "<td width='30%'>Tipo de Dieta</td>\n";
                         $salida .= "<td width='70%'>$datos</td>\n";
                         $salida .= "</tr>\n";unset($datos);
				}
				else{
                         foreach ($dietas_d as $key => $value)
                         {
                              $salida .= "<tr ".$this->Lista($key)."'>\n";
                              $salida .= "<td width='30%'>Tipo de Dietas</td>\n";
                              $salida .= "<td width='70%'>".$value['descripcion']."</td>\n";
                              $salida .= "</tr>\n";
                         }
				}

				$data=$this->GetCControlDietas($control);
                    if (!empty($data['observaciones'])) {
					$salida .= "<tr ".$this->Lista(2)."'>\n";
					$salida .= "<td width='30%'>Observación</td>\n";
					$salida .= "<td width='70%' align='justify'>".$data['observaciones']."</td>\n";
					$salida .= "</tr>\n";
				}
				$salida .= "					</table>\n";
				$salida .= "			</td>\n";
				$salida .= "		</tr>\n";
				$salida .= "	</table>\n";
			}
			return $salida;
		}


		function ControlTransfusiones($control)
		{
			$salida="";

			if (empty($control['evolucion_id']))
			{
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
					$salida .= "		<tr>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddCtrlTransfusiones"));
					$salida .= "			<td width='90%' class='normal_10N'><a href='".$href."'><img src='".GetThemePath()."/images/folder_vacio.png' border='0'>&nbsp;&nbsp;CONTROL DE TRANSFUSIONES</a></td>\n";
					$salida .= "			<td width='10%' class='normal_10N'><a href='".$href."'>Adicionar</a></td>\n";
					$salida .= "		</tr>\n";
					$salida .= "	</table>\n";
				}
			}
			else
			{
				$data=$this->GetCControlTransfusiones($control);
				if (!$data)
					return false;

				$controles=$this->GetControlTransfusiones($control['evolucion_id']);

				$salida .= "	<table width='100%' align='justify' border='0' class='modulo_table_list'>";
				$salida .= "		<tr>\n";
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "			<td width='85%' class='normal_10N'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;CONTROL DE TRANSFUSIONES</td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."EditCtrlTransfusiones"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/modificar.png'  width='17' heigth='15' border='0' alt='Editar Control'></a></td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlTransfusiones"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/elimina.png'  width='17' heigth='15' border='0' alt='Eliminar Control'></a></td>\n";
					//LINK de revisar resultados hecho por <duvan>
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo.'ConsCtrlTranfusiones'));
					$salida .= "			<td width='5%' class='normal_10N'><a href=\"".$href."\"><img src='".GetThemePath()."/images/resultado.png' width='17' height='17' border='0'></a></td>\n";

					//$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlReposo"));
					//$salida .= "			<td width='5%' align='center' valign='middle'><a href=\"".$href."\"><img src='".GetThemePath()."/images/estadistica.gif' border='0' alt='Grafica'></a></td>\n";
					$salida .= "			<td width='5%' align='center' valign='middle'>&nbsp;</td>\n";
				}
				else {
					$salida .= "			<td width='100%' class='normal_10N' colspan='3'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;CONTROL DE LIQUIDOS INGERIDOS Y ELIMINADOS</td>\n";
				}
				$salida .= "		</tr>\n";
				$salida .= "		<tr>\n";
				$salida .= "			<td align='center' colspan='4'>\n";
				if (!empty($controles[0]['observaciones']))
				{
					$salida .= "					<table width='100%' border='0' class='modulo_table_list'>\n";
					$salida .= "						<tr ".$this->Lista(1)."'>\n";
					$salida .= "							<td width='20%'>Descripción</td>\n";
					$salida .= "							<td width='80%'>".$controles[0]['observaciones']."</td>\n";
					$salida .= "						</tr>\n";
					$salida .= "					</table>\n";
				}
				$salida .= "			</td>\n";
				$salida .= "		</tr>\n";
				$salida .= "	</table>\n";
			}
			return $salida;
		}

		
          /*************************/
          function ControlDrenajes($control)
		{
			$salida="";

			if (empty($control['evolucion_id']))
			{
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
					$salida .= "		<tr>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddCtrlDrenajes"));
					$salida .= "			<td width='90%' class='normal_10N'><a href='".$href."'><img src='".GetThemePath()."/images/folder_vacio.png' border='0'>&nbsp;&nbsp;CONTROL DE DRENAJES</a></td>\n";
					$salida .= "			<td width='10%' class='normal_10N'><a href='".$href."'>Adicionar</a></td>\n";
					$salida .= "		</tr>\n";
					$salida .= "	</table>\n";
				}
			}
			else
			{
				$data=$this->GetCControlDrenajes($control);
				if (!$data)
					return false;

				$controles=$this->GetControlDrenajes($control['evolucion_id']);

				$salida .= "	<table width='100%' align='justify' border='0' class='modulo_table_list'>";
				$salida .= "		<tr>\n";
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "			<td width='85%' class='normal_10N'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;CONTROL DE DRENAJES</td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."EditCtrlDrenajes"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/modificar.png'  width='17' heigth='15' border='0' alt='Editar Control'></a></td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlDrenajes"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/elimina.png'  width='17' heigth='15' border='0' alt='Eliminar Control'></a></td>\n";
					$salida .= "			<td width='5%' class='normal_10N'>&nbsp;</td>\n";
					$salida .= "			<td width='5%' align='center' valign='middle'>&nbsp;</td>\n";
				}
				else {
					$salida .= "			<td width='100%' class='normal_10N' colspan='3'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;CONTROL DE DRENAJES</td>\n";
				}
				$salida .= "		</tr>\n";
				
                    $salida .= "		<tr>\n";
				$salida .= "			<td align='center' colspan='4'>\n";
				$salida .= "					<table width='100%' border='0' class='modulo_table_list'>\n";
				if (!empty($controles[0]['tipo_drenaje']))
				{
					$salida .= "						<tr ".$this->Lista(1).">\n";
					$salida .= "							<td width='20%'>Tipo de Drenaje</td>\n";
					$salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
					$salida .= "						</tr>\n";
				}
				if (!empty($controles[0]['observaciones'])) {
					$salida .= "						<tr ".$this->Lista(2).">\n";
					$salida .= "							<td width='20%'>Observación</td>\n";
					$salida .= "							<td width='80%' align='justify'>".$controles[0]['observaciones']."</td>\n";
					$salida .= "						</tr>\n";
				}
                    $salida .= "					</table>\n";
                    $salida .= "			</td>\n";
                    $salida .= "		</tr>\n";
				$salida .= "	</table>\n";
			}
			return $salida;
		}

          
          function ControlOtrosControles($control)
		{
			$salida="";

			if (empty($control['evolucion_id']))
			{
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "	<table width='100%' border='0' class='modulo_table_list'>";
					$salida .= "		<tr>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddCtrlAdicionales"));
					$salida .= "			<td width='90%' class='normal_10N'><a href='".$href."'><img src='".GetThemePath()."/images/folder_vacio.png' border='0'>&nbsp;&nbsp;CONTROLES ADICIONALES</a></td>\n";
					$salida .= "			<td width='10%' class='normal_10N'><a href='".$href."'>Adicionar</a></td>\n";
					$salida .= "		</tr>\n";
					$salida .= "	</table>\n";
				}
			}
			else
			{
				$data=$this->GetCControlesAdicionales($control);
				if (!$data)
					return false;

				$controles=$this->GetControlesAdicionales($control['evolucion_id']);

				$salida .= "	<table width='100%' align='justify' border='0' class='modulo_table_list'>";
				$salida .= "		<tr>\n";
				if ($this->estado && ($this->tipo_profesional==1 || $this->tipo_profesional==2)) {
					$salida .= "			<td width='85%' class='normal_10N'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;CONTROLES ADICIONALES</td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."EditCtrlAdicionales"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/modificar.png'  width='17' heigth='15' border='0' alt='Editar Control'></a></td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."DelCtrlAdicionales"));
					$salida .= "			<td width='5%' align='center' valign='middle'><a href='".$href."'><img src='".GetThemePath()."/images/elimina.png'  width='17' heigth='15' border='0' alt='Eliminar Control'></a></td>\n";
					$salida .= "			<td width='5%' class='normal_10N'>&nbsp;</td>\n";
					$salida .= "			<td width='5%' align='center' valign='middle'>&nbsp;</td>\n";
				}
				else {
					$salida .= "			<td width='100%' class='normal_10N' colspan='3'><img src='".GetThemePath()."/images/folder_lleno.png' border='0'>&nbsp;&nbsp;CONTROLES ADICIONALES</td>\n";
				}
				$salida .= "		</tr>\n";
				$salida .= "		<tr>\n";
				$salida .= "			<td align='center' colspan='4'>\n";
				if (!empty($controles[0]['observaciones']))
				{
					$salida .= "					<table width='100%' border='0' class='modulo_table_list'>\n";
					$salida .= "						<tr ".$this->Lista(1)."'>\n";
					$salida .= "							<td width='20%'>Descripción</td>\n";
					$salida .= "							<td width='80%'>".$controles[0]['observaciones']."</td>\n";
					$salida .= "						</tr>\n";
					$salida .= "					</table>\n";
				}
				$salida .= "			</td>\n";
				$salida .= "		</tr>\n";
				$salida .= "	</table>\n";
			}
			return $salida;
		}

          /*************************/
          function ImpresionControles($control)
          {
          	switch ($control['control_id'])
               {
                    case '10':
                         $VectorControl = $this->Listar_ControlesNeurologicos();

                         if (empty($contador)){
                              $contador=sizeof($VectorControl);
                         }
                         
					if($VectorControl)
                         {
                              $salidaControl .="<br>";
                              $salidaControl .="<table align=\"center\" width=\"100%\" border='0'>";
                              $salidaControl .= "<tr class=\"modulo_table_list_title\">\n";//".$this->Lista(1)."'
                              $salidaControl .= "<td width='100%' colspan=\"16\" align=\"center\">RESUMEN DE CONTROLES NEUROLOGICOS</td>\n";
                              $salidaControl .= "</tr>\n";                              
                              $salidaControl .="<tr class=\"modulo_table_list_title\">";
                              $salidaControl .="<td rowspan='2'>FECHA</td>";
                              $salidaControl .="<td rowspan='2'>HORA</td>";
                              $salidaControl .="<td colspan='2'>PUPILA DERECHA</td>";
                              $salidaControl .="<td colspan='2'>PUPILA IZQUIDA.</td>";
                              $salidaControl .="<td rowspan='2'>CONCIENCIA</td>";
                              $salidaControl .="<td colspan='4'> FUERZA </td>";
                              $salidaControl .="<td colspan='4'> ESCALA DE GLASGOW </td>";
                              $salidaControl .="<td rowspan='2'>USUARIO</td>";
                              $salidaControl .="</tr>";
                              $salidaControl .="<tr class='hc_table_submodulo_list_title'>";
                              $salidaControl .="<td align=\"center\"> TALLA </td>";
                              $salidaControl .="<td align=\"center\"> REACCION</td>";
                              $salidaControl .="<td align=\"center\"> TALLA </td>";
                              $salidaControl .="<td align=\"center\"> REACCION </td>";
                              $salidaControl .="<td align=\"center\"> B. DER. </td>";
                              $salidaControl .="<td align=\"center\"> B. IZQ. </td>";
                              $salidaControl .="<td align=\"center\"> P. DER. </td>";
                              $salidaControl .="<td align=\"center\"> P. IZQ. </td>";
                              $salidaControl .="<td align=\"center\"> A. OCULAR </td>";
                              $salidaControl .="<td align=\"center\"> R. VERBAL </td>";
                              $salidaControl .="<td align=\"center\"> R. MOTORA </td>";
                              $salidaControl .="<td align=\"center\"> E.G. </td>";
                              $salidaControl .="</tr>";
                              $cont=1;
                              $spy=0;
                              while ($cont <= sizeof($VectorControl) && $cont <= $contador)
                              {
                                   list($fecha,$hora) = explode(" ",$VectorControl[$cont-1][fecha]);
                                   list($ano,$mes,$dia) = explode("-",$fecha);
                                   list($hora,$min) = explode(":",$hora);
                                   $hora=$hora.":".$min;
//                                    if($fecha == date("Y-m-d"))
//                                    {
//                                         $fecha = "HOY";
//                                    }
//                                    elseif($fecha == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y"))))
//                                    {
//                                         $fecha = "AYER";
//                                    }
//                                    else
//                                    {
//                                         $fecha = $fecha;
//                                    }
//                
                                   if($spy==0)
                                   {
                                        $salidaControl.="<tr class=\"modulo_list_oscuro\">";
                                        $spy=1;
                                   }
                                   else
                                   {
                                        $salidaControl.="<tr class=\"modulo_list_claro\">";
                                        $spy=0;
                                   }
               
                                   if($VectorControl[$cont-1][pupila_talla_d] == 0) $ptallad = "--"; else $ptallad = $VectorControl[$cont-1][pupila_talla_d];
                                   if($VectorControl[$cont-1][pupila_reaccion_d] == ' ') $preacciond = "--"; else $preacciond = $VectorControl[$cont-1][pupila_reaccion_d];
                                   if($VectorControl[$cont-1][pupila_talla_i] == 0) $ptallai = "--"; else $ptallai = $VectorControl[$cont-1][pupila_talla_i];
                                   if($VectorControl[$cont-1][pupila_reaccion_i] == ' ') $preaccioni = "--"; else $preaccioni = $VectorControl[$cont-1][pupila_reaccion_i];
                                   if($VectorControl[$cont-1][descripcion] == ' ') $conciencia = "--"; else $conciencia = $VectorControl[$cont-1][descripcion];
                                   if($VectorControl[$cont-1][fuerza_brazo_d] == ' ') $brazod = "--"; else $brazod = $VectorControl[$cont-1][fuerza_brazo_d];
                                   if($VectorControl[$cont-1][fuerza_brazo_i] == ' ') $brazoi = "--"; else $brazoi = $VectorControl[$cont-1][fuerza_brazo_i];
                                   if($VectorControl[$cont-1][fuerza_pierna_d] == ' ') $piernad = "--"; else $piernad = $VectorControl[$cont-1][fuerza_pierna_d];
                                   if($VectorControl[$cont-1][fuerza_pierna_i] == ' ') $piernai = "--"; else $piernai = $VectorControl[$cont-1][fuerza_pierna_i];
                                   if($VectorControl[$cont-1][tipo_apertura_ocular_id] == 0 ) $AO = "--"; else $AO = $VectorControl[$cont-1][tipo_apertura_ocular_id];
                                   if($VectorControl[$cont-1][tipo_respuesta_verbal_id] == 0 ) $RV = "--"; else $RV = $VectorControl[$cont-1][tipo_respuesta_verbal_id];
                                   if($VectorControl[$cont-1][tipo_respuesta_motora_id] == 0 ) $RM = "--"; else $RM = $VectorControl[$cont-1][tipo_respuesta_motora_id];
                                   if($VectorControl[$cont-1][usuario] == ' ') $user = "--"; else $user = $VectorControl[$cont-1][usuario];
                                   $EG = $AO + $RV + $RM;
                                   if($EG == 0) $EG = "--"; else $EG = $EG;
               
                                   $salidaControl .="<td align=\"center\">" .$fecha. "</td>";
                                   $salidaControl .="<td align=\"center\">" .$hora. "</td>";
                                   $salidaControl .="<td align=\"center\">" .$ptallad. "</td>";
                                   $salidaControl .="<td align=\"center\">" .$preacciond. "</td>";
                                   $salidaControl .="<td align=\"center\">" .$ptallai. "</td>";
                                   $salidaControl .="<td align=\"center\">" .$preaccioni. "</td>";
                                   $salidaControl .="<td align=\"center\">" .$conciencia. "</td>";
                                   $salidaControl .="<td align=\"center\">" .$brazod. "</td>";
                                   $salidaControl .="<td align=\"center\">" .$brazoi. "</td>";
                                   $salidaControl .="<td align=\"center\">" .$piernad. "</td>";
                                   $salidaControl .="<td align=\"center\">" .$piernai. "</td>";
                                   $salidaControl .="<td align=\"center\">" .$AO. "</td>";
                                   $salidaControl .="<td align=\"center\">" .$RV. "</td>";
                                   $salidaControl .="<td align=\"center\">" .$RM. "</td>";
                                   if ($EG < 8)
                                   {
                                        $salidaControl .="<td align=\"center\" class ='GlasgowBajo'>" .$EG. "</td>";
                                   }
               
                                   if ($EG >= 8 && $EG < 12)
                                   {
                                        $salidaControl .="<td align=\"center\" class ='GlasgowIntermedio'>" .$EG. "</td>";
                                   }
               
                                   if ($EG >= 12)
                                   {
                                        $salidaControl .="<td align=\"center\" class ='GlasgowAlto'>" .$EG. "</td>";
                                   }
               
                                   $fechareg =$VectorControl[$cont-1][fecha_registro];
                                   $fechareg = explode(" ",$fechareg);
                                   $user=$this->GetDatosUsuarioSistema($VectorControl[$cont-1][usuario_id]);
                                   if ($VectorControl[$cont-1][usuario_id] == UserGetUID() AND $fechareg[0]==date("Y-m-d") AND $datosPaciente[ingreso]==$VectorControl[$cont-1][ingreso])
                                   {
                                        $action = ModuloGetURL('app','EE_ControlesPacientes','user','Borrar_ControlNeuro',
                                        array("fechar"=>$VectorControl[$cont-1][fecha_registro],'datosPaciente'=>$datosPaciente,'datos_estacion'=>$datos_estacion,'control'=>$control,'href_action_hora'=>$href_action_hora,'href_action_control'=>$href_action_control));
                                        $salidaControl .= "<td><a href='".$action."'>ELIMINAR</a></td>\n";
                                   }
                                   else
                                   {
                                        $salidaControl .="<td align=\"center\">" .$user[0][usuario]. "</td>";
                                   }
                                   $salidaControl .="</tr>";
                                   $cont++;
                              }
                              $salidaControl .="</table>";
                         }
                         else
                         {
                              $salidaControl .= "<div class='lable_mark' align='center'><br>AUN NO HAY REGISTRO DE CONTROLES NEUROLOGICOS</div>";
                         }
                    	$salidaControl .="<br>";
                         return $salidaControl;
                    break;
                    
                    case '8':
                         $Resumen = $this->GetResumenGlucometria($datosPaciente[ingreso]);
                         $salidaControl = "";
                         if($Resumen)
                         {
                              $salidaControl .="<br>";                    
                              $salidaControl .= "	<table width='100%' border='0'  align='center'>\n";
                              $salidaControl .= "	<tr class=\"modulo_table_list_title\" align='center'>\n";
                              $salidaControl .= "	<td colspan=\"6\">RESUMEN DE CONTROLES DE GLUCOMETRIA</td>\n";
                              $salidaControl .= "	</tr>\n";
                              $salidaControl .= "	<tr class=\"modulo_table_list_title\" align='center'>\n";
                              $salidaControl .= "	<td rowspan='2'>FECHA</td>\n";
                              $salidaControl .= "	<td rowspan='2'>GLUCOMETRIA</td>\n";
                              $salidaControl .= "	<td colspan='2'>INSULINA CRISTALINA</td>\n";
                              $salidaControl .= "	<td colspan='2'>INSULINA NHP</td>\n";
                              $salidaControl .= "	</tr>\n";
                              $salidaControl .= "	<tr class=\"modulo_table_list_title\">\n";
                              $salidaControl .= "	<td width='15%'>CANTIDAD</td>\n";
                              $salidaControl .= "	<td width='15%'>VIA</td>\n";
                              $salidaControl .= "	<td width='15%'>CANTIDAD</td>\n";
                              $salidaControl .= "	<td width='15%'>VIA</td>\n";
                              $salidaControl .= "	</tr>\n";
               
                              if (!IncludeLib('datospaciente')){
                                   $this->error = "Error al cargar la libreria [datospaciente].";
                                   $this->mensajeDeError = "datospaciente";
                                   return false;
                              }
               
                              $datos_hc=GetDatosPaciente("","",$this->ingreso,"","");
                              $paciente=array("edad"=>CalcularEdad($datos_hc["fecha_nacimiento"],date("Y-m-d")),"sexo"=>$datos_hc["sexo_id"]);
               
                              $Rangos = $this->GetRangoControl(8,$paciente);
                              if ($Rangos === false){
                                   return false;
                              }
                              
                              foreach($Resumen as $key => $value)
                              {
                                   if(!empty($value[0][glucometria]))			{ $gluco = number_format($value[0][glucometria], 0, ',', '.');} else { $gluco = "--"; }
                                   if(!empty($value[0][valor_cristalina]))	{ $valCristalina = number_format($value[0][valor_cristalina], 0, ',', '');} else { $valCristalina = "--"; }
                                   if(!empty($value[0][valor_nph]))				{ $valNPH = number_format($value[0][valor_nph], 0, ',', '');} else { $valNPH = "--"; }
                                   if(!empty($value[0][via_cristalina]))		{ $via_cristalina = $value[0][viacristalina];} else { $via_cristalina = "--"; }
                                   if(!empty($value[0][via_nph]))					{ $via_nph = $value[0][vianph];} else { $via_nph = "--"; }
               
                                   $salidaControl .= "				<tr ".$this->Lista($cont)." align='center'>\n";
                                   
                                   list($date,$time) = explode (" ",$key);
                                   $hora = explode(":",$time);
                                   $hora = $hora[0].":".$hora[1];
                                   $fecha = $date." ".$hora;
                                   
                                   $salidaControl .= "					<td>".$fecha."</td>\n";
                                   if($gluco >= $Rangos[rango_max] || $gluco<= $Rangos[rango_min]){
                                        $estilo = "alerta";
                                   }
                                   else{
                                        $estilo = "";
                                   }
                                   $salidaControl .= "					<td class='$estilo' align='center'>".$gluco."</td>\n";
                                   $salidaControl .= "					<td>".$valCristalina."</td>\n";
                                   $salidaControl .= "					<td>".$via_cristalina."</td>\n";
                                   $salidaControl .= "					<td>".$valNPH."</td>\n";
                                   $salidaControl .= "					<td>".$via_nph."</td>\n";
                                   $salidaControl .= "				</tr>\n";
                                   $cont++;
                              }
                              $salidaControl .= "		</td>\n";
                              $salidaControl .= "	</tr>\n";
                              $salidaControl .= "</table>\n";
                         }
                         else
                         {
                              $salidaControl .= "<div class='lable_mark' align='center'><br>AUN NO HAY REGISTRO DE CONTROLES DE GLUCOMETRIA</div>";                         
                         }
                         $salidaControl .="<br>";                           
                         return $salidaControl;
                    break;
               }
          }

		function GetControlPosicionOpt($resultado,$posicion_id)
		{
			$option="";
			while ($data = $resultado->FetchNextObject($toUpper=false))
			{
				if ($data->posicion_id==$posicion_id)
					$option.="<option value='".$data->posicion_id."' selected>".$data->descripcion."</option>\n";
				else
					$option.="<option value='".$data->posicion_id."'>".$data->descripcion."</option>\n";
			}
			return $option;
		}


		function GetControlOxiMetodoOpt($resultado,$posicion_id)
		{
			$option="";
			while ($data = $resultado->FetchNextObject($toUpper=false))
			{
				if ($data->metodo_id==$posicion_id)
					$option.="<option value='".$data->metodo_id."' selected>".$data->descripcion."</option>\n";
				else
					$option.="<option value='".$data->metodo_id."'>".$data->descripcion."</option>\n";
			}
			return $option;
		}


		function GetControlOxiConcentracionesOpt($resultado,$posicion_id)
		{
			$option="";
			while ($data = $resultado->FetchNextObject($toUpper=false))
			{
				if ($data->concentracion_id==$posicion_id)
					$option.="<option value='".$data->concentracion_id."' selected>".$data->descripcion."</option>\n";
				else
					$option.="<option value='".$data->concentracion_id."'>".$data->descripcion."</option>\n";
			}
			return $option;
		}


		function GetControlOxiFlujoOpt($resultado,$posicion_id)
		{
			$option="";
			while ($data = $resultado->FetchNextObject($toUpper=false))
			{
				if ($data->flujo_id==$posicion_id)
					$option.="<option value='".$data->flujo_id."' selected>".$data->descripcion."</option>\n";
				else
					$option.="<option value='".$data->flujo_id."'>".$data->descripcion."</option>\n";
			}
			return $option;
		}


		function GetControlReposoOpt($resultado,$posicion_id)
		{
			$option="";
			while ($data = $resultado->FetchNextObject($toUpper=false))
			{
				if ($data->tipo_reposo_id==$posicion_id)
					$option.="<option value='".$data->tipo_reposo_id."' selected>".$data->descripcion."</option>\n";
				else
					$option.="<option value='".$data->tipo_reposo_id."'>".$data->descripcion."</option>\n";
			}
			return $option;
		}



		function GetControlPerExtremidadesOpt($resultado,$posicion_id)
		{
			$option="";
			while ($data = $resultado->FetchNextObject($toUpper=false))
			{
				if ($data->tipo_extremidad_id==$posicion_id)
					$option.="<option value='".$data->tipo_extremidad_id."' selected>".$data->descripcion."</option>\n";
				else
					$option.="<option value='".$data->tipo_extremidad_id."'>".$data->descripcion."</option>\n";
			}
			return $option;
		}


		function GetControlDietasOpt($resultado,$dieta_id)
		{
			$option="";
			while ($data = $resultado->FetchNextObject($toUpper=false))
			{
				if ($data->hc_dieta_id==$dieta_id)
					$option.="<option value='".$data->hc_dieta_id."' selected>".$data->descripcion."</option>\n";
				else
					$option.="<option value='".$data->hc_dieta_id."'>".$data->descripcion."</option>\n";
			}
		}

          function GetHtmlDietas($vect,$TipoId)
          {
               foreach($vect as $value=>$titulo)
               {
                    if($titulo[hc_dieta_id]==$TipoId){
                         $this->salida .=" <option align=\"center\" value=\"$titulo[hc_dieta_id]\" selected>$titulo[descripcion]</option>";
                    }else{
                         $this->salida .=" <option align=\"center\" value=\"$titulo[hc_dieta_id]\">$titulo[descripcion]</option>";
                    }
               }
               return $titulo[hc_dieta_id];
          }

          function GetTiposDrenajes($vect,$TipoId)
          {
               foreach($vect as $value=>$titulo)
               {
                    if($titulo[tipo_drenaje]==$TipoId){
                         $this->salida .=" <option align=\"center\" value=\"$titulo[tipo_drenaje]\" selected>$titulo[descripcion]</option>";
                    }else{
                         $this->salida .=" <option align=\"center\" value=\"$titulo[tipo_drenaje]\">$titulo[descripcion]</option>";
                    }
               }
               return $titulo[tipo_drenaje];
          }

                    
          /*
		* function Verifica_Conexion($query)
		* $query es el query que se quiere verificar
		* Se ejecuta el query y si existe algun error => se retorna falso de los contrario se devuelve el obj resultado
		* retorna el resultado del query
		*/
		function Verifica_Conexion($query,$dbconn)
		{
			$resultado = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				return false;
			}
			return $resultado;
		}//End function


		function Lista($numero)
		{
			if ($numero%2)
				return ("class='hc_list_oscuro'");
			return ("class='hc_list_claro'");
		}//End function
}//End class
?>

