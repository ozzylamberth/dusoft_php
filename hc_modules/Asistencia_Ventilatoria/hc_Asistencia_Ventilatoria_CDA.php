<?php

/**
* $Id: hc_Asistencia_Ventilatoria_CDA.php,v 1.1 2005/06/14 23:24:36 tizziano Exp $
*/

/**
* Clase para consulta CDA-HL7 del submodulo Asistencia_Ventilatoria
*
* @author Tizziano Perea <tperea@ipsoft-sa.com>
* @version $Revision: 1.1 $
* @package SIIS
*/ 
class Asistencia_Ventilatoria_CDA extends Extenciones_CDA_HC
{
    /**
    * Variable que contendra el Parametro de Busqueda
    *
    * @var $datos
    * @access private
    */
    var $datos;
    
    /**
    * Variable que contendra el Parametro para el Metodo Busqueda
    *
    * @var $TipoMetodo
    * @access private
    */
    var $TipoMetodo;
 
    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public    
    */  
    function Asistencia_Ventilatoria_CDA()
    {
        $this->Extenciones_CDA_HC();
        return true;
    }
    
    /**
    * Metodo que genera y retorna la estructura CDA-XML para una EVOLUCION
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Evolucion($evolucion_id)
    {
          if (empty($evolucion_id))
          {
               return '';
          }
          else
          {
			$this->datos[evolucion] = $evolucion_id;
               $this->TipoMetodo = '1';
               $XML = $this->GetConsultaSubmodulo($this->datos,$this->TipoMetodo);
               $this->salida = $XML;
               return $this->salida;
          }
    }
    
    /**
    * Metodo que genera y retorna la estructura CDA-XML para una EPICRISIS DE UN INGRESO
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Epicrisis($ingreso)
    {
          if (empty($ingreso))
          {
               return '';
          }
          else
          {
			$this->datos[ingreso] = $ingreso;
               $this->TipoMetodo = '2';
               $XML = $this->GetConsultaSubmodulo($this->datos,$this->TipoMetodo);
               $this->salida = $XML;
               return $this->salida;
          }
    }    
    
    /**
    * Metodo que genera y retorna la estructura CDA-XML para un INGRESO
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Full_Ingreso($ingreso)
    {
          if (empty($ingreso))
          {
               return '';
          }
          else
          {
			$this->datos[ingreso] = $ingreso;
               $this->TipoMetodo = '3';
               $XML = $this->GetConsultaSubmodulo($this->datos,$this->TipoMetodo);
               $this->salida = $XML;
               return $this->salida;
          }
    }    
    
    /**
    * Metodo que genera y retorna la estructura CDA-XML para una HISTORIA CLINICA DE UN PACIENTE
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Full_Historia($paciente_id,$tipoidpaciente)
    {
          if (empty($paciente_id) || empty($tipoidpaciente))
          {
               return '';
          }
          else
          {
               $this->datos[paciente_id] = $paciente_id;
               $this->datos[tipoidpaciente] = $tipoidpaciente;
               $this->TipoMetodo = '4';
               $XML = $this->GetConsultaSubmodulo($this->datos,$this->TipoMetodo);
               $this->salida = $XML;
               return $this->salida;
          }
    }
    
    /**
    * Metodo que genera y retorna la estructura CDA-XML para un RESUMEN DE ATENCIONES DE UN PACIENTE
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Resumen_Historia($paciente_id,$tipoidpaciente)
    { 
          if (empty($paciente_id) || empty($tipoidpaciente))
          {
               return '';
          }
          else
          {
               $this->datos[paciente_id] = $paciente_id;
               $this->datos[tipoidpaciente] = $tipoidpaciente;
               $this->TipoMetodo = '5';
               $XML = $this->GetConsultaSubmodulo($this->datos,$this->TipoMetodo);
               $this->salida = $XML;
               return $this->salida;
          }
    }        

	/*		GetXML_Local
     *
     *		Crea la vista de los datos en XML para su posterior traspaso
     *		a HTML y generacion de impresion.
     *
     *		@Author Tizziano Perea O.
     *		@access Public
     *		@param array => $XML_Consulta - Vector de datos.
     */

