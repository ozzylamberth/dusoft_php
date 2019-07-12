<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ImpresionHistoria.HTML.php,v 1.4 2011/09/26 22:22:14 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  */
  /**
  * Clase: ImpresionHistoria_HTML
  * Clase encargada de la impresion de la historia en html
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.4 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  */
  class ImpresionHistoria_HTML extends ImpresionHistoria
  {
      /**
    *
    */
    function ImpresionHistoria_HTML($ingreso)
    {
      $this->ImpresionHistoria();
      $this->ingreso = $ingreso;
      $this->CargarVariables();
      return true;
    }
    /**
    *
    */
    function CabeceraImprimir()
    {
      $copiaryPegar = SessionGetVar('sw_copiar_pegar');
      $this->imprimir .= "<script lenguage=\"text/javascript\" src=\"../javascripts/Draw2D/jsDraw2D.js\"></script>\n";

      if ($copiaryPegar != 1)
      {
        $this->imprimir .= "<script language='javascript'>\n";
        $this->imprimir .= " function disableselect(e) \n";
        $this->imprimir .= "{ \n";
        $this->imprimir .= " return false;\n";
        $this->imprimir .= "} \n";
        $this->imprimir .= " function reEnable() \n";
        $this->imprimir .= "{ \n";
        $this->imprimir .= " return true;\n";
        $this->imprimir .= "} \n";
        $this->imprimir .= " function inhabilitar()\n";
        $this->imprimir .= "  {\n";
        $this->imprimir .= "    alert ('ESTA FUNCION ESTA DESHABILITADA ') ;\n";
        $this->imprimir .= "    return false;\n";
        $this->imprimir .= "  }\n";
        $this->imprimir .= "  document.oncontextmenu=inhabilitar;\n ";
        $this->imprimir .= "  if (window.sidebar)\n";
        $this->imprimir .= "  {\n";
        $this->imprimir .= "    document.onmousedown=disableselect;\n";
        $this->imprimir .= "    document.onclick=reEnable;\n";
        $this->imprimir .= "  }\n";
        $this->imprimir .=  "</script>\n";
      }
      
      $edad=CalcularEdad($this->datosPaciente['fecha_nacimiento'],$this->EvolucionGeneral['fecha']);
      $this->imprimir .= "<table width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
      $this->imprimir .= "  <tr>\n";
      $this->imprimir .= "    <td align=\"left\" width=\"40%\">\n";
      $this->imprimir .= "      <img src='images/logocliente.png' width=\"110\" height=\"100\">\n";
      $this->imprimir .= "    </td>\n";
      $this->imprimir .= "    <td align=\"center\" width=\"60%\" class=\"label\">\n";
      $this->imprimir .= "      <font size='4'>HISTORIA CLINICA</font>\n";
      $this->imprimir .= "    </td>\n";
      $this->imprimir .= "  </tr>\n";
      $this->imprimir .= "</table>\n";
      $this->imprimir .= "<table width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\">";
      $this->imprimir .= "  <tr class=\"normal_10\">\n";
      $this->imprimir .= "    <td align=\"justify\" width=\"40%\" >\n";
      $this->imprimir .= "      <b>PACIENTE:</b>&nbsp;".$this->datosPaciente['primer_nombre'].' '.$this->datosPaciente['segundo_nombre'].' '.$this->datosPaciente['primer_apellido'].' '.$this->datosPaciente['segundo_apellido']."\n";
      $this->imprimir .= "    </td>\n";
      $this->imprimir .= "    <td align=\"justify\" colspan=\"2\" width=\"35%\">\n";
      $this->imprimir .= "      <b>IDENTIFICACION:</b>&nbsp;".$this->datosPaciente['tipo_id_paciente']." ".$this->datosPaciente['paciente_id']."\n";
      $this->imprimir .= "    </td>\n";
      $this->imprimir .= "    <td align=\"justify\" width=\"25%\" >\n";
      $this->imprimir .= "      <b>HC:</b>&nbsp;";
      
      if($this->datosPaciente['historia_numero']!="")
      {
        if($this->datosPaciente['historia_prefijo']!="")
          $this->imprimir .= $this->datosPaciente['historia_numero']." - ". $this->datosPaciente['historia_prefijo'];
        else
          $this->imprimir .= $this->datosPaciente['historia_numero']." - ".$this->datosPaciente['tipo_id_paciente'];
      }
      else
      {
        $this->imprimir.= $this->datosPaciente['paciente_id']." - ".$this->datosPaciente['tipo_id_paciente'];
      }
      
      $this->imprimir .= "    </td>\n";
      $this->imprimir .= "  </tr>\n";
      
      $FechaNacimiento = $this->FechaStamp($this->datosPaciente['fecha_nacimiento']);          

      if($this->datosPaciente['pais']=="COLOMBIA")
        $direccion.= $this->datosPaciente['departamento'].'-'.$this->datosPaciente['municipio'];
      else
        $direccion.= $this->datosPaciente['pais']." / ".$this->datosPaciente['departamento']." / ".$this->datosPaciente['municipio'];
      $acudientes=$this->GetDatosAcudiente($this->ingreso);
	  $datosRespons=$this->GetDatosRespons($this->ingreso);
	  $this->imprimir .= "  <tr class=\"normal_10\">\n";
      $this->imprimir .= "    <td ><b>FECHA DE NACIMIENTO:</b>&nbsp;".$FechaNacimiento."</td>\n";
      $this->imprimir .= "    <td align=\"center\" ><b>EDAD:</b>&nbsp;".$edad['anos']."&nbsp;Años</td>\n";
      $this->imprimir .= "    <td align=\"center\" ><b>SEXO:</b>&nbsp;".$this->datosPaciente['sexo_id']."</td>\n";
      $this->imprimir .= "    <td align=\"justify\"><b>TIPO AFILIADO:</b>&nbsp;".$this->Responsable[9]."</td>\n";
      $this->imprimir .= "  </tr>\n";          
      $this->imprimir .= "  <tr class=\"normal_10\">\n";
      $this->imprimir .= "    <td align=\"justify\"><b>RESIDENCIA:</b>&nbsp;".$this->datosPaciente['residencia_direccion']."</td>\n";
      $this->imprimir .= "    <td align=\"justify\" colspan=\"2\">".$direccion."</td>\n";
      $this->imprimir .= "    <td align=\"justify\"><b>TELEFONO:</b>&nbsp;".$this->datosPaciente['residencia_telefono']."</td>\n";
      $this->imprimir .= "  </tr>\n";
	  $this->imprimir .= "  <tr class=\"normal_10\">\n";
      $this->imprimir .= "    <td align=\"justify\"><b>OCUPACION:</b>&nbsp;".$this->datosPaciente['ocupacion_descripcion']."</td>\n";
      $this->imprimir .= "    <td align=\"justify\" colspan=\"2\"><b>NOMBRE RESPONSABLE:</b>&nbsp;".$datosRespons['primer_nombre_garante']." ".$datosRespons['segundo_nombre_garante']." ".$datosRespons['primer_apellido_garante']." ".$datosRespons['segundo_apellido_garante']."</td>\n";
      $this->imprimir .= "    <td align=\"justify\"><b>TELEFONO:</b>&nbsp;".$datosRespons['telefono_garante']."</td>\n";
      $this->imprimir .= "  </tr>\n";
      $this->imprimir .= "  <tr class=\"normal_10\">\n";
      $this->imprimir .= "    <td><b>NOMBRE ACOMPAÑANTE:</b>&nbsp;".$acudientes['nombre_completo']."<b></td>";
      $this->imprimir .= "    <td colspan=\"2\"><b>PARENTESCO:</b>&nbsp;".$acudientes['descripcion']."<b></td>\n";
      $this->imprimir .= "    <td ><b>TELEFONO:</b></td>\n";
      $this->imprimir .= "  </tr>\n";
      
      $this->BuscarCamaActiva($this->ingreso);

      $FechaI = $this->FechaStamp($this->DatosIngreso_Paciente['fecha_ingreso']);
      $HoraI = $this->HoraStamp($this->DatosIngreso_Paciente['fecha_ingreso']);

      if($this->DatosCama['int'])
      {
        if($this->DatosCama['fecha_egreso'])
        {
          $FechaS = $this->FechaStamp($this->DatosCama['fecha_egreso']);
          $HoraS = $this->HoraStamp($this->DatosCama['fecha_egreso']);
        }
      }
      else
      {
        $FechaS = $this->FechaStamp($this->DatosIngreso_Paciente['cierre_evolucion']);
        $HoraS = $this->HoraStamp($this->DatosIngreso_Paciente['cierre_evolucion']);
      }
      
      $this->imprimir .= "  <tr class=\"normal_10\">\n";
      $this->imprimir .= "    <td><b>FECHA INGRESO:</b>&nbsp;".$FechaI." - ".$HoraI."</td>\n";
      $this->imprimir .= "    <td colspan=\"2\"><b>FECHA EGRESO:</b>&nbsp;".$FechaS." - ".$HoraS."</td>\n";
      $this->imprimir .= "    <td ><b>CAMA:</b>&nbsp;".$this->DatosCama['cama']."</td>\n";
      $this->imprimir .= "  </tr>\n";
      
      $servicio=$this->GetServicio($this->DatosIngreso_Paciente['departamento_actual']);
      $this->imprimir .= "  <tr class=\"normal_10\">\n";
      $this->imprimir .= "    <td ><b>DEPARTAMENTO:<b>&nbsp;".$this->DatosIngreso_Paciente['departamento_actual']."  -  ".$this->DatosIngreso_Paciente['descripcion']."</td>\n";
      $this->imprimir .= "    <td colspan=\"3\"><b>SERVICIO:</b>&nbsp;".$servicio."</td>\n";
      $this->imprimir .= "  </tr>\n";
      $this->imprimir .= "  <tr class=\"normal_10\">\n";
      $this->imprimir .= "    <td ><b>CLIENTE:</b>&nbsp;".$this->Responsable[8]."</td>\n";
      $this->imprimir .= "    <td colspan=\"3\"><b>PLAN:</b>&nbsp;".$this->Responsable[4]."</td>\n";
      $this->imprimir .= "  </tr>\n";
      $this->imprimir .= "</table>\n";
      $this->imprimir .= "<br>\n";
      return true;
    }


     function Vista_NotaMedica()
     {
		$nota = $this->Consulta_NotasMedicas();
		if (empty ($nota))
		{
			$salida2 .="<br><table width=\"50%\" border=\"0\" align=\"center\">";
			$salida2 .="<tr class=\"hc_table_submodulo_list_title\">";
			$salida2 .="<div class='label_mark' align='center'><BR>ESTA HISTORIA AUN NO PRESENTA NOTAS DE OBSERVACION<br><br>";
			$salida2 .="</tr>";
			$salida2 .="</table>";
		}
		if (!empty ($nota))
  		{
			$salida2 .="<br><table width=\"50%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$salida2 .="<tr class=\"hc_table_submodulo_list_title\">";
			$salida2 .="<td align=\"center\"colspan=\"2\">NOTAS DE OBSERVACION SOBRE HC</td>";
			$salida2 .="</tr>";

			$salida2 .="<tr class=\"hc_table_submodulo_list_title\">";
			$salida2 .="<td>FECHA</td>";
			$salida2 .="<td align=\"center\">NOTA</td>";
			$salida2 .="</tr>";

			$spy=0;
			foreach($nota as $k=>$v)
			{
				if($spy==0)
				{
					$salida2.="<tr class=\"hc_submodulo_list_oscuro\">";
					$spy=1;
				}
				else
				{
					$salida2.="<tr class=\"hc_submodulo_list_claro\">";
					$spy=0;
				}

				$salida2 .="<td width='10%' align='center'>$k</td>";


				$salida2 .="<td><table border='0' width='100%'>";
				foreach($v as $k2=>$vector){

					$salida2 .="<tr class=\"hc_submodulo_list_oscuro\">";
					$salida2 .="<td><b>$vector[hora]</b></td>";
					$salida2 .="<td><b>";
					$salida2 .=$vector[usuario].' - '.$vector[nombre];
					$salida2 .="</b></td>";

					$salida2 .="</tr>";
					$salida2 .="<tr class=\"hc_submodulo_list_claro\">";
					$salida2 .="<td class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";
					$salida2 .="<td width='100%'>".$vector[nota_medica]."</td>";
					$salida2 .="</tr>";
					$salida2 .="<tr>";

				}
				$salida2 .="</table>";
				$salida2 .="</td>";
				$salida2 .="</tr>";
			}

			$salida2.="</table><br>";
		}
          return $salida2;
	}

     
	function PiePaginaImprimir()
	{
		$datosProfesionalFirma = GetProfesionalTratante($this->ingreso);
		

		$this->imprimir .= "<BR><TABLE BORDER='0'>";

		$largo = strlen($this->datosProfesional['nombre']);
		if($largo < '5')
		{$largo = $largo + '12'; }
		$largo = $largo + '16';
		for ($l=0; $l<$largo; $l++)
		{
			$cad = $cad.'_';
		}
		$this->datosProfesional['firma'] = $datosProfesionalFirma['firma'];
		$this->datosProfesional['firma'] = trim($this->datosProfesional['firma']);
/*		
		echo'<pre>';
		print_r($datosProfesionalFirma);
		print_r("D".$this->datosProfesional['firma']);
		echo'</pre>';
		*/
		
		$this->imprimir .= "<TD ALIGN=\"LEFT\" >\n";
		if(empty($this->datosProfesional['firma'])){
			$RutaImagen="/images/firmas_profesionales/SinFirma.jpg";
			$this->imprimir .= "  <IMG SRC='images/firmas_profesionales/SinFirma.jpg'>\n";
		}else{
			$RutaImagen="/images/firmas_profesionales/".$datosProfesionalFirma['firma']."";
			$this->imprimir .= "  <IMG SRC='images/firmas_profesionales/".$datosProfesionalFirma['firma']."'>\n";
		}

		$this->imprimir .= "</td>";

		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH='20%'><FONT SIZE='2' FACE='arial'>$cad</FONT></TD>";
		$this->imprimir .= "</TR>";
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH='20%'><label class=\"normal_10N\">PROFESIONAL: </label>&nbsp;&nbsp;&nbsp;<label class=\"normal_10\">".$datosProfesionalFirma['nombre']."</label></TD>";
		$this->imprimir .= "</TR>";
		if(!empty($datosProfesionalFirma['tarjeta_profesional']))
		{
			$this->imprimir .= "<TR>";
			$this->imprimir .= "<TD WIDTH='50%'><label class=\"normal_10\">".$datosProfesionalFirma['tipo_id_tercero'].' - '.$datosProfesionalFirma['tercero_id']."&nbsp;&nbsp;-&nbsp;&nbsp;T.P&nbsp;&nbsp;".$datosProfesionalFirma['tarjeta_profesional']."</label></TD>";
			$this->imprimir .= "</TR>";
		}
		else
		{
			$this->imprimir .= "<TR>";
			$this->imprimir .= "<TD WIDTH='50%'><label class=\"normal_10\">".$datosProfesionalFirma['tipo_id_tercero'].' - '.$datosProfesionalFirma['tercero_id']."</label></TD>";
			$this->imprimir .= "</TR>";
		}
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH='20%'><label class=\"normal_10N\">ESPECIALIDAD </label>-&nbsp;&nbsp; <label class=\"normal_10\">".$datosProfesionalFirma['descripcion']."</label></TD>";
		$this->imprimir .= "</TR>";
		$this->imprimir .= "</TABLE><br>";

		$fechita = date("d-m-Y H:i:s");
		$FechaImprime = $this->FechaStamp($fechita);
		$HoraImprime = $this->HoraStamp($fechita);
	
		$this->imprimir .= "<TABLE BORDER='0' WIDTH=\"100%\">";
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<td ALIGN=\"JUSTIFY\" WIDTH=\"50%\"><FONT SIZE='1' FACE='arial'>Imprimió:&nbsp;".$this->User[0]['nombre']." - ".$this->User[0]['usuario']."</FONT></td>\n";
		$this->imprimir .= "<td ALIGN=\"RIGHT\" WIDTH=\"50%\"><FONT SIZE='1' FACE='arial'>Fecha Impresión :&nbsp;&nbsp;".$FechaImprime." - ".$HoraImprime."</FONT></td>\n";
		$this->imprimir .= "</TR>";
		$this->imprimir.= "</table>";
		return true;
	}

}//fin clase
?>