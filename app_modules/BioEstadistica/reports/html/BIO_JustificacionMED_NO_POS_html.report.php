<?php
  /**
  * $Id: JustificacionMED_NO_POS_html.report.php,v 1.3 2010/03/26 21:47:50 sandra Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * Reporte de prueba formato HTML
  */
  class BIO_JustificacionMED_NO_POS_html_report
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
    function BIO_JustificacionMED_NO_POS_html_report($datos=array())
    {
     	$this->datos = $datos;
    // print_r($this->datos);
      return true;
    }
    /**
    * Funcion para crear el membrete
    *
    * @return array
    */
  	function GetMembrete()
  	{
      $Empresa = $this->ObtenerEmpresa($this->datos['justificacion_id']);
	  $titulo  = "<font size=\"1\"><b>".$Empresa[0]['tipo_id_tercero']." ".$Empresa[0]['id']." ";
	  $titulo .= "".$Empresa[0]['razon_social']."<br>";
      $titulo .= "SOLICITUD Y JUSTIFICACION DEL USO DE MEDICAMENTO NO POS</b></font>";
  		
      $Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
  				'subtitulo'=>'',
  				'logo'=>'logocliente.png',
  				'align'=>'left',
  				'height'=>'40',
  				'width'=>'60'));
  		return $Membrete;
  	}
    /**
    *
    */
    function BuscarCamaActiva($ingreso)
    {
      list($dbconn) = GetDBconn();
      GLOBAL $ADODB_FETCH_MODE;
      $query = "SELECT Count(movimiento_id) 
                FROM movimientos_habitacion 
                WHERE ingreso = '".$this->ingreso."';";
  		$result = $dbconn->Execute($query);
  		if($dbconn->ErrorNo() != 0)
  		{
  			$this->error = "Error al Cargar el Modulo";
  			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  			return false;
  		}
          
      if($result->fields[0] > 0)
      {
        $query = "SELECT  A.cama, 
                          A.fecha_ingreso, 
                          B.fecha_registro AS fecha_egreso, 
                          '1' AS int
                  FROM    movimientos_habitacion AS A
                          LEFT JOIN ingresos_salidas AS B 
                          ON (A.ingreso = B.ingreso)
                  WHERE   A.ingreso = '".$this->ingreso."'
                  AND     A.movimiento_id = (
                            SELECT MAX(movimiento_id) 
                            FROM movimientos_habitacion 
                            WHERE ingreso = '".$this->ingreso."' ) ";
      }
      else
      {
        $query = "SELECT  A.sw_estado, 
                          B.fecha_ingreso, 
                          C.fecha_registro AS fecha_egreso, 
                          '1' AS int
                  FROM    pacientes_urgencias AS A, 
                          ingresos AS B 
                          LEFT JOIN ingresos_salidas AS C 
                          ON (B.ingreso = C.ingreso)
                  WHERE   A.ingreso = '".$this->ingreso."'
                  AND     A.ingreso = B.ingreso ";          
      }
                          
      $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
      $result = $dbconn->Execute($query);
      $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

      $DatosCama = $result->FetchRow();
      return $DatosCama;
    }
    /**
    *
    */
  	function Datos_Ingreso($ingreso)
  	{
  		list($dbconn) = GetDBconn();
  		GLOBAL $ADODB_FETCH_MODE;
  			$query = "SELECT A.fecha_registro, A.departamento, A.departamento_actual,
  				A.fecha_cierre, B.fecha, B.fecha_cierre AS cierre_evolucion,
  				C.descripcion
  			FROM ingresos AS A
  			LEFT JOIN hc_evoluciones AS B ON (A.ingreso = B.ingreso)
  			LEFT JOIN departamentos AS C ON (A.departamento_actual = C.departamento)
  			WHERE A.ingreso='".$ingreso."'
  			AND B.evolucion_id = (SELECT MAX(evolucion_id) 
  							FROM hc_evoluciones 
  						WHERE ingreso = '".$ingreso."' 
  						AND fecha_cierre IS NOT NULL);";
  		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
  		$result = $dbconn->Execute($query);
  		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
  		if($dbconn->ErrorNo() != 0)
  		{
  			$this->error = "Error al Cargar el Modulo";
  			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  			return false;
  		}
  		else
  		{
  			$DatosIngreso_Paciente = $result->FetchRow();
  			return $DatosIngreso_Paciente;
  		}
  	}
    /**
    *
    */
  	function GetServicio($departamento)
  	{
  		$sql  = "SELECT b.descripcion ";
      $sql .= "FROM   departamentos a, ";
      $sql .= "       servicios b ";
      $sql .= "WHERE  a.servicio=b.servicio ";
      $sql .= "AND    a.departamento='".$departamento."' ";
      
  		$cxn = new ConexionBD();
      if(!$rst = $cxn->ConexionBaseDatos($sql))
      {
        echo $cxn->mensajeDeError;
        return false;
      }         
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
      }
      $rst->Close();
      
      return $datos;
  	}
    /**
    * Funcion donde se obtienen los datos del responsable
    *
    * @param integer $numerodecuenta Identificador del numero de cuenta
    *
    * 2return mixed
    */
  	function GetDatosResponsable($numerodecuenta)
  	{
      $sql  = "SELECT a.plan_id, 
                      a.tipo_afiliado_id, 
                      a.rango, 
                      a.semanas_cotizadas, 
                      b.plan_descripcion, 
                      b.tipo_tercero_id, 
                      b.tercero_id, 
                      b.num_contrato, 
                      c.nombre_tercero, 
                      X.tipo_afiliado_nombre, 
                      b.sw_tipo_plan
                FROM  cuentas a 
                      LEFT JOIN tipos_afiliado X 
                      ON (a.tipo_afiliado_id = X.tipo_afiliado_id), 
                      planes b, 
                      terceros  c
                WHERE a.plan_id = b.plan_id
                AND   b.tercero_id = c.tercero_id
                AND   b.tercero_id = c.tercero_id
                AND   a.numerodecuenta = ".$numerodecuenta.";";
      
      $cxn = new ConexionBD();
      if(!$rst = $cxn->ConexionBaseDatos($sql))
      {
        echo $cxn->mensajeDeError;
        return false;
      }         
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
      }
      $rst->Close();
      
      return $datos;
  	}

    function CrearReporte()
    {
    
      IncludeClass("ConexionBD");
      if(!IncludeLib('datospaciente'))
  		{
  			echo $error = "Error al Cargar el Modulo";
  			echo $this->mensajeDeError = "No se pudo cargar la libreria de datos de pacientes.";
  			return false;
  		}
      $style =" style=\"border-bottom-width: 1px ;border-bottom: thin solid; border-color: #000000;\" ";
      $styl1 =" style=\"border-width: 1px ;border: thin solid; border-color: #000000;\" ";
      $Datos = $this->ObtenerDatosJustificaciones($this->datos['justificacion_id']);
      $sustituto = $this->ObtenerMedicamentoSustituto($this->datos['justificacion_id']);
   		
      $DatosIngreso_Paciente = $this->Datos_Ingreso($Datos['ingreso']);
  		$servicio = $this->GetServicio($DatosIngreso_Paciente['departamento_actual']);
  		$DX = $this->ObtenerDiagnosticos($this->datos['justificacion_id']);
      $alternativas = $this->ObtenerAlternativas_POS($this->datos['justificacion_id']);
      $DatosMed = $this->ObtenerDatosMedicamentos($Datos['codigo_producto'],$Datos['ingreso'],$this->datos['justificacion_id']);
          
      $html  = $this->CabeceraImprimir($Datos);
      $html .= "<br>\n";
	  $html .= "<STYLE TYPE=\"text/css\"> <!--TD{font-family: Arial; font-size: 6pt;}---></STYLE>";
      $html .= "<table width=\"100%\" align=\"center\" class=\"normal_10\" border=\"1\">\n";
  		$html .= "  <tr>\n";
  		$html .= "	  <td colspan=\"2\"><b>DIAGNOSTICO</b></td>\n";
  		$html .= "	</tr>\n";
  		foreach($DX as $key => $dtl)
      {
        $html .= "	<tr>\n";
        $html .= "	  <td width=\"5%\">".$dtl['diagnostico_id']."&nbsp;</td>\n";
        $html .= "		<td width=\"%\">".$dtl['diagnostico_nombre']."&nbsp;</td>\n";
        $html .= "	</tr>\n";
  		}
      $html .= "</table>\n";
            
      $html .= "<table width=\"100%\" align=\"center\" class=\"normal_10\" border=\"1\">\n";
      $html .= "  <tr>\n";
  		$html .= "	  <td ><b>DESCRIPCION DEL CASO CLINICO:</b></td>\n";
  		$html .= "	</tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td>".$Datos['descripcion_caso_clinico']."&nbsp;</td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      
      $html .= "<table width=\"100%\" align=\"center\" class=\"normal_10\" border=\"1\">\n";
      $html .= "  <tr>\n";
  		$html .= "	  <td colspan=\"4\" align=\"center\"><b>MEDICAMENTOS POS UTILIZADOS</b></td>\n";
  		$html .= "	</tr>\n";
      foreach($alternativas as $k => $dtl) 
      {
    		$html .= "		<tr>\n";
    		$html .= "			<td width=\"20%\"><b>PRODUCTO:</b></td>\n";
    		$html .= "			<td colspan=\"3\">".$dtl['medicamento_pos']."&nbsp;</td>\n";
    		$html .= "		</tr>\n";
    		$html .= "		<tr>\n";
    		$html .= "			<td width=\"20%\"><b>PRINCIPIO ACTIVO:</b></td>\n";
    		$html .= "			<td width=\"30%\">".$dtl['principio_activo']."&nbsp;</td>\n";
    		$html .= "			<td width=\"20%\"><b>PRESENTACION:</b></td>\n";
    		$html .= "			<td width=\"30%\">".$dtl['presentacion']."&nbsp;</td>\n";
    		$html .= "		</tr>\n";
        $html .= "		<tr>\n";
    		$html .= "			<td ><b>POSOLOGIA:</b></td>\n";
    		$html .= "			<td>".$dtl['frecuencia']."&nbsp;</td>\n";
    		$html .= "			<td ><b>DOSIS/DIA:</b></td>\n";
    		$html .= "			<td>".$dtl['dosis_dia_pos']."&nbsp;</td>\n";
    		$html .= "		</tr>\n";        
        $html .= "		<tr>\n";
    		$html .= "			<td ><b>TIEMPO DE TRATAMIENTO:</b></td>\n";
    		$html .= "			<td>".$dtl['duracion_pos']."&nbsp;</td>\n";
    		$html .= "			<td ><b>CANTIDAD:</b></td>\n";
    		$html .= "			<td>".$dtl['cantidad']."&nbsp;</td>\n";
    		$html .= "		</tr>\n";
    		$html .= "		<tr>\n";
    		$html .= "			<td colspan=\"4\">\n";
        $html .= "        <b>RESPUESTA CLINICA CON EL MEDICAMENTO POS :</b>".$dtl['otras']."\n";
        $html .= "      </td>\n";
    		$html .= "		</tr>\n";    		
        $html .= "		<tr>\n";
    		$html .= "			<td colspan=\"4\">\n";
        $html .= "        <b>REACCIONES ADVERSAS O INTOLERANCIA A LOS MEDICAMENTOS POS :</b>".$dtl['reaccion_secundaria']."\n";
        $html .= "      </td>\n";
    		$html .= "		</tr>\n";
      }
      $html .= "</table>\n";
      $html .= "<table width=\"100%\" align=\"center\" class=\"normal_10\" border=\"1\">\n";
      $html .= "  <tr>\n";
  		$html .= "	  <td colspan=\"4\" align=\"center\"><b>MEDICAMENTO NO POS UTILIZADO</b></td>\n";
  		$html .= "  </tr>\n";
      $html .= "	<tr>\n";
      $html .= "		<td width=\"20%\"><b>PRODUCTO:</b></td>\n";
      $html .= "		<td>".$DatosMed['producto']."  ".$DatosMed['concentracion_forma_farmacologica']."   ".$DatosMed['forma_farma']."  &nbsp;</td>\n";
      $html .= "		<td width=\"20%\" ><b>PRINCIPIO ACTIVO:</b></td>\n";
      $html .= "		<td width=\"30%\">".$DatosMed['principio_activo']."&nbsp;</td>\n";
      $html .= "	</tr>\n";
      $html .= "	<tr>\n";
      $html .= "		<td width=\"20%\" ><b>PRESENTACION:</b></td>\n";
      $html .= "		<td width=\"30%\">".$DatosMed['unidad_medicamento']." ".$DatosMed['unidad_venta']."&nbsp;</td>\n";
      $html .= "		<td width=\"20%\" ><b>REGISTRO INVIMA:</b></td>\n";
      $html .= "		<td width=\"30%\">".$DatosMed['codigo_invima']."&nbsp;</td>\n";
      $html .= "	</tr>\n";
	  $html .= "	<tr>\n";
      $html .= "		<td ><b>FABRICANTE:</b></td>\n";
      $html .= "		<td colspan=\"3\">".$DatosMed['fabricante']."&nbsp;</td>\n";
      $html .= "	</tr>\n";
      $html .= "	<tr>\n";
      $html .= "		<td ><b>POSOLOGIA:</b></td>\n";
      $html .= "		<td>".$DatosMed['frecuencia']."</td>\n";
      $html .= "		<td ><b>DOSIS/DIA:</b></td>\n";
      $html .= "		<td>".($DatosMed['cantidad']*1)." ".$DatosMed['unidad_dosificacion']." ".$DatosMed['via_administracion']."&nbsp;</td>\n";
      $html .= "	</tr>\n";        
      $html .= "	<tr>\n";
      $html .= "		<td ><b>TIEMPO DE TRATAMIENTO:</b></td>\n";
      $html .= "		<td>".($DatosMed['dias_tratamiento']*1)." DIAS</td>\n";
      $html .= "		<td ><b>CANTIDAD:</b></td>\n";
      
      $cantidad = $DatosMed['cantidad'] *$DatosMed['cantidad'];
      $fac_conversion = $this->ObtenerFactorConversion($Datos['codigo_producto']);
      if($fac_conversion['factor_conversion'])
        $cantidad = $cantidad/$fac_conversion['factor_conversion'];

      $valor = round($cantidad);
      if($valor < $cantidad) $valor++;
        
      $html .= "		<td>".($valor)."  ".$DatosMed['umm']."&nbsp;</td>\n";
      $html .= "	</tr>\n";
	  $html .= "	<tr>\n";
      $html .= "		<td colspan=\"4\">\n";
      $html .= "       <b>INDICACION TERAPEUTICA :</b>".$Datos['indicacion_terapeutica']."&nbsp;\n";
      $html .= "    </td>\n";
      $html .= "	</tr>\n";
      $html .= "	<tr>\n";
      $html .= "		<td colspan=\"4\">\n";
      $html .= "       <b>EFECTO DESEADO :</b>".$Datos['efecto']."&nbsp;\n";
      $html .= "    </td>\n";
      $html .= "	</tr>\n";    		
      $html .= "	<tr>\n";
      $html .= "		<td colspan=\"4\"><b>JUSTIFICACION PARA EL USO DEL MEDICAMENTONO POS</b><td>\n";
      $html .= "	</tr>\n";    		
      $html .= "	<tr>\n";
      $html .= "		<td colspan=\"4\">".$Datos['justificacion']."</td>\n";
      $html .= "	</tr>\n";
	  $html .= "  <tr>\n";
	  $html .= "    <td width=\"20%\" colspan=\"4\"><b>TIPO SOLICITUD:</b>\n";
      $eq[$Datos['tipo_solicitud']] = "X";
      $html .= "    PRIMERA VEZ (".$eq['1'].")&nbsp; ";
	  $html .= "    RENOVACION (".$eq['2'].")&nbsp;";
	  $html .= "    FALLO DE TUTELA (".$eq['3'].")&nbsp;</td>\n";
      
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      
      $html .= "<table width=\"100%\" align=\"center\" class=\"normal_10\" border=\"1\">\n";
      $html .= "  <tr>\n";
      $html .= "	  <td colspan=\"4\"  align=\"center\" ><b>MEDICAMENTO POS QUE SUSTITUYE O REEMPLAZA AL MEDICAMENTO NO POS SOLICITADO <font style=\"font-size: 8px;\">(DEBE SER DEL MISMO GRUPO TERAPEUTICO)</font></b></td>\n";
      $html .= "	</tr>\n";
      $html .= "	<tr>\n";
      $html .= "	  <td width=\"20%\" ><b>MEDICAMENTO</b></td>";
      $html .= "		<td width=\"30%\">".$sustituto['medicamento']."&nbsp;</td>\n" ;
      $html .= "		<td width=\"20%\" ><b>PRINCIPIO ACTIVO</b></td>";
      $html .= "		<td width=\"30%\">".$sustituto['principio_activo']."&nbsp;</td>\n" ;
      $html .= "	</tr>\n";				
      $html .= "	<tr>\n";
      $html .= "		<td ><b>PRESENTACION</b></td>";
      $html .= "		<td colspan=\"3\">".$sustituto['presentacion']."&nbsp;</td>\n";	
      $html .= "	</tr>\n";				
      $html .= "	<tr>\n";        
      $html .= "		<td ><b>POSOLOGIA</b></td>";
      $html .= "		<td>".$sustituto['frecuencia']."&nbsp;</td>\n";        
      $html .= "		<td ><b>DOSIS /DIA</b></td>\n";
      $html .= "		<td>".$sustituto['dosis']."&nbsp;</td>\n" ;
      $html .= "	</tr>\n";
      $html .= "	<tr>\n";
      $html .= "		<td ><b>TIEMPO DE TRATAMIENTO</b></td>\n";
      $html .= "		<td colspan=\"3\" >".$sustituto['tiempo_tratamiento']."&nbsp;</td>\n" ;
      $html .= "	</tr>\n";
      $html .= "</table><br>\n";
      $html .= "<table width=\"100%\" align=\"center\" class=\"normal_10\">\n";
      $html .= "  <tr>\n";
  		$html .= "	  <td>\n";
      $html .= "    Declaro que la informaci?n aqui suministrada se encuentra soportada en la historia cl?nica y no existe conflicito de intereses, no recibo beneficios";
      $html .= "    econ?micos, ni materiales, ni de ningun otro tipo, de parte de la industria farmac?utica por la formulaci?n del medicamento no pos, por tal motivo en\n";
      $html .= "    constancia firmo.\n";
      $html .= "    </td>\n";
  		$html .= "  </tr>\n";
  		$html .= "</table>\n";
      
      $this->GetDatosProfesional($Datos['usuario_id_autoriza']);
		
      $html .= "<table width=\"100%\" align=\"center\" class=\"normal_10\">\n";
      $html .= "  <tr>\n";
  		$html .= "	  <td width=\"32%\" ".$style.">&nbsp;&nbsp;&nbsp;".$this->datosProfesional['nombre']."</td>\n";
  		$html .= "	  <td width=\"1%\">&nbsp;</td>\n";
		$html .= "	  <td width=\"32%\" ".$style."><IMG SRC='images/firmas_profesionales/".$this->datosProfesional['firma']."'></td>\n";
  		$html .= "	  <td width=\"1%\">&nbsp;</td>\n";
      $html .= "	  <td >&nbsp;</td>\n";
  		$html .= "  </tr>\n";      
      $html .= "  <tr>\n";
  		$html .= "	  <td align=\"center\">NOMBRE DEL MEDICO TRATANTE</td>\n";
      $html .= "	  <td width=\"1%\">&nbsp;</td>\n";
  		$html .= "	  <td align=\"center\">FIRMA</td>\n";
      $html .= "	  <td width=\"1%\">&nbsp;</td>\n";
  		$html .= "	  <td>REGISTRO: <u>".$this->datosProfesional['tarjeta_profesional']."&nbsp;&nbsp;&nbsp;&nbsp;</u></td>\n";
  		$html .= "  </tr>\n";
  		$html .= "</table>\n";
		  $html .= "<table width=\"100%\" align=\"center\" class=\"normal_10\">\n";
      $html .= "  <tr>\n";
  		$html .= "	  <td width=\"33%\">C?digo: AUMFM006</td>\n";
  		$html .= "	  <td width=\"33%\">Original: Auditor?a M?dica</td>\n";
  		$html .= "	  <td width=\"%\">&nbsp;</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
  		$html .= "	  <td width=\"33%\">Ultima modificaci?n: Abril / 07</td>\n";
  		$html .= "	  <td width=\"33%\">Copia: EPS</td>\n";
  		$html .= "	  <td width=\"%\">&nbsp;</td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      
      $this->User = $this->GetDatosUsuarioSistema(UserGetUID());          
		
      $fechita = date("d-m-Y H:i:s");
      $FechaImprime = $this->FechaStamp($fechita);
      $HoraImprime = $this->HoraStamp($fechita);
		
      $html .="<TABLE width=\"100%\" ALIGN=\"center\">";
			$html .="<TR>";
      $html .="<td ALIGN=\"JUSTIFY\" WIDTH=\"50%\"><FONT SIZE='1' FACE='arial'>Imprimi?:&nbsp;".$this->User[0]['nombre']." - ".$this->User[0]['usuario']."</FONT></td>\n";
      $html .="<td ALIGN=\"RIGHT\" WIDTH=\"50%\"><FONT SIZE='1' FACE='arial'>Fecha Impresi?n :&nbsp;&nbsp;".$FechaImprime." - ".$HoraImprime."</FONT></td>\n";
      $html .="</TR>";
      $html .="</table>";
          
      return $html;			
    }	 
    /**
    *
    */
  	function CabeceraImprimir($datos_justificacion)
  	{
      $ingreso = $datos_justificacion['ingreso'];
      $style =" style=\"border-bottom-width: 1px ;border-bottom: thin solid; border-color: #000000;\" ";
  		$datosPaciente = GetDatosPaciente("","",$datos_justificacion['ingreso']);
		$numerodecuenta = $this->ObtenerIngresoPaciente($datos_justificacion['ingreso']);
  		$edad=CalcularEdad($datosPaciente['fecha_nacimiento'],$this->EvolucionGeneral['fecha']);
  		$datos_plan = $this->GetDatosResponsable($numerodecuenta[0]['numerodecuenta']);
		$Servicio = $this->ObtenerServicioFormulacion($this->datos['justificacion_id']);
  		//$DatosCama = $this->BuscarCamaActiva($ingreso);

      
	  $html .= "<STYLE TYPE=\"text/css\"> <!--TD{font-family: Arial; font-size: 6pt;}---></STYLE>";
	  $html .= "<table width=\"100%\" align=\"left\" class=\"normal_10\" border=\"1\">\n";
  		$html .= "  <tr>\n";
  		$html .= "    <td><b>FECHA: </b></td>\n";
      $html .= "    <td  colspan=\"11\">".$datos_justificacion['fecha_registro']."</td>\n";
	  $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td><b>NOMBRE:</b></td>\n";
      $html .= "    <td colspan=\"11\">\n";
      $html .= "      ".$datosPaciente['primer_nombre']." ".$datosPaciente['segundo_nombre']." ".$datosPaciente['primer_apellido']." ".$datosPaciente['segundo_apellido']."\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
  	  $html .= "    <td><b>DOCUMENTO DE IDENTIDAD N?:</b></td>\n";
      $html .= "    <td colspan=\"2\"> ".$datosPaciente['paciente_id']."</td>\n";
      $eq[$datosPaciente['tipo_id_paciente']] = "X";
      $html .= "    <td align=\"center\">CC</td>\n";
      $html .= "    <td align=\"center\"> ".$eq['CC']."&nbsp;</td>\n";
      $html .= "    <td align=\"center\">TI</td>\n";
      $html .= "    <td align=\"center\"> ".$eq['TI']."&nbsp;</td>\n";
      $html .= "    <td align=\"center\">RC</td>\n";
      $html .= "    <td align=\"center\"> ".$eq['RC']."&nbsp;</td>\n";
      $html .= "    <td align=\"center\">CE</td>\n";
      $html .= "    <td align=\"center\" colspan=\"2\"> ".$eq['CE']."&nbsp;</td>\n";
	  $html .= "  </tr>\n";
	  $html .= "  <tr>\n";
      $html .= "    <td><b>FECHA NACIMIENTO:</b></td>\n";
      $html .= "    <td colspan=\"3\">\n";
      $html .= "      ".$datosPaciente['fecha_nacimiento']."\n";
      $html .= "    </td>\n";
	  $html .= "    <td><b>EDAD :</b></td>\n";
      $html .= "    <td colspan=\"3\">\n";
      $html .= "      ".$edad['edad_aprox']."\n";
      $html .= "    </td>\n";
	  $html .= "    <td><b>SEXO :</b></td>\n";
      $html .= "    <td colspan=\"3\">\n";
      $html .= "      ".$datosPaciente['sexo_id']."\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
	  $html .= "  <tr>\n";
      $html .= "    <td><b>DIRECCION:</b></td>\n";
      $html .= "    <td colspan=\"5\">\n";
      $html .= "      ".$datosPaciente['residencia_direccion']."\n";
      $html .= "    </td>\n";
	  $html .= "    <td><b>TELEFONO :</b></td>\n";
      $html .= "    <td colspan=\"5\">\n";
      $html .= "      ".$datosPaciente['residencia_telefono']."\n";
      $html .= "    </td>\n";
	  $html .= "  </tr>\n";
	  $html .= "  <tr>\n";
      $html .= "    <td><b>CLIENTE:</b></td>\n";
      $html .= "    <td colspan=\"2\">\n";
      $html .= "      ".$datos_plan['nombre_tercero']."\n";
      $html .= "    </td>\n";
	  $html .= "    <td><b>PLAN :</b></td>\n";
      $html .= "    <td colspan=\"2\">\n";
      $html .= "      ".$datos_plan['plan_descripcion']."\n";
      $html .= "    </td>\n";
	  $html .= "    <td><b>TIPO AFILIADO :</b></td>\n";
      $html .= "    <td colspan=\"2\">\n";
      $html .= "      ".$datos_plan['tipo_afiliado_nombre']."\n";
      $html .= "    </td>\n";
	  $html .= "    <td><b>RANGO :</b></td>\n";
      $html .= "    <td colspan=\"2\">\n";
      $html .= "      ".$datos_plan['rango']."\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
	  $html .= "  <tr>\n";
      $html .= "    <td><b>DEPARTAMENTO:</b></td>\n";
      $html .= "    <td colspan=\"5\">\n";
      $html .= "      ".$Servicio['descripcion_departamento']."\n";
      $html .= "    </td>\n";
	  $html .= "    <td><b>TIPO DE MANEJO :</b></td>\n";
      $html .= "    <td colspan=\"5\">\n";
      $html .= "      ".$Servicio['servicio']."\n";
      $html .= "    </td>\n";
	  $html .= "  </tr>\n";
	  $html .= "  <tr>\n";
      $html .= "    <td><b>FECHA INGRESO:</b></td>\n";
      $html .= "    <td colspan=\"3\">\n";
      $html .= "      ".$numerodecuenta[0]['fecha_ingreso']."\n";
      $html .= "    </td>\n";
	  $html .= "    <td colspan=\"2\"><b>FECHA EGRESO :</b></td>\n";
      $html .= "    <td>\n";
      $html .= "      ".$numerodecuenta[0]['fecha_salida']."\n";
      $html .= "    </td>\n";
	  $html .= "    <td colspan=\"2\"><b>CUENTA :</b></td>\n";
      $html .= "    <td colspan=\"3\">\n";
      $html .= "      ".$numerodecuenta[0]['numerodecuenta']."\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
  		return $html;          
  	}
    /**
    * Funcion donde se obtiene el factor de conversion, por medicamento
    *
    */
    function ObtenerFactorConversion($codigos)
    {      
      $sql  = "SELECT ME.codigo_producto,";
      $sql .= " 	    HC.factor_conversion ";
      $sql .= "FROM   solicitudes_tratamiento ST, ";
      $sql .= "       hc_formulacion_medicamentos ME, ";
      $sql .= "       hc_formulacion_factor_conversion HC ";
      $sql .= "WHERE 	ST.solicitud_tratamiento_id = '".$codigos."' ";
      $sql .= "AND    ME.ingreso = ST.ingreso ";
      $sql .= "AND    ME.codigo_producto = ST.codigo_medicamento ";
      $sql .= "AND    HC.codigo_producto = ME.codigo_producto ";
      $sql .= "AND    HC.unidad_dosificacion = ME.unidad_dosificacion ";
      
      $cxn = new ConexionBD(); 
      if(!$rst = $cxn->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      return $datos;
    }
    /**
		* Funcion donde se obtiene la informacion del medicamento sustituto
    *
    * @param integer $justificacion Identificador de la justificacion realizada
    *
    * @return mixed
		*/
		function ObtenerMedicamentoSustituto($justificacion)
		{
      $sql  = "SELECT medicamento,";
      $sql .= "       principio_activo,";
      $sql .= "       presentacion,";
      $sql .= "       frecuencia,";
      $sql .= "       cantidad,";
      $sql .= "       dosis,";
      $sql .= "       tiempo_tratamiento "; 
			$sql .= "FROM		hc_justificaciones_no_pos_medicamento_sustituto  ";	
			$sql .= "WHERE	justificacion_no_pos_id = ".$justificacion." ";
			
      $cxn = new ConexionBD();
      if(!$rst = $cxn->ConexionBaseDatos($sql))
      {
        echo $cxn->mensajeDeError;
        return false;
      }         
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
      }
      $rst->Close();
			return $datos;
		}
    /**
    *
    */
     function ObtenerIngresoPaciente($ingreso)
     {
          list($dbconn) = GetDBconn();
          $query  = "SELECT 	PC.paciente_id,
                              PC.tipo_id_paciente,
                              PC.primer_apellido ||' '|| PC.segundo_apellido AS apellidos,
                              PC.primer_nombre ||' '|| PC.segundo_nombre AS nombres,
                              PC.fecha_nacimiento,
                              PC.fecha_nacimiento_es_calculada,
                              PC.residencia_direccion,
                              PC.residencia_telefono,
                              PC.sexo_id,
                              IG.ingreso,
                              TO_CHAR(IG.fecha_ingreso,'DD/MM/YYYY HH12:MI am') AS fecha_ingreso,
                              CU.numerodecuenta,
                              CU.rango,
                              TA.tipo_afiliado_nombre,
                              VI.via_ingreso_nombre,
                              PL.plan_descripcion,
                              PL.tercero_id,
                              PL.tipo_tercero_id,
                              TE.nombre_tercero AS cliente,
							  INS.fecha_registro AS fecha_salida
          		 FROM	pacientes PC,
                              vias_ingreso VI,
                              cuentas CU
                              LEFT JOIN tipos_afiliado AS TA ON (CU.tipo_afiliado_id = TA.tipo_afiliado_id),
                              planes PL,
                              ingresos IG
							  LEFT JOIN ingresos_salidas AS INS ON (IG.ingreso = INS.ingreso),
                              terceros AS TE
                    WHERE	IG.paciente_id = PC.paciente_id
                    AND		IG.tipo_id_paciente = PC.tipo_id_paciente
                    AND		VI.via_ingreso_id = IG.via_ingreso_id
                    AND		CU.ingreso = IG.ingreso
                    AND		PL.plan_id = CU.plan_id
                    AND		PL.tercero_id = TE.tercero_id
                    AND		PL.tipo_tercero_id = TE.tipo_id_tercero
                    AND		IG.ingreso = ".$ingreso." ";

          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }else{		
               if(!$result->EOF)
               {
                    $Datingreso[0] = $result->GetRowAssoc($ToUpper = false);					
               }
               $result->Close();
          }	
          return $Datingreso;
     }
    /**
    * Funcion donde se obtienen los datos de la justificacion
    *
    * @param integer $justificacion Identificador de la justificacion asignada
    *
    * @return mixed
    */
    function ObtenerDatosJustificaciones($justificacion)
    {
      $sql  = "SELECT 	justificacion_no_pos_id,";
      $sql .= "	        ingreso 	,";
      $sql .= "	        codigo_producto 	,";
      $sql .= "	        usuario_id_autoriza 	,";
      $sql .= "	        duracion 	,";
      $sql .= "	        dosis_dia 	,";
      $sql .= "	        justificacion 	,";
      $sql .= "	        ventajas_medicamento 	,";
      $sql .= "	        ventajas_tratamiento 	,";
      $sql .= "	        precauciones 	,";
      $sql .= "	        controles_evaluacion_efectividad 	,";
      $sql .= "	        tiempo_respuesta_esperado 	,";
      $sql .= "	        riesgo_inminente 	,";
      $sql .= "	        descripcion_caso_clinico 	,";
      $sql .= "	        efecto 	,";
      $sql .= "	        TO_CHAR(fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
	  $sql .= "	        indicacion_terapeutica, ";
	  $sql .= "	        tipo_solicitud ";
      $sql .= "FROM     hc_justificaciones_no_pos_hospitalaria_medicamentos ";
      $sql .= "WHERE    justificacion_no_pos_id = ".$justificacion." ";
      
      $cxn = new ConexionBD();
      if(!$rst = $cxn->ConexionBaseDatos($sql))
      {
        echo $cxn->mensajeDeError;
        return false;
      }         
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
      }
      $rst->Close();
      
      return $datos;
    }
    /**
    *
    */
    function ObtenerDatosMedicamentos($codigo,$ingreso,$justificacion)
    {              
      $sql  = "SELECT ID.descripcion AS producto, ";
			$sql .= "				ID.codigo_invima, ";
			$sql .= "				IA.descripcion AS principio_activo, ";
			$sql .= "				IM.descripcion AS umm, ";
			$sql .= "				FM.dosis, ";
			$sql .= "				FM.unidad_dosificacion, ";
			$sql .= "				FM.cantidad, ";
			$sql .= "				FM.observacion, ";
			$sql .= "				FM.codigo_producto, ";
			$sql .= "				FM.frecuencia, ";
			$sql .= "				FM.via_administracion_id, ";
			$sql .= "				FM.dias_tratamiento, ";
			$sql .= "				ME.concentracion_forma_farmacologica, ";
			$sql .= "				IF.descripcion AS forma_farma, ";
			$sql .= "				IM.descripcion AS unidad_medicamento, ";
			$sql .= "				ID.contenido_unidad_venta AS unidad_venta, ";
			$sql .= "				HVA.nombre AS via_administracion, ";
			$sql .= "				INF.descripcion as fabricante ";
			$sql .= "FROM 	inv_med_cod_principios_activos AS IA, ";
			$sql .= "				hc_formulacion_medicamentos FM,";
			$sql .= "				inventarios_productos ID, ";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), ";
			$sql .= "				inv_med_cod_forma_farmacologica IF, ";
			$sql .= "				hc_vias_administracion HVA, ";
			$sql .= "				inv_fabricantes INF ";
			$sql .= "WHERE	ID.codigo_producto = '".$codigo."' ";
			$sql .= "AND  	ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		FM.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND		FM.ingreso = ".$ingreso." ";
			$sql .= "AND		FM.justificacion_no_pos_id = ".$justificacion." ";
			$sql .= "AND    IF.cod_forma_farmacologica = ME.cod_forma_farmacologica ";
			$sql .= "AND    FM.via_administracion_id = HVA.via_administracion_id ";
			$sql .= "AND    ID.fabricante_id = INF.fabricante_id ";
			$sql .= "ORDER BY FM.sw_estado,producto ";
      
      $cxn = new ConexionBD();
      if(!$rst = $cxn->ConexionBaseDatos($sql))
      {
        echo $cxn->mensajeDeError;
        return false;
      }         
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
      }
      $rst->Close();
      
      return $datos;
    }
     
     function ObtenerDiagnosticos($justificacion_id)
     {
		list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          $sql = "SELECT A.*,
          			B.diagnostico_nombre
          	   FROM hc_justificaciones_no_pos_hospitalaria_medicamentos_diagnostico AS A,
                  	   diagnosticos AS B
                  WHERE A.justificacion_no_pos_id = ".$justificacion_id."
                  AND A.diagnostico_id = B.diagnostico_id;";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($sql);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;           
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          while ($data = $result->FetchRow())
          {
               $DatosDX[] = $data;
          }        
          return $DatosDX;
     }
    /**
    *
    */
    function ObtenerAlternativas_POS($justificacion_id)
    {
      $sql = "SELECT *
              FROM  hc_justificaciones_no_pos_hospitalaria_medicamentos_alternativa
              WHERE justificacion_no_pos_id = ".$justificacion_id." ";
      $cxn = new ConexionBD();
      if(!$rst = $cxn->ConexionBaseDatos($sql))
      {
        echo $cxn->mensajeDeError;
        return false;
      }         
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;
    }
     
     
     function GetDatosProfesional($usuario)
	{
          list($dbconn) = GetDBconn();
		  $sql="SELECT A.tipo_id_tercero, A.tercero_id, A.nombre,
               	   A.tarjeta_profesional, B.especialidad, C.descripcion, A.firma
                FROM profesionales AS A,
               	 profesionales_usuarios AS E
                LEFT JOIN profesionales_especialidades AS B
                ON(E.tipo_tercero_id=B.tipo_id_tercero AND E.tercero_id=B.tercero_id)
                LEFT JOIN especialidades AS C ON(C.especialidad = B.especialidad)
                WHERE A.usuario_id = ".$usuario."
                AND A.usuario_id = E.usuario_id
                AND E.tercero_id = A.tercero_id
                AND E.tipo_tercero_id = A.tipo_id_tercero;";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
          {
               return false;
          }
		while(!$result->EOF)
		{
			$this->datosProfesional = $result->GetRowAssoc(false);
			$result->MoveNext();
		}
		return $this->datosProfesional;
	}
	//---------------------------------------
     
     function GetDatosUsuarioSistema($usuario)
     {
          $pfj=$this->frmPrefijo;
          $query = "SELECT usuario,
                    nombre
                    FROM system_usuarios
                    WHERE usuario_id = $usuario";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurri? un error al intentar obtener los datos del usuario.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if($result->EOF){
                    return "ShowMensaje";
               }
               else
               {
                    while ($data = $result->FetchRow()){
                         $DatosUser[] = $data;
                    }
                    return $DatosUser;
               }
          }
     }
	 
	 function ObtenerEmpresa($justificacion_id)
    {
      $sql  = "SELECT 	A.tipo_id_tercero, ";
	  $sql .= "			A.id, ";
	  $sql .= "			A.razon_social ";
	  $sql .= "FROM		empresas A, ";
	  $sql .= "			ingresos B, ";
	  $sql .= "			cuentas C, ";
	  $sql .= "			hc_justificaciones_no_pos_hospitalaria_medicamentos D ";
	  $sql .= "WHERE	A.empresa_id = C.empresa_id ";
	  $sql .= "AND		C.ingreso = B.ingreso ";
	  $sql .= "AND		B.ingreso = D.ingreso ";
	  $sql .= "AND		D.justificacion_no_pos_id = ".$justificacion_id." ";
      $cxn = new ConexionBD();
      if(!$rst = $cxn->ConexionBaseDatos($sql))
      {
        echo $cxn->mensajeDeError;
        return false;
      }         
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;
    }
	
	function ObtenerServicioFormulacion($justificacion_id)
    {
      $sql  = "SELECT 	SE.descripcion as servicio, ";
	  $sql .= "			DE.descripcion as descripcion_departamento ";
	  $sql .= "FROM		hc_formulacion_medicamentos HFM, ";
	  $sql .= "			hc_formulacion_medicamentos_eventos HFME, ";
	  $sql .= "			hc_evoluciones HE, ";
	  $sql .= "			departamentos DE, ";
	  $sql .= "			servicios SE ";
	  $sql .= "WHERE	HFM.justificacion_no_pos_id = ".$justificacion_id." ";
	  $sql .= "AND		HFM.num_reg_formulacion = HFME.num_reg ";
	  $sql .= "AND		HFME.evolucion_id = HE.evolucion_id ";
	  $sql .= "AND		HE.departamento = DE.departamento ";
	  $sql .= "AND		DE.servicio = SE.servicio ";
	  
      $cxn = new ConexionBD();
      if(!$rst = $cxn->ConexionBaseDatos($sql))
      {
        echo $cxn->mensajeDeError;
        return false;
      }         
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
      }
      $rst->Close();
      
      return $datos;
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

}
?>