    function GetXML_Local($XML_Consulta)
    {
          $salida.="<caption>ASISTENCIA VENTILATORIA</caption>";
          $FechaInicio = $this->FechaNacimiento_Paciente($this->datos[evolucion],$this->datos[ingreso]);
          $FechaFin = date("Y-m-d");
          $edad_paciente = CalcularEdad($FechaInicio,$FechaFin);

          if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_pediatrica'))
          {
               $salida.="<TABLE border=\"1\" width=\"100%\">";
               
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               
               $salida.="<THEAD valign=\"top\">";
               $salida.="<TR>";
               $salida.="<TH>FECHA</TH>";
               $salida.="<TH>HORA</TH>";
               $salida.="<TH>MODO</TH>";
               $salida.="<TH>FIO<sub>2</sub></TH>";
               $salida.="<TH>F.R.</TH>";
               $salida.="<TH>F. VENT</TH>";
               $salida.="<TH>ESPONT</TH>";
               $salida.="<TH>TI</TH>";  
               $salida.="<TH>REL I:E</TH>";
               $salida.="<TH>PEEP</TH>";
               $salida.="<TH>P PICO</TH>";  
               $salida.="<TH>P MESE</TH>";               
               $salida.="<TH>PI MED</TH>";  
               $salida.="<TH>PAW</TH>";
               $salida.="<TH>To. VIA A</TH>";
               $salida.="<TH>ETCO<sub>2</sub></TH>";
               $salida.="</TR>";
               $salida.="</THEAD>";
     
               foreach($XML_Consulta as $k => $v)
               {              
                    $FechaM = $this->FechaStamp($v[fecha]);
                    $HoraM = $this->HoraStamp($v[fecha]);
                    $User=$this->GetDatosUsuarioSistema($v[usuario_id]);
                    
                    $salida.="<TBODY>";
                    $salida.="<TR>";
                    $salida.="<TD align=\"center\">".$FechaM."</TD>";
                    $salida.="<TD align=\"center\">".$HoraM."</TD>";
                    
                    if(is_null($v[descripcion])){$v[descripcion] = '--';}
                    $salida.="<TD align=\"center\">".$v[descripcion]."</TD>";
                    
                    if(is_null($v[f102_id])){$v[descripcion_f] = '--';}
                    $salida.="<TD align=\"center\">".$v[descripcion_f]."</TD>";
                    
                    if($v[fr_respiratoria] == 0){$v[fr_respiratoria] = '--';}
                    $salida.="<TD align=\"center\">".$v[fr_respiratoria]."</TD>";
                    
                    if($v[fr_ventilatoria] == 0.00){$v[fr_ventilatoria] = '--';}
                    $salida.="<TD align=\"center\">".$v[fr_ventilatoria]."</TD>";
                    
                    if($v[expontanea] == 0){$v[expontanea] = '--';}
                    $salida.="<TD align=\"center\">".$v[expontanea]."</TD>";
                    
                    if($v[ti] == 0.00){$v[ti] = '--';} 
                    $salida.="<TD align=\"center\">".$v[ti]."</TD>";
                    
                    if($v[i_e] == 0){$v[i_e] = '--';} 
                    $salida.="<TD align=\"center\">".$v[i_e]."</TD>";

                    if($v[peep] == 0){$v[peep] = '--';} 
                    $salida.="<TD align=\"center\">".$v[peep]."</TD>";

                    if($v[pip] == 0.00){$v[pip] = '--';} 
                    $salida.="<TD align=\"center\">".$v[pip]."</TD>";
                    
                    if($v[pp] == 0.0){$v[pp] = '--';} 
                    $salida.="<TD align=\"center\">".$v[pp]."</TD>";
                    
                    if($v[pm] == 0.0){$v[pm] = '--';} 
                    $salida.="<TD align=\"center\">".$v[pm]."</TD>";
                    
                    if($v[paw] == 0.00){$v[paw] = '--';}
                    $salida.="<TD align=\"center\">".$v[paw]."</TD>";
               
                    if($v[t_via_a] == 0.00){$v[t_via_a] = '--';}
                    $salida.="<TD align=\"center\">".$v[t_via_a]."</TD>";

                    if($v[etco2] == 0.0){$v[etco2] = '--';} 
                    $salida.="<TD align=\"center\">".$v[etco2]."</TD>"; 
                    $salida.="</TR>";
                    $salida.="</TBODY>";
               }
               $salida.="</TABLE>";
          }
          else
          {
               $salida.="<TABLE border=\"1\" width=\"100%\">";
               
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               $salida.="<COLGROUP align=\"center\">";
               
               $salida.="<THEAD valign=\"top\">";
               $salida.="<TR>";
               $salida.="<TH>FECHA</TH>";
               $salida.="<TH>HORA</TH>";
               $salida.="<TH>MODO</TH>";
               $salida.="<TH>FIO<sub>2</sub></TH>";
               $salida.="<TH>F.R.</TH>";
               $salida.="<TH>F. VENT</TH>";
               $salida.="<TH>ESPONT</TH>";
               $salida.="<TH>VOL/MIN</TH>";
               $salida.="<TH>SENS</TH>";
               $salida.="<TH>P. INSP</TH>";
               $salida.="<TH>TI</TH>";
               $salida.="<TH>REL I:E</TH>";
               $salida.="<TH>PEEP</TH>";
               $salida.="<TH>P PICO</TH>";
               $salida.="<TH>P MESE</TH>";
               $salida.="<TH>PI MED</TH>";
               $salida.="<TH>ETCO<sub>2</sub></TH>";
               $salida.="</TR>";
               $salida.="</THEAD>";
     
               foreach($XML_Consulta as $k => $v)
               {              
                    $FechaM = $this->FechaStamp($v[fecha]);
                    $HoraM = $this->HoraStamp($v[fecha]);
                    $User=$this->GetDatosUsuarioSistema($v[usuario_id]);
                    
                    $salida.="<TBODY>";
                    $salida.="<TR>";
                    $salida.="<TD align=\"center\">".$FechaM."</TD>";
                    $salida.="<TD align=\"center\">".$HoraM."</TD>";
                    
                    if(is_null($v[descripcion])){$v[descripcion] = '--';}
                    $salida.="<TD align=\"center\">".$v[descripcion]."</TD>";
                    
                    if(is_null($v[f102_id])){$v[descripcion_f] = '--';}
                    $salida.="<TD align=\"center\">".$v[descripcion_f]."</TD>";
                    
                    if($v[fr_respiratoria] == 0){$v[fr_respiratoria] = '--';}
                    $salida.="<TD align=\"center\">".$v[fr_respiratoria]."</TD>";
                    
                    if($v[fr_ventilatoria] == 0.00){$v[fr_ventilatoria] = '--';}
                    $salida.="<TD align=\"center\">".$v[fr_ventilatoria]."</TD>";
                    
                    if($v[expontanea] == 0){$v[expontanea] = '--';}
                    $salida.="<TD align=\"center\">".$v[expontanea]."</TD>";
                    
                    if($v[volumen] == 0){$v[volumen] = '--';}
                    $salida.="<TD align=\"center\">".$v[volumen]."</TD>";
                    
                    if($v[sens] == 0){$v[sens] = '--';}
                    $salida.="<TD align=\"center\">".$v[sens]."</TD>";
                    
                    if($v[p_insp] == 0){$v[p_insp] = '--';}
                    $salida.="<TD align=\"center\">".$v[p_insp]."</TD>";
                    
                    if($v[ti] == 0.00){$v[ti] = '--';} 
                    $salida.="<TD align=\"center\">".$v[ti]."</TD>";
                    
                    if($v[i_e] == 0){$v[i_e] = '--';} 
                    $salida.="<TD align=\"center\">".$v[i_e]."</TD>";
                    
                    if($v[peep] == 0){$v[peep] = '--';} 
                    $salida.="<TD align=\"center\">".$v[peep]."</TD>";
                    
                    if($v[pip] == 0.00){$v[pip] = '--';} 
                    $salida.="<TD align=\"center\">".$v[pip]."</TD>";
                    
                    if($v[pp] == 0.0){$v[pp] = '--';} 
                    $salida.="<TD align=\"center\">".$v[pp]."</TD>";
                    
                    if($v[pm] == 0.0){$v[pm] = '--';} 
                    $salida.="<TD align=\"center\">".$v[pm]."</TD>";
                    
                    if($v[etco2] == 0.0){$v[etco2] = '--';} 
                    $salida.="<TD align=\"center\">".$v[etco2]."</TD>";
                    $salida.="</TR>";
                    $salida.="</TBODY>";
               }
               $salida.="</TABLE>";
          }
          $salida.="<CAPTION>PROFESIONAL: ".$User[0][nombre]."</CAPTION>";
          return $salida;
     }
    
