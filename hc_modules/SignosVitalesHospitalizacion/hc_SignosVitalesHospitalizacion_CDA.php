<?php

/**
* $Id: hc_SignosVitalesHospitalizacion_CDA.php,v 1.1 2005/06/14 23:25:32 tizziano Exp $
*/

/**
* Clase para consulta CDA-HL7 del submodulo SignosVitalesHospitalizacion
*
* @author Tizziano Perea <tperea@ipsoft-sa.com>
* @version $Revision: 1.1 $
* @package SIIS
*/ 
class SignosVitalesHospitalizacion_CDA extends Extenciones_CDA_HC
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
    function SignosVitalesHospitalizacion_CDA()
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
          $salida.="<caption>SIGNOS VITALES</caption>";
               
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
          
          $salida.="<THEAD valign=\"top\">";
          $salida.="<TR>";
          $salida.="<TH>FECHA</TH>";
          $salida.="<TH>HORA</TH>";
          $salida.="<TH>F.C.</TH>";
          $salida.="<TH>F.R.</TH>";
          $salida.="<TH>PVC</TH>";
          $salida.="<TH>PIC</TH>";
          $salida.="<TH>PESO (Kg)</TH>";
          $salida.="<TH>T.A</TH>";
          $salida.="<TH>MEDIA</TH>";
          $salida.="<TH>SITIO TOMA DE T.A.</TH>";
          $salida.="<TH>TEMP.</TH>";
          $salida.="<TH>T. INCUB</TH>";
          $salida.="<TH>MANUAL</TH>";
          $salida.="<TH>EVA</TH>";
          $salida.="<TH>SAT O<sub>2</sub></TH>";
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
               
               if($v[fc] == 0){$v[fc] = '--';}
               $salida.="<TD align=\"center\">".$v[fc]."</TD>";
               
               if($v[fr] == 0){$v[fr] = '--';}
               $salida.="<TD align=\"center\">".$v[fr]."</TD>";
               
               if($v[pvc] == 0){$v[pvc] = '--';}
               $salida.="<TD align=\"center\">".$v[pvc]."</TD>";
               
               if($v[presion_intracraneana] == 0.00){$v[presion_intracraneana] = '--';}
               $salida.="<TD align=\"center\">".$v[presion_intracraneana]."</TD>";
               
               if($v[peso] == 0.00){$v[peso] = '--';}
               $salida.="<TD align=\"center\">".$v[peso]."</TD>";
               
               if($v[ta_alta] AND $v[ta_baja])
               {$tension=$v[ta_alta]." / ".$v[ta_baja];}else{$tension='--';}
               $salida.="<TD align=\"center\">".$tension."</TD>";
               
               if($v[media] == 0){$v[media] = '--';}
               $salida.="<TD align=\"center\">".$v[media]."</TD>";
               					
               if($v[sitio_id] <> '' OR is_null($v[sitio_id]))
               {
               	$sitio=$this->GetSignosVitalesSitios($v[sitio_id]);
               }

               $salida.="<TD align=\"center\">".$sitio[0][descripcion]."</TD>";
               
               if($v[temp_piel] == 0.00){$v[temp_piel] = '--';} 
               $salida.="<TD align=\"center\">".$v[temp_piel]."</TD>";
               
               if($v[servo] == 0.00){$v[servo] = '--';} 
               $salida.="<TD align=\"center\">".$v[servo]."</TD>";
               
               if($v[manual] == 0.00){$v[manual] = '--';} 
               $salida.="<TD align=\"center\">".$v[manual]."</TD>";
               
               if($v[evaluacion_dolor] == 0){$v[evaluacion_dolor] = '--';} 
               $salida.="<TD align=\"center\">".$v[evaluacion_dolor]."</TD>";
               
               if($v[sato2] == 0.00){$v[sato2] = '--';} 
               $salida.="<TD align=\"center\">".$v[sato2]."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
               					
          	if($v[observacion] !='' AND $v[observacion] != 'NULL')
               {
                    $salida.="<TBODY>";
                    $salida.="<TR>";
                    $salida.="<TH>NOTA</TH>";
                    $salida.="<TD colspan=\"14\">".$v[observacion]."</TD>";
                    $salida.="</TR>";
                    $salida.="</TBODY>";
              }
          }
          $salida.="</TABLE>";
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
               $query = "SELECT *
                         FROM hc_signos_vitales
                         WHERE evolucion_id=".$Paramdatos[evolucion]."
                         ORDER BY fecha DESC;";
          	
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($signos = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $signos;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;
                              
               case '2':
               $query = "SELECT *
                         FROM hc_signos_vitales
                         WHERE ingreso=".$Paramdatos[ingreso]."
                         ORDER BY fecha DESC;";
          	
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($signos = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $signos;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;

               case '3':
               $query = "SELECT *
                         FROM hc_signos_vitales
                         WHERE ingreso=".$Paramdatos[ingreso]."
                         ORDER BY fecha DESC;";
          	
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($signos = $resultado->FetchRow())
               {
               	$XML_Consulta[] = $signos;
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


	/**
	*		GetSignosVitalesSitios
	*
	*		Obtiene el sitios donde o modo como se tomaran los signos vitales.
	*
	*		@Author Tizziano Perea O.
	*		@access Public
	*		@return bool
	*/
	function GetSignosVitalesSitios($sitio)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
          $query="SELECT *
                    FROM hc_signos_vitales_sitios
                    WHERE sitio_id='$sitio'
                    ORDER BY sitio_id";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_signos_vitales_sitios\"<br>";
               $this->mensajeDeError = $query;
               return false;
          }
          while ($data = $resultado->FetchRow())
          {
               $sitios[]=$data;
          }
          return $sitios;
	}

}//fin de la clase

?>