     /*		GetConsultaSubmodulo
     *
     *		Realiza la consulta de datos a partir de parametros como los datos 
     *		del paciente y el tipo de impresion a realizar.
     *
     *		@Author Tizziano Perea O.
     *		@access Public
     *		@param integer => ingreso, evolucion_id, paciente_id, tipoidpaciente.
     */

    function GetConsultaSubmodulo($Paramdatos, $ParamTipo)
    {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();

          switch($ParamTipo)
          {
               case '1':
               $query ="SELECT A.*, C.*
               	    FROM ((SELECT a.*
                               FROM  hc_asistencia_ventilatoria a
                               WHERE A.evolucion_id=".$Paramdatos[evolucion].") AS A
                              LEFT JOIN
                              (SELECT f.concentracion_id, f.descripcion as descripcion_f
                               FROM hc_tipos_concentracion_oxigenoterapia f
                              ) AS B ON A.f102_id = B.concentracion_id) AS A
						LEFT JOIN
						(SELECT s.*
						 FROM hc_asistencia_ventilatoria_modos S
						) AS C ON A.modo_id = C.modo_id
					ORDER BY A.fecha DESC;";
     	
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($asistencia = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $asistencia;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;
                              
               case '2':
               $query ="SELECT A.*, C.*
               	    FROM ((SELECT a.*
                               FROM  hc_asistencia_ventilatoria a
                               WHERE A.ingreso=".$Paramdatos[ingreso].") AS A
                              LEFT JOIN
                              (SELECT f.concentracion_id, f.descripcion as descripcion_f
                               FROM hc_tipos_concentracion_oxigenoterapia f
                              ) AS B ON A.f102_id = B.concentracion_id) AS A
						LEFT JOIN
						(SELECT s.*
						 FROM hc_asistencia_ventilatoria_modos S
						) AS C ON A.modo_id = C.modo_id
					ORDER BY A.fecha DESC;";
               
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($asistencia = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $asistencia;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;

               case '3':
               $query ="SELECT A.*, C.*
               	    FROM ((SELECT a.*
                               FROM  hc_asistencia_ventilatoria a
                               WHERE A.ingreso=".$Paramdatos[ingreso].") AS A
                              LEFT JOIN
                              (SELECT f.concentracion_id, f.descripcion as descripcion_f
                               FROM hc_tipos_concentracion_oxigenoterapia f
                              ) AS B ON A.f102_id = B.concentracion_id) AS A
						LEFT JOIN
						(SELECT s.*
						 FROM hc_asistencia_ventilatoria_modos S
						) AS C ON A.modo_id = C.modo_id
					ORDER BY A.fecha DESC;";
          	
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($asistencia = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $asistencia;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;
               
               case '4':
//                $sql="SELECT ingreso
//                	 FROM ingresos
//                      WHERE tipo_id_paciente='".$Paramdatos[tipoidpaciente]."'
//                      AND paciente_id='".$Paramdatos[paciente_id]."'
//                      ORDER BY ingreso DESC;";
//                $resulta = $dbconn->Execute($sql);
//                if($dbconn->ErrorNo() != 0)
//                {
//                     $this->error = "Error al Cargar el Modulo";
//                     $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                     return false;
//                }
//                while($data = $resulta->FetchRow())
//                {
//                     $ingreso[] = $data;
//                }
// 
//                if(!empty($ingreso))
// 			{
// 				for($i=0; $i<sizeof($ingreso); $i++)
// 				{
//                          $query = "SELECT B.diagnostico_id, B.diagnostico_nombre,
//                                           A.evolucion_id, A.usuario_id 
//                                    FROM hc_diagnosticos_muerte AS A, diagnosticos AS B,
//                                         hc_evoluciones C
//                                    WHERE A.tipo_diagnostico_id=B.diagnostico_id
//                                    AND A.evolucion_id = C.evolucion_id
//                                    AND C.ingreso = ".$ingreso[$i][0]."
//                                    ORDER BY B.diagnostico_id;";
//                
//                          $resultado = $dbconn->Execute($query);
//                          if ($dbconn->ErrorNo() != 0)
//                          {
//                               $this->error = "Error al Cargar el Modulo";
//                               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                               return false;
//                          }
//                          while($dx = $resultado->FetchRow())
//                          {
//                               $XML_Consulta[] = $dx;
//                          }
//                     }
//                }
//                     
//                $salida = $this->GetXML_Local($XML_Consulta);
			return true;
               break;

               case '5':
//                $sql="SELECT ingreso
//                	 FROM ingresos
//                      WHERE tipo_id_paciente='".$Paramdatos[tipoidpaciente]."'
//                      AND paciente_id='".$Paramdatos[paciente_id]."'
//                      ORDER BY ingreso DESC;";
//                $resulta = $dbconn->Execute($sql);
//                if($dbconn->ErrorNo() != 0)
//                {
//                     $this->error = "Error al Cargar el Modulo";
//                     $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                     return false;
//                }
//                while($data = $resulta->FetchRow())
//                {
//                     $ingreso[] = $data;
//                }
// 
//                if(!empty($ingreso))
// 			{
// 				for($i=0; $i<sizeof($ingreso); $i++)
// 				{
//                          $query = "SELECT B.diagnostico_id, B.diagnostico_nombre,
//                                           A.evolucion_id, A.usuario_id 
//                                    FROM hc_diagnosticos_muerte AS A, diagnosticos AS B,
//                                         hc_evoluciones C
//                                    WHERE A.tipo_diagnostico_id=B.diagnostico_id
//                                    AND A.evolucion_id = C.evolucion_id
//                                    AND C.ingreso = ".$ingreso[$i][0]."
//                                    ORDER BY B.diagnostico_id;";
//                
//                          $resultado = $dbconn->Execute($query);
//                          if ($dbconn->ErrorNo() != 0)
//                          {
//                               $this->error = "Error al Cargar el Modulo";
//                               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                               return false;
//                          }
//                          while($dx = $resultado->FetchRow())
//                          {
//                               $XML_Consulta[] = $dx;
//                          }
//                     }
//                }
//                     
//                $salida = $this->GetXML_Local($XML_Consulta);
			return true;
               break;

               default:
               return false;                        
           }

    }

     /*		FechaStamp
     *
     *		Convierte los datos en Fechas a partir de la Fecha Registro.
     *
     *		@Author Alexander Giraldo.
     *		@access Public
     *		@param integer => fecha_registro
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

     /*		HoraStamp
     *
     *		Convierte los datos en Horas a partir de la Fecha Registro.
     *
     *		@Author Alexander Giraldo.
     *		@access Public
     *		@param integer => fecha_registro
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
     
     
     /*		GetDatosUsuarioSistema
     *
     *		Obtiene el nombre de usuario del sistema
     *
     *		@Author Tizziano Perea O.
     *		@access Public
     *		@return bool
     *		@param integer => usuario_id
     */
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
               $this->mensajeDeError = "Ocurrió un error al intentar obtener los datos del usuario.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
     }/// GetDatosUsuarioSistema
     
     
     /*		FechaNacimiento_Paciente
     *
     *		Obtiene la fecha de nacimiento del paciente.
     *
     *		@Author Tizziano Perea O.
     *		@access Public
     *		@return bool
     *		@param integer => fecha_nacimiento
     */
     
     function FechaNacimiento_Paciente($evolucion,$ingreso)
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
     	if(!empty($evolucion))
          {
          	$query="SELECT ingreso FROM hc_evoluciones WHERE evolucion_id=".$evolucion.";";
               $result = $dbconn->Execute($query);
               list($ingreso) = $result->FetchRow();
          }
          $sql="SELECT tipo_id_paciente, paciente_id FROM ingresos WHERE ingreso=".$ingreso.";";		
          $result = $dbconn->Execute($sql);
          $paciente = $result->GetRows();
          
          $query2="SELECT fecha_nacimiento FROM pacientes
          	    WHERE tipo_id_paciente ='".$paciente[0][0]."'
                   AND paciente_id ='".$paciente[0][1]."';";
          $result = $dbconn->Execute($query2);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar obtener los datos del usuario.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
		list($fecha_nacimiento) = $result->FetchRow();              
          return $fecha_nacimiento;
     }// FechaNacimiento_Paciente

}//fin de la clase

?>
